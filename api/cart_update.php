<?php
require_once __DIR__ . '/../includes/cart.php';
header('Content-Type: application/json');

$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$qty        = isset($_POST['qty']) ? (int)$_POST['qty'] : 0;

$product = Product::find($product_id);
if ($product && $qty > (int)$product['stock']) {
    $qty = (int)$product['stock'];
}

cart_update_qty($product_id, $qty);

echo json_encode(cart_json_response(true, 'Bag updated.'));
