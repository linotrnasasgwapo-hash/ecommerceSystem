<?php
require_once __DIR__ . '/../config/database.php';
$newImagePath = '/e-commerceSystem/assets/img/products/ceramic_table_lamp.png';
$stmt = $pdo->prepare("UPDATE products SET image = ? WHERE name = 'Ceramic Table Lamp'");
if ($stmt->execute([$newImagePath])) {
    echo "Success: Image updated for Ceramic Table Lamp.";
} else {
    echo "Error: Failed to update image.";
}
