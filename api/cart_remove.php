<?php
require_once __DIR__ . '/../includes/cart.php';
header('Content-Type: application/json');

$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;

cart_remove($product_id);

echo json_encode(cart_json_response(true, 'Removed from bag.'));
