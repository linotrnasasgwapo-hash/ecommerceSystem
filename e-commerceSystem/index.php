<?php
/**
 * Home Page
 */
$pageTitle = 'Home';
require_once __DIR__ . '/includes/header.php';

// Fetch featured products
$stmt = $pdo->query("SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC LIMIT 8");
$featuredProducts = $stmt->fetchAll();

// Fetch categories
$stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$categories = $stmt->fetchAll();
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <span class="hero-badge animate-fade-up" style="opacity:0;"><i class="fas fa-fire"></i> New Arrivals 2026</span>
            <h1 class="animate-fade-up delay-1" style="opacity:0;">Discover <span>Premium</span> Products Online</h1>
            <p class="animate-fade-up delay-2" style="opacity:0;">Shop the latest trends in electronics, fashion, and lifestyle with fast delivery and premium quality guaranteed.</p>
            <div class="hero-actions animate-fade-up delay-3" style="opacity:0;">
                <a href="<?= baseUrl('pages/shop.php') ?>" class="btn btn-primary btn-lg">
                    <i class="fas fa-shopping-bag"></i> Shop Now
                </a>
                <a href="<?= baseUrl('pages/about.php') ?>" class="btn btn-outline btn-lg">
                    Learn More
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>Browse <span>Categories</span></h2>
            <p>Explore our curated collections across all your favorite categories</p>
        </div>
        <div class="category-grid">
            <?php
            $icons = ['fas fa-microchip', 'fas fa-tshirt', 'fas fa-gem', 'fas fa-couch'];
            foreach ($categories as $i => $cat):
            ?>
            <a href="<?= baseUrl('pages/shop.php?category=' . $cat['id']) ?>" class="category-card">
                <i class="<?= $icons[$i % count($icons)] ?>"></i>
                <h3><?= sanitize($cat['name']) ?></h3>
                <p><?= sanitize($cat['description']) ?></p>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="section" style="background: var(--bg-surface);">
    <div class="container">
        <div class="section-header">
            <h2>Featured <span>Products</span></h2>
            <p>Handpicked items just for you — quality meets style</p>
        </div>
        <div class="product-grid">
            <?php foreach ($featuredProducts as $product): ?>
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
                        <input type="hidden" name="redirect" value="<?= baseUrl() ?>">
                        <button type="submit" class="btn btn-primary btn-sm btn-full">
                            <i class="fas fa-cart-plus"></i> Add to Cart
                        </button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div style="text-align: center; margin-top: 40px;">
            <a href="<?= baseUrl('pages/shop.php') ?>" class="btn btn-outline btn-lg">View All Products <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="section">
    <div class="container" style="text-align: center;">
        <h2 style="font-size: 2rem; margin-bottom: 12px;">Ready to <span style="color: var(--primary);">Shop</span>?</h2>
        <p style="color: var(--text-secondary); margin-bottom: 28px; max-width: 500px; margin-left:auto; margin-right:auto;">
            Join thousands of happy customers. Create your account today and start shopping.
        </p>
        <div style="display: flex; gap: 14px; justify-content: center; flex-wrap: wrap;">
            <a href="<?= baseUrl('pages/register.php') ?>" class="btn btn-primary btn-lg">
                <i class="fas fa-user-plus"></i> Create Account
            </a>
            <a href="<?= baseUrl('pages/contact.php') ?>" class="btn btn-outline btn-lg">
                <i class="fas fa-envelope"></i> Contact Us
            </a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
