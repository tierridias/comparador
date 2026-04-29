<?php
require_once "../../includes/bd_connect.php";
session_start();

header('Content-Type: application/json');

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['id_cliente'])) {
    echo json_encode(['sucesso' => false, 'erro' => 'Sessão inválida']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id_orcamento'])) {
    echo json_encode(['sucesso' => false, 'erro' => 'Pedido inválido']);
    exit;
}

$id_orcamento = intval($_POST['id_orcamento']);
$id_cliente = intval($_SESSION['id_cliente']);

try {

    $preco_total = floatval($_POST['preco_total'] ?? 0);

    // INSERIR COMPRA
    $insert = $conn->prepare("
        INSERT INTO compras (id_cliente, id_orcamento, preco_total)
        VALUES (?, ?, ?)
    ");

    if (!$insert) {
        throw new Exception("Erro prepare insert: " . $conn->error);
    }

    $insert->bind_param("iid", $id_cliente, $id_orcamento, $preco_total);

    if (!$insert->execute()) {
        throw new Exception("Erro execute insert: " . $insert->error);
    }

    // FECHAR ORÇAMENTO
    $update = $conn->prepare("
        UPDATE orcamentos 
        SET status = 'finalizado' 
        WHERE id_orcamento = ?
    ");

    if (!$update) {
        throw new Exception("Erro prepare update: " . $conn->error);
    }

    $update->bind_param("i", $id_orcamento);
    $update->execute();

    echo json_encode(['sucesso' => true]);

} catch (Exception $e) {
    echo json_encode([
        'sucesso' => false,
        'erro' => $e->getMessage()
    ]);
}