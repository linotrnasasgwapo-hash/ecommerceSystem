<?php
require_once __DIR__ . '/../config/database.php';
$newImagePath = '/e-commerceSystem/assets/img/products/smart_watch_pro.png';
$stmt = $pdo->prepare("UPDATE products SET image = ? WHERE name = 'Smart Watch Pro'");
if ($stmt->execute([$newImagePath])) {
    echo "Success: Image updated for Smart Watch Pro.";
} else {
    echo "Error: Failed to update image.";
}
