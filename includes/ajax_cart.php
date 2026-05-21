<?php
/**
 * AJAX Cart Handler
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Not logged in.']);
    exit;
}

$userId = currentUserId();

$stmt = $pdo->prepare("
    SELECT c.*, p.name, p.price, p.image 
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ?
");
$stmt->execute([$userId]);
$items = $stmt->fetchAll();

$total = 0;
$count = 0;
$itemsList = [];

foreach ($items as $item) {
    $total += $item['price'] * $item['quantity'];
    $count += $item['quantity'];
    $itemsList[] = [
        'id' => $item['id'],
        'product_id' => $item['product_id'],
        'name' => sanitize($item['name']),
        'priceFormatted' => formatPrice($item['price']),
        'quantity' => $item['quantity'],
        'image' => sanitize($item['image'])
    ];
}

echo json_encode([
    'success' => true,
    'items' => $itemsList,
    'totalFormatted' => formatPrice($total),
    'count' => $count
]);
