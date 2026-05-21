</main>

<!-- Footer -->
<?php if (!isset($isAuthPage) || !$isAuthPage): ?>
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-col">
                <a href="<?= baseUrl() ?>" class="footer-logo">
                    <i class="fas fa-bolt"></i> Shop<span>Vibe</span>
                </a>
                <p class="footer-desc">Your premium destination for quality products. We bring you the best in electronics, fashion, accessories, and home essentials.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div class="footer-col">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="<?= baseUrl() ?>">Home</a></li>
                    <li><a href="<?= baseUrl('pages/shop.php') ?>">Shop</a></li>
                    <li><a href="<?= baseUrl('pages/about.php') ?>">About Us</a></li>
                    <li><a href="<?= baseUrl('pages/contact.php') ?>">Contact</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Customer Service</h4>
                <ul>
                    <li><a href="<?= baseUrl('pages/cart.php') ?>">Shopping Cart</a></li>
                    <li><a href="<?= baseUrl('pages/login.php') ?>">My Account</a></li>
                    <li><a href="#">Shipping Policy</a></li>
                    <li><a href="#">Return Policy</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Contact Info</h4>
                <ul class="contact-info">
                    <li><i class="fas fa-map-marker-alt"></i> Saraet Himamaylan City</li>
                    <li><i class="fas fa-phone"></i> +1 (555) 123-4567</li>
                    <li><i class="fas fa-envelope"></i> support@shopvibe.com</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> ShopVibe. All rights reserved.</p>
        </div>
    </div>
</footer>
<?php else: ?>
<div class="auth-minimal-footer" style="padding: 20px; text-align: center; color: rgba(255,255,255,0.4); font-size: 0.85rem; position: relative; z-index: 10;">
    &copy; <?= date('Y') ?> ShopVibe Premium. All rights reserved.
</div>
<?php endif; ?>

<script src="<?= baseUrl('assets/js/main.js') ?>"></script>
<script>
window.baseUrlString = '<?= baseUrl() ?>';
function quickView(id) {
    Swal.fire({
        title: 'Loading...', 
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading(),
        background: 'var(--bg-card)',
        color: 'var(--text-primary)'
    });
    fetch(window.baseUrlString + 'includes/quick_view.php?id=' + id)
    .then(r => r.json())
    .then(d => {
       if (d.success) {
          Swal.fire({
             html: `
                <div style="display:flex; gap:20px; text-align:left; flex-wrap:wrap;">
                    <div style="flex:1; min-width:250px;">
                        <img src="${d.product.image}" style="width:100%; border-radius:12px; object-fit:cover;">
                    </div>
                    <div style="flex:1; min-width:250px;">
                        <span style="color:var(--primary); font-size:0.8rem; text-transform:uppercase; font-weight:bold;">${d.product.category}</span>
                        <h2 style="margin:5px 0 10px; font-size:1.5rem;">${d.product.name}</h2>
                        <p style="color:var(--primary); font-size:1.5rem; font-weight:bold;">${d.product.priceFormatted}</p>
                        <p style="color:var(--text-secondary); font-size:0.9rem; margin:15px 0; display:-webkit-box; -webkit-line-clamp:4; -webkit-box-orient:vertical; overflow:hidden;">${d.product.description}</p>
                        <a href="${d.product.url}" class="btn btn-primary btn-full" style="margin-top:10px;">View Full Details</a>
                    </div>
                </div>`,
             width: 800,
             showConfirmButton: false,
             showCloseButton: true,
             background: 'var(--bg-card)',
             color: 'var(--text-primary)',
             customClass: { popup: 'quick-view-popup' }
          });
       } else {
          Swal.fire({ icon: 'error', title: 'Error', text: d.message, background: 'var(--bg-card)', color: 'var(--text-primary)' });
       }
    }).catch(e => {
        Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to load product.', background: 'var(--bg-card)', color: 'var(--text-primary)' });
    });
}
</script>
</body>
</html>
