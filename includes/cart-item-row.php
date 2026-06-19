<?php
/** @var array $item ['product' => row, 'qty' => int, 'line_total' => float] */
$p = $item['product'];
?>
<div class="cart-item" data-id="<?php echo (int)$p['id']; ?>">
  <div class="cart-item-thumb">
    <img src="<?php echo htmlspecialchars($p['image_url']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
  </div>
  <div class="cart-item-info">
    <p class="cart-item-category"><?php echo htmlspecialchars($p['category']); ?></p>
    <p class="cart-item-name"><?php echo htmlspecialchars($p['name']); ?></p>
    <div class="cart-item-row-bottom">
      <div class="cart-item-qty">
        <button class="qty-btn" data-action="decrease" data-id="<?php echo (int)$p['id']; ?>" type="button" aria-label="Decrease quantity">&minus;</button>
        <span class="qty-value"><?php echo (int)$item['qty']; ?></span>
        <button class="qty-btn" data-action="increase" data-id="<?php echo (int)$p['id']; ?>" type="button" aria-label="Increase quantity">+</button>
      </div>
      <p class="cart-item-price">$<?php echo number_format($item['line_total'], 2); ?></p>
    </div>
  </div>
  <button class="cart-item-remove" data-id="<?php echo (int)$p['id']; ?>" type="button" aria-label="Remove <?php echo htmlspecialchars($p['name']); ?> from bag">&times;</button>
</div>
