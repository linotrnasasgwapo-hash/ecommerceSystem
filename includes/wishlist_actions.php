<?php
/**
 * Wishlist Actions Handler
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Please log in to use the wishlist.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$action = $_POST['action'] ?? '';
$productId = (int)($_POST['product_id'] ?? 0);
$userId = currentUserId();

if ($productId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product.']);
    exit;
}

try {
    if ($action === 'toggle') {
        // Check if exists
        $stmt = $pdo->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$userId, $productId]);
        $exists = $stmt->fetch();

        if ($exists) {
            // Remove
            $stmt = $pdo->prepare("DELETE FROM wishlist WHERE id = ?");
            $stmt->execute([$exists['id']]);
            echo json_encode(['success' => true, 'status' => 'removed', 'message' => 'Removed from wishlist.']);
        } else {
            // Add
            $stmt = $pdo->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
            $stmt->execute([$userId, $productId]);
            echo json_encode(['success' => true, 'status' => 'added', 'message' => 'Added to wishlist.']);
        }
    } elseif ($action === 'remove') {
        $stmt = $pdo->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$userId, $productId]);
        echo json_encode(['success' => true, 'status' => 'removed', 'message' => 'Removed from wishlist.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error occurred.']);
}
