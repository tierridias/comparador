<?php

header('Content-Type: application/json');

$q = strtolower($_GET['q'] ?? '');

$produtos = [
    "logitech g203" => [
        "nome" => "Logitech G203 Lightsync",
        "preco" => 22.99
    ],
    "logitech g pro x superlight" => [
        "nome" => "Logitech G Pro X Superlight",
        "preco" => 114.99
    ],
    "razer deathadder v2" => [
        "nome" => "Razer DeathAdder V2",
        "preco" => 41.50
    ],
    "razer basilisk v3" => [
        "nome" => "Razer Basilisk V3",
        "preco" => 57.90
    ],
    "steelseries rival 3" => [
        "nome" => "SteelSeries Rival 3",
        "preco" => 27.50
    ]
];

if (isset($produtos[$q])) {
    echo json_encode([
        "loja" => "FNAC",
        "produto" => $produtos[$q]["nome"],
        "preco" => $produtos[$q]["preco"]
    ]);
} else {
    echo json_encode([
        "loja" => "FNAC",
        "produto" => $q,
        "preco" => null,
        "erro" => "Produto não encontrado"
    ]);
}