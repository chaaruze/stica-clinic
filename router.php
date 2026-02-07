<?php
/**
 * Router for PHP Built-in Server
 * PHP's built-in server doesn't support .htaccess, so we need this router script.
 * 
 * Usage: php -S localhost:8080 router.php
 */

// Get the requested URL path (remove leading slash)
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = ltrim($uri, '/');

// Serve static files from public folder if they exist
$publicPath = __DIR__ . '/public/' . $uri;
if ($uri !== '' && is_file($publicPath)) {
    // Get the file extension
    $ext = strtolower(pathinfo($publicPath, PATHINFO_EXTENSION));

    // Set appropriate Content-Type header
    $mimeTypes = [
        'css' => 'text/css',
        'js' => 'application/javascript',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'ico' => 'image/x-icon',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf' => 'font/ttf',
        'eot' => 'application/vnd.ms-fontobject',
        'json' => 'application/json',
        'pdf' => 'application/pdf',
        'webp' => 'image/webp'
    ];

    if (isset($mimeTypes[$ext])) {
        header('Content-Type: ' . $mimeTypes[$ext]);
    }

    // Output the file contents
    readfile($publicPath);
    return;
}

// For all other routes, handle via MVC router
$_GET['url'] = $uri;

// Change to public directory
chdir(__DIR__ . '/public');

// Include index.php
require __DIR__ . '/public/index.php';
