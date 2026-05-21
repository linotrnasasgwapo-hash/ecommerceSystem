<?php
/**
 * Checkout Handler
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(baseUrl('pages/checkout.php'));
}

$userId = currentUserId();

// Validate form
$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$phone   = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');
$city    = trim($_POST['city'] ?? '');

if (empty($name) || empty($email) || empty($phone) || empty($address) || empty($city)) {
    setFlash('error', 'Please fill in all shipping fields.');
    redirect(baseUrl('pages/checkout.php'));
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    setFlash('error', 'Please enter a valid email address.');
    redirect(baseUrl('pages/checkout.php'));
}

// Get cart items
$stmt = $pdo->prepare("
    SELECT c.*, p.price, p.name AS product_name, p.stock
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ?
");
$stmt->execute([$userId]);
$cartItems = $stmt->fetchAll();

if (empty($cartItems)) {
    setFlash('error', 'Your cart is empty.');
    redirect(baseUrl('pages/cart.php'));
}

// Calculate total
$total = 0;
foreach ($cartItems as $item) {
    $total += $item['price'] * $item['quantity'];
}

try {
    $pdo->beginTransaction();

    // Create order
    $stmt = $pdo->prepare("
        INSERT INTO orders (user_id, total, name, email, phone, address, city)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$userId, $total, $name, $email, $phone, $address, $city]);
    $orderId = $pdo->lastInsertId();

    // Insert order items and update stock
    $stmtItem  = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmtStock = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?");

    foreach ($cartItems as $item) {
        $stmtItem->execute([$orderId, $item['product_id'], $item['quantity'], $item['price']]);
        $stmtStock->execute([$item['quantity'], $item['product_id'], $item['quantity']]);
    }

    // Clear cart
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->execute([$userId]);

    $pdo->commit();

    setFlash('success', 'Order #' . $orderId . ' placed successfully! Thank you for your purchase.');
    redirect(baseUrl());

} catch (Exception $e) {
    $pdo->rollBack();
    setFlash('error', 'Something went wrong. Please try again.');
    redirect(baseUrl('pages/checkout.php'));
}
