<?php
/**
 * About Us Page
 */
$pageTitle = 'About Us';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1>About Us</h1>
        <div class="breadcrumb">
            <a href="<?= baseUrl() ?>">Home</a>
            <span>/</span>
            <span>About Us</span>
        </div>
    </div>
</div>

<!-- About Section -->
<section class="section">
    <div class="container">
        <div class="about-grid">
            <div class="about-text">
                <h2>Welcome to <span>Renalyn's Favorite</span></h2>
                <p>Founded with love in 2026, <strong>Renalyn's Favorite Online Shop</strong> brings together Renalyn's absolute favorite treats, fresh flowers, home-cooked dishes, and pampering salon services into one seamless hybrid experience!</p>
                <p>We believe everyone deserves easy access to authentic, high-quality favorites — whether it's rich dark chocolate kisses, fresh baby pink flower arrangements, comforting native chicken tinola, crispy fried fish with veggie soup, or professional salon appointments for hair rebonding, makeup, and nail specialization.</p>
                <p>With thousands of happy customers and counting, our team is dedicated to delivering delight, premium quality, and exceptional care with every single order and appointment booking.</p>
            </div>
            <div class="about-img">
                <img src="<?= baseUrl('assets/img/team-group.jpg') ?>" alt="Renalyn's Favorite Online Shop Team" style="border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); width: 100%; max-height: 520px; object-fit: cover; object-position: center;">
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="section" style="background: var(--bg-surface);">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">5K+</div>
                <div class="stat-label">Happy Customers</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">100%</div>
                <div class="stat-label">Quality Guaranteed</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">7</div>
                <div class="stat-label">Curated Favorites & Services</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">24/7</div>
                <div class="stat-label">Customer Care</div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
