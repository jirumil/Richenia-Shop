<?php
$page_title = 'My Orders';
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/models/Order.php';

require_login();

$me     = current_user();
$orders = Order::allForUser($me['id']);

require_once __DIR__ . '/includes/header.php';
?>

<div class="cart-page-hero">
  <p class="eyebrow">Richenia — Account</p>
  <h1>My Orders</h1>
</div>

<?php if (empty($orders)): ?>

  <div class="cart-empty-page">
    <p class="eyebrow">Nothing here yet</p>
    <h2>You haven't placed an order.</h2>
    <p>Discover quiet luxury, built to last — explore the full collection.</p>
    <a href="<?php echo BASE_URL; ?>shop.php" class="btn-primary">Explore the Shop</a>
  </div>

<?php else: ?>

  <div class="orders-list">
    <table class="cart-table orders-table">
      <thead class="cart-table-head">
        <tr>
          <th>Order</th>
          <th>Date</th>
          <th>Discount</th>
          <th>Total</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($orders as $order): ?>
        <tr class="cart-table-row">
          <td>#<?php echo (int)$order['id']; ?></td>
          <td><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
          <td><?php echo $order['discount_applied'] > 0 ? '−$' . number_format($order['discount_applied'], 2) : '—'; ?></td>
          <td class="cart-table-total">$<?php echo number_format($order['total_price'], 2); ?></td>
          <td><a href="<?php echo BASE_URL; ?>receipt.php?order_id=<?php echo (int)$order['id']; ?>" class="view-all">View Receipt →</a></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
