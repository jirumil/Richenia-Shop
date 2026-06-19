<?php
/**
 * Expects includes/cart.php to already be loaded (header.php does this).
 * Rendered once on every page so the drawer is always available to slide
 * open, regardless of which page the visitor is on.
 */
?>
<div id="cart-overlay" class="cart-overlay"></div>

<aside id="cart-drawer" class="cart-drawer" aria-hidden="true" aria-label="Shopping bag">
  <div class="cart-drawer-header">
    <p class="cart-eyebrow">Your Bag</p>
    <button id="cart-close" class="cart-close" type="button" aria-label="Close cart">&times;</button>
  </div>

  <div id="cart-items" class="cart-items">
    <?php include __DIR__ . '/cart-items-partial.php'; ?>
  </div>

  <div class="cart-drawer-footer">
    <div class="cart-subtotal-row">
      <span>Subtotal</span>
      <span id="cart-subtotal">$<?php echo number_format(cart_subtotal(), 2); ?></span>
    </div>
    <p class="cart-shipping-note">Shipping &amp; taxes calculated at checkout.</p>
    <a class="btn-checkout" href="<?php echo BASE_URL; ?>checkout.php">Proceed to Checkout</a>
  </div>
</aside>
