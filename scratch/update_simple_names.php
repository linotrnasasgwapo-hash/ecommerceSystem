<?php
require_once __DIR__ . '/../config/database.php';

try {
    echo "Updating product names to clean, simple names...\n";

    $names = [
        1 => 'Dark Chocolate Kisses',
        2 => 'Baby Pink Flowers',
        3 => 'Native Chicken Tinola',
        4 => 'Fried Fish with Veggie Soup',
        5 => 'Nail Specialist Service',
        6 => 'Hair Rebonding Service',
        7 => 'Professional Makeup'
    ];

    $stmt = $pdo->prepare("UPDATE products SET name = ? WHERE id = ?");
    foreach ($names as $id => $name) {
        $stmt->execute([$name, $id]);
        echo "Updated product $id to '$name'\n";
    }

    echo "All product names updated on Railway DB!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
