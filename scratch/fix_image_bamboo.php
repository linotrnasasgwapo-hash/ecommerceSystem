<?php
require_once __DIR__ . '/../config/database.php';
$newImagePath = '/e-commerceSystem/assets/img/products/bamboo_kitchen_organizer.png';
$stmt = $pdo->prepare("UPDATE products SET image = ? WHERE name = 'Bamboo Kitchen Organizer'");
if ($stmt->execute([$newImagePath])) {
    echo "Success: Image updated for Bamboo Kitchen Organizer.";
} else {
    echo "Error: Failed to update image.";
}
