<?php
/**
 * User Wishlist Page
 */
$pageTitle = 'My Wishlist';
require_once __DIR__ . '/../includes/header.php';

requireLogin();

$userId = currentUserId();

// Fetch wishlist items
$stmt = $pdo->prepare("
    SELECT w.product_id, p.name, p.price, p.image, p.stock, c.name AS category_name
    FROM wishlist w
    JOIN products p ON w.product_id = p.id
    JOIN categories c ON p.category_id = c.id
    WHERE w.user_id = ?
    ORDER BY w.created_at DESC
");
$stmt->execute([$userId]);
$wishlistItems = $stmt->fetchAll();
?>

<div class="page-header">
    <div class="container">
        <h1>My Wishlist</h1>
        <div class="breadcrumb">
            <a href="<?= baseUrl() ?>">Home</a>
            <span>/</span>
            <span>Wishlist</span>
        </div>
    </div>
</div>

<section class="section">
    <div class="container">
        <?php if (empty($wishlistItems)): ?>
            <div class="empty-state card" style="text-align: center; padding: 60px 20px;">
                <i class="fas fa-heart" style="font-size: 4rem; color: var(--text-muted); margin-bottom: 20px;"></i>
                <h2 style="margin-bottom: 15px;">Your wishlist is empty</h2>
                <p style="color: var(--text-secondary); margin-bottom: 25px;">Save items you like and they will appear here.</p>
                <a href="<?= baseUrl('pages/shop.php') ?>" class="btn btn-primary">Discover Products</a>
            </div>
        <?php else: ?>
            <div class="product-grid">
                <?php foreach ($wishlistItems as $item): ?>
                <div class="card product-card" id="wishlist-item-<?= $item['product_id'] ?>">
                    <div class="product-img-wrapper">
                        <a href="<?= baseUrl('pages/product.php?id=' . $item['product_id']) ?>">
                            <img src="<?= sanitize($item['image']) ?>" alt="<?= sanitize($item['name']) ?>" class="product-img" loading="lazy">
                        </a>
                        <!-- Remove button -->
                        <button class="wishlist-remove-btn" onclick="removeFromWishlist(<?= $item['product_id'] ?>)" style="position: absolute; top: 10px; right: 10px; background: rgba(11,15,26,0.7); color: var(--text-primary); border: none; width: 36px; height: 36px; border-radius: 50%; cursor: pointer; transition: var(--transition);">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <div class="product-info">
                        <span class="product-category"><?= sanitize($item['category_name']) ?></span>
                        <h3 class="product-name">
                            <a href="<?= baseUrl('pages/product.php?id=' . $item['product_id']) ?>" style="color: inherit;">
                                <?= sanitize($item['name']) ?>
                            </a>
                        </h3>
                        <div class="product-price"><?= formatPrice($item['price']) ?></div>
                    </div>
                    <div class="product-actions" style="display: flex; gap: 10px; justify-content: space-between;">
                        <?php if ($item['stock'] > 0): ?>
                            <form action="<?= baseUrl('includes/cart_actions.php') ?>" method="POST" style="flex:1;">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                <input type="hidden" name="quantity" value="1">
                                <input type="hidden" name="redirect" value="<?= baseUrl('pages/wishlist.php') ?>">
                                <button type="submit" class="btn btn-primary btn-sm btn-full">
                                    <i class="fas fa-cart-plus"></i> Add to Cart
                                </button>
                            </form>
                        <?php else: ?>
                            <button class="btn btn-outline btn-sm btn-full" disabled style="opacity: 0.5; cursor: not-allowed;">Out of Stock</button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
function removeFromWishlist(productId) {
    if(!confirm('Remove this item from your wishlist?')) return;
    
    fetch('<?= baseUrl("includes/wishlist_actions.php") ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            'action': 'remove',
            'product_id': productId
        })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            const card = document.getElementById('wishlist-item-' + productId);
            if(card) {
                card.remove();
                // If no more items, reload to show empty state
                if(document.querySelectorAll('.product-card').length === 0) {
                    window.location.reload();
                }
            }
        } else {
            alert(data.message);
        }
    })
    .catch(err => console.error(err));
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
