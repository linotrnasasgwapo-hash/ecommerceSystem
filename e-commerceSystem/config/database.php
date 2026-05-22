<?php

define('DB_HOST', 'kodama.proxy.rlwy.net');
define('DB_NAME', 'railway');
define('DB_USER', 'root');
define('DB_PASS', 'igyIIECWHWvLJVpXSNPIUCoKhqaiYhpW');
define('DB_PORT', '50255');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>