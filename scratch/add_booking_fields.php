<?php
require_once __DIR__ . '/../config/database.php';

try {
    echo "Adding booking fields to cart and order_items tables on Railway DB...\n";

    $queries = [
        "ALTER TABLE cart ADD COLUMN booking_date VARCHAR(50) DEFAULT NULL",
        "ALTER TABLE cart ADD COLUMN booking_time VARCHAR(50) DEFAULT NULL",
        "ALTER TABLE cart ADD COLUMN specialist VARCHAR(100) DEFAULT NULL",
        "ALTER TABLE order_items ADD COLUMN booking_date VARCHAR(50) DEFAULT NULL",
        "ALTER TABLE order_items ADD COLUMN booking_time VARCHAR(50) DEFAULT NULL",
        "ALTER TABLE order_items ADD COLUMN specialist VARCHAR(100) DEFAULT NULL"
    ];

    foreach ($queries as $q) {
        try {
            $pdo->exec($q);
            echo "Executed: $q\n";
        } catch (PDOException $e) {
            echo "Notice (may already exist): " . $e->getMessage() . "\n";
        }
    }

    echo "Booking fields migration completed!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
