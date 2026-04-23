<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Password — Comparador</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="auth-page">

    <main class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>Recuperar Password</h1>
                <p>Introduz o teu e-mail para receberes um link de redefinição.</p>
            </div>
            
            <form id="form-recuperar">
                <div class="input-group">
                    <label for="email">E-mail da Conta</label>
                    <input type="email" id="email" placeholder="exemplo@email.com" required 
                           style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd; margin-top: 5px;">
                </div>

                <button type="submit" class="btn-cta auth-btn" style="width: 100%; margin-top: 20px; border: none; cursor: pointer;">
                    Enviar Link de Recuperação
                </button>
            </form>

            <div class="auth-footer">
                <p><a href="login.php" class="forgot-link">← Voltar ao Login</a></p>
            </div>
        </div>
    </main>

    <script src="../assets/js/recuperar.js"></script>
</body>
</html>