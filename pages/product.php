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
                <img src="<?= sanitize(baseUrl($product['image'])) ?>" alt="<?= sanitize($product['name']) ?>">
            </div>
            <div class="product-detail-info">
                <span class="detail-category"><?= sanitize($product['category_name']) ?></span>
                <h1><?= sanitize($product['name']) ?></h1>
                <div class="detail-price"><?= formatPrice($product['price']) ?></div>
                <p class="detail-desc"><?= nl2br(sanitize($product['description'])) ?></p>

                <?php 
                $isService = ($product['category_id'] == 4 || stripos($product['category_name'], 'beauty') !== false || stripos($product['category_name'], 'salon') !== false || stripos($product['category_name'], 'service') !== false);
                $todayDate = date('Y-m-d');
                ?>

                <div class="detail-stock">
                    <?php if ($isService): ?>
                        <span class="stock-badge stock-in" style="background: rgba(236,72,153,0.15); color: #ec4899; border: 1px solid rgba(236,72,153,0.3);"><i class="fas fa-calendar-check"></i> Available for Online Appointment Booking</span>
                    <?php elseif ($product['stock'] > 0): ?>
                        <span class="stock-badge stock-in"><i class="fas fa-check"></i> In Stock</span>
                        <span style="color: var(--text-muted); font-size: 0.85rem;">(<?= $product['stock'] ?> available)</span>
                    <?php else: ?>
                        <span class="stock-badge stock-out"><i class="fas fa-times"></i> Out of Stock</span>
                    <?php endif; ?>
                </div>

                <?php if ($isService): ?>
                <!-- Service Appointment Booking Form -->
                <form action="<?= baseUrl('includes/cart_actions.php') ?>" method="POST" id="bookingForm" style="background: var(--bg-surface); padding: 20px; border-radius: var(--radius); border: 1px solid var(--border-color); margin-top: 20px;">
                    <input type="hidden" name="action" value="book">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <input type="hidden" name="quantity" value="1">
                    <input type="hidden" name="redirect" value="<?= baseUrl('pages/cart.php') ?>">

                    <h3 style="font-size: 1.1rem; margin-bottom: 16px; color: var(--primary); display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-calendar-alt"></i> Select Appointment Details
                    </h3>

                    <div class="form-group" style="margin-bottom: 14px;">
                        <label for="booking_date" style="font-size: 0.88rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px; display: block;">
                            <i class="fas fa-calendar"></i> Appointment Date *
                        </label>
                        <input type="date" id="booking_date" name="booking_date" min="<?= $todayDate ?>" value="<?= $todayDate ?>" required class="form-control" style="background: var(--bg-card);">
                    </div>

                    <div class="form-group" style="margin-bottom: 14px;">
                        <label for="booking_time" style="font-size: 0.88rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px; display: block;">
                            <i class="fas fa-clock"></i> Time Slot *
                        </label>
                        <select id="booking_time" name="booking_time" required class="form-control" style="background: var(--bg-card);">
                            <option value="">-- Select Available Time Slot --</option>
                            <option value="09:00 AM">09:00 AM - Morning Slot</option>
                            <option value="11:00 AM">11:00 AM - Midday Slot</option>
                            <option value="01:30 PM">01:30 PM - Afternoon Slot</option>
                            <option value="03:30 PM">03:30 PM - Late Afternoon</option>
                            <option value="05:30 PM">05:30 PM - Evening Slot</option>
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom: 18px;">
                        <label for="specialist" style="font-size: 0.88rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px; display: block;">
                            <i class="fas fa-user-nurse"></i> Preferred Specialist
                        </label>
                        <select id="specialist" name="specialist" class="form-control" style="background: var(--bg-card);">
                            <option value="Renalyn (Lead Specialist)">Renalyn (Lead Specialist)</option>
                            <option value="Senior Salon Stylist (Girl)">Senior Salon Stylist (Girl)</option>
                            <option value="Nail & Beauty Tech (Girl)">Nail & Beauty Tech (Girl)</option>
                            <option value="First Available Specialist">First Available Specialist</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg btn-full" style="background: linear-gradient(135deg, #ec4899, #db2777); border: none;">
                        <i class="fas fa-calendar-check"></i> Confirm Service Booking
                    </button>
                </form>
                <?php elseif ($product['stock'] > 0): ?>
                <!-- Standard Purchasing Form -->
                <form action="<?= baseUrl('includes/cart_actions.php') ?>" method="POST" id="addToCartForm" style="margin-top: 16px;">
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
                        <img src="<?= sanitize(baseUrl($rp['image'])) ?>" alt="<?= sanitize($rp['name']) ?>" class="product-img" loading="lazy">
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
