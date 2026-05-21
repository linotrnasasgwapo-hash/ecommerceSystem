<?php
require_once __DIR__ . '/../config/database.php';
$stmt = $pdo->query("SELECT id, name, image FROM products WHERE id IN (10, 11, 12)");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($products);
