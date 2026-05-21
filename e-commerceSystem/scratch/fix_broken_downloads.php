<?php
$toFix = [
    'portable_power_bank.png' => 'https://unsplash.com/photos/7-EE-gckz7w/download?force=true',
    'polarized_sunglasses.png' => 'https://unsplash.com/photos/J2hMgJvVmvo/download?force=true',
    'running_sneakers.png' => 'https://unsplash.com/photos/GWTnBqB7HdU/download?force=true'
];

$imgDir = __DIR__ . '/../assets/img/products/';

foreach ($toFix as $filename => $url) {
    $destination = $imgDir . $filename;
    echo "Downloading $filename...\n";
    
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
        $size = filesize($destination);
        echo "Success: $filename saved ($size bytes)\n";
    } else {
        echo "Error: Failed to download $filename (HTTP $httpCode)\n";
    }
}
