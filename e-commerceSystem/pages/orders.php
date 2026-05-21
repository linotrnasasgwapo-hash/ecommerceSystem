<?php
/**
 * User Order History Page
 */
$pageTitle = 'My Orders';
require_once __DIR__ . '/../includes/header.php';

requireLogin();

$userId = currentUserId();

$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$userId]);
$orders = $stmt->fetchAll();
?>

<div class="page-header">
    <div class="container">
        <h1>My Orders</h1>
        <div class="breadcrumb">
            <a href="<?= baseUrl() ?>">Home</a>
            <span>/</span>
            <span>Orders</span>
        </div>
    </div>
</div>

<section class="section">
    <div class="container" style="max-width: 1000px;">
        <?php if (empty($orders)): ?>
            <div class="empty-state card" style="text-align: center; padding: 60px 20px;">
                <i class="fas fa-box-open" style="font-size: 4rem; color: var(--text-muted); margin-bottom: 20px;"></i>
                <h2 style="margin-bottom: 15px;">You have no orders yet</h2>
                <p style="color: var(--text-secondary); margin-bottom: 25px;">Looks like you haven't made any purchases with us.</p>
                <a href="<?= baseUrl('pages/shop.php') ?>" class="btn btn-primary">Start Shopping</a>
            </div>
        <?php else: ?>
            <div class="card" style="padding: 24px;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <th style="padding: 16px 8px; color: var(--text-secondary); font-weight: 600;">Order ID</th>
                            <th style="padding: 16px 8px; color: var(--text-secondary); font-weight: 600;">Date</th>
                            <th style="padding: 16px 8px; color: var(--text-secondary); font-weight: 600;">Total</th>
                            <th style="padding: 16px 8px; color: var(--text-secondary); font-weight: 600;">Status</th>
                            <th style="padding: 16px 8px; color: var(--text-secondary); font-weight: 600;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 16px 8px;">#<?= $order['id'] ?></td>
                            <td style="padding: 16px 8px;"><?= date('M j, Y - g:i a', strtotime($order['created_at'])) ?></td>
                            <td style="padding: 16px 8px; color: var(--primary); font-weight: 600;"><?= formatPrice($order['total']) ?></td>
                            <td style="padding: 16px 8px;">
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
                            </td>
                            <td style="padding: 16px 8px;">
                                <a href="<?= baseUrl('pages/order_details.php?id=' . $order['id']) ?>" class="btn btn-outline btn-sm">View Details</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
