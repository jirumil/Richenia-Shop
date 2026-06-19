<?php
$page_title = 'Admin Dashboard';
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/models/Product.php';
require_once __DIR__ . '/models/Order.php';
require_once __DIR__ . '/models/Coupon.php';

require_admin();

/* ---------- Analytics ---------- */
$total_revenue = Order::totalRevenue();
$total_orders  = Order::totalCount();
$avg_order     = Order::averageOrderValue();
$top_products  = Order::topSellingProducts(5);

/* ---------- Data for the Products / Coupons / Orders tabs ---------- */
$products    = Product::all();
$low_stock   = array_values(array_filter($products, function ($p) { return (int)$p['stock'] <= 5; }));
$categories  = Product::categories();
$coupons     = Coupon::all();
$all_orders  = Order::allWithBuyer();

$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'dashboard';
if (!in_array($active_tab, ['dashboard', 'products', 'orders', 'coupons'], true)) {
    $active_tab = 'dashboard';
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="admin-shell">

  <aside class="admin-sidebar">
    <p class="admin-sidebar-title">Admin</p>
    <nav class="admin-nav">
      <button class="admin-nav-link" data-target="dashboard" type="button">Dashboard</button>
      <button class="admin-nav-link" data-target="products" type="button">Products</button>
      <button class="admin-nav-link" data-target="orders" type="button">Orders</button>
      <button class="admin-nav-link" data-target="coupons" type="button">Coupons</button>
    </nav>
    <a href="<?php echo BASE_URL; ?>index.php" class="admin-sidebar-exit">← Back to Storefront</a>
  </aside>

  <div class="admin-content">

    <!-- ============================================================
         DASHBOARD / ANALYTICS
         ============================================================ -->
    <section class="admin-panel" data-panel="dashboard">
      <div class="admin-panel-head">
        <h1>Analytics Summary</h1>
        <p>A live snapshot of how the storefront is performing.</p>
      </div>

      <div class="stat-cards">
        <div class="stat-card">
          <p class="stat-label">Total Revenue</p>
          <p class="stat-value">$<?php echo number_format($total_revenue, 2); ?></p>
        </div>
        <div class="stat-card">
          <p class="stat-label">Orders Placed</p>
          <p class="stat-value"><?php echo (int)$total_orders; ?></p>
        </div>
        <div class="stat-card">
          <p class="stat-label">Average Order Value</p>
          <p class="stat-value">$<?php echo number_format($avg_order, 2); ?></p>
        </div>
        <div class="stat-card">
          <p class="stat-label">Active Coupons</p>
          <p class="stat-value"><?php echo count(array_filter($coupons, function ($c) { return (int)$c['active'] === 1; })); ?></p>
        </div>
      </div>

      <div class="admin-two-col">
        <div class="admin-card">
          <p class="admin-card-title">Top-Selling Products</p>
          <?php if (empty($top_products)): ?>
            <p class="empty-state">No sales recorded yet.</p>
          <?php else: ?>
            <table class="admin-table">
              <thead><tr><th>Product</th><th>Units Sold</th><th>Revenue</th></tr></thead>
              <tbody>
                <?php foreach ($top_products as $tp): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($tp['product_name']); ?></td>
                    <td><?php echo (int)$tp['total_qty']; ?></td>
                    <td>$<?php echo number_format($tp['revenue'], 2); ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php endif; ?>
        </div>

        <div class="admin-card">
          <p class="admin-card-title">Low Stock Alert <span class="admin-card-sub">(5 or fewer units)</span></p>
          <?php if (empty($low_stock)): ?>
            <p class="empty-state">Every product is well stocked.</p>
          <?php else: ?>
            <table class="admin-table">
              <thead><tr><th>Product</th><th>Stock</th></tr></thead>
              <tbody>
                <?php foreach ($low_stock as $lp): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($lp['name']); ?></td>
                    <td><span class="stock-pill <?php echo (int)$lp['stock'] === 0 ? 'stock-pill-empty' : 'stock-pill-low'; ?>"><?php echo (int)$lp['stock']; ?></span></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <!-- ============================================================
         PRODUCTS
         ============================================================ -->
    <section class="admin-panel" data-panel="products">
      <div class="admin-panel-head">
        <h1>Products</h1>
        <p>Add new pieces or update price, stock, and details.</p>
      </div>

      <div class="admin-card">
        <p class="admin-card-title" id="product-form-title">Add a Product</p>
        <form method="post" action="<?php echo BASE_URL; ?>admin_products_save.php" class="admin-form" id="product-form">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="product_id" id="product_id" value="">

          <div class="form-row">
            <div class="form-group">
              <label for="p_name">Name</label>
              <input type="text" id="p_name" name="name" required>
            </div>
            <div class="form-group">
              <label for="p_category">Category</label>
              <input type="text" id="p_category" name="category" list="category-options" required>
              <datalist id="category-options">
                <?php foreach ($categories as $cat): ?>
                  <option value="<?php echo htmlspecialchars($cat); ?>"></option>
                <?php endforeach; ?>
              </datalist>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="p_price">Price ($)</label>
              <input type="number" id="p_price" name="price" min="0" step="0.01" required>
            </div>
            <div class="form-group">
              <label for="p_stock">Stock</label>
              <input type="number" id="p_stock" name="stock" min="0" step="1" required>
            </div>
          </div>

          <div class="form-group">
            <label for="p_image">Image URL</label>
            <input type="text" id="p_image" name="image_url" placeholder="https://placehold.co/700x900/161512/F2EEE6?text=New+Piece" required>
          </div>

          <div class="form-group">
            <label for="p_description">Description</label>
            <textarea id="p_description" name="description" rows="3"></textarea>
          </div>

          <label class="checkbox-row">
            <input type="checkbox" id="p_featured" name="is_featured">
            <span>Feature this product on the homepage</span>
          </label>

          <div class="admin-form-actions">
            <button type="submit" class="btn-submit" id="product-form-submit">Add Product</button>
            <button type="button" class="btn-cancel" id="product-form-cancel" style="display:none;">Cancel Edit</button>
          </div>
        </form>
      </div>

      <div class="admin-card">
        <p class="admin-card-title">Catalog <span class="admin-card-sub">(<?php echo count($products); ?> products)</span></p>
        <table class="admin-table products-table">
          <thead>
            <tr><th></th><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Featured</th><th></th></tr>
          </thead>
          <tbody>
            <?php foreach ($products as $p): ?>
              <tr>
                <td><img src="<?php echo htmlspecialchars($p['image_url']); ?>" alt="" class="admin-thumb"></td>
                <td><?php echo htmlspecialchars($p['name']); ?></td>
                <td><?php echo htmlspecialchars($p['category']); ?></td>
                <td>$<?php echo number_format($p['price'], 2); ?></td>
                <td><span class="stock-pill <?php echo (int)$p['stock'] === 0 ? 'stock-pill-empty' : ((int)$p['stock'] <= 5 ? 'stock-pill-low' : ''); ?>"><?php echo (int)$p['stock']; ?></span></td>
                <td><?php echo (int)$p['is_featured'] ? '✓' : '—'; ?></td>
                <td class="admin-row-actions">
                  <button type="button" class="btn-edit product-edit-btn"
                    data-id="<?php echo (int)$p['id']; ?>"
                    data-name="<?php echo htmlspecialchars($p['name']); ?>"
                    data-category="<?php echo htmlspecialchars($p['category']); ?>"
                    data-price="<?php echo htmlspecialchars($p['price']); ?>"
                    data-stock="<?php echo (int)$p['stock']; ?>"
                    data-image="<?php echo htmlspecialchars($p['image_url']); ?>"
                    data-description="<?php echo htmlspecialchars($p['description']); ?>"
                    data-featured="<?php echo (int)$p['is_featured']; ?>">Edit</button>
                  <form method="post" action="<?php echo BASE_URL; ?>admin_products_delete.php" class="inline-form admin-delete-form">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="product_id" value="<?php echo (int)$p['id']; ?>">
                    <button type="submit" class="btn-delete">Delete</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>

    <!-- ============================================================
         ORDERS
         ============================================================ -->
    <section class="admin-panel" data-panel="orders">
      <div class="admin-panel-head">
        <h1>Orders</h1>
        <p>Every order placed on the storefront.</p>
      </div>

      <div class="admin-card">
        <?php if (empty($all_orders)): ?>
          <p class="empty-state">No orders have been placed yet.</p>
        <?php else: ?>
          <table class="admin-table">
            <thead>
              <tr><th>Order</th><th>Customer</th><th>Date</th><th>Coupon</th><th>Discount</th><th>Total</th><th></th></tr>
            </thead>
            <tbody>
              <?php foreach ($all_orders as $o): ?>
                <tr>
                  <td>#<?php echo (int)$o['id']; ?></td>
                  <td><?php echo htmlspecialchars($o['username']); ?><br><span class="admin-card-sub"><?php echo htmlspecialchars($o['email']); ?></span></td>
                  <td><?php echo date('M j, Y g:i A', strtotime($o['created_at'])); ?></td>
                  <td><?php echo $o['coupon_code'] ? htmlspecialchars($o['coupon_code']) : '—'; ?></td>
                  <td><?php echo $o['discount_applied'] > 0 ? '−$' . number_format($o['discount_applied'], 2) : '—'; ?></td>
                  <td>$<?php echo number_format($o['total_price'], 2); ?></td>
                  <td><a href="<?php echo BASE_URL; ?>receipt.php?order_id=<?php echo (int)$o['id']; ?>" class="view-all">View →</a></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    </section>

    <!-- ============================================================
         COUPONS
         ============================================================ -->
    <section class="admin-panel" data-panel="coupons">
      <div class="admin-panel-head">
        <h1>Coupons</h1>
        <p>Create promotional codes for the checkout page.</p>
      </div>

      <div class="admin-card">
        <p class="admin-card-title">New Coupon</p>
        <form method="post" action="<?php echo BASE_URL; ?>admin_coupons_save.php" class="admin-form">
          <?php echo csrf_field(); ?>
          <div class="form-row">
            <div class="form-group">
              <label for="c_code">Code</label>
              <input type="text" id="c_code" name="code" placeholder="SUMMER15" required>
            </div>
            <div class="form-group">
              <label for="c_type">Type</label>
              <select id="c_type" name="type" required>
                <option value="percentage">Percentage (%)</option>
                <option value="fixed">Fixed amount ($)</option>
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="c_value">Value</label>
              <input type="number" id="c_value" name="value" min="0" step="0.01" required>
            </div>
            <div class="form-group">
              <label for="c_max_uses">Max Uses <span class="admin-card-sub">(optional)</span></label>
              <input type="number" id="c_max_uses" name="max_uses" min="1" step="1" placeholder="Unlimited">
            </div>
          </div>
          <div class="form-group">
            <label for="c_expires">Expires <span class="admin-card-sub">(optional)</span></label>
            <input type="date" id="c_expires" name="expires_at">
          </div>
          <div class="admin-form-actions">
            <button type="submit" class="btn-submit">Create Coupon</button>
          </div>
        </form>
      </div>

      <div class="admin-card">
        <p class="admin-card-title">Existing Coupons</p>
        <?php if (empty($coupons)): ?>
          <p class="empty-state">No coupons yet.</p>
        <?php else: ?>
          <table class="admin-table">
            <thead>
              <tr><th>Code</th><th>Type</th><th>Value</th><th>Used</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
              <?php foreach ($coupons as $c): ?>
                <tr>
                  <td><?php echo htmlspecialchars($c['code']); ?></td>
                  <td><?php echo $c['type'] === 'percentage' ? 'Percentage' : 'Fixed'; ?></td>
                  <td><?php echo $c['type'] === 'percentage' ? (float)$c['value'] . '%' : '$' . number_format($c['value'], 2); ?></td>
                  <td><?php echo (int)$c['times_used']; ?><?php echo $c['max_uses'] !== null ? ' / ' . (int)$c['max_uses'] : ''; ?></td>
                  <td>
                    <span class="role-badge <?php echo (int)$c['active'] === 1 ? 'role-badge-active' : 'role-badge-inactive'; ?>">
                      <?php echo (int)$c['active'] === 1 ? 'Active' : 'Inactive'; ?>
                    </span>
                  </td>
                  <td class="admin-row-actions">
                    <form method="post" action="<?php echo BASE_URL; ?>admin_coupons_toggle.php" class="inline-form">
                      <?php echo csrf_field(); ?>
                      <input type="hidden" name="coupon_id" value="<?php echo (int)$c['id']; ?>">
                      <button type="submit" class="btn-edit"><?php echo (int)$c['active'] === 1 ? 'Deactivate' : 'Activate'; ?></button>
                    </form>
                    <form method="post" action="<?php echo BASE_URL; ?>admin_coupons_delete.php" class="inline-form admin-delete-form">
                      <?php echo csrf_field(); ?>
                      <input type="hidden" name="coupon_id" value="<?php echo (int)$c['id']; ?>">
                      <button type="submit" class="btn-delete">Delete</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    </section>

  </div>
</div>

<script>window.RICHENIA_ADMIN_TAB = '<?php echo htmlspecialchars($active_tab); ?>';</script>

<?php
$extra_js = ['assets/js/admin.js'];
require_once __DIR__ . '/includes/footer.php';
?>
