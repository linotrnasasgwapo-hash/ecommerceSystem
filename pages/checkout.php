<?php
/**
 * Checkout Page
 */
$pageTitle = 'Checkout';
require_once __DIR__ . '/../includes/header.php';

requireLogin();

// Get cart items
$stmt = $pdo->prepare("
    SELECT c.*, p.name, p.price, p.image
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ?
");
$stmt->execute([currentUserId()]);
$cartItems = $stmt->fetchAll();

if (empty($cartItems)) {
    setFlash('error', 'Your cart is empty.');
    redirect(baseUrl('pages/cart.php'));
}

$total = 0;
foreach ($cartItems as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Pre-fill user info
$stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->execute([currentUserId()]);
$user = $stmt->fetch();
?>

<div class="page-header">
    <div class="container">
        <h1>Checkout</h1>
        <div class="breadcrumb">
            <a href="<?= baseUrl() ?>">Home</a>
            <span>/</span>
            <a href="<?= baseUrl('pages/cart.php') ?>">Cart</a>
            <span>/</span>
            <span>Checkout</span>
        </div>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="checkout-layout">
            <div class="checkout-form-card">
                <h2><i class="fas fa-shipping-fast" style="color: var(--primary); margin-right: 8px;"></i> Shipping Information</h2>
                <form action="<?= baseUrl('includes/checkout_handler.php') ?>" method="POST" data-validate>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Full Name *</label>
                            <input type="text" id="name" name="name" class="form-control" value="<?= sanitize($user['name']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" class="form-control" value="<?= sanitize($user['email']) ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number *</label>
                        <input type="tel" id="phone" name="phone" class="form-control" placeholder="e.g. +1 555 123 4567" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Street Address *</label>
                        <input type="text" id="address" name="address" class="form-control" placeholder="e.g. 123 Main Street, Apt 4B" required>
                    </div>
                    <div class="form-group">
                        <label for="city">City *</label>
                        <input type="text" id="city" name="city" class="form-control" placeholder="e.g. Metro City" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg btn-full" style="margin-top: 8px;">
                        <i class="fas fa-lock"></i> Place Order — <?= formatPrice($total) ?>
                    </button>
                </form>
            </div>

            <div class="order-summary-card">
                <h2>Order Summary</h2>
                <?php foreach ($cartItems as $item): ?>
                <div class="order-item">
                    <span class="order-item-name"><?= sanitize($item['name']) ?></span>
                    <span class="order-item-qty">×<?= $item['quantity'] ?></span>
                    <span class="order-item-price"><?= formatPrice($item['price'] * $item['quantity']) ?></span>
                </div>
                <?php endforeach; ?>
                <div class="summary-row" style="margin-top: 16px;">
                    <span>Shipping</span>
                    <span style="color: var(--success);">Free</span>
                </div>
                <div class="summary-total">
                    <span>Total</span>
                    <span><?= formatPrice($total) ?></span>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
