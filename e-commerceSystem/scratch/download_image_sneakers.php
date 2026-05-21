<?php
$url = 'https://unsplash.com/photos/1Us_GlM9j-M/download?force=true';
$destination = __DIR__ . '/../assets/img/products/running_sneakers.png';

$ch = curl_init($url);
$fp = fopen($destination, 'wb');
curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$result = curl_exec($ch);
curl_close($ch);
fclose($fp);

if ($result) {
    echo "Success: Image downloaded and replaced at $destination";
} else {
    echo "Error: Failed to download image.";
}
