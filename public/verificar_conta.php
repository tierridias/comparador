<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Conta — Comparador</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="app-page" style="display: flex; align-items: center; justify-content: center; min-height: 100vh;">

    <main class="auth-container" style="width: 100%; max-width: 400px;">
        <a href="register.php" class="link-remover" style="display: block; margin-bottom: 20px; text-decoration: none;">← Voltar ao registo</a>
        
        <div class="card" style="text-align: center; padding: 40px;">
            <div class="product-info">
                <h1 style="font-size: 1.8rem; margin-bottom: 10px;">Verificar E-mail</h1>
                <p style="color: #666; font-size: 0.9rem; margin-bottom: 30px;">
                    Introduz o código de 6 dígitos que enviámos para o teu e-mail.
                </p>
            </div>
            
            <form id="form-verify">
                <input type="hidden" id="email" value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>">

                <div style="margin-bottom: 25px;">
                    <input type="text" id="codigo" class="input-qtd" placeholder="000000" maxlength="6" required 
                           style="width: 100%; font-size: 2rem; letter-spacing: 10px; height: auto; padding: 15px; text-align: center; border-radius: 12px;">
                </div>

                <button type="submit" class="btn-add" style="width: 100%; padding: 15px; font-size: 1rem;">
                    Ativar Conta
                </button>
            </form>

            <div style="margin-top: 25px; font-size: 0.85rem; color: #777;">
                Não recebeste o código? <a href="#" id="resend-code" style="color: #007bff; font-weight: 600; text-decoration: none;">Reenviar código</a>
            </div>
        </div>
    </main>

    <script src="../assets/js/verify.js"></script>
</body>
</html>