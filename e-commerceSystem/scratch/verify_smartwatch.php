<?php
require_once __DIR__ . '/../config/database.php';
$stmt = $pdo->prepare("SELECT id, name, image FROM products WHERE name = 'Smart Watch Pro'");
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);
echo json_encode($product);
