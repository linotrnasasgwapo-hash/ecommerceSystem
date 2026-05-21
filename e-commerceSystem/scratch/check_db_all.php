<?php
require_once __DIR__ . '/../config/database.php';
$stmt = $pdo->query("SELECT id, name, image FROM products LIMIT 5");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($products);
