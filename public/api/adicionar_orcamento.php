<?php
require_once "../../includes/bd_connect.php";
session_start();

if (!isset($_SESSION['id_cliente'])) {
    header("Location: ../login.php?erro=sessao_expirada");
    exit();
}

$id_cliente = $_SESSION['id_cliente'];
$id_produto = isset($_POST['id_produto']) ? intval($_POST['id_produto']) : 0;
$quantidade = isset($_POST['quantidade']) ? intval($_POST['quantidade']) : 1;

if ($id_produto <= 0 || $quantidade <= 0) {
    header("Location: ../selecionar_produto.php?erro=dados_invalidos");
    exit();
}

try {
    $stmt = $conn->prepare("SELECT id_orcamento FROM orcamentos WHERE id_cliente = ? AND status = 'aberto' LIMIT 1");
    $stmt->bind_param("i", $id_cliente);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $orcamento = $resultado->fetch_assoc();
        $id_orcamento = $orcamento['id_orcamento'];
    } else {
        $stmt_novo = $conn->prepare("INSERT INTO orcamentos (id_cliente, status) VALUES (?, 'aberto')");
        $stmt_novo->bind_param("i", $id_cliente);
        $stmt_novo->execute();
        $id_orcamento = $conn->insert_id;
    }

    $stmt_item = $conn->prepare("SELECT id_item, quantidade FROM itens_orcamento WHERE id_orcamento = ? AND id_produto = ?");
    $stmt_item->bind_param("ii", $id_orcamento, $id_produto);
    $stmt_item->execute();
    $res_item = $stmt_item->get_result();

    if ($res_item->num_rows > 0) {
        $item_atual = $res_item->fetch_assoc();
        $nova_qtd = $item_atual['quantidade'] + $quantidade;
        
        $upd = $conn->prepare("UPDATE itens_orcamento SET quantidade = ? WHERE id_item = ?");
        $upd->bind_param("ii", $nova_qtd, $item_atual['id_item']);
        $upd->execute();
    } else {
        $ins = $conn->prepare("INSERT INTO itens_orcamento (id_orcamento, id_produto, quantidade) VALUES (?, ?, ?)");
        $ins->bind_param("iii", $id_orcamento, $id_produto, $quantidade);
        $ins->execute();
    }

    header("Location: ../selecionar_produto.php?sucesso=1");
    exit();

} catch (Exception $e) {
    die("Erro ao processar orçamento: " . $e->getMessage());
}