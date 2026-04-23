<?php
// Inicia a sessão para verificar se o utilizador está logado
session_start();
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comparador Inteligente</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <header>
        <a href="index.php" class="logo">⚡ COMPARADOR</a>
        <nav class="nav-links">
            <?php if (isset($_SESSION['id_cliente'])): ?>
                <a href="selecionar_produto.php">Catálogo</a>
                <a href="ver_carrinho.php">Carrinho</a>
                <a href="logout.php" class="btn-signup" style="background: #ff4d4d; color: white;">Sair</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php" class="btn-signup">Criar Conta</a>
            <?php endif; ?>
        </nav>
    </header>

    <main class="hero">
        <h1>Preços baixos.<br>Sem esforço.</h1>
        <p style="color: #64748b; margin-bottom: 30px;">
            <?php 
                if(isset($_SESSION['nome_cliente'])) {
                    echo "Bem-vindo de volta, " . htmlspecialchars($_SESSION['nome_cliente']) . "!";
                }
            ?>
        </p>
        <a href="selecionar_produto.php" class="btn-cta">
            Começar Agora
        </a>
    </main>

    <footer class="features-footer">
        <div class="features-container">
            <div class="feature-item">
                <span class="icon">💰</span>
                <h3>Mais Barato</h3>
                <p>Otimização radical.</p>
            </div>
            <div class="feature-item">
                <span class="icon">⚡</span>
                <h3>Mais Rápido</h3>
                <p>Stock local prioritário.</p>
            </div>
            <div class="feature-item">
                <span class="icon">⚖️</span>
                <h3>Equilibrado</h3>
                <p>Melhor custo-benefício.</p>
            </div>
        </div>
    </footer>

</body>
</html>