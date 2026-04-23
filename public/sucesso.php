<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sucesso — Comparador</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="auth-page"> <main class="auth-container">
        <div class="auth-card success-anim">
            <div class="success-icon-wrapper">
                <span class="success-icon">✔</span>
            </div>
            
            <div class="auth-header">
                <h1>Tudo Pronto!</h1>
                <p>A tua estratégia foi processada com sucesso e a simulação de stock foi concluída.</p>
            </div>

            <div class="success-footer">
                <a href="selecionar_produto.php" class="btn-cta btn-full" style="text-decoration: none; display: block; text-align: center;">
                    Fazer Nova Comparação
                </a>
                <a href="index.php" class="forgot-link" style="margin-top: 20px; display: block;">Voltar ao Início</a>
            </div>
        </div>
    </main>

</body>
</html>