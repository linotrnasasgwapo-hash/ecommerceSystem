<?php
require_once __DIR__ . '/../config/database.php';
$stmt = $pdo->prepare("SELECT id, name, image FROM products WHERE name LIKE ?");
$stmt->execute(['%Ceramic Table Lamp%']);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
echo json_encode($product);
