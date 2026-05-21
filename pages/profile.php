<?php
/**
 * User Profile Page
 */
$pageTitle = 'My Profile';
require_once __DIR__ . '/../includes/header.php';

requireLogin();

$userId = currentUserId();
$stmt = $pdo->prepare("SELECT name, email, created_at FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    redirect(baseUrl('includes/auth_actions.php?action=logout'));
}
?>

<div class="page-header">
    <div class="container">
        <h1>My Dashboard</h1>
        <div class="breadcrumb">
            <a href="<?= baseUrl() ?>">Home</a>
            <span>/</span>
            <span>Profile Dashboard</span>
        </div>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="profile-layout">
            <!-- Sidebar -->
            <aside class="profile-sidebar">
                <div class="card user-summary-card">
                    <div class="user-avatar-wrapper">
                        <div class="user-avatar">
                            <?= strtoupper(substr($user['name'], 0, 1)) ?>
                        </div>
                    </div>
                    <div class="user-info">
                        <h3><?= sanitize($user['name']) ?></h3>
                        <p><?= sanitize($user['email']) ?></p>
                        <span class="member-badge">Premium Member</span>
                        <div style="margin-top: 15px; font-size: 0.8rem; color: var(--text-muted);">
                            Joined <?= date('M Y', strtotime($user['created_at'])) ?>
                        </div>
                    </div>
                </div>

                <nav class="profile-nav">
                    <a href="<?= baseUrl('pages/profile.php') ?>" class="profile-nav-item active">
                        <i class="fas fa-user-circle"></i> Profile Overview
                    </a>
                    <a href="<?= baseUrl('pages/orders.php') ?>" class="profile-nav-item">
                        <i class="fas fa-shopping-bag"></i> My Orders
                    </a>
                    <a href="<?= baseUrl('pages/wishlist.php') ?>" class="profile-nav-item">
                        <i class="fas fa-heart"></i> My Wishlist
                    </a>
                    <a href="<?= baseUrl('includes/auth_actions.php?action=logout') ?>" class="profile-nav-item" style="color: var(--error);">
                        <i class="fas fa-sign-out-alt"></i> Logout Activity
                    </a>
                </nav>
            </aside>

            <!-- Main Content -->
            <div class="profile-main">
                <!-- Profile Settings -->
                <div class="card" style="margin-bottom: 30px;">
                    <div class="card-header">
                        <h2><i class="fas fa-id-card" style="color: var(--primary); margin-right: 10px;"></i> Account Details</h2>
                    </div>
                    <form action="<?= baseUrl('includes/profile_handler.php') ?>" method="POST" data-validate>
                        <input type="hidden" name="action" value="update_profile">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input type="text" id="name" name="name" class="form-control" value="<?= sanitize($user['name']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" class="form-control" value="<?= sanitize($user['email']) ?>" disabled style="opacity: 0.6; cursor: not-allowed;">
                                <small style="color: var(--text-muted); font-size: 0.75rem;">Email cannot be changed for security.</small>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" style="margin-top: 10px;">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </form>
                </div>

                <!-- Security -->
                <div class="card">
                    <div class="card-header">
                        <h2><i class="fas fa-shield-alt" style="color: var(--primary); margin-right: 10px;"></i> Security & Password</h2>
                    </div>
                    <form action="<?= baseUrl('includes/profile_handler.php') ?>" method="POST" data-validate>
                        <input type="hidden" name="action" value="change_password">
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" id="current_password" name="current_password" class="form-control" placeholder="••••••••" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="new_password">New Password</label>
                                <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Min. 6 characters" required minlength="6">
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Confirm New Password</label>
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Repeat new password" required minlength="6">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-outline" style="margin-top: 10px;">
                            <i class="fas fa-key"></i> Update Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
