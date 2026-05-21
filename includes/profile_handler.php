<?php
/**
 * Profile Handler
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(baseUrl('pages/profile.php'));
}

$action = $_POST['action'] ?? '';
$userId = currentUserId();

if ($action === 'update_profile') {
    $name = trim($_POST['name'] ?? '');
    
    if (empty($name)) {
        setFlash('error', 'Name cannot be empty.');
        redirect(baseUrl('pages/profile.php'));
    }

    try {
        $stmt = $pdo->prepare("UPDATE users SET name = ? WHERE id = ?");
        $stmt->execute([$name, $userId]);
        
        // Update session name if it exists
        if (isset($_SESSION['user_name'])) {
            $_SESSION['user_name'] = $name;
        }

        setFlash('success', 'Profile updated successfully.');
    } catch (Exception $e) {
        setFlash('error', 'Failed to update profile.');
    }
} elseif ($action === 'change_password') {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        setFlash('error', 'Please fill in all password fields.');
        redirect(baseUrl('pages/profile.php'));
    }

    if ($newPassword !== $confirmPassword) {
        setFlash('error', 'New passwords do not match.');
        redirect(baseUrl('pages/profile.php'));
    }

    if (strlen($newPassword) < 6) {
        setFlash('error', 'New password must be at least 6 characters.');
        redirect(baseUrl('pages/profile.php'));
    }

    // Verify current password
    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    if ($user && password_verify($currentPassword, $user['password'])) {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmtUpdate = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmtUpdate->execute([$hash, $userId]);
        setFlash('success', 'Password changed successfully!');
    } else {
        setFlash('error', 'Incorrect current password.');
    }
}

redirect(baseUrl('pages/profile.php'));
