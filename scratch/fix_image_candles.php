<?php
require_once __DIR__ . '/../config/database.php';
$newImagePath = '/e-commerceSystem/assets/img/products/scented_candle_set.png';
$stmt = $pdo->prepare("UPDATE products SET image = ? WHERE name = 'Scented Candle Set'");
if ($stmt->execute([$newImagePath])) {
    echo "Success: Image updated for Scented Candle Set.";
} else {
    echo "Error: Failed to update image.";
}
