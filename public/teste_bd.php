<?php
// teste_db.php
include_once '../includes/db_connect.php';

// Testar ligação
if ($conn) {
    echo "Ligação à base de dados bem-sucedida! ✅";
} else {
    echo "Erro na ligação: " . $conn->connect_error;
}
?>
