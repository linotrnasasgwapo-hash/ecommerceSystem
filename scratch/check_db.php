<?php
require_once __DIR__ . '/../config/database.php';

echo "--- CATEGORIES ---\n";
$stmt = $pdo->query("SELECT * FROM categories");
print_r($stmt->fetchAll());

echo "--- PRODUCTS ---\n";
$stmt = $pdo->query("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id");
print_r($stmt->fetchAll());
