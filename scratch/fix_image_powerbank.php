<?php
require_once __DIR__ . '/../config/database.php';
$newImagePath = '/e-commerceSystem/assets/img/products/portable_power_bank.png';
$stmt = $pdo->prepare("UPDATE products SET image = ? WHERE name = 'Portable Power Bank'");
if ($stmt->execute([$newImagePath])) {
    echo "Success: Image updated for Portable Power Bank.";
} else {
    echo "Error: Failed to update image.";
}
