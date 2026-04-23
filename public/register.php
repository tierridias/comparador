<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta — Comparador</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="auth-page">

    <a href="index.php" class="back-button">← Voltar</a>

    <main class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>Criar Conta</h1>
            </div>
            
            <form id="registerForm" action="processar_registo.php" method="POST">
                <div class="input-group">
                    <input type="text" id="nome" name="nome" placeholder="Nome Completo" required>
                </div>

                <div class="input-group">
                    <input type="email" id="email" name="email" placeholder="Email" required>
                </div>

                <div class="input-group">
                    <input type="password" id="senha" name="senha" placeholder="Palavra-passe" required>
                </div>

                <div class="input-group">
                    <input type="password" id="senha_conf" name="senha_conf" placeholder="Confirmar Palavra-passe" required>
                </div>

                <button type="submit" class="btn-cta auth-btn">
                    Criar Conta
                </button>
            </form>

            <div class="auth-footer">
                <div class="divider"></div>
                <p>Já tens uma conta? <a href="login.php">Faz login aqui</a></p>
            </div>
        </div>
    </main>

    <script src="../assets/js/register.js"></script>
</body>
</html>