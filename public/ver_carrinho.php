<?php
require_once "../includes/bd_connect.php";
session_start();

if (!isset($_SESSION['id_cliente'])) {
    header("Location: login.php");
    exit();
}

$id_cliente = $_SESSION['id_cliente'];

// Lógica de Remoção
if (isset($_GET['remover_id'])) {
    $id_item = intval($_GET['remover_id']);
    $sql_delete = "DELETE i FROM itens_orcamento i 
                   INNER JOIN orcamentos o ON i.id_orcamento = o.id_orcamento 
                   WHERE i.id_item = ? AND o.id_cliente = ?";
    
    $stmt_del = $conn->prepare($sql_delete);
    $stmt_del->bind_param("ii", $id_item, $id_cliente);
    $stmt_del->execute();

    header("Location: ver_carrinho.php");
    exit();
}

// Procurar Carrinho Aberto
$sql_carrinho = "SELECT id_orcamento FROM orcamentos WHERE id_cliente = ? AND status = 'aberto' LIMIT 1";
$stmt_cart = $conn->prepare($sql_carrinho);
$stmt_cart->bind_param("i", $id_cliente);
$stmt_cart->execute();
$res = $stmt_cart->get_result();

$itens = [];
$id_orcamento = 0;

if ($res->num_rows > 0) {
    $dados = $res->fetch_assoc();
    $id_orcamento = $dados['id_orcamento'];

    $sql_itens = "SELECT i.id_item, p.nome_produto, i.quantidade 
                  FROM itens_orcamento i 
                  INNER JOIN produtos p ON i.id_produto = p.id_produto 
                  WHERE i.id_orcamento = ?";
    $stmt_itens = $conn->prepare($sql_itens);
    $stmt_itens->bind_param("i", $id_orcamento);
    $stmt_itens->execute();
    $itens = $stmt_itens->get_result();
}
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Carrinho — Comparador</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="app-page">

    <header class="main-header">
        <a href="index.php" class="logo">⚡ COMPARADOR</a>
        <nav class="nav-links">
            <a href="selecionar_produto.php">Voltar ao Catálogo</a>
            <a href="logout.php" class="btn-signup">Sair</a>
        </nav>
    </header>

    <main class="cart-wrapper">
        <div class="cart-card">
            <h1>🛒 O Teu Carrinho</h1>

            <?php if ($id_orcamento > 0 && $itens->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Qtd</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($item = $itens->fetch_assoc()): ?>
                            <tr>
                                <td><strong><?php echo $item['nome_produto']; ?></strong></td>
                                <td class="text-center"><?php echo $item['quantidade']; ?></td>
                                <td class="text-center">
                                    <a href="ver_carrinho.php?remover_id=<?php echo $item['id_item']; ?>" class="link-remover">Remover</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <div class="checkout-box">
                    <h3>Finalizar Comparação</h3>
                    <p>Qual é o teu orçamento máximo para estes produtos?</p>
                    
                    <form action="api/comparador.php" method="POST">
                        <input type="hidden" name="id_orcamento" value="<?php echo $id_orcamento; ?>">
                        
                        <div class="orcamento-input-wrapper">
                            <input type="number" name="orcamento_maximo" placeholder="Ex: 500" step="0.01" required>
                            <span class="currency-symbol">€</span>
                        </div>

                        <button type="submit" class="btn-cta btn-full">
                            🔍 Comparar Preços Agora
                        </button>
                    </form>
                </div>

            <?php else: ?>
                <div class="empty-cart">
                    <p>O teu carrinho está vazio.</p>
                    <a href="selecionar_produto.php" class="btn-cta">Ir para o Catálogo</a>
                </div>
            <?php endif; ?>
        </div>
    </main>

</body>
</html>