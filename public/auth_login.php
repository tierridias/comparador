<?php
require_once "../includes/bd_connect.php";
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (empty($email) || empty($senha)) {
        echo json_encode(["sucesso" => false, "mensagem" => "Preencha todos os campos."]);
        exit;
    }

    try {
        $stmt = $conn->prepare("SELECT id_cliente, nome, password FROM clientes WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($user = $resultado->fetch_assoc()) {
            // password_verify deteta automaticamente se é Argon2 ou BCrypt
            if (password_verify($senha, $user['password'])) {
                
                // PADRONIZAÇÃO DAS SESSÕES:
                $_SESSION['id_cliente'] = $user['id_cliente'];
                $_SESSION['nome_cliente'] = $user['nome']; // Alterado de user_nome para nome_cliente

                echo json_encode(["sucesso" => true]);
            } else {
                echo json_encode(["sucesso" => false, "mensagem" => "Senha incorreta."]);
            }
        } else {
            echo json_encode(["sucesso" => false, "mensagem" => "Utilizador não encontrado."]);
        }
    } catch (Exception $e) {
        echo json_encode(["sucesso" => false, "mensagem" => "Erro no servidor."]);
    }
}