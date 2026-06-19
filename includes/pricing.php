<?php
/**
 * includes/pricing.php
 * Shared pricing rules used by cart.php, checkout.php, and the coupon
 * AJAX endpoint, so the shipping threshold only ever lives in one place.
 */

/** @return float Shipping cost for a given subtotal ($0 once $300+, $18 flat otherwise, free if cart is empty). */
function calculate_shipping($subtotal)
{
    $subtotal = (float)$subtotal;
    if ($subtotal <= 0) {
        return 0.0;
    }
    return $subtotal >= 300 ? 0.0 : 18.0;
}
