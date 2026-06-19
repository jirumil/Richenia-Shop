<?php
/**
 * api/coupon_remove.php
 * Clears any coupon applied to the current checkout session.
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/cart.php';
require_once __DIR__ . '/../includes/pricing.php';

header('Content-Type: application/json');

unset($_SESSION['checkout_coupon']);

$subtotal = cart_subtotal();
$shipping = calculate_shipping($subtotal);
$total    = round($subtotal + $shipping, 2);

echo json_encode([
    'success'  => true,
    'message'  => 'Coupon removed.',
    'subtotal' => number_format($subtotal, 2),
    'discount' => '0.00',
    'shipping' => $shipping > 0 ? number_format($shipping, 2) : '0.00',
    'total'    => number_format($total, 2),
]);
