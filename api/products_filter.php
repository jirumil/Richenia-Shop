<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../models/Product.php';

$category = isset($_GET['category']) ? trim($_GET['category']) : 'All';

if ($category === '' || strcasecmp($category, 'All') === 0) {
    $products = Product::all();
} else {
    $products = Product::byCategory($category);
}

header('Content-Type: text/html; charset=utf-8');

if (empty($products)) {
    echo '<p class="empty-state">No pieces found in this collection yet.</p>';
    exit;
}

foreach ($products as $product) {
    include __DIR__ . '/../includes/product-card.php';
}
