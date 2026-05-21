<?php
/**
 * AJAX Quick View Handler
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

header('Content-Type: application/json');

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product.']);
    exit;
}

$stmt = $pdo->prepare("
    SELECT p.*, c.name AS category_name
    FROM products p
    JOIN categories c ON p.category_id = c.id
    WHERE p.id = ?
");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    echo json_encode(['success' => false, 'message' => 'Product not found.']);
    exit;
}

echo json_encode([
    'success' => true,
    'product' => [
        'id' => $product['id'],
        'name' => sanitize($product['name']),
        'category' => sanitize($product['category_name']),
        'description' => sanitize($product['description']),
        'priceFormatted' => formatPrice($product['price']),
        'stock' => $product['stock'],
        'image' => sanitize($product['image']),
        'url' => baseUrl('pages/product.php?id=' . $product['id'])
    ]
]);
