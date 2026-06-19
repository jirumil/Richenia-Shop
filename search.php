<?php
/**
 * search.php
 *
 * Backend for the live search bar and the category/price sidebar on
 * shop.php. Accepts any combination of:
 *   q          free-text search (matches name + description)
 *   category   exact category name, or 'All'
 *   min_price  lower price bound
 *   max_price  upper price bound
 *
 * Returns rendered product-card HTML (not JSON) so the front-end can
 * drop the response straight into #product-grid — exactly the same
 * contract api/products_filter.php already used for category tabs.
 */
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/models/Product.php';

$q         = isset($_GET['q']) ? trim($_GET['q']) : '';
$category  = isset($_GET['category']) ? trim($_GET['category']) : 'All';
$min_price = isset($_GET['min_price']) && $_GET['min_price'] !== '' ? (float)$_GET['min_price'] : null;
$max_price = isset($_GET['max_price']) && $_GET['max_price'] !== '' ? (float)$_GET['max_price'] : null;

$products = Product::filter($q, $category, $min_price, $max_price);

header('Content-Type: text/html; charset=utf-8');

if (empty($products)) {
    echo '<p class="empty-state">No pieces match those filters — try widening your search.</p>';
    exit;
}

foreach ($products as $product) {
    include __DIR__ . '/includes/product-card.php';
}
