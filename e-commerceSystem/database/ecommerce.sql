-- =====================================================
-- E-Commerce System Database
-- Run this file in phpMyAdmin or MySQL CLI
-- =====================================================

CREATE DATABASE IF NOT EXISTS `ecommerce_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `ecommerce_db`;

-- -----------------------------------------------------
-- Table: users
-- -----------------------------------------------------
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('user','admin') NOT NULL DEFAULT 'user',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- Table: categories
-- -----------------------------------------------------
CREATE TABLE `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `description` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- Table: products
-- -----------------------------------------------------
CREATE TABLE `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT NOT NULL,
  `name` VARCHAR(200) NOT NULL,
  `description` TEXT,
  `price` DECIMAL(10,2) NOT NULL,
  `stock` INT NOT NULL DEFAULT 0,
  `image` VARCHAR(500) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- Table: cart
-- -----------------------------------------------------
CREATE TABLE `cart` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `quantity` INT NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- Table: orders
-- -----------------------------------------------------
CREATE TABLE `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `total` DECIMAL(10,2) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(30) NOT NULL,
  `address` TEXT NOT NULL,
  `city` VARCHAR(100) NOT NULL,
  `status` ENUM('Pending','Processing','Shipped','Delivered','Cancelled') DEFAULT 'Pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- Table: order_items
-- -----------------------------------------------------
CREATE TABLE `order_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `quantity` INT NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- Table: contacts
-- -----------------------------------------------------
CREATE TABLE `contacts` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `subject` VARCHAR(200) NOT NULL,
  `message` TEXT NOT NULL,
  `is_read` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- Table: wishlist
-- -----------------------------------------------------
CREATE TABLE `wishlist` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
  UNIQUE KEY `user_product` (`user_id`, `product_id`)
) ENGINE=InnoDB;

-- =====================================================
-- SAMPLE DATA
-- =====================================================

-- Admin user (password: admin123)
INSERT INTO `users` (`name`, `email`, `password`, `role`) VALUES
('Admin User', 'admin@shop.com', '$2y$10$8K1p/a0dR1xFc0aGOd0aNeYjHCIEu6DC3b6B2hdibnLIMnOVY7g6W', 'admin');

-- Test user (password: user123)
INSERT INTO `users` (`name`, `email`, `password`, `role`) VALUES
('John Doe', 'user@shop.com', '$2y$10$YS3rH6aT1Dq3GxzmE0rNcON3SxFJqYTqf8r0L8uKVF0k0jR9CRBXS', 'user');

-- Categories
INSERT INTO `categories` (`name`, `description`) VALUES
('Electronics', 'Smartphones, laptops, gadgets and accessories'),
('Clothing', 'Men and women fashion, casual and formal wear'),
('Accessories', 'Watches, bags, jewelry and more'),
('Home & Living', 'Furniture, decor, kitchen essentials');

-- Products
INSERT INTO `products` (`category_id`, `name`, `description`, `price`, `stock`, `image`) VALUES
(1, 'Wireless Bluetooth Headphones', 'Premium noise-cancelling wireless headphones with 30-hour battery life and crystal-clear sound quality.', 79.99, 50, 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500'),
(1, 'Smart Watch Pro', 'Feature-packed smartwatch with health monitoring, GPS tracking, and 7-day battery life.', 199.99, 30, 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=500'),
(1, 'Portable Power Bank', 'Ultra-slim 20000mAh power bank with fast charging support for all devices.', 39.99, 100, 'https://images.unsplash.com/photo-1609091839311-d5365f9ff1c5?w=500'),
(2, 'Classic Denim Jacket', 'Timeless denim jacket crafted from premium cotton with a comfortable modern fit.', 89.99, 40, 'https://images.unsplash.com/photo-1576995853123-5a10305d93c0?w=500'),
(2, 'Cotton Casual T-Shirt', 'Soft breathable cotton t-shirt perfect for everyday comfort and style.', 24.99, 200, 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=500'),
(2, 'Running Sneakers', 'Lightweight performance sneakers with cushioned sole for maximum comfort.', 119.99, 60, 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=500'),
(3, 'Leather Crossbody Bag', 'Elegant genuine leather crossbody bag with adjustable strap and multiple compartments.', 64.99, 35, 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=500'),
(3, 'Minimalist Analog Watch', 'Sleek minimalist watch with sapphire crystal glass and genuine leather strap.', 149.99, 25, 'https://images.unsplash.com/photo-1524592094714-0f0654e20314?w=500'),
(3, 'Polarized Sunglasses', 'UV400 polarized sunglasses with lightweight titanium frame.', 54.99, 80, 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=500'),
(4, 'Ceramic Table Lamp', 'Handcrafted ceramic table lamp with warm ambient lighting for any room.', 44.99, 45, 'https://images.unsplash.com/photo-1507473885765-e6ed057ab3fe?w=500'),
(4, 'Scented Candle Set', 'Set of 3 premium soy wax candles with relaxing lavender, vanilla, and jasmine scents.', 29.99, 120, 'https://images.unsplash.com/photo-1602028915047-37269d1a73f7?w=500'),
(4, 'Bamboo Kitchen Organizer', 'Eco-friendly bamboo organizer with multiple compartments for kitchen essentials.', 34.99, 70, 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=500');
