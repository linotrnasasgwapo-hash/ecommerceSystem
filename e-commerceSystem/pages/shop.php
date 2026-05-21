<?php
/**
 * Shop / Products Page
 */
$pageTitle = 'Shop';
require_once __DIR__ . '/../includes/header.php';

// Filters
$categoryFilter = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$search = trim($_GET['search'] ?? '');
$sort = $_GET['sort'] ?? 'newest';
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 12;
$offset = ($page - 1) * $perPage;

// Build query
$where = [];
$params = [];

if ($categoryFilter > 0) {
    $where[] = "p.category_id = ?";
    $params[] = $categoryFilter;
}

if ($search !== '') {
    $where[] = "(p.name LIKE ? OR p.description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$orderSQL = match($sort) {
    'price_low'  => 'ORDER BY p.price ASC',
    'price_high' => 'ORDER BY p.price DESC',
    'name'       => 'ORDER BY p.name ASC',
    default      => 'ORDER BY p.created_at DESC',
};

// Count total
$stmtCount = $pdo->prepare("SELECT COUNT(*) FROM products p $whereSQL");
$stmtCount->execute($params);
$totalProducts = $stmtCount->fetchColumn();
$totalPages = max(1, ceil($totalProducts / $perPage));

// Fetch products
$stmt = $pdo->prepare("
    SELECT p.*, c.name AS category_name
    FROM products p
    JOIN categories c ON p.category_id = c.id
    $whereSQL
    $orderSQL
    LIMIT $perPage OFFSET $offset
");
$stmt->execute($params);
$products = $stmt->fetchAll();

// Fetch categories for sidebar
$stmtCats = $pdo->query("SELECT * FROM categories ORDER BY name");
$categories = $stmtCats->fetchAll();
?>

<div class="page-header">
    <div class="container">
        <h1>Shop</h1>
        <div class="breadcrumb">
            <a href="<?= baseUrl() ?>">Home</a>
            <span>/</span>
            <span>Shop</span>
        </div>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="shop-layout">
            <!-- Sidebar -->
            <aside class="shop-sidebar">
                <div class="sidebar-section">
                    <h3>Search</h3>
                    <form method="GET" action="">
                        <?php if ($categoryFilter): ?>
                            <input type="hidden" name="category" value="<?= $categoryFilter ?>">
                        <?php endif; ?>
                        <input type="text" name="search" id="shopSearch" class="form-control" placeholder="Search products..." value="<?= sanitize($search) ?>">
                    </form>
                </div>

                <div class="sidebar-section">
                    <h3>Categories</h3>
                    <ul class="category-list">
                        <li>
                            <a href="<?= baseUrl('pages/shop.php') ?>" class="<?= $categoryFilter === 0 ? 'active' : '' ?>">
                                All Products
                            </a>
                        </li>
                        <?php foreach ($categories as $cat): ?>
                        <li>
                            <a href="<?= baseUrl('pages/shop.php?category=' . $cat['id']) ?>" class="<?= $categoryFilter === $cat['id'] ? 'active' : '' ?>">
                                <?= sanitize($cat['name']) ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </aside>

            <!-- Product List -->
            <div>
                <div class="shop-toolbar">
                    <span class="result-count"><?= $totalProducts ?> product<?= $totalProducts !== 1 ? 's' : '' ?> found</span>
                    <form method="GET" action="" style="display: flex; gap: 10px; align-items: center;">
                        <?php if ($categoryFilter): ?>
                            <input type="hidden" name="category" value="<?= $categoryFilter ?>">
                        <?php endif; ?>
                        <?php if ($search): ?>
                            <input type="hidden" name="search" value="<?= sanitize($search) ?>">
                        <?php endif; ?>
                        <select name="sort" onchange="this.form.submit()">
                            <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest</option>
                            <option value="price_low" <?= $sort === 'price_low' ? 'selected' : '' ?>>Price: Low to High</option>
                            <option value="price_high" <?= $sort === 'price_high' ? 'selected' : '' ?>>Price: High to Low</option>
                            <option value="name" <?= $sort === 'name' ? 'selected' : '' ?>>Name: A-Z</option>
                        </select>
                    </form>
                </div>

                <?php if (empty($products)): ?>
                    <div class="empty-state">
                        <i class="fas fa-search"></i>
                        <h2>No products found</h2>
                        <p>Try adjusting your search or category filter.</p>
                        <a href="<?= baseUrl('pages/shop.php') ?>" class="btn btn-outline">Clear Filters</a>
                    </div>
                <?php else: ?>
                    <div class="product-grid">
                        <?php foreach ($products as $product): ?>
                        <div class="card product-card">
                            <div class="product-img-wrapper" style="position: relative;">
                                <a href="<?= baseUrl('pages/product.php?id=' . $product['id']) ?>">
                                    <img src="<?= sanitize($product['image']) ?>" alt="<?= sanitize($product['name']) ?>" class="product-img" loading="lazy">
                                </a>
                                <button type="button" class="quick-view-btn" onclick="quickView(<?= $product['id'] ?>)">
                                    <i class="fas fa-eye"></i> Quick View
                                </button>
                                <?php if (isLoggedIn()): ?>
                                <button type="button" class="wishlist-toggle-btn" data-id="<?= $product['id'] ?>" style="position: absolute; top: 10px; right: 10px; background: rgba(11,15,26,0.6); backdrop-filter: blur(4px); color: var(--text-muted); border: 1px solid var(--border-color); width: 36px; height: 36px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: var(--transition);">
                                    <i class="fas fa-heart"></i>
                                </button>
                                <?php endif; ?>
                            </div>
                            <div class="product-info">
                                <span class="product-category"><?= sanitize($product['category_name']) ?></span>
                                <div class="product-stars">
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                                </div>
                                <h3 class="product-name">
                                    <a href="<?= baseUrl('pages/product.php?id=' . $product['id']) ?>" style="color: inherit;">
                                        <?= sanitize($product['name']) ?>
                                    </a>
                                </h3>
                                <div class="product-price"><?= formatPrice($product['price']) ?></div>
                            </div>
                            <div class="product-actions">
                                <form action="<?= baseUrl('includes/cart_actions.php') ?>" method="POST" style="flex:1;">
                                    <input type="hidden" name="action" value="add">
                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <input type="hidden" name="redirect" value="<?= baseUrl('pages/shop.php?' . http_build_query($_GET)) ?>">
                                    <button type="submit" class="btn btn-primary btn-sm btn-full">
                                        <i class="fas fa-cart-plus"></i> Add to Cart
                                    </button>
                                </form>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <?php
                        $queryParams = $_GET;
                        for ($i = 1; $i <= $totalPages; $i++):
                            $queryParams['page'] = $i;
                        ?>
                            <?php if ($i === $page): ?>
                                <span class="active"><?= $i ?></span>
                            <?php else: ?>
                                <a href="<?= baseUrl('pages/shop.php?' . http_build_query($queryParams)) ?>"><?= $i ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php if (isLoggedIn()): ?>
<script>
document.querySelectorAll('.wishlist-toggle-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const productId = this.getAttribute('data-id');
        fetch('<?= baseUrl("includes/wishlist_actions.php") ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ 'action': 'toggle', 'product_id': productId })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                if(data.status === 'added') {
                    this.style.color = 'var(--error)';
                    this.innerHTML = '<i class="fas fa-heart"></i>';
                } else {
                    this.style.color = 'var(--text-muted)';
                }
            } else {
                alert(data.message);
            }
        });
    });
});
</script>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
