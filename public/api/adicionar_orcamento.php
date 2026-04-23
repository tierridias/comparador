<?php
session_start();
require_once "../../includes/bd_connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['id_cliente'])) {
    $id_cliente = $_SESSION['id_cliente'];
    $id_produto = intval($_POST['id_produto']);
    $quantidade = intval($_POST['quantidade'] ?? 1);

    $sql_orcamento = "SELECT id_orcamento FROM orcamentos WHERE id_cliente = ? AND status = 'aberto' LIMIT 1";
    $stmt = $conn->prepare($sql_orcamento);
    $stmt->bind_param("i", $id_cliente);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $id_orcamento = $res->fetch_assoc()['id_orcamento'];
    } else {
        $stmt_novo = $conn->prepare("INSERT INTO orcamentos (id_cliente, data_criacao, status) VALUES (?, NOW(), 'aberto')");
        $stmt_novo->bind_param("i", $id_cliente);
        $stmt_novo->execute();
        $id_orcamento = $conn->insert_id;
    }

    $sql_item = "SELECT id_item, quantidade FROM itens_orcamento WHERE id_orcamento = ? AND id_produto = ?";
    $stmt_item = $conn->prepare($sql_item);
    $stmt_item->bind_param("ii", $id_orcamento, $id_produto);
    $stmt_item->execute();
    $res_item = $stmt_item->get_result();

    if ($res_item->num_rows > 0) {
        $item_existente = $res_item->fetch_assoc();
        $nova_qtd = $item_existente['quantidade'] + $quantidade;
        $upd = $conn->prepare("UPDATE itens_orcamento SET quantidade = ? WHERE id_item = ?");
        $upd->bind_param("ii", $nova_qtd, $item_existente['id_item']);
        $upd->execute();
    } else {
        $ins = $conn->prepare("INSERT INTO itens_orcamento (id_orcamento, id_produto, quantidade) VALUES (?, ?, ?)");
        $ins->bind_param("iii", $id_orcamento, $id_produto, $quantidade);
        $ins->execute();
    }

    header("Location: ../selecionar_produto.php?sucesso=1");
    exit();
} else {
    header("Location: ../login.php");
    exit();
}