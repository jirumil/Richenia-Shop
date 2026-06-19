<?php
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/../models/Product.php';

/**
 * Cart state lives entirely in $_SESSION['cart'] as
 *   [ product_id => quantity, ... ]
 * Product details are always re-read from the database, so the
 * cart never goes stale even if a price changes.
 */

function cart_init()
{
    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
}

function cart_add($product_id, $qty = 1)
{
    cart_init();
    $product_id = (int)$product_id;
    $qty = max(1, (int)$qty);

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $qty;
    } else {
        $_SESSION['cart'][$product_id] = $qty;
    }
}

function cart_remove($product_id)
{
    cart_init();
    unset($_SESSION['cart'][(int)$product_id]);
}

function cart_update_qty($product_id, $qty)
{
    cart_init();
    $product_id = (int)$product_id;
    $qty = (int)$qty;

    if ($qty <= 0) {
        unset($_SESSION['cart'][$product_id]);
    } else {
        $_SESSION['cart'][$product_id] = $qty;
    }
}

function cart_clear()
{
    $_SESSION['cart'] = [];
}

/** @return int Total number of items in the cart (sum of quantities). */
function cart_count()
{
    cart_init();
    $count = 0;
    foreach ($_SESSION['cart'] as $qty) {
        $count += $qty;
    }
    return $count;
}

/**
 * @return array List of [ 'product' => row, 'qty' => int, 'line_total' => float ]
 * Silently drops any cart entry whose product no longer exists.
 */
function cart_items()
{
    cart_init();
    $items = [];

    foreach ($_SESSION['cart'] as $product_id => $qty) {
        $product = Product::find($product_id);
        if (!$product) {
            continue;
        }
        $items[] = [
            'product'    => $product,
            'qty'        => $qty,
            'line_total' => (float)$product['price'] * $qty,
        ];
    }

    return $items;
}

/** @return float Sum of all line totals. */
function cart_subtotal()
{
    $subtotal = 0.0;
    foreach (cart_items() as $item) {
        $subtotal += $item['line_total'];
    }
    return $subtotal;
}

/**
 * Builds the standard JSON payload returned by every cart API endpoint.
 * Re-renders the cart-items partial server-side so the drawer markup
 * (PHP) and the JS that injects it never drift apart.
 */
function cart_json_response($success = true, $message = '')
{
    ob_start();
    include __DIR__ . '/cart-items-partial.php';
    $html = ob_get_clean();

    return [
        'success'  => $success,
        'message'  => $message,
        'count'    => cart_count(),
        'subtotal' => number_format(cart_subtotal(), 2),
        'html'     => $html,
    ];
}
