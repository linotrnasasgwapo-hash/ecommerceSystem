<?php
/**
 * Login Page
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

if (isLoggedIn()) {
    redirect(baseUrl());
}

$pageTitle = 'Login';
$isAuthPage = true;
require_once __DIR__ . '/../includes/header.php';
?>

<div class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <h1>Welcome Back</h1>
            <p class="auth-subtitle">Sign in to your premium ShopVibe account</p>

            <form action="<?= baseUrl('includes/auth_actions.php') ?>" method="POST" data-validate>
                <input type="hidden" name="action" value="login">

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="name@example.com" required autocomplete="email">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-input-wrapper">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required autocomplete="current-password">
                        <button type="button" class="password-toggle" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="auth-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>
                    <a href="#" class="forgot-password">Forgot password?</a>
                </div>

                <button type="submit" class="btn btn-primary btn-lg btn-full">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </button>
            </form>

            <div class="auth-divider">Or continue with</div>

            <div class="social-auth">
                <button class="btn-social"><i class="fab fa-google"></i> Google</button>
                <button class="btn-social"><i class="fab fa-facebook-f"></i> Facebook</button>
            </div>

            <div class="auth-footer">
                Don't have an account? <a href="<?= baseUrl('pages/register.php') ?>">Join ShopVibe</a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');

    if (togglePassword && password) {
        togglePassword.addEventListener('click', () => {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            togglePassword.querySelector('i').classList.toggle('fa-eye');
            togglePassword.querySelector('i').classList.toggle('fa-eye-slash');
        });
    }
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
