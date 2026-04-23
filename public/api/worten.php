<?php
header('Content-Type: application/json');

// Recebe o nome vindo da BD (ex: Logitech G203)
$q = isset($_GET['q']) ? strtolower(trim($_GET['q'])) : '';

$produtos = [
    "logitech g203" => ["nome" => "Logitech G203 Lightsync","preco" => 24.99,"stock" => 10,"entrega_dias" => 2],
    "logitech g pro x superlight" => ["nome" => "Logitech G Pro X Superlight","preco" => 118.99,"stock" => 8,"entrega_dias" => 4],
    "razer deathadder v2" => ["nome" => "Razer DeathAdder V2","preco" => 39.99,"stock" => 7,"entrega_dias" => 7],
    "razer basilisk v3" => ["nome" => "Razer Basilisk V3","preco" => 59.99,"stock" => 5,"entrega_dias" => 4],
    "steelseries rival 3" => ["nome" => "SteelSeries Rival 3","preco" => 29.99,"stock" => 4,"entrega_dias" => 6],
    "logitech g502" => ["nome" => "Logitech G502 Hero", "preco" => 54.90, "stock" => 15, "entrega_dias" => 2],
    "razer deathadder v2" => ["nome" => "Razer DeathAdder V2", "preco" => 42.00, "stock" => 2, "entrega_dias" => 1],
    "corsair k70 rgb" => ["nome" => "Corsair K70 RGB Mechanical", "preco" => 135.00, "stock" => 10, "entrega_dias" => 3],
    "razer blackwidow" => ["nome" => "Razer BlackWidow Elite", "preco" => 105.00, "stock" => 4, "entrega_dias" => 2],
    "lg ultragear 27\"" => ["nome" => "LG UltraGear 27 GL650", "preco" => 255.00, "stock" => 8, "entrega_dias" => 5],
    "samsung odyssey g5" => ["nome" => "Samsung Odyssey G5 Curved", "preco" => 289.00, "stock" => 12, "entrega_dias" => 4]
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
        "loja" => "Worten", 
        "produto" => $encontrado["nome"],
        "preco" => $encontrado["preco"],
        "stock" => $encontrado["stock"],
        "entrega_dias" => $encontrado["entrega_dias"]
    ]);
} else {
    echo json_encode([
        "loja" => "Worten",
        "produto" => $q,
        "preco" => 0,
        "stock" => 0,         
        "entrega_dias" => 0,    
        "erro" => "Produto não encontrado"
    ]);
}