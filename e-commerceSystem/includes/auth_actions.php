<?php
/**
 * Authentication Actions: Login, Register, Logout
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';

$action = $_REQUEST['action'] ?? '';

// ── LOGOUT ──
if ($action === 'logout') {
    session_destroy();
    header('Location: ' . baseUrl('pages/login.php'));
    exit;
}

// ── LOGIN ──
if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        setFlash('error', 'Please fill in all fields.');
        redirect(baseUrl('pages/login.php'));
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];

        if ($user['role'] === 'admin') {
            redirect(baseUrl('admin/'));
        } else {
            redirect(baseUrl());
        }
    } else {
        setFlash('error', 'Invalid email or password.');
        redirect(baseUrl('pages/login.php'));
    }
}

// ── REGISTER ──
if ($action === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if (empty($name) || empty($email) || empty($password) || empty($confirm)) {
        setFlash('error', 'Please fill in all fields.');
        redirect(baseUrl('pages/register.php'));
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        setFlash('error', 'Please enter a valid email address.');
        redirect(baseUrl('pages/register.php'));
    }

    if (strlen($password) < 6) {
        setFlash('error', 'Password must be at least 6 characters.');
        redirect(baseUrl('pages/register.php'));
    }

    if ($password !== $confirm) {
        setFlash('error', 'Passwords do not match.');
        redirect(baseUrl('pages/register.php'));
    }

    // Check duplicate email
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        setFlash('error', 'Email already registered.');
        redirect(baseUrl('pages/register.php'));
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$name, $email, $hashed]);

    // Auto-login after registration
    $newUserId = $pdo->lastInsertId();
    $_SESSION['user_id']   = $newUserId;
    $_SESSION['user_name'] = $name;
    $_SESSION['user_role'] = 'user';

    setFlash('success', "Welcome to ShopVibe, $name! Your account has been created successfully.");
    redirect(baseUrl('pages/profile.php'));
}

redirect(baseUrl());
