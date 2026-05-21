<?php
/**
 * Database Configuration
 * PDO connection to MySQL
 */

define('DB_HOST', 'sql212.infinityfree.com');
define('DB_NAME', 'if0_41944598_ecommerce_db');
define('DB_USER', 'if0_41944598');
define('DB_PASS', 'fh9nLW6I1USpid');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
