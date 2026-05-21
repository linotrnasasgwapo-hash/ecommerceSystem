<?php
/**
 * Database Configuration
 * Railway MySQL PDO Connection
 */

$host = getenv("mysql.railway.internal");
$database = getenv("railway");
$user = getenv("root");
$password = getenv("igyIIECWHWvLJVpXSNPIUCoKhqaiYhpW");
$port = getenv("3306");

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4",
        $user,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>