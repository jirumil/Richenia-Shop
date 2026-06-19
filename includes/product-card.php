<?php
/** @var array $product */
$in_stock = (int)($product['stock'] ?? 0) > 0;
?>
<article class="product-card<?php echo $in_stock ? '' : ' is-sold-out'; ?>" data-category="<?php echo htmlspecialchars($product['category']); ?>">
  <div class="product-card-image">
    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" loading="lazy">
    <?php if ($in_stock): ?>
      <button class="quick-add" data-id="<?php echo (int)$product['id']; ?>" type="button">
        Add to Bag
      </button>
    <?php else: ?>
      <span class="sold-out-badge">Sold Out</span>
    <?php endif; ?>
  </div>
  <div class="product-card-info">
    <p class="product-category"><?php echo htmlspecialchars($product['category']); ?></p>
    <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
    <p class="product-price">$<?php echo number_format($product['price'], 2); ?></p>
  </div>
</article>
