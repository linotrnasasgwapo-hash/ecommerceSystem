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
                <h2>Welcome to <span>Renalyn's Favorite Online Shop</span></h2>
                <p>Founded with love in 2026, Renalyn's Favorite Online Shop brings together Renalyn's absolute favorite treats, flowers, home-cooked dishes, and pampering salon services!</p>
                <p>Our mission is to curate top-quality dark chocolates, fresh baby pink flower arrangements, comforting native chicken tinola, crispy fried fish with kalabasa & alugbati vegetable soup, as well as expert nail care, hair rebonding, and professional makeup services.</p>
                <p>Whether you are treating yourself or someone special, we're dedicated to delivering delight and exceptional service with every order.</p>
            </div>
            <div class="about-img">
                <img src="../assets/img/team-group.jpg" alt="Renalyn's Favorite Online Shop Team">
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
                <div class="stat-number">500+</div>
                <div class="stat-label">Products</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">50+</div>
                <div class="stat-label">Brands</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">24/7</div>
                <div class="stat-label">Support</div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>Meet Our <span>Team</span></h2>
            <p>The people behind ShopVibe's mission</p>
        </div>
        <div class="team-grid">
            <div class="team-card">
                <div class="team-avatar"><i class="fas fa-user-tie"></i></div>
                <h3>Avelino Tenasas</h3>
                <p>Founder & CEO</p>
            </div>
            <div class="team-card">
                <div class="team-avatar"><i class="fas fa-box-open"></i></div>
                <h3>Jhomel Gaylon</h3>
                <p>Head of Product</p>
            </div>
            <div class="team-card">
                <div class="team-avatar"><i class="fas fa-laptop-code"></i></div>
                <h3>Johlin Presquito</h3>
                <p>Lead Developer</p>
            </div>
            <div class="team-card">
                <div class="team-avatar"><i class="fas fa-headset"></i></div>
                <h3>Ma. Luiz Nanoy</h3>
                <p>Customer Success</p>
            </div>
            <div class="team-card">
                <div class="team-avatar"><i class="fas fa-paint-brush"></i></div>
                <h3>Sheila Marie Questorio</h3>
                <p>Lead Designer</p>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
