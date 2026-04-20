<?php session_start(); ?>
<nav>
    <?php if(isset($_SESSION['user_id'])): ?>
        <span>Olá, <?php echo $_SESSION['user_nome']; ?>!</span>
        <a href="logout.php">Sair</a>
    <?php else: ?>
        <a href="login.php">Login</a>
        <a href="register.php" class="register">Registar</a>
    <?php endif; ?>
</nav>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Comparador - Landing Page</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <div class="wrapper">
        <div class="content">
            <header>
                <div class="logo">Comparador</div>
                <nav>
                    <a href="login.php">Login</a>
                    <a href="register.php" class="register">Registar</a>
                </nav>
            </header>

            <main>
                <div class="search-area">
                    <div class="top-row">
                        <button class="selecionar-produto">Selecionar produto</button>
                    </div class="inputs">
                    <input class="selecionar-quantidade" type="number" name="quantidade" min="1" placeholder="Qtd">
                    <input class="selecionar-orcamento" type="number" name="orcamento" min="0" step="0.01" placeholder="Orçamento (€)">
                    </div>
            </main>
        </div>
    </div>

</body>
</html>
