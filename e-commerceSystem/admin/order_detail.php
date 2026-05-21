<?php
/**
 * Admin — Order Detail
 */
$pageTitle = 'Order Detail';
require_once __DIR__ . '/includes/admin_header.php';

$orderId = (int)($_GET['id'] ?? 0);

if ($orderId <= 0) {
    setFlash('error', 'Order not found.');
    redirect(baseUrl('admin/orders.php'));
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
    $status = $_POST['status'];
    $allowed = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];
    if (in_array($status, $allowed)) {
        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$status, $orderId]);
        setFlash('success', 'Order status updated.');
        redirect(baseUrl('admin/order_detail.php?id=' . $orderId));
    }
}

// Fetch order
$stmt = $pdo->prepare("
    SELECT o.*, u.name AS user_name, u.email AS user_email
    FROM orders o
    JOIN users u ON o.user_id = u.id
    WHERE o.id = ?
");
$stmt->execute([$orderId]);
$order = $stmt->fetch();

if (!$order) {
    setFlash('error', 'Order not found.');
    redirect(baseUrl('admin/orders.php'));
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

<div class="admin-header">
    <h1>Order #<?= $order['id'] ?></h1>
    <a href="<?= baseUrl('admin/orders.php') ?>" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i> Back to Orders
    </a>
</div>

<div class="order-detail-grid">
    <div class="detail-box">
        <h3><i class="fas fa-user" style="color: var(--primary); margin-right: 6px;"></i> Customer Info</h3>
        <div class="detail-row"><span>Name</span><span><?= sanitize($order['name']) ?></span></div>
        <div class="detail-row"><span>Email</span><span><?= sanitize($order['email']) ?></span></div>
        <div class="detail-row"><span>Phone</span><span><?= sanitize($order['phone']) ?></span></div>
        <div class="detail-row"><span>Account</span><span><?= sanitize($order['user_name']) ?></span></div>
    </div>
    <div class="detail-box">
        <h3><i class="fas fa-shipping-fast" style="color: var(--primary); margin-right: 6px;"></i> Shipping Info</h3>
        <div class="detail-row"><span>Address</span><span><?= sanitize($order['address']) ?></span></div>
        <div class="detail-row"><span>City</span><span><?= sanitize($order['city']) ?></span></div>
        <div class="detail-row"><span>Date</span><span><?= date('M d, Y H:i', strtotime($order['created_at'])) ?></span></div>
        <div class="detail-row">
            <span>Status</span>
            <span class="status-badge status-<?= strtolower($order['status']) ?>"><?= $order['status'] ?></span>
        </div>
    </div>
</div>

<!-- Update Status -->
<div class="admin-form-card" style="margin-bottom: 30px; max-width: 100%;">
    <h2>Update Status</h2>
    <form method="POST" style="display: flex; gap: 12px; align-items: end;">
        <div class="form-group" style="margin-bottom: 0; flex: 1; max-width: 300px;">
            <select name="status" class="form-control">
                <?php foreach (['Pending','Processing','Shipped','Delivered','Cancelled'] as $s): ?>
                    <option value="<?= $s ?>" <?= $order['status'] === $s ? 'selected' : '' ?>><?= $s ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Update</button>
    </form>
</div>

<!-- Order Items -->
<div class="admin-table-wrapper">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><img src="<?= sanitize($item['image']) ?>" alt="" class="product-thumb"></td>
                <td><?= sanitize($item['product_name']) ?></td>
                <td><?= formatPrice($item['price']) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td><?= formatPrice($item['price'] * $item['quantity']) ?></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="4" style="text-align: right; font-weight: 700;">Grand Total</td>
                <td style="font-weight: 700; color: var(--primary);"><?= formatPrice($order['total']) ?></td>
            </tr>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
