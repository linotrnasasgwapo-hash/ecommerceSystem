<?php
require_once __DIR__ . '/../config/database.php';

$stmt = $pdo->query("SELECT p.id, p.name, p.category_id, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "--- PRODUCTS & CATEGORIES IN DB ---\n";
foreach ($products as $p) {
    echo "ID: {$p['id']} | Name: {$p['name']} | CatID: {$p['category_id']} | CatName: '{$p['category_name']}'\n";
}
