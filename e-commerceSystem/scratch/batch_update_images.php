<?php
require_once __DIR__ . '/../config/database.php';

$productsToUpdate = [
    'Wireless Bluetooth Headphones' => 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?q=80&w=1000&auto=format&fit=crop',
    'Cotton Casual T-Shirt'         => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?q=80&w=1000&auto=format&fit=crop',
    'Running Sneakers'              => 'https://images.unsplash.com/photo-1542219173-2df6cd32a51f?q=80&w=1000&auto=format&fit=crop',
    'Leather Crossbody Bag'         => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?q=80&w=1000&auto=format&fit=crop',
    'Minimalist Analog Watch'       => 'https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3?q=80&w=1000&auto=format&fit=crop',
    'Polarized Sunglasses'          => 'https://images.unsplash.com/photo-1511499767350-a1590fdbd57b?q=80&w=1000&auto=format&fit=crop'
];

$imgDir = __DIR__ . '/../assets/img/products/';

foreach ($productsToUpdate as $name => $url) {
    $filename = strtolower(str_replace(' ', '_', $name)) . '.png';
    $destination = $imgDir . $filename;
    
    // Download
    $ch = curl_init($url);
    $fp = fopen($destination, 'wb');
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $dlResult = curl_exec($ch);
    curl_close($ch);
    fclose($fp);
    
    if ($dlResult) {
        // Update DB
        $dbPath = '/e-commerceSystem/assets/img/products/' . $filename;
        $stmt = $pdo->prepare("UPDATE products SET image = ? WHERE name = ?");
        if ($stmt->execute([$dbPath, $name])) {
            echo "Success: Updated $name\n";
        } else {
            echo "Error: Failed to update DB for $name\n";
        }
    } else {
        echo "Error: Failed to download image for $name\n";
    }
}
