<?php
require_once __DIR__ . '/config/database.php';
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS `wishlist` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `user_id` INT NOT NULL,
      `product_id` INT NOT NULL,
      `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
      FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
      UNIQUE KEY `user_product` (`user_id`, `product_id`)
    ) ENGINE=InnoDB;");
    echo "Wishlist table created.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
