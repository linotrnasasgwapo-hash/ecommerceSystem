<?php
/**
 * Admin Header
 */
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';

requireAdmin();

$flash = getFlash();
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? sanitize($pageTitle) . ' — Admin' : 'Admin Panel' ?> — ShopVibe</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= baseUrl('assets/css/style.css') ?>">
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

<div class="admin-layout">
    <!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="admin-logo">
            <a href="<?= baseUrl('admin/') ?>"><i class="fas fa-bolt"></i> Shop<span>Vibe</span></a>
        </div>
        <div class="admin-label">Main Menu</div>
        <nav class="admin-nav">
            <a href="<?= baseUrl('admin/') ?>" class="<?= $currentPage === 'index' ? 'active' : '' ?>">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="<?= baseUrl('admin/products.php') ?>" class="<?= in_array($currentPage, ['products','product_form']) ? 'active' : '' ?>">
                <i class="fas fa-box"></i> Products
            </a>
            <a href="<?= baseUrl('admin/categories.php') ?>" class="<?= $currentPage === 'categories' ? 'active' : '' ?>">
                <i class="fas fa-tags"></i> Categories
            </a>
            <a href="<?= baseUrl('admin/orders.php') ?>" class="<?= in_array($currentPage, ['orders','order_detail']) ? 'active' : '' ?>">
                <i class="fas fa-shopping-cart"></i> Orders
            </a>
            <a href="<?= baseUrl('admin/contacts.php') ?>" class="<?= $currentPage === 'contacts' ? 'active' : '' ?>">
                <i class="fas fa-envelope"></i> Contacts
            </a>
        </nav>
        <div class="admin-label" style="margin-top: 20px;">Other</div>
        <nav class="admin-nav">
            <a href="<?= baseUrl() ?>"><i class="fas fa-store"></i> View Store</a>
            <a href="#" id="adminThemeToggle"><i class="fas fa-moon"></i> Theme Toggle</a>
            <a href="<?= baseUrl('includes/auth_actions.php?action=logout') ?>"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </aside>

    <script>
        const adminThemeToggle = document.getElementById('adminThemeToggle');
        const updateAdminThemeIcon = () => {
            if (document.documentElement.classList.contains('light-mode')) {
                adminThemeToggle.innerHTML = '<i class="fas fa-sun"></i> Light Mode';
            } else {
                adminThemeToggle.innerHTML = '<i class="fas fa-moon"></i> Dark Mode';
            }
        };
        updateAdminThemeIcon();

        adminThemeToggle.addEventListener('click', (e) => {
            e.preventDefault();
            document.documentElement.classList.toggle('light-mode');
            updateAdminThemeIcon();
            if (document.documentElement.classList.contains('light-mode')) {
                localStorage.setItem('shopvibe-theme', 'light');
            } else {
                localStorage.setItem('shopvibe-theme', 'dark');
            }
        });
    </script>

    <!-- Main Content -->
    <div class="admin-main">
        <button class="btn btn-outline btn-sm" id="adminSidebarToggle" style="display:none; margin-bottom: 16px;">
            <i class="fas fa-bars"></i> Menu
        </button>
        <style>
            @media (max-width: 1024px) {
                #adminSidebarToggle { display: inline-flex !important; }
            }
        </style>
