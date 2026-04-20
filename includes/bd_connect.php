<?php
$host = 'localhost';
$user = 'root';      // Utilizador padrão
$password = 'mysql'; // Senha padrão do Ampps (se não mudaste)
$db   = 'comparador'; // O nome da base de dados que criaste no phpMyAdmin

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($host, $user, $password, $db);
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    // Se ainda der erro, vamos mostrar o erro REAL para sabermos o que é
    die("Erro real: " . $e->getMessage()); 
}
?>