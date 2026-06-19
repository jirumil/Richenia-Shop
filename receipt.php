<?php
$page_title = 'Receipt';
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/models/Order.php';
require_once __DIR__ . '/models/User.php';

require_login();

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
$order    = $order_id > 0 ? Order::find($order_id) : false;

if (!$order) {
    flash_set('error', 'That order could not be found.');
    header('Location: ' . BASE_URL . (is_admin() ? 'admin.php' : 'orders.php'));
    exit;
}

$me = current_user();
if ((int)$order['user_id'] !== (int)$me['id'] && !is_admin()) {
    flash_set('error', 'You do not have permission to view that receipt.');
    header('Location: ' . BASE_URL . 'orders.php');
    exit;
}

$items = Order::items($order_id);
$buyer = User::findById($order['user_id']);

$subtotal = (float)$order['subtotal'];
$discount = (float)$order['discount_applied'];
$total    = (float)$order['total_price'];
$shipping = max(0, round($total - $subtotal + $discount, 2));

require_once __DIR__ . '/includes/header.php';
?>

<section class="receipt-page">
  <div class="receipt-card" id="receipt-printable">

    <div class="receipt-head">
      <div>
        <p class="logo receipt-logo">RICHENIA</p>
        <p class="receipt-tagline">Considered menswear, made in small runs.</p>
      </div>
      <div class="receipt-meta">
        <p class="receipt-order-no">Order #<?php echo (int)$order['id']; ?></p>
        <p class="receipt-date"><?php echo date('F j, Y \a\t g:i A', strtotime($order['created_at'])); ?></p>
        <p class="receipt-status">Status: <?php echo htmlspecialchars(ucfirst($order['status'])); ?></p>
      </div>
    </div>

    <?php if (is_admin() && (int)$order['user_id'] !== (int)$me['id']): ?>
      <div class="receipt-admin-note">Viewing as admin — billed to <?php echo htmlspecialchars($buyer['username'] ?? 'unknown'); ?> (<?php echo htmlspecialchars($buyer['email'] ?? ''); ?>)</div>
    <?php else: ?>
      <div class="receipt-billed-to">
        <p class="receipt-label">Billed To</p>
        <p><?php echo htmlspecialchars($buyer['username'] ?? ''); ?></p>
        <p class="receipt-email"><?php echo htmlspecialchars($buyer['email'] ?? ''); ?></p>
      </div>
    <?php endif; ?>

    <table class="receipt-table">
      <thead>
        <tr>
          <th>Item</th>
          <th>Qty</th>
          <th>Unit Price</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $item): ?>
          <tr>
            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
            <td><?php echo (int)$item['quantity']; ?></td>
            <td>$<?php echo number_format((float)$item['price'], 2); ?></td>
            <td>$<?php echo number_format((float)$item['price'] * (int)$item['quantity'], 2); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="receipt-totals">
      <div class="receipt-totals-row">
        <span>Subtotal</span>
        <span>$<?php echo number_format($subtotal, 2); ?></span>
      </div>
      <?php if ($discount > 0): ?>
        <div class="receipt-totals-row">
          <span>Discount<?php echo $order['coupon_code'] ? ' (' . htmlspecialchars($order['coupon_code']) . ')' : ''; ?></span>
          <span>&minus;$<?php echo number_format($discount, 2); ?></span>
        </div>
      <?php endif; ?>
      <div class="receipt-totals-row">
        <span>Shipping</span>
        <span><?php echo $shipping > 0 ? '$' . number_format($shipping, 2) : 'Complimentary'; ?></span>
      </div>
      <div class="receipt-totals-row receipt-grand-total">
        <span>Total Paid</span>
        <span>$<?php echo number_format($total, 2); ?></span>
      </div>
    </div>

    <p class="receipt-thanks">Thank you for shopping Richenia. This receipt was generated automatically for your records.</p>
  </div>

  <div class="receipt-actions no-print">
    <button type="button" class="btn-submit" onclick="window.print()">Print Receipt</button>
    <a href="<?php echo BASE_URL; ?><?php echo is_admin() ? 'admin.php' : 'orders.php'; ?>" class="view-all">
      <?php echo is_admin() ? '← Back to Admin' : '← View All Orders'; ?>
    </a>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
