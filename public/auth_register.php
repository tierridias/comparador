<?php
require_once "../includes/bd_connect.php";
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (empty($nome) || empty($email) || empty($senha)) {
        echo json_encode(["sucesso" => false, "mensagem" => "Preencha todos os campos."]);
        exit;
    }

    // ALTERAÇÃO: Usar PASSWORD_ARGON2ID em vez de DEFAULT
    $senhaHash = password_hash($senha, PASSWORD_ARGON2ID);

    try {
        $stmt = $conn->prepare("INSERT INTO clientes (nome, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nome, $email, $senhaHash);

        if ($stmt->execute()) {
            echo json_encode(["sucesso" => true]);
        } else {
            echo json_encode(["sucesso" => false, "mensagem" => "Email já registado."]);
        }
    } catch (Exception $e) {
        echo json_encode(["sucesso" => false, "mensagem" => "Erro no servidor."]);
    }
}