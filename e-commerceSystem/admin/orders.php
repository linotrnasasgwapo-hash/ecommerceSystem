<?php
/**
 * Admin — Orders List
 */
$pageTitle = 'Orders';
require_once __DIR__ . '/includes/admin_header.php';

$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;

$totalOrders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$totalPages = max(1, ceil($totalOrders / $perPage));

$orders = $pdo->query("
    SELECT o.*, u.name AS user_name
    FROM orders o
    JOIN users u ON o.user_id = u.id
    ORDER BY o.created_at DESC
    LIMIT $perPage OFFSET $offset
")->fetchAll();
?>

<div class="admin-header">
    <h1>Orders</h1>
</div>

<div class="admin-table-wrapper">
    <?php if (empty($orders)): ?>
        <div class="empty-state" style="padding: 40px;">
            <i class="fas fa-shopping-cart"></i>
            <h2>No orders yet</h2>
        </div>
    <?php else: ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Email</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
            <tr>
                <td>#<?= $order['id'] ?></td>
                <td><?= sanitize($order['name']) ?></td>
                <td><?= sanitize($order['email']) ?></td>
                <td><?= formatPrice($order['total']) ?></td>
                <td>
                    <span class="status-badge status-<?= strtolower($order['status']) ?>">
                        <?= $order['status'] ?>
                    </span>
                </td>
                <td><?= date('M d, Y H:i', strtotime($order['created_at'])) ?></td>
                <td>
                    <a href="<?= baseUrl('admin/order_detail.php?id=' . $order['id']) ?>" class="action-view">View</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <?php if ($totalPages > 1): ?>
    <div class="pagination" style="margin-top: 20px; display: flex; gap: 8px; justify-content: center;">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>" class="btn <?= $i === $page ? 'btn-primary' : 'btn-outline' ?> btn-sm" style="padding: 6px 12px;"><?= $i ?></a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>

    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
