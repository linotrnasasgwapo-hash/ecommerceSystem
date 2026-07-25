<?php
require_once __DIR__ . '/../config/database.php';

try {
    echo "Updating product images to local assets...\n";

    $updates = [
        1 => '/e-commerceSystem/assets/img/products/kisses_dark_chocolate.jpg',
        2 => '/e-commerceSystem/assets/img/products/flowers_baby_pink.jpg',
        3 => '/e-commerceSystem/assets/img/products/tinola_native_chicken.jpg',
        4 => '/e-commerceSystem/assets/img/products/fish_preto_utan.jpg',
        5 => '/e-commerceSystem/assets/img/products/nails_specialist.jpg',
        6 => '/e-commerceSystem/assets/img/products/rebond_specialist.jpg',
        7 => '/e-commerceSystem/assets/img/products/makeup_service.jpg',
    ];

    $stmt = $pdo->prepare("UPDATE products SET image = ? WHERE id = ?");
    foreach ($updates as $id => $imagePath) {
        $stmt->execute([$imagePath, $id]);
        echo "Updated product $id image.\n";
    }

    echo "All product images updated on Railway DB!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
