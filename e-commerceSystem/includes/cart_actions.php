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

// ── ADD TO CART ──
if ($action === 'add') {
    $productId = (int) ($_POST['product_id'] ?? 0);
    $quantity  = max(1, (int) ($_POST['quantity'] ?? 1));

    if ($productId <= 0) {
        setFlash('error', 'Invalid product.');
        redirect(baseUrl('pages/shop.php'));
    }

    // Check if product already in cart
    $stmt = $pdo->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$userId, $productId]);
    $existing = $stmt->fetch();

    if ($existing) {
        $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity + ? WHERE id = ?");
        $stmt->execute([$quantity, $existing['id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $productId, $quantity]);
    }

    setFlash('success', 'Product added to cart!');

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
