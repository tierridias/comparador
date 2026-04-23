<?php
ob_start();

require_once "../includes/bd_connect.php";
require_once "../includes/config_email.php"; 

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (empty($nome) || empty($email) || empty($senha)) {
        ob_clean();
        echo json_encode(["sucesso" => false, "mensagem" => "Preencha todos os campos."]);
        exit;
    }

    $senhaHash = password_hash($senha, PASSWORD_ARGON2ID);
    $codigo = mt_rand(100000, 999999);

    try {
        $stmt = $conn->prepare("INSERT INTO clientes (nome, email, password, codigo_verificacao, email_verificado) VALUES (?, ?, ?, ?, 0)");
        $stmt->bind_param("sssi", $nome, $email, $senhaHash, $codigo);

        if ($stmt->execute()) {
            $assunto = "Verificação de Conta - Comparador";
            $mensagem = "Olá $nome! O teu código de verificação é: <b>$codigo</b>";

            $emailEnviado = enviarEmail($email, $assunto, $mensagem);

            ob_clean();

            if ($emailEnviado) {
                echo json_encode(["sucesso" => true, "proximo" => "verificar"]);
            } else {
                echo json_encode([
                    "sucesso" => true, 
                    "proximo" => "verificar", 
                    "aviso" => "Conta criada, mas o serviço de e-mail falhou. Verifica o código na base de dados."
                ]);
            }
            exit;

        } else {
            ob_clean();
            echo json_encode(["sucesso" => false, "mensagem" => "Email já registado."]);
            exit;
        }
    } catch (Exception $e) {
        ob_clean();
        echo json_encode(["sucesso" => false, "mensagem" => "Erro no servidor: " . $e->getMessage()]);
        exit;
    }
}