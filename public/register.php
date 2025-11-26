<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Comparador — Registo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<main class="container">
    <header>
        <div class="brand">Comparador</div>
        <nav>
            <a class="btn btn-outline" href="index.php">Voltar</a>
            <a class="btn btn-primary" href="login.php">Entrar</a>
        </nav>
    </header>

    <section class="form-section">
        <h1>Criar Conta</h1>
        <form>
            <label for="nome">Nome Completo</label>
            <input type="text" id="nome" placeholder="Ex: Tierri Dias">

            <label for="email">Email</label>
            <input type="email" id="email" placeholder="exemplo@email.com">

            <label for="senha">Palavra-passe</label>
            <input type="password" id="senha" placeholder="••••••••">

            <label for="senha_conf">Confirmar Palavra-passe</label>
            <input type="password" id="senha_conf" placeholder="••••••••">

            <button type="submit" class="btn btn-primary">Registar</button>
        </form>
    </section>
</main>
<script src="../assets/js/register.js"></script>
</body>
</html>
