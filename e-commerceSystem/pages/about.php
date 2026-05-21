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
                <h2>We Are <span>ShopVibe</span></h2>
                <p>Founded in 2026, ShopVibe has grown from a small startup into a premier online shopping destination. We believe that everyone deserves access to quality products at fair prices, delivered with exceptional service.</p>
                <p>Our mission is to curate the best products across electronics, fashion, accessories, and home essentials — all in one place. We work directly with manufacturers and verified suppliers to ensure authenticity and quality.</p>
                <p>With thousands of satisfied customers and counting, we're committed to making your shopping experience seamless, enjoyable, and rewarding.</p>
            </div>
            <div class="about-img">
                <img src="../assets/img/team-group.jpg" alt="ShopVibe Team">
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
