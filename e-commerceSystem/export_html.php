<?php
/**
 * Static HTML Exporter
 * This script fetches the local PHP pages and saves them as static HTML files.
 * Also copies all assets (CSS, JS, images) for a complete deployment.
 */
$baseUrl = 'http://localhost/e-commerceSystem/';
$exportDir = __DIR__ . '/static_export/';

// Ensure export directories exist
if (!is_dir($exportDir)) mkdir($exportDir, 0777, true);
if (!is_dir($exportDir . 'pages/')) mkdir($exportDir . 'pages/', 0777, true);

// Copy all assets (CSS, JS, images)
function copyDir($src, $dst) {
    if (!is_dir($dst)) mkdir($dst, 0777, true);
    $dir = opendir($src);
    while (($file = readdir($dir)) !== false) {
        if ($file === '.' || $file === '..') continue;
        $srcPath = $src . '/' . $file;
        $dstPath = $dst . '/' . $file;
        if (is_dir($srcPath)) {
            copyDir($srcPath, $dstPath);
        } else {
            copy($srcPath, $dstPath);
        }
    }
    closedir($dir);
}

echo "Copying assets...\n";
copyDir(__DIR__ . '/assets', $exportDir . 'assets');
echo "Assets copied.\n";

$pagesToExport = [
    'index.php' => 'index.html',
    'pages/shop.php' => 'pages/shop.html',
    'pages/about.php' => 'pages/about.html',
    'pages/contact.php' => 'pages/contact.html',
    'pages/login.php' => 'pages/login.html',
    'pages/register.php' => 'pages/register.html'
];

foreach ($pagesToExport as $phpFile => $htmlFile) {
    echo "Exporting $phpFile...\n";
    $html = file_get_contents($baseUrl . $phpFile);
    
    if ($html !== false) {
        // Rewrite navigation links
        $html = str_replace('href="/e-commerceSystem/pages/shop.php"', 'href="pages/shop.html"', $html);
        $html = str_replace('href="/e-commerceSystem/pages/about.php"', 'href="pages/about.html"', $html);
        $html = str_replace('href="/e-commerceSystem/pages/contact.php"', 'href="pages/contact.html"', $html);
        $html = str_replace('href="/e-commerceSystem/pages/login.php"', 'href="pages/login.html"', $html);
        $html = str_replace('href="/e-commerceSystem/pages/register.php"', 'href="pages/register.html"', $html);
        $html = str_replace('href="/e-commerceSystem/"', 'href="index.html"', $html);

        // Rewrite assets links
        $html = str_replace('/e-commerceSystem/assets/', 'assets/', $html);

        // Fix recursive paths from subfolders
        if (strpos($htmlFile, 'pages/') === 0) {
            $html = str_replace('href="pages/', 'href="', $html);
            $html = str_replace('href="index.html"', 'href="../index.html"', $html);
            $html = str_replace('src="assets/', 'src="../assets/', $html);
            $html = str_replace('href="assets/', 'href="../assets/', $html);
            $html = str_replace("url('assets/", "url('../assets/", $html);
        }

        // Remove Quick View AJAX calls since there is no backend
        $html = preg_replace('/onclick="quickView\(\d+\)"/', 'onclick="alert(\'Quick View requires PHP backend.\')"', $html);
        $html = preg_replace('/onclick="this.parentElement.submit\(\)"/', 'onclick="alert(\'Backend features disabled in static view.\'); return false;"', $html);
        $html = str_replace('type="submit"', 'type="button" onclick="alert(\'Backend features disabled in static view.\')"', $html);

        file_put_contents($exportDir . $htmlFile, $html);
        echo "Saved to $htmlFile\n";
    } else {
        echo "Failed to export $phpFile\n";
    }
}
echo "Export complete! Your static HTML files are in the 'static_export' folder.\n";
