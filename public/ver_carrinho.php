<?php
require_once "../includes/bd_connect.php";
session_start();

if (isset($_GET['remover_id'])) {
    $id_item = intval($_GET['remover_id']);
    $id_cliente = $_SESSION['id_cliente'];

    // Apaga o item se ele pertencer ao orçamento do cliente logado
    $sql_delete = "DELETE i FROM itens_orcamento i 
                   INNER JOIN orcamentos o ON i.id_orcamento = o.id_orcamento 
                   WHERE i.id_item = ? AND o.id_cliente = ?";
    
    $stmt_del = $conn->prepare($sql_delete);
    $stmt_del->bind_param("ii", $id_item, $id_cliente);
    $stmt_del->execute();

    header("Location: ver_carrinho.php");
    exit();
}

if (!isset($_SESSION['id_cliente'])) {
    header("Location: login.php");
    exit();
}

$id_cliente = $_SESSION['id_cliente'];

// Procurar o carrinho (orçamento aberto)
$sql_carrinho = "SELECT id_orcamento FROM orcamentos WHERE id_cliente = $id_cliente AND status = 'aberto' LIMIT 1";
$res = $conn->query($sql_carrinho);

$itens = [];
$id_orcamento = 0;

if ($res->num_rows > 0) {
    $dados = $res->fetch_assoc();
    $id_orcamento = $dados['id_orcamento'];

    // Buscar os produtos que estão no carrinho
    $sql_itens = "SELECT i.id_item, p.nome_produto, i.quantidade 
                  FROM itens_orcamento i 
                  INNER JOIN produtos p ON i.id_produto = p.id_produto 
                  WHERE i.id_orcamento = $id_orcamento";
    $itens = $conn->query($sql_itens);
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Meu Carrinho — Comparador</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container" style="max-width: 800px; margin: auto; padding: 20px;">
        <h1>🛒 O Teu Carrinho</h1>

        <?php if ($id_orcamento > 0 && $itens->num_rows > 0): ?>
            <table style="width:100%; border-collapse: collapse;">
                <tr style="border-bottom: 2px solid #333;">
                    <th style="text-align:left;">Produto</th>
                    <th>Qtd</th>
                    <th>Ação</th>
                </tr>
                <?php while($item = $itens->fetch_assoc()): ?>
                <tr style="border-bottom: 1px solid #ddd;">
                    <td style="padding: 10px;"><?php echo $item['nome_produto']; ?></td>
                    <td style="text-align:center;"><?php echo $item['quantidade']; ?></td>
                    <td style="text-align:center;">
                        <a href="ver_carrinho.php?remover_id=<?php echo $item['id_item']; ?>">Remover</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>

            <div style="margin-top: 40px; background: #f4f4f4; padding: 20px; border-radius: 10px; text-align: center;">
                <h3>Finalizar Comparação</h3>
                <form action="api/comparador.php" method="POST">
                    <input type="hidden" name="id_orcamento" value="<?php echo $id_orcamento; ?>">
                    
                    <p>Quanto dinheiro pretendes gastar no total?</p>
                    <div style="margin-bottom: 20px;">
                        <input type="number" name="orcamento_maximo" placeholder="Ex: 500" step="0.01" required 
                               style="padding: 10px; font-size: 1.2rem; width: 200px; border-radius: 5px; border: 1px solid #ccc;">
                        <span style="font-size: 1.5rem; font-weight: bold;"> €</span>
                    </div>

                    <button type="submit" style="background: #28a745; color: white; padding: 15px 40px; border: none; border-radius: 5px; font-size: 1.2rem; cursor: pointer; font-weight: bold;">
                        🔍 Comparar Preços Agora
                    </button>
                </form>
            </div>

        <?php else: ?>
            <p style="text-align:center;">O teu carrinho está vazio.</p>
            <div style="text-align:center;"><a href="selecionar_produto.php">Voltar aos produtos</a></div>
        <?php endif; ?>
    </div>
</body>
</html>