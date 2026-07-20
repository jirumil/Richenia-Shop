<?php
/**
 * admin_products_delete.php
 * Past orders keep a product_name/price snapshot in order_items, so
 * deleting a product never corrupts historical order history.
 */
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/models/Product.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf($_POST['csrf_token'] ?? '')) {
    flash_set('error', 'Invalid request. Please try again.');
    header('Location: ' . BASE_URL . 'admin.php?tab=products');
    exit;
}

$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$product    = $product_id > 0 ? Product::find($product_id) : null;

if ($product) {
    Product::delete($product_id);
    flash_set('success', 'Product "' . $product['name'] . '" deleted.');
} else {
    flash_set('error', 'That product no longer exists.');
}

header('Location: ' . BASE_URL . 'admin.php?tab=products');
exit;
