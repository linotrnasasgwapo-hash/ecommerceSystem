<?php
/**
 * Admin Dashboard
 */
$pageTitle = 'Dashboard';
require_once __DIR__ . '/includes/admin_header.php';

// Stats
$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalOrders   = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$totalUsers    = $pdo->query("SELECT COUNT(*) FROM users WHERE role='user'")->fetchColumn();
$totalRevenue  = $pdo->query("SELECT COALESCE(SUM(total), 0) FROM orders WHERE status != 'Cancelled'")->fetchColumn();

// Sales trend (last 7 days)
$salesLabels = [];
$salesValues = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $stmt = $pdo->prepare("SELECT COALESCE(SUM(total), 0) FROM orders WHERE DATE(created_at) = ? AND status != 'Cancelled'");
    $stmt->execute([$date]);
    $salesLabels[] = date('M d', strtotime($date));
    $salesValues[] = (float)$stmt->fetchColumn();
}

// Recent orders
$recentOrders = $pdo->query("
    SELECT o.*, u.name AS user_name
    FROM orders o
    JOIN users u ON o.user_id = u.id
    ORDER BY o.created_at DESC
    LIMIT 10
")->fetchAll();
?>

<div class="admin-header">
    <h1>Dashboard</h1>
    <span style="color: var(--text-muted);">Welcome back, <?= sanitize(currentUserName()) ?></span>
</div>

<div class="dashboard-stats">
    <div class="stat-box">
        <div class="stat-icon purple"><i class="fas fa-box"></i></div>
        <div class="stat-value"><?= $totalProducts ?></div>
        <div class="stat-label">Total Products</div>
    </div>
    <div class="stat-box">
        <div class="stat-icon blue"><i class="fas fa-shopping-cart"></i></div>
        <div class="stat-value"><?= $totalOrders ?></div>
        <div class="stat-label">Total Orders</div>
    </div>
    <div class="stat-box">
        <div class="stat-icon green"><i class="fas fa-users"></i></div>
        <div class="stat-value"><?= $totalUsers ?></div>
        <div class="stat-label">Customers</div>
    </div>
    <div class="stat-box">
        <div class="stat-icon gold"><i class="fas fa-dollar-sign"></i></div>
        <div class="stat-value"><?= formatPrice($totalRevenue) ?></div>
        <div class="stat-label">Total Revenue</div>
    </div>
</div>

<div class="admin-header" style="margin-top: 40px;">
    <h2 style="font-size: 1.2rem;">Sales Overview (Last 7 Days)</h2>
</div>
<div class="admin-form-card" style="margin-bottom: 30px; max-width: 100%;">
    <canvas id="salesChart" height="100"></canvas>
</div>

<div class="admin-header">
    <h2 style="font-size: 1.2rem;">Recent Orders</h2>
</div>

<div class="admin-table-wrapper">
    <?php if (empty($recentOrders)): ?>
        <div class="empty-state" style="padding: 40px;">
            <p>No orders yet.</p>
        </div>
    <?php else: ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($recentOrders as $order): ?>
            <tr>
                <td>#<?= $order['id'] ?></td>
                <td><?= sanitize($order['user_name']) ?></td>
                <td><?= formatPrice($order['total']) ?></td>
                <td>
                    <span class="status-badge status-<?= strtolower($order['status']) ?>">
                        <?= $order['status'] ?>
                    </span>
                </td>
                <td><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                <td>
                    <a href="<?= baseUrl('admin/order_detail.php?id=' . $order['id']) ?>" class="action-view">View</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('salesChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($salesLabels) ?>,
        datasets: [{
            label: 'Daily Revenue ($)',
            data: <?= json_encode($salesValues) ?>,
            borderColor: '#c9a84c',
            backgroundColor: 'rgba(201, 168, 76, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
