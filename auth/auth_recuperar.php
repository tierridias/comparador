<?php
date_default_timezone_set('Europe/Lisbon');
require_once __DIR__ . "/../includes/bd_connect.php";
require_once __DIR__ . "/../includes/config_email.php";
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';

    $stmt = $conn->prepare("SELECT id_cliente FROM clientes WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $token = bin2hex(random_bytes(32));
        $expira = date("Y-m-d H:i:s", strtotime("+1 hour"));

        $update = $conn->prepare("UPDATE clientes SET reset_token = ?, reset_token_expira = ? WHERE email = ?");
        $update->bind_param("sss", $token, $expira, $email);
        $update->execute();

        $link = "http://localhost/comparador/public/redefinir_pass.php?token=" . $token;

        $assunto = "Recuperacao de Password - Comparador";
        $mensagem = "Clique no link abaixo para redefinir a sua senha (válido por 30 min):<br><br>
                     <a href='$link'>$link</a>";

        if (enviarEmail($email, $assunto, $mensagem)) {
            echo json_encode(["sucesso" => true, "mensagem" => "Link enviado! Verifique o e-mail."]);
        } else {
            echo json_encode(["sucesso" => false, "mensagem" => "Erro ao enviar e-mail."]);
        }
    } else {
        // Por segurança, não confirmamos se o email existe ou não, mas aqui podes avisar
        echo json_encode(["sucesso" => false, "mensagem" => "E-mail não encontrado."]);
    }
}