<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';

$cartCount = getCartCount($pdo, currentUserId());
$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ShopVibe - Your premium online shopping destination for electronics, fashion, accessories and home essentials.">
    <title><?= isset($pageTitle) ? sanitize($pageTitle) . ' — ShopVibe' : 'ShopVibe — Premium Online Store' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= baseUrl('assets/css/style.css?v=' . time()) ?>">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const storedTheme = localStorage.getItem('shopvibe-theme');
        if (storedTheme === 'light' || (!storedTheme && window.matchMedia('(prefers-color-scheme: light)').matches)) {
            document.documentElement.classList.add('light-mode');
        }
    </script>
</head>
<body>

<!-- Flash Messages (SweetAlert) -->
<?php if ($flash): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
        Toast.fire({
            icon: '<?= $flash['type'] === 'error' ? 'error' : ($flash['type'] === 'success' ? 'success' : 'info') ?>',
            title: '<?= sanitize(str_replace("'", "\'", $flash['message'])) ?>'
        });
    });
</script>
<?php endif; ?>

<!-- Navigation -->
<?php if (!isset($isAuthPage) || !$isAuthPage): ?>
<nav class="navbar" id="navbar">
    <div class="container nav-container">
        <a href="<?= baseUrl() ?>" class="logo">
            <i class="fas fa-bolt"></i> Shop<span>Vibe</span>
        </a>
<?php else: ?>
<nav class="navbar auth-navbar" style="background: transparent; border: none; position: absolute; width: 100%; top: 0;">
    <div class="container nav-container" style="justify-content: center; height: 100px;">
        <a href="<?= baseUrl() ?>" class="logo" style="font-size: 2rem;">
            <i class="fas fa-bolt"></i> Shop<span>Vibe</span>
        </a>
    </div>
</nav>
<?php endif; ?>

<?php if (!isset($isAuthPage) || !$isAuthPage): ?>
        <ul class="nav-links" id="navLinks">
            <li class="nav-search-mobile">
                <form action="<?= baseUrl('pages/shop.php') ?>" method="GET">
                    <input type="text" name="search" placeholder="Search products...">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            </li>
            <li><a href="<?= baseUrl() ?>" class="nav-link">Home</a></li>
            <li><a href="<?= baseUrl('pages/shop.php') ?>" class="nav-link">Shop</a></li>
            <li><a href="<?= baseUrl('pages/about.php') ?>" class="nav-link">About</a></li>
            <li><a href="<?= baseUrl('pages/contact.php') ?>" class="nav-link">Contact</a></li>
        </ul>

        <div class="nav-actions">
            <form action="<?= baseUrl('pages/shop.php') ?>" method="GET" class="nav-search-form nav-search-desktop">
                <input type="text" name="search" placeholder="Search products...">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>

            <div class="nav-cart-wrapper" style="position: relative;">
                <a href="<?= baseUrl('pages/cart.php') ?>" class="cart-icon" title="Cart" id="navCartIcon">
                    <i class="fas fa-shopping-bag"></i>
                    <?php if ($cartCount > 0): ?>
                        <span class="cart-badge" id="navCartBadge"><?= $cartCount ?></span>
                    <?php else: ?>
                        <span class="cart-badge" id="navCartBadge" style="display: none;">0</span>
                    <?php endif; ?>
                </a>
                
                <div class="cart-dropdown" id="navCartDropdown" style="position: absolute; top: 100%; right: 0; width: 320px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-sm); box-shadow: var(--shadow-lg); padding: 16px; display: none; z-index: 1000; margin-top: 10px;">
                    <div id="cartDropdownItems" style="max-height: 300px; overflow-y: auto; margin-bottom: 12px;">
                        <!-- Skeleton Loader -->
                        <div class="skeleton-item" style="display: flex; gap: 12px; margin-bottom: 12px;">
                            <div class="skeleton" style="width: 50px; height: 50px; border-radius: 4px;"></div>
                            <div style="flex: 1; display:flex; flex-direction:column; gap:8px;">
                                <div class="skeleton" style="width: 80%; height: 12px; border-radius: 2px;"></div>
                                <div class="skeleton" style="width: 40%; height: 10px; border-radius: 2px;"></div>
                            </div>
                        </div>
                    </div>
                    <div style="border-top: 1px solid var(--border-color); padding-top: 12px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <strong>Total:</strong>
                        <span id="cartDropdownTotal" style="color: var(--primary); font-weight: bold;">$0.00</span>
                    </div>
                    <a href="<?= baseUrl('pages/checkout.php') ?>" class="btn btn-primary btn-sm btn-full" style="display: block;">Checkout</a>
                </div>
            </div>

            <button id="themeToggle" class="btn btn-outline btn-sm" style="padding: 6px 10px; border:none;" aria-label="Toggle Theme">
                <i class="fas fa-moon"></i>
            </button>

            <?php if (isLoggedIn()): ?>
                <div class="nav-user-dropdown">
                    <button class="nav-user-btn">
                        <i class="fas fa-user-circle"></i>
                        <span class="nav-user-name"><?= sanitize(currentUserName()) ?></span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu">
                        <?php if (isAdmin()): ?>
                            <a href="<?= baseUrl('admin/') ?>"><i class="fas fa-tachometer-alt"></i> Admin Panel</a>
                        <?php endif; ?>
                        <a href="<?= baseUrl('pages/profile.php') ?>"><i class="fas fa-user-cog"></i> My Profile</a>
                        <a href="<?= baseUrl('pages/wishlist.php') ?>"><i class="fas fa-heart"></i> My Wishlist</a>
                        <a href="<?= baseUrl('pages/orders.php') ?>"><i class="fas fa-box-open"></i> My Orders</a>
                        <a href="<?= baseUrl('includes/auth_actions.php?action=logout') ?>"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="<?= baseUrl('pages/login.php') ?>" class="btn btn-outline btn-sm">Login</a>
                <a href="<?= baseUrl('pages/register.php') ?>" class="btn btn-primary btn-sm nav-register-btn">Register</a>
            <?php endif; ?>

            <button class="hamburger" id="hamburger" aria-label="Toggle Menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</nav>

<!-- Mobile Bottom Navigation Bar -->
<div class="mobile-bottom-nav">
    <a href="<?= baseUrl() ?>"><i class="fas fa-home"></i><span>Home</span></a>
    <a href="<?= baseUrl('pages/shop.php') ?>"><i class="fas fa-store"></i><span>Shop</span></a>
    <a href="<?= baseUrl('pages/cart.php') ?>"><i class="fas fa-shopping-bag"></i><span>Cart</span></a>
    <a href="<?= isLoggedIn() ? baseUrl('pages/profile.php') : baseUrl('pages/login.php') ?>"><i class="fas fa-user"></i><span>Profile</span></a>
</div>
<?php endif; ?>

<main class="main-content">

<?php if (isLoggedIn()): ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const cartWrapper = document.querySelector('.nav-cart-wrapper');
    const cartDropdown = document.getElementById('navCartDropdown');
    const itemsContainer = document.getElementById('cartDropdownItems');
    const totalEl = document.getElementById('cartDropdownTotal');
    const badge = document.getElementById('navCartBadge');

    let isHovered = false;

    if (cartWrapper) {
        cartWrapper.addEventListener('mouseenter', () => {
            isHovered = true;
            cartDropdown.style.display = 'block';
            
            fetch('<?= baseUrl("includes/ajax_cart.php") ?>')
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        badge.style.display = data.count > 0 ? 'flex' : 'none';
                        badge.textContent = data.count;
                        totalEl.textContent = data.totalFormatted;
                        
                        if (data.items.length === 0) {
                            itemsContainer.innerHTML = '<p style="text-align:center; color: var(--text-muted); font-size: 0.9rem;">Your cart is empty.</p>';
                        } else {
                            itemsContainer.innerHTML = data.items.map(item => `
                                <div style="display: flex; gap: 12px; margin-bottom: 12px; align-items: center;">
                                    <img src="${item.image}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                    <div style="flex: 1;">
                                        <div style="font-size: 0.9rem; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 150px;">${item.name}</div>
                                        <div style="font-size: 0.85rem; color: var(--text-muted);">${item.quantity} × ${item.priceFormatted}</div>
                                    </div>
                                </div>
                            `).join('');
                        }
                    }
                });
        });

        cartWrapper.addEventListener('mouseleave', () => {
            isHovered = false;
            cartDropdown.style.display = 'none';
        });
    }
});

    const themeToggle = document.getElementById('themeToggle');
    const updateThemeIcon = () => {
        if (document.documentElement.classList.contains('light-mode')) {
            themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
        } else {
            themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
        }
    };
    updateThemeIcon();

    themeToggle.addEventListener('click', () => {
        document.documentElement.classList.toggle('light-mode');
        updateThemeIcon();
        if (document.documentElement.classList.contains('light-mode')) {
            localStorage.setItem('shopvibe-theme', 'light');
        } else {
            localStorage.setItem('shopvibe-theme', 'dark');
        }
    });
</script>
<?php endif; ?>
