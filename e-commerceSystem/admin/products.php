<?php
/**
 * Admin — Manage Products
 */
$pageTitle = 'Products';
require_once __DIR__ . '/includes/admin_header.php';

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    setFlash('success', 'Product deleted.');
    redirect(baseUrl('admin/products.php'));
}

$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;

$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalPages = max(1, ceil($totalProducts / $perPage));

$products = $pdo->query("
    SELECT p.*, c.name AS category_name
    FROM products p
    JOIN categories c ON p.category_id = c.id
    ORDER BY p.created_at DESC
    LIMIT $perPage OFFSET $offset
")->fetchAll();
?>

<div class="admin-header">
    <h1>Products</h1>
    <a href="<?= baseUrl('admin/product_form.php') ?>" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Add Product
    </a>
</div>

<div class="admin-table-wrapper">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($products)): ?>
                <tr><td colspan="7" style="text-align:center; padding: 30px;">No products found.</td></tr>
            <?php else: ?>
                <?php foreach ($products as $p): ?>
                <tr>
                    <td><img src="<?= sanitize($p['image']) ?>" alt="" class="product-thumb"></td>
                    <td><?= sanitize($p['name']) ?></td>
                    <td><?= sanitize($p['category_name']) ?></td>
                    <td><?= formatPrice($p['price']) ?></td>
                    <td><?= $p['stock'] ?></td>
                    <td><?= date('M d, Y', strtotime($p['created_at'])) ?></td>
                    <td>
                        <div class="table-actions">
                            <a href="<?= baseUrl('admin/product_form.php?id=' . $p['id']) ?>" class="action-edit">Edit</a>
                            <a href="<?= baseUrl('admin/products.php?delete=' . $p['id']) ?>" class="action-delete confirm-delete">Delete</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    
    <?php if ($totalPages > 1): ?>
    <div class="pagination" style="margin-top: 20px; display: flex; gap: 8px; justify-content: center;">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>" class="btn <?= $i === $page ? 'btn-primary' : 'btn-outline' ?> btn-sm" style="padding: 6px 12px;"><?= $i ?></a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
