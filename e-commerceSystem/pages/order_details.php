<?php
/**
 * User Order Detail Page
 */
$pageTitle = 'Order Details';
require_once __DIR__ . '/../includes/header.php';

requireLogin();

$userId = currentUserId();
$orderId = (int)($_GET['id'] ?? 0);

if ($orderId <= 0) {
    setFlash('error', 'Order not found.');
    redirect(baseUrl('pages/orders.php'));
}

// Fetch order verifying it belongs to logged in user
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$orderId, $userId]);
$order = $stmt->fetch();

if (!$order) {
    setFlash('error', 'Order not found or access denied.');
    redirect(baseUrl('pages/orders.php'));
}

// Fetch order items
$stmt = $pdo->prepare("
    SELECT oi.*, p.name AS product_name, p.image
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");
$stmt->execute([$orderId]);
$items = $stmt->fetchAll();
?>

<div class="page-header">
    <div class="container">
        <h1>Order #<?= $order['id'] ?> Details</h1>
        <div class="breadcrumb">
            <a href="<?= baseUrl() ?>">Home</a>
            <span>/</span>
            <a href="<?= baseUrl('pages/orders.php') ?>">Orders</a>
            <span>/</span>
            <span>#<?= $order['id'] ?></span>
        </div>
    </div>
</div>

<section class="section">
    <div class="container" style="max-width: 1000px;">
        <div style="margin-bottom: 20px;">
            <a href="<?= baseUrl('pages/orders.php') ?>" class="btn btn-outline btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Orders
            </a>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 30px;">
            <div class="card" style="padding: 24px;">
                <h3 style="margin-bottom: 16px;"><i class="fas fa-shipping-fast" style="color: var(--primary); margin-right: 6px;"></i> Shipping Information</h3>
                <p><strong>Name:</strong> <?= sanitize($order['name']) ?></p>
                <p><strong>Email:</strong> <?= sanitize($order['email']) ?></p>
                <p><strong>Phone:</strong> <?= sanitize($order['phone']) ?></p>
                <p><strong>Address:</strong> <?= sanitize($order['address']) ?>, <?= sanitize($order['city']) ?></p>
            </div>
            
            <div class="card" style="padding: 24px;">
                <h3 style="margin-bottom: 16px;"><i class="fas fa-receipt" style="color: var(--primary); margin-right: 6px;"></i> Order Summary</h3>
                <p><strong>Order Date:</strong> <?= date('F j, Y, g:i a', strtotime($order['created_at'])) ?></p>
                <p style="display: flex; align-items: center; gap: 8px;">
                    <strong>Status:</strong> 
                    <?php
                    $statusColor = match($order['status']) {
                        'Pending' => 'var(--warning)',
                        'Processing' => 'var(--info)',
                        'Shipped' => '#8b5cf6',
                        'Delivered' => 'var(--success)',
                        'Cancelled' => 'var(--error)',
                        default => 'var(--text-secondary)'
                    };
                    ?>
                    <span style="background: color-mix(in srgb, <?= $statusColor ?> 15%, transparent); color: <?= $statusColor ?>; padding: 4px 10px; border-radius: 50px; font-size: 0.85rem; font-weight: 600;">
                        <?= sanitize($order['status']) ?>
                    </span>
                </p>
                <p><strong>Total Amount:</strong> <span style="color: var(--primary); font-weight: bold; font-size: 1.1rem;"><?= formatPrice($order['total']) ?></span></p>
            </div>
        </div>

        <div class="card" style="padding: 24px;">
            <h3 style="margin-bottom: 20px;"><i class="fas fa-box" style="color: var(--primary); margin-right: 6px;"></i> Items Ordered</h3>
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <th style="padding: 12px 8px; color: var(--text-secondary);">Product</th>
                        <th style="padding: 12px 8px; color: var(--text-secondary);">Price</th>
                        <th style="padding: 12px 8px; color: var(--text-secondary);">Quantity</th>
                        <th style="padding: 12px 8px; color: var(--text-secondary); text-align: right;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td style="padding: 16px 8px; display: flex; align-items: center; gap: 12px;">
                            <img src="<?= sanitize($item['image']) ?>" alt="<?= sanitize($item['product_name']) ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: var(--radius-sm);">
                            <a href="<?= baseUrl('pages/product.php?id=' . $item['product_id']) ?>" style="color: var(--text-primary); font-weight: 500;">
                                <?= sanitize($item['product_name']) ?>
                            </a>
                        </td>
                        <td style="padding: 16px 8px;"><?= formatPrice($item['price']) ?></td>
                        <td style="padding: 16px 8px;"><?= $item['quantity'] ?></td>
                        <td style="padding: 16px 8px; text-align: right; font-weight: 600;"><?= formatPrice($item['price'] * $item['quantity']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="padding: 20px 8px 8px; text-align: right; font-weight: 700; font-size: 1.1rem;">Grand Total</td>
                        <td style="padding: 20px 8px 8px; text-align: right; font-weight: 800; font-size: 1.2rem; color: var(--primary);"><?= formatPrice($order['total']) ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
