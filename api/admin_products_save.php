<?php
/**
 * admin_products_save.php
 * Handles both "Add Product" and "Update Product" — if product_id is
 * present and non-zero we update, otherwise we insert.
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

$name        = trim($_POST['name'] ?? '');
$category    = trim($_POST['category'] ?? '');
$price       = $_POST['price'] ?? '';
$stock       = $_POST['stock'] ?? '';
$image_url   = trim($_POST['image_url'] ?? '');
$description = trim($_POST['description'] ?? '');
$is_featured = isset($_POST['is_featured']) ? 1 : 0;

$errors = [];
if ($name === '') $errors[] = 'Name is required.';
if ($category === '') $errors[] = 'Category is required.';
if (!is_numeric($price) || (float)$price < 0) $errors[] = 'Price must be a positive number.';
if (!is_numeric($stock) || (int)$stock < 0) $errors[] = 'Stock must be a non-negative whole number.';
if ($image_url === '') $errors[] = 'Image URL is required.';

if (!empty($errors)) {
    flash_set('error', implode(' ', $errors));
    header('Location: ' . BASE_URL . 'admin.php?tab=products');
    exit;
}

$data = [
    'name'        => $name,
    'category'    => $category,
    'price'       => (float)$price,
    'stock'       => (int)$stock,
    'image_url'   => $image_url,
    'description' => $description,
    'is_featured' => $is_featured,
];

if ($product_id > 0 && Product::find($product_id)) {
    Product::update($product_id, $data);
    flash_set('success', 'Product "' . $name . '" updated.');
} else {
    Product::create($data);
    flash_set('success', 'Product "' . $name . '" added to the catalog.');
}

header('Location: ' . BASE_URL . 'admin.php?tab=products');
exit;
