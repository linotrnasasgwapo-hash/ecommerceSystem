</main>

<!-- Footer -->
<?php if (!isset($isAuthPage) || !$isAuthPage): ?>
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-col">
                <a href="<?= baseUrl() ?>" class="footer-logo">
                    <i class="fas fa-heart" style="color:#ff4757;"></i> Renalyn's<span>Favorite</span>
                </a>
                <p class="footer-desc">Your favorite online store for chocolates, flowers, home-cooked food, and professional beauty services.</p>
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
                    <li><i class="fas fa-envelope"></i> support@renalynsfavorite.com</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> Renalyn's Favorite Online Shop. All rights reserved.</p>
        </div>
    </div>
</footer>
<?php else: ?>
<div class="auth-minimal-footer" style="padding: 20px; text-align: center; color: rgba(255,255,255,0.4); font-size: 0.85rem; position: relative; z-index: 10;">
    &copy; <?= date('Y') ?> Renalyn's Favorite Online Shop. All rights reserved.
</div>
<?php endif; ?>

<script src="<?= baseUrl('assets/js/main.js') ?>"></script>
<script>
window.baseUrlString = '<?= baseUrl() ?>';

function openBookingModal(id, name, price, image) {
    const today = new Date().toISOString().split('T')[0];
    const priceFormatted = '₱' + parseFloat(price).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    
    Swal.fire({
        title: '<i class="fas fa-calendar-check" style="color:#ec4899;"></i> Salon Service Booking',
        html: `
            <div style="text-align:left; font-family: var(--font);">
                <div style="display:flex; gap:14px; align-items:center; background:var(--bg-surface); padding:12px; border-radius:12px; margin-bottom:18px; border:1px solid var(--border-color);">
                    <img src="${image}" style="width:65px; height:65px; border-radius:10px; object-fit:cover;">
                    <div>
                        <div style="font-size:0.75rem; color:#ec4899; font-weight:700; text-transform:uppercase;"><i class="fas fa-spa"></i> Salon Service</div>
                        <h4 style="margin:2px 0; font-size:1.05rem; color:var(--text-primary);">${name}</h4>
                        <div style="font-weight:700; color:var(--primary); font-size:1.1rem;">${priceFormatted}</div>
                    </div>
                </div>

                <form id="swalBookingForm" action="${window.baseUrlString}includes/cart_actions.php" method="POST">
                    <input type="hidden" name="action" value="book">
                    <input type="hidden" name="product_id" value="${id}">
                    <input type="hidden" name="quantity" value="1">
                    <input type="hidden" name="redirect" value="${window.location.href}">

                    <div style="margin-bottom:14px;">
                        <label style="display:block; font-size:0.85rem; font-weight:600; color:var(--text-secondary); margin-bottom:6px;"><i class="fas fa-calendar-alt"></i> Select Preferred Date *</label>
                        <input type="date" name="booking_date" min="${today}" value="${today}" required class="form-control" style="width:100%; padding:10px; border-radius:10px; border:1px solid var(--border-color); background:var(--bg-dark); color:var(--text-primary); font-family:var(--font);">
                    </div>

                    <div style="margin-bottom:14px;">
                        <label style="display:block; font-size:0.85rem; font-weight:600; color:var(--text-secondary); margin-bottom:6px;"><i class="fas fa-clock"></i> Select Time Slot *</label>
                        <select name="booking_time" required class="form-control" style="width:100%; padding:10px; border-radius:10px; border:1px solid var(--border-color); background:var(--bg-dark); color:var(--text-primary); font-family:var(--font);">
                            <option value="">-- Choose Time Slot --</option>
                            <option value="09:00 AM">09:00 AM - Morning Slot</option>
                            <option value="11:00 AM">11:00 AM - Midday Slot</option>
                            <option value="01:30 PM">01:30 PM - Afternoon Slot</option>
                            <option value="03:30 PM">03:30 PM - Late Afternoon</option>
                            <option value="05:30 PM">05:30 PM - Evening Slot</option>
                        </select>
                    </div>

                    <div style="margin-bottom:18px;">
                        <label style="display:block; font-size:0.85rem; font-weight:600; color:var(--text-secondary); margin-bottom:6px;"><i class="fas fa-user-nurse"></i> Specialist Preference</label>
                        <select name="specialist" class="form-control" style="width:100%; padding:10px; border-radius:10px; border:1px solid var(--border-color); background:var(--bg-dark); color:var(--text-primary); font-family:var(--font);">
                            <option value="Renalyn (Lead Specialist)">Renalyn (Lead Specialist)</option>
                            <option value="Senior Salon Stylist (Girl)">Senior Salon Stylist (Girl)</option>
                            <option value="Nail & Beauty Tech (Girl)">Nail & Beauty Tech (Girl)</option>
                            <option value="First Available Specialist">First Available Specialist</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary btn-full" style="background: linear-gradient(135deg, #ec4899, #db2777); padding:14px; border-radius:12px; font-weight:700; font-size:1rem; border:none; color:#fff; cursor:pointer;">
                        <i class="fas fa-calendar-check"></i> Confirm Service Booking
                    </button>
                </form>
            </div>
        `,
        showConfirmButton: false,
        showCloseButton: true,
        background: 'var(--bg-card)',
        color: 'var(--text-primary)',
        customClass: { popup: 'quick-view-popup' }
    });
}

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
                        <a href="${d.product.url}" class="btn btn-primary btn-full" style="margin-top:10px;">View Details / Booking</a>
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
