<?php
$page_title = 'Checkout';
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/cart.php';
require_once __DIR__ . '/includes/pricing.php';
require_once __DIR__ . '/models/Coupon.php';
require_once __DIR__ . '/models/Order.php';

require_login();

$items = cart_items();
if (empty($items)) {
    flash_set('info', 'Your bag is empty — add something before checking out.');
    header('Location: ' . BASE_URL . 'shop.php');
    exit;
}

$subtotal = cart_subtotal();

/**
 * Re-validate any session-stored coupon against the *current* subtotal —
 * handles the case where the cart changed after a coupon was applied.
 */
$applied_coupon = null;
$discount = 0.0;
if (!empty($_SESSION['checkout_coupon'])) {
    $result = Coupon::validate($_SESSION['checkout_coupon']);
    if ($result['valid']) {
        $applied_coupon = $result['coupon'];
        $discount = Coupon::calculateDiscount($applied_coupon, $subtotal);
    } else {
        unset($_SESSION['checkout_coupon']);
    }
}

$shipping = calculate_shipping($subtotal);
$total    = round($subtotal - $discount + $shipping, 2);
$place_order_error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $place_order_error = 'Your session expired — please try again.';
    } else {
        // Recompute everything fresh at the moment of submission. The
        // discount amount is NEVER taken from the form — only from the
        // coupon code stored server-side in the session.
        $items    = cart_items();
        $subtotal = cart_subtotal();

        if (empty($items)) {
            flash_set('info', 'Your bag is empty.');
            header('Location: ' . BASE_URL . 'shop.php');
            exit;
        }

        $coupon_code = null;
        $discount    = 0.0;
        if (!empty($_SESSION['checkout_coupon'])) {
            $result = Coupon::validate($_SESSION['checkout_coupon']);
            if ($result['valid']) {
                $coupon_code = $result['coupon']['code'];
                $discount    = Coupon::calculateDiscount($result['coupon'], $subtotal);
            }
        }

        $shipping = calculate_shipping($subtotal);
        $total    = round($subtotal - $discount + $shipping, 2);

        try {
            $user    = current_user();
            $orderId = Order::create($user['id'], $items, $subtotal, $discount, $total, $coupon_code);

            if ($coupon_code) {
                Coupon::recordUse($coupon_code);
            }

            cart_clear();
            unset($_SESSION['checkout_coupon']);

            header('Location: ' . BASE_URL . 'receipt.php?order_id=' . $orderId);
            exit;
        } catch (Exception $e) {
            $place_order_error = $e->getMessage();
            // Stock may have changed mid-checkout — refresh everything shown below.
            $items    = cart_items();
            $subtotal = cart_subtotal();
            $shipping = calculate_shipping($subtotal);
            $coupon_row = $coupon_code ? Coupon::findByCode($coupon_code) : null;
            $discount = $coupon_row ? Coupon::calculateDiscount($coupon_row, $subtotal) : 0.0;
            $total    = round($subtotal - $discount + $shipping, 2);
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="cart-page-hero">
  <p class="eyebrow">Richenia — Checkout</p>
  <h1>Review &amp; Confirm</h1>
</div>

<?php if ($place_order_error): ?>
  <div class="form-error form-error-banner checkout-page-error"><?php echo htmlspecialchars($place_order_error); ?></div>
<?php endif; ?>

<div class="cart-page-layout checkout-layout">

  <div>
    <table class="cart-table">
      <thead class="cart-table-head">
        <tr>
          <th style="width:50%">Product</th>
          <th>Price</th>
          <th>Qty</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $item): $p = $item['product']; ?>
        <tr class="cart-table-row">
          <td>
            <div class="cart-table-product">
              <div class="cart-table-thumb">
                <img src="<?php echo htmlspecialchars($p['image_url']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
              </div>
              <div class="cart-table-meta">
                <p class="product-category"><?php echo htmlspecialchars($p['category']); ?></p>
                <p class="product-name"><?php echo htmlspecialchars($p['name']); ?></p>
              </div>
            </div>
          </td>
          <td class="cart-table-price">$<?php echo number_format((float)$p['price'], 2); ?></td>
          <td><?php echo (int)$item['qty']; ?></td>
          <td class="cart-table-total">$<?php echo number_format($item['line_total'], 2); ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <a href="<?php echo BASE_URL; ?>cart.php" class="view-all">&larr; Edit Bag</a>
  </div>

  <div class="cart-summary checkout-summary">
    <p class="cart-summary-title">Order Summary</p>

    <!-- Coupon code -->
    <div class="coupon-box">
      <label for="coupon-code" class="coupon-label">Coupon Code</label>
      <div class="coupon-input-row">
        <input type="text" id="coupon-code" placeholder="e.g. WELCOME10"
               value="<?php echo $applied_coupon ? htmlspecialchars($applied_coupon['code']) : ''; ?>"
               <?php echo $applied_coupon ? 'readonly' : ''; ?>>
        <button type="button" id="coupon-apply-btn" class="btn-coupon" <?php echo $applied_coupon ? 'style="display:none;"' : ''; ?>>Apply</button>
        <button type="button" id="coupon-remove-btn" class="btn-coupon btn-coupon-remove" <?php echo $applied_coupon ? '' : 'style="display:none;"'; ?>>Remove</button>
      </div>
      <p id="coupon-feedback" class="coupon-feedback <?php echo $applied_coupon ? 'is-success' : ''; ?>">
        <?php echo $applied_coupon ? 'Coupon "' . htmlspecialchars($applied_coupon['code']) . '" applied.' : ''; ?>
      </p>
    </div>

    <div class="cart-summary-row">
      <span>Subtotal</span>
      <span id="checkout-subtotal">$<?php echo number_format($subtotal, 2); ?></span>
    </div>
    <div class="cart-summary-row checkout-discount-row" id="checkout-discount-row" style="<?php echo $discount > 0 ? '' : 'display:none;'; ?>">
      <span>Discount</span>
      <span id="checkout-discount">&minus;$<?php echo number_format($discount, 2); ?></span>
    </div>
    <div class="cart-summary-row">
      <span>Shipping</span>
      <span id="checkout-shipping"><?php echo $shipping > 0 ? '$' . number_format($shipping, 2) : 'Complimentary'; ?></span>
    </div>

    <div class="cart-summary-total">
      <span>Total</span>
      <span id="checkout-total">$<?php echo number_format($total, 2); ?></span>
    </div>

    <form method="post" id="place-order-form">
      <?php echo csrf_field(); ?>
      <button type="submit" name="place_order" value="1" class="btn-checkout-full">Place Order</button>
    </form>

    <p class="checkout-disclaimer">This is a local development demo — no real payment is processed.</p>
  </div>

</div>

<script>
  window.RICHENIA_SUBTOTAL = <?php echo json_encode(round($subtotal, 2)); ?>;
</script>

<?php
$extra_js = ['assets/js/checkout.js'];
require_once __DIR__ . '/includes/footer.php';
?>
