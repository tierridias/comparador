<?php
require_once "../../includes/bd_connect.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_orcamento'])) {
    $id_orcamento = intval($_POST['id_orcamento']);
    
    // Atualiza o orçamento para 'finalizado'
    $stmt = $conn->prepare("UPDATE orcamentos SET status = 'finalizado' WHERE id_orcamento = ?");
    $stmt->bind_param("i", $id_orcamento);
    
    if ($stmt->execute()) {
        echo json_encode(['sucesso' => true]);
    } else {
        echo json_encode(['sucesso' => false]);
    }
}