<?php
$host = 'localhost';
$user = 'comparador';
$password = 'Senha123!';
$db   = 'comparador';

$conn = new mysqli($host, $user, $password, $db);
if ($conn->connect_error) {
    die("Erro na ligação: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>
