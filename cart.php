<?php
$page_title = 'Your Bag';
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/pricing.php';
require_once __DIR__ . '/models/Product.php';

$items    = cart_items();
$subtotal = cart_subtotal();
$shipping = calculate_shipping($subtotal);
$total    = $subtotal + $shipping;
?>

<!-- CART PAGE -->
<div class="cart-page-hero">
  <p class="eyebrow">Richenia — Your Bag</p>
  <h1>Shopping Bag<?php if (!empty($items)) echo ' <span style="color:var(--color-ink-soft);font-weight:300;">(' . count($items) . ')</span>'; ?></h1>
</div>

<?php if (empty($items)): ?>

  <div class="cart-empty-page">
    <p class="eyebrow">Nothing here yet</p>
    <h2>Your bag is empty.</h2>
    <p>Discover quiet luxury, built to last — explore the full collection.</p>
    <a href="<?php echo BASE_URL; ?>shop.php" class="btn-primary">Explore the Shop</a>
  </div>

<?php else: ?>

<div class="cart-page-layout">

  <!-- Item table -->
  <div>
    <table class="cart-table" id="cart-page-table">
      <thead class="cart-table-head">
        <tr>
          <th style="width:50%">Product</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Total</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $item):
          $p = $item['product']; ?>
        <tr class="cart-table-row" data-id="<?php echo (int)$p['id']; ?>">
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
          <td>
            <div class="cart-table-qty">
              <button class="qty-btn" data-action="decrease" data-id="<?php echo (int)$p['id']; ?>" type="button" aria-label="Decrease">−</button>
              <span class="qty-value"><?php echo (int)$item['qty']; ?></span>
              <button class="qty-btn" data-action="increase" data-id="<?php echo (int)$p['id']; ?>" type="button" aria-label="Increase">+</button>
            </div>
          </td>
          <td class="cart-table-total">$<?php echo number_format($item['line_total'], 2); ?></td>
          <td>
            <button class="cart-remove-btn cart-item-remove" data-id="<?php echo (int)$p['id']; ?>" type="button" aria-label="Remove">×</button>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div style="margin-top:2rem;display:flex;justify-content:space-between;align-items:center;padding-top:1rem;border-top:1px solid var(--color-line);">
      <a href="<?php echo BASE_URL; ?>shop.php" class="view-all">← Continue Shopping</a>
    </div>
  </div>

  <!-- Summary sidebar -->
  <div class="cart-summary">
    <p class="cart-summary-title">Order Summary</p>

    <div class="cart-summary-row">
      <span>Subtotal</span>
      <span id="page-subtotal">$<?php echo number_format($subtotal, 2); ?></span>
    </div>
    <div class="cart-summary-row">
      <span>Shipping</span>
      <span id="page-shipping"><?php echo $shipping === 0 && $subtotal > 0 ? 'Complimentary' : ($shipping > 0 ? '$' . number_format($shipping, 2) : '—'); ?></span>
    </div>

    <?php if ($subtotal < 300 && $subtotal > 0): ?>
    <p style="font-size:0.76rem;color:var(--color-accent);margin-top:0.5rem;">
      Add $<?php echo number_format(300 - $subtotal, 2); ?> more for complimentary shipping.
    </p>
    <?php endif; ?>

    <div class="cart-summary-total">
      <span>Total</span>
      <span id="page-total">$<?php echo number_format($total, 2); ?></span>
    </div>

    <a href="<?php echo BASE_URL; ?>checkout.php" class="btn-checkout-full">
      Proceed to Checkout
    </a>
    <a href="<?php echo BASE_URL; ?>shop.php" class="cart-continue">Continue Shopping</a>

    <div style="margin-top:1.5rem;padding-top:1.5rem;border-top:1px solid var(--color-line);">
      <p style="font-size:0.74rem;color:var(--color-ink-soft);line-height:1.6;">
        ✦ Complimentary shipping on orders over $300<br>
        ✦ Returns accepted within 14 days<br>
        ✦ Secure checkout
      </p>
    </div>
  </div>

</div>

<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
