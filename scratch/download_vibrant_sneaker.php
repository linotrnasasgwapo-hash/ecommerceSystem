<?php
// Attempt to download a vibrant red running shoe from Unsplash
$url = 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=1000&q=80';
$destination = __DIR__ . '/../assets/img/products/running_sneakers.png';

$ch = curl_init($url);
$fp = fopen($destination, 'wb');
curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
fclose($fp);

if ($result && $httpCode == 200) {
    echo "Success: Image downloaded (Size: " . filesize($destination) . " bytes)\n";
} else {
    echo "Error: Failed to download image. HTTP Code: $httpCode\n";
}
