<?php
/**
 * api/coupon_apply.php
 * Validates a coupon code server-side and stores it in the session so
 * checkout.php's final order-placement step recalculates the discount
 * from the database again rather than trusting anything sent by the browser.
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/cart.php';
require_once __DIR__ . '/../includes/pricing.php';
require_once __DIR__ . '/../models/Coupon.php';

header('Content-Type: application/json');

if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Please sign in to apply a coupon.']);
    exit;
}

$code     = trim($_POST['code'] ?? '');
$subtotal = cart_subtotal();

if ($subtotal <= 0) {
    echo json_encode(['success' => false, 'message' => 'Your bag is empty.']);
    exit;
}

$result = Coupon::validate($code);

if (!$result['valid']) {
    unset($_SESSION['checkout_coupon']);
    echo json_encode(['success' => false, 'message' => $result['message']]);
    exit;
}

$coupon   = $result['coupon'];
$discount = Coupon::calculateDiscount($coupon, $subtotal);
$shipping = calculate_shipping($subtotal);
$total    = round($subtotal - $discount + $shipping, 2);

$_SESSION['checkout_coupon'] = $coupon['code'];

echo json_encode([
    'success'  => true,
    'message'  => 'Coupon "' . $coupon['code'] . '" applied.',
    'code'     => $coupon['code'],
    'type'     => $coupon['type'],
    'value'    => (float)$coupon['value'],
    'subtotal' => number_format($subtotal, 2),
    'discount' => number_format($discount, 2),
    'shipping' => $shipping > 0 ? number_format($shipping, 2) : '0.00',
    'total'    => number_format($total, 2),
]);
