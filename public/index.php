<?php session_start(); ?>
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
                    <?php if(isset($_SESSION['id_cliente'])): ?>
                        <span>Olá, <strong><?php echo $_SESSION['nome_cliente']; ?></strong>!</span>
                        <a href="logout.php" style="margin-left:10px; color:red;">Sair</a>
                    <?php else: ?>
                        <a href="login.php">Login</a>
                        <a href="register.php" class="register">Registar</a>
                    <?php endif; ?>
                </nav>
            </header>

            <main>
                <div class="search-area">
                    <div class="top-row">
                        <a href="selecionar_produto.php">
                            <button class="selecionar-produto">Selecionar produto</button>
                        </a>
                    </div>
                    
                    <div class="inputs">
                        <input class="selecionar-quantidade" type="number" name="quantidade" min="1" placeholder="Qtd">
                        <input class="selecionar-orcamento" type="number" name="orcamento" min="0" step="0.01" placeholder="Orçamento (€)">
                    </div>
                </div>
            </main>
        </div>
    </div>

</body>
</html>