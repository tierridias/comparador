<?php

header('Content-Type: application/json');

$q = strtolower($_GET['q'] ?? '');

// catálogo simulado da Worten
$produtos = [
    "logitech g203" => [
        "nome" => "Logitech G203 Lightsync",
        "preco" => 24.99
    ],
    "logitech g pro x superlight" => [
        "nome" => "Logitech G Pro X Superlight",
        "preco" => 119.99
    ],
    "razer deathadder v2" => [
        "nome" => "Razer DeathAdder V2",
        "preco" => 39.99
    ],
    "razer basilisk v3" => [
        "nome" => "Razer Basilisk V3",
        "preco" => 59.99
    ],
    "steelseries rival 3" => [
        "nome" => "SteelSeries Rival 3",
        "preco" => 29.99
    ]
];

if (isset($produtos[$q])) {
    echo json_encode([
        "loja" => "Worten",
        "produto" => $produtos[$q]["nome"],
        "preco" => $produtos[$q]["preco"]
    ]);
} else {
    echo json_encode([
        "loja" => "Worten",
        "produto" => $q,
        "preco" => null,
        "erro" => "Produto não encontrado"
    ]);
}