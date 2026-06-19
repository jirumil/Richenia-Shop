<?php
require_once __DIR__ . '/../includes/cart.php';
header('Content-Type: application/json');

echo json_encode(cart_json_response(true));
