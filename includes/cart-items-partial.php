<?php
// Expects cart.php to already be loaded. Self-contained: pulls its own data
// so it can be reused both for the first page load and for AJAX responses.
$items = cart_items();
?>
<?php if (empty($items)): ?>
  <p class="cart-empty">Your bag is currently empty.</p>
<?php else: ?>
  <?php foreach ($items as $item): ?>
    <?php include __DIR__ . '/cart-item-row.php'; ?>
  <?php endforeach; ?>
<?php endif; ?>
