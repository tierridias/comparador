<?php
require_once "../includes/bd_connect.php";
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $nova_senha = $_POST['senha'] ?? '';

    if (empty($token) || empty($nova_senha)) {
        echo json_encode(["sucesso" => false, "mensagem" => "Dados incompletos."]);
        exit;
    }

    $senhaHash = password_hash($nova_senha, PASSWORD_ARGON2ID);

    $stmt = $conn->prepare("UPDATE clientes SET password = ?, reset_token = NULL, reset_token_expira = NULL WHERE reset_token = ?");
    $stmt->bind_param("ss", $senhaHash, $token);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        echo json_encode(["sucesso" => true, "mensagem" => "Password alterada com sucesso!"]);
    } else {
        echo json_encode(["sucesso" => false, "mensagem" => "Erro ao atualizar ou link expirado."]);
    }
}