<?php

require_once "../includes/bd_connect.php";

$q = $_GET['q'] ?? '';

header('Content-Type: application/json');

$q = urlencode($_GET['q'] ?? '');


$worten = json_decode(file_get_contents("worten.php?q=$q"), true);
$fnac = json_decode(file_get_contents("fnac.php?q=$q"), true);

$opcoes = [];

if ($worten && $worten['preco'] !== null) {
    $opcoes[] = $worten;
}

if ($fnac && $fnac['preco'] !== null) {
    $opcoes[] = $fnac;
}

if (empty($opcoes)) {
    echo json_encode([
        "erro" => "Produto não encontrado em nenhuma loja"
    ]);
    exit;
}

$maisBarato = $opcoes[0];

foreach ($opcoes as $opcao) {
    if ($opcao['preco'] < $maisBarato['preco']) {
        $maisBarato = $opcao;
    }
}

echo json_encode([ 
    "produto" => $maisBarato['produto'],
    "melhor_preco" => $maisBarato['preco'],
    "melhor_loja" => $maisBarato['loja'],
    "opcoes" => $opcoes
]);