<?php
/**
 * Authentication & Session Helper
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

function isAdmin(): bool {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'Please log in to continue.'];
        header('Location: /e-commerceSystem/pages/login.php');
        exit;
    }
}

function requireAdmin(): void {
    if (!isLoggedIn() || !isAdmin()) {
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'Access denied.'];
        header('Location: /e-commerceSystem/pages/login.php');
        exit;
    }
}

function currentUserId(): ?int {
    return $_SESSION['user_id'] ?? null;
}

function currentUserName(): ?string {
    return $_SESSION['user_name'] ?? null;
}
