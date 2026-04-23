<?php
require_once "../../includes/bd_connect.php";
session_start();

// Ativar exibição de erros para debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['id_cliente']) || !isset($_POST['id_orcamento'])) {
    header("Location: ../selecionar_produto.php");
    exit();
}

$id_orcamento = intval($_POST['id_orcamento']);
$orcamento_maximo = floatval($_POST['orcamento_maximo']);

// 1. Inicializar o array de carrinhos (CRUCIAL para evitar os Warnings que tinhas)
$carrinhos = [
    'Mais Barato' => ['total' => 0, 'itens' => []],
    'Mais Rapido' => ['total' => 0, 'itens' => []],
    'Equilibrado' => ['total' => 0, 'itens' => []]
];

// 2. Procurar os itens do orçamento na BD
$sql = "SELECT p.nome_produto, i.quantidade, p.id_produto 
        FROM itens_orcamento i 
        INNER JOIN produtos p ON i.id_produto = p.id_produto 
        WHERE i.id_orcamento = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_orcamento);
$stmt->execute();
$resultado = $stmt->get_result();

// Função para consultar as APIs locais
function consultarAPI($url) {
    $options = ["http" => ["method" => "GET", "header" => "User-Agent: PHP\r\n", "timeout" => 3]];
    $context = stream_context_create($options);
    $json = @file_get_contents($url, false, $context);
    return $json ? json_decode($json, true) : null;
}

// Função para distribuir o pedido entre lojas com base no stock disponível
function distribuirPorLojas($quantidade_pedida, $opcoes, $criterio) {
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
                'prazo' => $opcao['prazo']
            ];
            $restante -= $unidades_a_comprar;
        }
    }
    return $divisao;
}

// 3. Processar cada produto do carrinho
while ($item = $resultado->fetch_assoc()) {
    $nome_url = urlencode(trim($item['nome_produto']));
    $qtd_total = $item['quantidade'];

    $opcoes_produto = [];
    $lojas_urls = [
        'Worten' => "http://localhost/comparador/public/api/worten.php?q=$nome_url",
        'Fnac'   => "http://localhost/comparador/public/api/fnac.php?q=$nome_url",
        'PCDIGA' => "http://localhost/comparador/public/api/pcdiga.php?q=$nome_url"
    ];

    foreach ($lojas_urls as $nome_loja => $url) {
        $res = consultarAPI($url);
        if ($res && isset($res['preco']) && $res['preco'] > 0 && $res['stock'] > 0) {
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
        $estrategias = [
            'Mais Barato' => 'preco',
            'Mais Rapido' => 'prazo',
            'Equilibrado' => 'pontos'
        ];

        foreach ($estrategias as $tipo => $criterio) {
            $itens_distribuidos = distribuirPorLojas($qtd_total, $opcoes_produto, $criterio);
            foreach ($itens_distribuidos as $fatia) {
                $carrinhos[$tipo]['itens'][] = array_merge($fatia, ['nome' => $item['nome_produto']]);
                $carrinhos[$tipo]['total'] += $fatia['subtotal'];
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estratégias de Compra — Comparador</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body class="app-page">

    <header class="main-header">
        <a href="../index.php" class="logo">⚡ COMPARADOR</a>
        <div class="budget-badge">
            Orçamento: <strong><?php echo number_format($orcamento_maximo, 2); ?>€</strong>
        </div>
    </header>

    <main class="compare-container">
        <div class="selection-header">
            <h1>🧩 Resultados da Comparação</h1>
        </div>

        <div class="grid-estrategias">
            <?php foreach ($carrinhos as $tipo => $dados): ?>
                <?php 
                    $excede = ($dados['total'] > $orcamento_maximo);
                    $id_limpo = str_replace(' ', '-', $tipo);
                ?>
                <div class="strategy-card <?php echo $excede ? 'exceeds' : ''; ?>" 
                     id="card-<?php echo $id_limpo; ?>" 
                     onclick="selecionarPlano('<?php echo $id_limpo; ?>', '<?php echo $tipo; ?>')">
                    
                    <div class="strategy-header">
                        <h2><?php echo $tipo; ?></h2>
                        <div class="strategy-icon">
                            <?php 
                                if($tipo == 'Mais Barato') echo '💰';
                                elseif($tipo == 'Mais Rapido') echo '⚡';
                                else echo '⚖️';
                            ?>
                        </div>
                    </div>

                    <div class="strategy-content">
                        <?php 
                        $last_name = "";
                        foreach ($dados['itens'] as $it): 
                            if ($it['nome'] !== $last_name): ?>
                                <h4 class="product-title"><?php echo $it['nome']; ?></h4>
                                <?php $last_name = $it['nome']; ?>
                            <?php endif; ?>
                            
                            <div class="item-row">
                                <span class="loja-tag <?php echo strtolower($it['loja']); ?>"><?php echo $it['loja']; ?></span>
                                <div class="item-details">
                                    <span><strong><?php echo $it['qtd']; ?> un.</strong> x <?php echo number_format($it['preco_uni'], 2); ?>€</span>
                                    <small><?php echo $it['prazo']; ?> dias de entrega</small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="strategy-footer">
                        <div class="total-price <?php echo $excede ? 'price-red' : 'price-green'; ?>">
                            <small>TOTAL ESTIMADO</small>
                            <?php echo number_format($dados['total'], 2); ?>€
                        </div>
                        <?php if ($excede): ?>
                            <span class="warning-label">⚠️ Excede o orçamento</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <div id="barra-checkout" class="checkout-bar">
        <div class="checkout-bar-content">
            <p>Estratégia: <strong id="nome-plano-selecionado">-</strong></p>
            <button class="btn-cta" onclick="finalizarCompra()">Confirmar Encomenda</button>
        </div>
    </div>

    <script>
    function selecionarPlano(idCard, nomeReal) {
        document.querySelectorAll('.strategy-card').forEach(card => card.classList.remove('selected'));
        document.getElementById('card-' + idCard).classList.add('selected');
        
        const barra = document.getElementById('barra-checkout');
        barra.classList.add('visible');
        document.getElementById('nome-plano-selecionado').innerText = nomeReal;
    }

    function finalizarCompra() {
        const idOrcamento = <?php echo $id_orcamento; ?>;
        // ... (o teu fetch mantém-se igual)
        fetch('api/finalizar_pedido.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id_orcamento=' + idOrcamento
        })
        .then(() => {
            alert("Pedido confirmado!");
            window.location.href = "../sucesso.php";
        });
    }
    </script>
</body>
</html>