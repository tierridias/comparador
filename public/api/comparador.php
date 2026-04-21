<?php
require_once "../../includes/bd_connect.php";
session_start();

if (!isset($_SESSION['id_cliente']) || !isset($_POST['id_orcamento'])) {
    header("Location: ../selecionar_produto.php");
    exit();
}

$id_orcamento = intval($_POST['id_orcamento']);
$orcamento_maximo = floatval($_POST['orcamento_maximo']);

$sql = "SELECT p.nome_produto, i.quantidade, p.id_produto 
        FROM itens_orcamento i 
        INNER JOIN produtos p ON i.id_produto = p.id_produto 
        WHERE i.id_orcamento = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_orcamento);
$stmt->execute();
$resultado = $stmt->get_result();

$carrinhos = [
    'Mais Barato' => ['total' => 0, 'itens' => []],
    'Mais Rápido' => ['total' => 0, 'itens' => []],
    'Equilibrado' => ['total' => 0, 'itens' => []]
];

function consultarAPI($url) {
    $ctx = stream_context_create(['http' => ['timeout' => 5]]);
    $json = @file_get_contents($url, false, $ctx);
    return $json ? json_decode($json, true) : null;
}

// Função auxiliar para dividir a compra por várias lojas conforme o stock
function distribuirPorLojas($quantidade_pedida, $opcoes, $criterio) {
    // Ordenar as opções conforme o critério (preco, prazo ou pontos)
    usort($opcoes, function($a, $b) use ($criterio) {
        return $a[$criterio] <=> $b[$criterio];
    });

    $divisao = [];
    $restante = $quantidade_pedida;

    foreach ($opcoes as $opcao) {
        if ($restante <= 0) break;

        $unidades_a_comprar = min($restante, $opcao['stock']);
        
        if ($unidades_a_comprar > 0) {
            $divisao[] = [
                'loja' => $opcao['loja'],
                'qtd' => $unidades_a_comprar,
                'preco_uni' => $opcao['preco'],
                'subtotal' => $opcao['preco'] * $unidades_a_comprar,
                'prazo' => $opcao['prazo'],
                'stock_disponivel' => $opcao['stock']
            ];
            $restante -= $unidades_a_comprar;
        }
    }
    return ['itens' => $divisao, 'completo' => ($restante == 0)];
}

while ($item = $resultado->fetch_assoc()) {
    $nome_limpo = trim($item['nome_produto']);
    $nome_url = urlencode($nome_limpo);
    $qtd_total = $item['quantidade'];

    $opcoes_produto = [];
    $lojas_urls = [
        'Worten' => "http://localhost/comparador/public/api/worten.php?q=$nome_url",
        'Fnac'   => "http://localhost/comparador/public/api/fnac.php?q=$nome_url",
        'PCDIGA' => "http://localhost/comparador/public/api/pcdiga.php?q=$nome_url"
    ];

    foreach ($lojas_urls as $nome_loja => $url) {
        $res = consultarAPI($url);
        if ($res && $res['preco'] > 0 && $res['stock'] > 0) {
            $opcoes_produto[] = [
                'loja' => $nome_loja,
                'preco' => floatval($res['preco']),
                'prazo' => intval($res['entrega_dias'] ?? 99),
                'stock' => intval($res['stock']),
                'pontos' => floatval($res['preco']) + (intval($res['entrega_dias'] ?? 99) * 5)
            ];
        }
    }

    if (!empty($opcoes_produto)) {
        // Distribuir para cada estratégia
        foreach (['Mais Barato' => 'preco', 'Mais Rápido' => 'prazo', 'Equilibrado' => 'pontos'] as $tipo => $criterio) {
            $distribuicao = distribuirPorLojas($qtd_total, $opcoes_produto, $criterio);
            foreach ($distribuicao['itens'] as $fatia) {
                $carrinhos[$tipo]['itens'][] = array_merge($fatia, ['nome' => $nome_limpo]);
                $carrinhos[$tipo]['total'] += $fatia['subtotal'];
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Comparador de Stock Inteligente</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; padding: 20px; }
        .grid { display: flex; gap: 20px; justify-content: center; flex-wrap: wrap; }
        .card { background: white; border-radius: 15px; width: 380px; padding: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border-top: 8px solid #ccc; }
        .Mais.Barato { border-top-color: #2196f3; }
        .Mais.Rápido { border-top-color: #ff9800; }
        .Equilibrado { border-top-color: #9c27b0; }
        .item-box { background: #fafafa; padding: 10px; border-radius: 8px; margin-bottom: 10px; border-left: 4px solid #ddd; }
        .loja-tag { background: #333; color: white; padding: 2px 6px; border-radius: 4px; font-size: 0.7rem; }
        .total { font-size: 1.8rem; font-weight: bold; text-align: center; margin-top: 20px; }
    </style>
</head>
<body>

    <h1 style="text-align:center;">🧩 Estratégias com Divisão de Stock</h1>

    <div class="grid">
        <?php foreach ($carrinhos as $tipo => $dados): ?>
            <div class="card <?php echo $tipo; ?>">
                <h2><?php echo $tipo; ?></h2>
                
                <?php 
                $last_name = "";
                foreach ($dados['itens'] as $it): 
                    if ($it['nome'] !== $last_name) {
                        echo "<h4 style='margin-bottom:10px; color:#555;'>{$it['nome']}</h4>";
                        $last_name = $it['nome'];
                    }
                ?>
                    <div class="item-box">
                        <span class="loja-tag"><?php echo $it['loja']; ?></span>
                        <strong><?php echo $it['qtd']; ?> unidades</strong> x <?php echo number_format($it['preco_uni'], 2); ?>€
                        <div style="font-size: 0.8rem; color: #777;">
                            Subtotal: <?php echo number_format($it['subtotal'], 2); ?>€ | Prazo: <?php echo $it['prazo']; ?>d
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="total">
                    <small style="font-size: 0.9rem; color:#888;">TOTAL:</small><br>
                    <?php echo number_format($dados['total'], 2); ?>€
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div id="barra-checkout">
    <span>Selecionou a estratégia: <strong id="nome-plano-selecionado"></strong></span>
    <button class="btn-comprar" onclick="finalizarCompra()">🛒 Finalizar e Comprar</button>
</div>
<script>
function selecionarPlano(idCard, nomeReal) {
    // 1. Remover a classe 'selecionado' de todos os cards
    document.querySelectorAll('.card').forEach(card => {
        card.classList.remove('selecionado');
    });

    // 2. Adicionar a classe ao card clicado
    const cardClicado = document.getElementById('card-' + idCard);
    cardClicado.classList.add('selecionado');

    // 3. Mostrar a barra de checkout e atualizar o nome
    document.getElementById('barra-checkout').style.display = 'block';
    document.getElementById('nome-plano-selecionado').innerText = nomeReal;

    // 4. Scroll suave para a barra de checkout
    window.scrollTo({
        top: document.body.scrollHeight,
        behavior: 'smooth'
    });
}

function finalizarCompra() {
    alert("Parabéns! A tua encomenda foi processada com base no stock real das lojas.");
}
</script>
</body>
</html>