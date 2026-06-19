<?php
require_once __DIR__ . '/../includes/cart.php';
header('Content-Type: application/json');

$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$qty        = isset($_POST['qty']) ? max(1, (int)$_POST['qty']) : 1;

$product = $product_id > 0 ? Product::find($product_id) : null;

if (!$product) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Product not found.']);
    exit;
}

cart_init();
$already_in_cart = $_SESSION['cart'][$product_id] ?? 0;

if ($already_in_cart + $qty > (int)$product['stock']) {
    echo json_encode(['success' => false, 'message' => 'Only ' . (int)$product['stock'] . ' left in stock.'] + cart_json_response(true));
    exit;
}

cart_add($product_id, $qty);

echo json_encode(cart_json_response(true, 'Added to bag.'));
