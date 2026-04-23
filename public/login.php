<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar — Comparador</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="auth-page">

    <a href="index.php" class="back-button">← Voltar</a>

    <main class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>Bem-vindo de volta</h1>
                <p>Insere os teus dados para aceder à conta.</p>
            </div>
            
            <form id="loginForm">
                <div class="input-group">
                    <input type="email" id="email" name="email" placeholder="Email" required>
                </div>

                <div class="input-group">
                    <input type="password" id="senha" name="senha" placeholder="Palavra-passe" required>
                </div>

                <button type="submit" class="btn-cta auth-btn">
                    Entrar na Conta
                </button>
            </form>

            <div class="auth-footer">
                <a href="recuperar_pass.php" class="forgot-link">Esqueci-me da senha</a>
                <div class="divider"></div>
                <p>Ainda não tens conta? <a href="register.php">Cria uma agora</a></p>
            </div>
        </div>
    </main>

    <script src="../assets/js/login.js"></script>
</body>
</html>