<?php
require_once "../includes/bd_connect.php";

$token = $_GET['token'] ?? '';
$token_valido = false;

if (!empty($token)) {
    $stmt = $conn->prepare("SELECT id_cliente FROM clientes WHERE reset_token = ? AND reset_token_expira > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $res = $stmt->get_result();
    
    if ($res->num_rows > 0) {
        $token_valido = true;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comparador — Nova Password</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="auth-page">

    <main class="auth-container">
        <div class="auth-card">
            <?php if ($token_valido): ?>
                <div class="auth-header">
                    <h1>Nova Password</h1>
                    <p>Escolhe a tua nova palavra-passe abaixo.</p>
                </div>
                
                <form id="form-redefinir">
                    <input type="hidden" id="token" value="<?php echo htmlspecialchars($token); ?>">

                    <div class="input-group" style="margin-bottom: 15px;">
                        <label for="nova_senha">Nova Password</label>
                        <input type="password" id="nova_senha" placeholder="••••••••" required 
                               style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd; margin-top: 5px;">
                    </div>

                    <div class="input-group">
                        <label for="conf_senha">Confirmar Password</label>
                        <input type="password" id="conf_senha" placeholder="••••••••" required 
                               style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd; margin-top: 5px;">
                    </div>

                    <button type="submit" class="btn-cta auth-btn" style="width: 100%; margin-top: 25px; border: none; cursor: pointer;">
                        Alterar Password
                    </button>
                </form>

            <?php else: ?>
                <div class="auth-header" style="text-align: center;">
                    <span style="font-size: 3rem;">⚠️</span>
                    <h1 style="color: #ef4444; margin-top: 10px;">Link Expirado</h1>
                    <p>O link de recuperação já não é válido ou já foi utilizado.</p>
                </div>
                
                <div class="auth-footer" style="margin-top: 20px;">
                    <a href="recuperar_pass.php" class="btn-cta" style="text-decoration: none; display: block; text-align: center; background: #64748b;">
                        Pedir novo link
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script src="../assets/js/redefinir.js"></script>
</body>
</html>