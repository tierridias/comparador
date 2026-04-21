<?php
header('Content-Type: application/json');

$q = isset($_GET['q']) ? strtolower(trim($_GET['q'])) : '';

$produtos = [
    "logitech g203" => ["nome" => "Logitech G203 Lightsync","preco" => 24.99,"stock" => 3,"entrega_dias" => 1],
    "logitech g pro x superlight" => ["nome" => "Logitech G Pro X Superlight","preco" => 118.99,"stock" => 5,"entrega_dias" => 3],
    "razer deathadder v2" => ["nome" => "Razer DeathAdder V2","preco" => 39.99,"stock" => 10,"entrega_dias" => 4],
    "razer basilisk v3" => ["nome" => "Razer Basilisk V3","preco" => 58.99,"stock" => 2,"entrega_dias" => 7],
    "steelseries rival 3" => ["nome" => "SteelSeries Rival 3","preco" => 29.99,"stock" => 8,"entrega_dias" => 3]
];

$encontrado = null;

foreach ($produtos as $chave => $dados) {
    if (strpos($chave, $q) !== false && $q !== '') {
        $encontrado = $dados;
        break;
    }
}

if ($encontrado) {
    echo json_encode([
        "loja" => "pcdiga", 
        "produto" => $encontrado["nome"],
        "preco" => $encontrado["preco"],
        "stock" => $encontrado["stock"],
        "entrega_dias" => $encontrado["entrega_dias"]
    ]);
} else {
    echo json_encode([
        "loja" => "pcdiga",
        "produto" => $q,
        "preco" => 0,
        "stock" => 0,         
        "entrega_dias" => 0,    
        "erro" => "Produto não encontrado"
    ]);
}