<?php
/**
 * Register Page
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

if (isLoggedIn()) {
    redirect(baseUrl());
}

$pageTitle = 'Register';
$isAuthPage = true;
require_once __DIR__ . '/../includes/header.php';
?>

<div class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <h1>Create Account</h1>
            <p class="auth-subtitle">Join the premium ShopVibe community</p>

            <form action="<?= baseUrl('includes/auth_actions.php') ?>" method="POST" data-validate>
                <input type="hidden" name="action" value="register">

                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="John Doe" required autocomplete="name">
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="name@example.com" required autocomplete="email">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-input-wrapper">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Min. 6 characters" minlength="6" required autocomplete="new-password">
                        <button type="button" class="password-toggle" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <div class="password-input-wrapper">
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Repeat your password" required autocomplete="new-password">
                        <button type="button" class="password-toggle" id="toggleConfirmPassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg btn-full">
                    <i class="fas fa-user-plus"></i> Create Account
                </button>
            </form>

            <div class="auth-divider">Or join with</div>

            <div class="social-auth">
                <button class="btn-social"><i class="fab fa-google"></i> Google</button>
                <button class="btn-social"><i class="fab fa-facebook-f"></i> Facebook</button>
            </div>

            <div class="auth-footer">
                Already have an account? <a href="<?= baseUrl('pages/login.php') ?>">Sign In</a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const setupToggle = (toggleId, inputId) => {
        const toggle = document.getElementById(toggleId);
        const input = document.getElementById(inputId);
        if (toggle && input) {
            toggle.addEventListener('click', () => {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                toggle.querySelector('i').classList.toggle('fa-eye');
                toggle.querySelector('i').classList.toggle('fa-eye-slash');
            });
        }
    };
    setupToggle('togglePassword', 'password');
    setupToggle('toggleConfirmPassword', 'confirm_password');
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
