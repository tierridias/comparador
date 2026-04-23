<?php
header('Content-Type: application/json');

// Recebe o nome vindo da BD (ex: Logitech G203)
$q = isset($_GET['q']) ? strtolower(trim($_GET['q'])) : '';

$produtos = [
    "logitech g203" => ["nome" => "Logitech G203 Lightsync","preco" => 24.99,"stock" => 10,"entrega_dias" => 7],
    "logitech g pro x superlight" => ["nome" => "Logitech G Pro X Superlight","preco" => 118.99,"stock" => 5,"entrega_dias" => 5],
    "razer deathadder v2" => ["nome" => "Razer DeathAdder V2","preco" => 39.99,"stock" => 6,"entrega_dias" => 4],
    "razer basilisk v3" => ["nome" => "Razer Basilisk V3","preco" => 59.99,"stock" => 9,"entrega_dias" => 3],
    "steelseries rival 3" => ["nome" => "SteelSeries Rival 3","preco" => 29.99,"stock" => 3,"entrega_dias" => 3],
    "logitech g502" => ["nome" => "Logitech G502 Hero", "preco" => 49.99, "stock" => 3, "entrega_dias" => 7],
    "razer deathadder v2" => ["nome" => "Razer DeathAdder V2", "preco" => 39.90, "stock" => 5, "entrega_dias" => 5],
    "corsair k70 rgb" => ["nome" => "Corsair K70 RGB Mechanical", "preco" => 125.99, "stock" => 2, "entrega_dias" => 8],
    "razer blackwidow" => ["nome" => "Razer BlackWidow Elite", "preco" => 95.00, "stock" => 1, "entrega_dias" => 6],
    "lg ultragear 27\"" => ["nome" => "LG UltraGear 27 GL650", "preco" => 239.00, "stock" => 4, "entrega_dias" => 10],
    "samsung odyssey g5" => ["nome" => "Samsung Odyssey G5 Curved", "preco" => 275.00, "stock" => 3, "entrega_dias" => 9]
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
        "loja" => "fnac", 
        "produto" => $encontrado["nome"],
        "preco" => $encontrado["preco"],
        "stock" => $encontrado["stock"],
        "entrega_dias" => $encontrado["entrega_dias"]
    ]);
} else {
    echo json_encode([
        "loja" => "fnac",
        "produto" => $q,
        "preco" => 0,
        "stock" => 0,         
        "entrega_dias" => 0,    
        "erro" => "Produto não encontrado"
    ]);
}