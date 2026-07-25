<?php
/**
 * Cart Actions: Add, Update, Remove
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';

requireLogin();

$action = $_POST['action'] ?? '';
$userId = currentUserId();

// ── ADD TO CART / BOOK SERVICE ──
if ($action === 'add' || $action === 'book') {
    $productId   = (int) ($_POST['product_id'] ?? 0);
    $quantity    = max(1, (int) ($_POST['quantity'] ?? 1));
    $bookingDate = trim($_POST['booking_date'] ?? '');
    $bookingTime = trim($_POST['booking_time'] ?? '');
    $specialist   = trim($_POST['specialist'] ?? '');

    if ($productId <= 0) {
        setFlash('error', 'Invalid product or service.');
        redirect(baseUrl('pages/shop.php'));
    }

    // Check if product is a service
    $stmtCategory = $pdo->prepare("SELECT c.name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
    $stmtCategory->execute([$productId]);
    $catName = $stmtCategory->fetchColumn() ?: '';
    $isService = (stripos($catName, 'beauty') !== false || stripos($catName, 'salon') !== false || stripos($catName, 'service') !== false);

    if ($isService && (empty($bookingDate) || empty($bookingTime))) {
        setFlash('error', 'Please select an appointment date and time slot.');
        $redirect = $_POST['redirect'] ?? baseUrl('pages/product.php?id=' . $productId);
        redirect($redirect);
    }

    // Insert cart entry with booking details
    $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity, booking_date, booking_time, specialist) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$userId, $productId, $quantity, $bookingDate ?: null, $bookingTime ?: null, $specialist ?: null]);

    if ($isService) {
        setFlash('success', 'Appointment booked! Service added to your cart.');
    } else {
        setFlash('success', 'Product added to cart!');
    }

    // Redirect back
    $redirect = $_POST['redirect'] ?? baseUrl('pages/cart.php');
    redirect($redirect);
}

// ── UPDATE QUANTITY ──
if ($action === 'update') {
    $cartId   = (int) ($_POST['cart_id'] ?? 0);
    $quantity = (int) ($_POST['quantity'] ?? 1);

    if ($quantity < 1) {
        // Remove if quantity is 0 or less
        $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
        $stmt->execute([$cartId, $userId]);
    } else {
        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$quantity, $cartId, $userId]);
    }

    redirect(baseUrl('pages/cart.php'));
}

// ── REMOVE FROM CART ──
if ($action === 'remove') {
    $cartId = (int) ($_POST['cart_id'] ?? 0);
    $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->execute([$cartId, $userId]);

    setFlash('success', 'Item removed from cart.');
    redirect(baseUrl('pages/cart.php'));
}

redirect(baseUrl('pages/cart.php'));
