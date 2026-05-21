<?php
/**
 * Cart Page
 */
$pageTitle = 'Shopping Cart';
require_once __DIR__ . '/../includes/header.php';

$cartItems = [];
$total = 0;

if (isLoggedIn()) {
    $stmt = $pdo->prepare("
        SELECT c.*, p.name, p.price, p.image, p.stock
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ?
        ORDER BY c.created_at DESC
    ");
    $stmt->execute([currentUserId()]);
    $cartItems = $stmt->fetchAll();

    foreach ($cartItems as $item) {
        $total += $item['price'] * $item['quantity'];
    }
}
?>

<div class="page-header">
    <div class="container">
        <h1>Shopping Cart</h1>
        <div class="breadcrumb">
            <a href="<?= baseUrl() ?>">Home</a>
            <span>/</span>
            <span>Cart</span>
        </div>
    </div>
</div>

<section class="section">
    <div class="container">
        <?php if (!isLoggedIn()): ?>
            <div class="empty-state">
                <i class="fas fa-lock"></i>
                <h2>Please log in</h2>
                <p>You need to be logged in to view your cart.</p>
                <a href="<?= baseUrl('pages/login.php') ?>" class="btn btn-primary">Login</a>
            </div>
        <?php elseif (empty($cartItems)): ?>
            <div class="empty-state">
                <i class="fas fa-shopping-bag"></i>
                <h2>Your cart is empty</h2>
                <p>Looks like you haven't added anything yet.</p>
                <a href="<?= baseUrl('pages/shop.php') ?>" class="btn btn-primary">Shop Now</a>
            </div>
        <?php else: ?>
            <div class="cart-layout">
                <div class="cart-items">
                    <?php foreach ($cartItems as $item): ?>
                    <div class="cart-item">
                        <img src="<?= sanitize($item['image']) ?>" alt="<?= sanitize($item['name']) ?>" class="cart-item-img">

                        <div class="cart-item-info">
                            <h3><a href="<?= baseUrl('pages/product.php?id=' . $item['product_id']) ?>" style="color: inherit;"><?= sanitize($item['name']) ?></a></h3>
                            <p>Unit price: <?= formatPrice($item['price']) ?></p>
                        </div>

                        <form action="<?= baseUrl('includes/cart_actions.php') ?>" method="POST" class="cart-qty-form">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
                            <div class="quantity-selector">
                                <button type="button" class="qty-btn" data-action="decrease">−</button>
                                <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" max="<?= $item['stock'] ?>" class="qty-input">
                                <button type="button" class="qty-btn" data-action="increase">+</button>
                            </div>
                        </form>

                        <span class="cart-item-price"><?= formatPrice($item['price'] * $item['quantity']) ?></span>

                        <form action="<?= baseUrl('includes/cart_actions.php') ?>" method="POST">
                            <input type="hidden" name="action" value="remove">
                            <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
                            <button type="submit" class="cart-item-remove" title="Remove">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="cart-summary">
                    <h2>Order Summary</h2>
                    <div class="summary-row">
                        <span>Subtotal (<?= count($cartItems) ?> item<?= count($cartItems) > 1 ? 's' : '' ?>)</span>
                        <span><?= formatPrice($total) ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping</span>
                        <span style="color: var(--success);">Free</span>
                    </div>
                    <div class="summary-total">
                        <span>Total</span>
                        <span><?= formatPrice($total) ?></span>
                    </div>
                    <a href="<?= baseUrl('pages/checkout.php') ?>" class="btn btn-primary btn-full btn-lg">
                        Proceed to Checkout <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
