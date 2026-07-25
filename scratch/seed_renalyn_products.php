<?php
require_once __DIR__ . '/../config/database.php';

try {
    echo "Starting database migration for Renalyn's Favorite Online Shop...\n";

    // Disable foreign key checks temporarily
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");

    // Truncate existing cart, wishlist, order items, products, categories
    $pdo->exec("TRUNCATE TABLE `cart`;");
    $pdo->exec("TRUNCATE TABLE `wishlist`;");
    $pdo->exec("TRUNCATE TABLE `order_items`;");
    $pdo->exec("TRUNCATE TABLE `products`;");
    $pdo->exec("TRUNCATE TABLE `categories`;");

    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");

    // Insert new categories
    $categories = [
        1 => ['name' => 'Sweets & Chocolates', 'description' => 'Delicious chocolates, candies, and sweet treats'],
        2 => ['name' => 'Flowers & Gifts', 'description' => 'Fresh flower bouquets and romantic gift arrangements'],
        3 => ['name' => 'Food & Delicacies', 'description' => 'Delicious home-cooked Filipino meals and traditional dishes'],
        4 => ['name' => 'Beauty & Salon Services', 'description' => 'Expert pampering, nail care, hair rebonding, and makeup services']
    ];

    $stmtCat = $pdo->prepare("INSERT INTO `categories` (`id`, `name`, `description`) VALUES (?, ?, ?)");
    foreach ($categories as $id => $cat) {
        $stmtCat->execute([$id, $cat['name'], $cat['description']]);
    }
    echo "Categories seeded successfully.\n";

    // Insert 7 new products from Renalyn's note
    $products = [
        [
            'category_id' => 1,
            'name' => 'Dark Chocolate Kisses',
            'description' => 'Indulgent rich dark chocolate kisses, velvety smooth and perfect for satisfying sweet cravings.',
            'price' => 199.00,
            'stock' => 50,
            'image' => '/e-commerceSystem/assets/img/products/kisses_dark_chocolate.jpg'
        ],
        [
            'category_id' => 2,
            'name' => 'Baby Pink Flowers',
            'description' => 'A charming bouquet of fresh, delicate baby pink flowers, beautifully arranged for special occasions.',
            'price' => 899.00,
            'stock' => 30,
            'image' => '/e-commerceSystem/assets/img/products/flowers_baby_pink.jpg'
        ],
        [
            'category_id' => 3,
            'name' => 'Native Chicken Tinola',
            'description' => 'Authentic Filipino native chicken tinola soup simmered with ginger, green papaya, lemongrass, and chili leaves.',
            'price' => 350.00,
            'stock' => 25,
            'image' => '/e-commerceSystem/assets/img/products/tinola_native_chicken.jpg'
        ],
        [
            'category_id' => 3,
            'name' => 'Fried Fish with Veggie Soup',
            'description' => 'Golden crispy fried fish served with wholesome vegetable soup featuring fresh squash (kalabasa) and alugbati greens.',
            'price' => 280.00,
            'stock' => 25,
            'image' => '/e-commerceSystem/assets/img/products/fish_preto_utan.jpg'
        ],
        [
            'category_id' => 4,
            'name' => 'Nail Specialist Service',
            'description' => 'Professional manicure, gel polish, and custom nail art performed by expert female nail technicians.',
            'price' => 450.00,
            'stock' => 15,
            'image' => '/e-commerceSystem/assets/img/products/nails_specialist.jpg'
        ],
        [
            'category_id' => 4,
            'name' => 'Hair Rebonding Service',
            'description' => 'Premium hair rebonding and silk smoothing treatment by top female hair specialists for sleek, shiny hair.',
            'price' => 1999.00,
            'stock' => 10,
            'image' => '/e-commerceSystem/assets/img/products/rebond_specialist.jpg'
        ],
        [
            'category_id' => 4,
            'name' => 'Professional Makeup',
            'description' => 'Professional full-face glam makeup application for events, photoshoots, and parties using high-end cosmetics.',
            'price' => 999.00,
            'stock' => 20,
            'image' => '/e-commerceSystem/assets/img/products/makeup_service.jpg'
        ]
    ];

    $stmtProd = $pdo->prepare("INSERT INTO `products` (`category_id`, `name`, `description`, `price`, `stock`, `image`) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($products as $p) {
        $stmtProd->execute([$p['category_id'], $p['name'], $p['description'], $p['price'], $p['stock'], $p['image']]);
    }

    echo "7 products seeded successfully into Railway DB!\n";

} catch (Exception $e) {
    echo "Migration Error: " . $e->getMessage() . "\n";
}
