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

// Serve files from public folder directly if they exist
$publicPath = __DIR__ . '/public/' . $uri;
if ($uri !== '' && is_file($publicPath)) {
    return false;
}

// For all other routes, handle via MVC router
$_GET['url'] = $uri;

// Change to public directory
chdir(__DIR__ . '/public');

// Include index.php
require __DIR__ . '/public/index.php';
