<?php
require_once __DIR__ . '/../config/database.php';
$exclude = ['Ceramic Table Lamp', 'Bamboo Kitchen Organizer', 'Smart Watch Pro'];
$placeholders = implode(',', array_fill(0, count($exclude), '?'));
$stmt = $pdo->prepare("SELECT id, name FROM products WHERE name NOT IN ($placeholders)");
$stmt->execute($exclude);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($products);
