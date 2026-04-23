<?php
require_once "../includes/bd_connect.php";
session_start();

$mensagem = "";
if (isset($_GET['sucesso'])) {
    $mensagem = "<div class='msg-sucesso'>✔ Produto adicionado com sucesso!</div>";
}

$sql = "SELECT * FROM produtos ORDER BY categoria, nome_produto";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo — Comparador</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="app-page">

    <header class="main-header">
        <a href="index.php" class="logo">⚡ COMPARADOR</a>
        <nav class="nav-links">
            <a href="ver_carrinho.php" class="cart-link">Carrinho</a>
            <a href="logout.php" class="btn-signup">Sair</a>
        </nav>
    </header>

    <main class="catalog-container">
        <div class="selection-header">
            <?php echo $mensagem; ?>
        </div>

        <div class="filter-bar">
    <div class="categories">
        <button class="btn-filter active" data-category="todos">Todos</button>
        <button class="btn-filter" data-category="rato">Ratos</button>
        <button class="btn-filter" data-category="teclado">Teclados</button>
        <button class="btn-filter" data-category="monitor">Monitores</button>
    </div>
    <div class="search-container">
        <input type="text" id="search-input" placeholder="Pesquisar produto...">
    </div>
</div>

<div class="grid-produtos" id="product-grid">
    <?php while($row = $resultado->fetch_assoc()): ?>
        <div class="card" data-category="<?php echo strtolower($row['categoria']); ?>" data-name="<?php echo strtolower($row['nome_produto']); ?>">
            <div class="product-info">
                <span class="categoria-tag"><?php echo $row['categoria']; ?></span>
                <h3><?php echo $row['nome_produto']; ?></h3>
                <p><?php echo $row['descricao']; ?></p>
            </div>

            <form action="api/adicionar_orcamento.php" method="POST" class="form-adicionar">
                <input type="hidden" name="id_produto" value="<?php echo $row['id_produto']; ?>">
                <div class="qtd-wrapper">
                    <span>Qtd:</span>
                    <input type="number" name="quantidade" value="1" min="1" class="input-qtd">
                </div>
                <button type="submit" class="btn-add">Adicionar</button>
            </form>
        </div>
    <?php endwhile; ?>
</div>
        
        <div class="floating-footer">
            <a href="ver_carrinho.php" class="btn-cta">
                Ir para o Carrinho →    
            </a>
        </div>
    </main>

    <script src="../assets/js/catalog.js"></script>
</body>
</html>