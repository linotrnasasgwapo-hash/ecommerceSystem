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
INSERT INTO `categories` (`id`, `name`, `description`) VALUES
(1, 'Sweets & Chocolates', 'Delicious chocolates, candies, and sweet treats'),
(2, 'Flowers & Gifts', 'Fresh flower bouquets and romantic gift arrangements'),
(3, 'Food & Delicacies', 'Delicious home-cooked Filipino meals and traditional dishes'),
(4, 'Beauty & Salon Services', 'Expert pampering, nail care, hair rebonding, and makeup services');

-- Products
INSERT INTO `products` (`category_id`, `name`, `description`, `price`, `stock`, `image`) VALUES
(1, 'kisses (dark) chocolate', 'Indulgent rich dark chocolate kisses, velvety smooth and perfect for satisfying sweet cravings.', 199.00, 50, 'https://images.unsplash.com/photo-1548907040-4baa42d10919?w=600'),
(2, 'Flowers (baby pink)', 'A charming bouquet of fresh, delicate baby pink flowers, beautifully arranged for special occasions.', 899.00, 30, 'https://images.unsplash.com/photo-1563241527-3004b7be0ffd?w=600'),
(3, 'Tinola (Native Chicken)', 'Authentic Filipino native chicken tinola soup simmered with ginger, green papaya, lemongrass, and chili leaves.', 350.00, 25, 'https://images.unsplash.com/photo-1547592166-23ac45744acd?w=600'),
(3, 'fish preto butangan utan(kalabasa,alogbati)', 'Golden crispy fried fish served with wholesome vegetable soup featuring fresh squash (kalabasa) and alugbati greens.', 280.00, 25, 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?w=600'),
(4, 'nails specialist (girl)', 'Professional manicure, gel polish, and custom nail art performed by expert female nail technicians.', 450.00, 15, 'https://images.unsplash.com/photo-1604654894610-df63bc536371?w=600'),
(4, 'Rebond specialist (girl)', 'Premium hair rebonding and silk smoothing treatment by top female hair specialists for sleek, shiny hair.', 1999.00, 10, 'https://images.unsplash.com/photo-1560066984-138dadb4c035?w=600'),
(4, 'Make up', 'Professional full-face glam makeup application for events, photoshoots, and parties using high-end cosmetics.', 999.00, 20, 'https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?w=600');

