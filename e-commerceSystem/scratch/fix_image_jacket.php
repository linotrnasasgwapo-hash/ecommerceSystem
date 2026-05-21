<?php
require_once __DIR__ . '/../config/database.php';
$newImagePath = '/e-commerceSystem/assets/img/products/classic_denim_jacket.png';
$stmt = $pdo->prepare("UPDATE products SET image = ? WHERE name = 'Classic Denim Jacket'");
if ($stmt->execute([$newImagePath])) {
    echo "Success: Image updated for Classic Denim Jacket.";
} else {
    echo "Error: Failed to update image.";
}
