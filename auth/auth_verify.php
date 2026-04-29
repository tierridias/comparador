<?php
require_once __DIR__ . "/../includes/bd_connect.php";
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email  = $_POST['email'] ?? '';
    $codigo = $_POST['codigo'] ?? '';

    if (empty($email) || empty($codigo)) {
        echo json_encode(["sucesso" => false, "mensagem" => "Código necessário."]);
        exit;
    }

    $stmt = $conn->prepare("SELECT id_cliente FROM clientes WHERE email = ? AND codigo_verificacao = ?");
    $stmt->bind_param("ss", $email, $codigo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $update = $conn->prepare("UPDATE clientes SET email_verificado = 1, codigo_verificacao = NULL WHERE email = ?");
        $update->bind_param("s", $email);
        
        if ($update->execute()) {
            echo json_encode(["sucesso" => true]);
        } else {
            echo json_encode(["sucesso" => false, "mensagem" => "Erro ao ativar conta."]);
        }
    } else {
        echo json_encode(["sucesso" => false, "mensagem" => "Código inválido ou e-mail incorreto."]);
    }
}