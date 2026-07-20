<?php
/**
 * api/index.php — front controller.
 *
 * Every non-api, non-asset request gets rewritten here by vercel.json.
 * This file maps a clean URL path to the matching page file that already
 * lives at the project root (index.php, shop.php, cart.php, ...) and
 * includes it directly. The page files themselves are NOT moved and NOT
 * modified — their existing __DIR__-relative requires (config/app.php,
 * includes/header.php, models/...) keep working exactly as before,
 * because __DIR__ resolves to each file's real location on disk
 * regardless of who includes it.
 */

$projectRoot = dirname(__DIR__);

// Whitelisted routes only — deliberately not a dynamic file_exists() lookup,
// to avoid any possibility of path traversal via the request URI.
$routes = [
    ''                => 'index.php',
    'shop'            => 'shop.php',
    'cart'            => 'cart.php',
    'checkout'        => 'checkout.php',
    'login'           => 'login.php',
    'register'        => 'register.php',
    'logout'          => 'logout.php',
    'orders'          => 'orders.php',
    'admin'           => 'admin.php',
    'blog'            => 'blog.php',
    'blog-single'     => 'blog-single.php',
    'contact'         => 'contact.php',
    'our-story'       => 'our-story.php',
    'privacy-policy'  => 'privacy-policy.php',
    'receipt'         => 'receipt.php',
    'search'          => 'search.php',
];

$path = trim((string) parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$page = $routes[$path] ?? null;

if ($page !== null) {
    require $projectRoot . '/' . $page;
} else {
    http_response_code(404);
    echo '404 — page not found';
}