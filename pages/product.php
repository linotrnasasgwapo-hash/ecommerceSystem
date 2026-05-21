<?php
/**
 * Product Detail Page
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

$productId = (int)($_GET['id'] ?? 0);

if ($productId <= 0) {
    setFlash('error', 'Product not found.');
    redirect(baseUrl('pages/shop.php'));
}

$stmt = $pdo->prepare("
    SELECT p.*, c.name AS category_name
    FROM products p
    JOIN categories c ON p.category_id = c.id
    WHERE p.id = ?
");
$stmt->execute([$productId]);
$product = $stmt->fetch();

if (!$product) {
    setFlash('error', 'Product not found.');
    redirect(baseUrl('pages/shop.php'));
}

$pageTitle = $product['name'];

// Related products (same category, exclude current)
$stmt = $pdo->prepare("
    SELECT p.*, c.name AS category_name
    FROM products p
    JOIN categories c ON p.category_id = c.id
    WHERE p.category_id = ? AND p.id != ?
    LIMIT 4
");
$stmt->execute([$product['category_id'], $product['id']]);
$related = $stmt->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1><?= sanitize($product['name']) ?></h1>
        <div class="breadcrumb">
            <a href="<?= baseUrl() ?>">Home</a>
            <span>/</span>
            <a href="<?= baseUrl('pages/shop.php') ?>">Shop</a>
            <span>/</span>
            <a href="<?= baseUrl('pages/shop.php?category=' . $product['category_id']) ?>"><?= sanitize($product['category_name']) ?></a>
            <span>/</span>
            <span><?= sanitize($product['name']) ?></span>
        </div>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="product-detail">
            <div class="product-detail-img">
                <img src="<?= sanitize($product['image']) ?>" alt="<?= sanitize($product['name']) ?>">
            </div>
            <div class="product-detail-info">
                <span class="detail-category"><?= sanitize($product['category_name']) ?></span>
                <h1><?= sanitize($product['name']) ?></h1>
                <div class="detail-price"><?= formatPrice($product['price']) ?></div>
                <p class="detail-desc"><?= nl2br(sanitize($product['description'])) ?></p>

                <div class="detail-stock">
                    <?php if ($product['stock'] > 0): ?>
                        <span class="stock-badge stock-in"><i class="fas fa-check"></i> In Stock</span>
                        <span style="color: var(--text-muted); font-size: 0.85rem;">(<?= $product['stock'] ?> available)</span>
                    <?php else: ?>
                        <span class="stock-badge stock-out"><i class="fas fa-times"></i> Out of Stock</span>
                    <?php endif; ?>
                </div>

                <?php if ($product['stock'] > 0): ?>
                <form action="<?= baseUrl('includes/cart_actions.php') ?>" method="POST" id="addToCartForm">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <input type="hidden" name="redirect" value="<?= baseUrl('pages/product.php?id=' . $product['id']) ?>">

                    <div class="quantity-selector">
                        <button type="button" class="qty-btn" data-action="decrease">−</button>
                        <input type="number" name="quantity" value="1" min="1" max="<?= $product['stock'] ?>" class="qty-input" id="qtyInput">
                        <button type="button" class="qty-btn" data-action="increase">+</button>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg" style="margin-top: 8px;">
                        <i class="fas fa-cart-plus"></i> Add to Cart
                    </button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Related Products -->
<?php if (!empty($related)): ?>
<section class="section" style="background: var(--bg-surface);">
    <div class="container">
        <div class="section-header">
            <h2>Related <span>Products</span></h2>
        </div>
        <div class="product-grid">
            <?php foreach ($related as $rp): ?>
            <div class="card product-card">
                <div class="product-img-wrapper">
                    <a href="<?= baseUrl('pages/product.php?id=' . $rp['id']) ?>">
                        <img src="<?= sanitize($rp['image']) ?>" alt="<?= sanitize($rp['name']) ?>" class="product-img" loading="lazy">
                    </a>
                </div>
                <div class="product-info">
                    <span class="product-category"><?= sanitize($rp['category_name']) ?></span>
                    <h3 class="product-name">
                        <a href="<?= baseUrl('pages/product.php?id=' . $rp['id']) ?>" style="color: inherit;">
                            <?= sanitize($rp['name']) ?>
                        </a>
                    </h3>
                    <div class="product-price"><?= formatPrice($rp['price']) ?></div>
                </div>
                <div class="product-actions">
                    <form action="<?= baseUrl('includes/cart_actions.php') ?>" method="POST" style="flex:1;">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="product_id" value="<?= $rp['id'] ?>">
                        <input type="hidden" name="quantity" value="1">
                        <input type="hidden" name="redirect" value="<?= baseUrl('pages/product.php?id=' . $product['id']) ?>">
                        <button type="submit" class="btn btn-primary btn-sm btn-full">
                            <i class="fas fa-cart-plus"></i> Add to Cart
                        </button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
