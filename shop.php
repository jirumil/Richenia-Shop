<?php
$page_title = 'Shop';
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/models/Product.php';

$q                = isset($_GET['q']) ? trim($_GET['q']) : '';
$active_category  = (isset($_GET['category']) && $_GET['category'] !== '') ? $_GET['category'] : 'All';
$min_price         = isset($_GET['min_price']) && $_GET['min_price'] !== '' ? (float)$_GET['min_price'] : null;
$max_price         = isset($_GET['max_price']) && $_GET['max_price'] !== '' ? (float)$_GET['max_price'] : null;

$categories  = Product::categories();
$price_bound = Product::priceBounds();
$products    = Product::filter($q, $active_category, $min_price, $max_price);
?>

<section class="shop-hero">
  <p class="eyebrow">Collection</p>
  <h1>The Menswear Edit</h1>
  <p class="shop-intro">Considered staples cut from honest materials. Search, filter by category, or narrow by price.</p>
</section>

<div class="shop-toolbar">
  <div class="search-bar">
    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
      <circle cx="7" cy="7" r="5.2" stroke="currentColor" stroke-width="1.4"/>
      <path d="M11 11.5L14.5 15" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
    </svg>
    <input type="text" id="live-search" placeholder="Search pieces by name or description…" value="<?php echo htmlspecialchars($q); ?>" autocomplete="off">
  </div>

  <div class="price-filter">
    <label>
      <span>Min $</span>
      <input type="number" id="price-min" min="0" step="1" placeholder="<?php echo (int)$price_bound['min']; ?>" value="<?php echo $min_price !== null ? (int)$min_price : ''; ?>">
    </label>
    <label>
      <span>Max $</span>
      <input type="number" id="price-max" min="0" step="1" placeholder="<?php echo (int)$price_bound['max']; ?>" value="<?php echo $max_price !== null ? (int)$max_price : ''; ?>">
    </label>
    <button type="button" id="price-clear" class="price-clear-btn" title="Clear price filter">Clear</button>
  </div>
</div>

<div class="filter-tabs" role="tablist" aria-label="Filter products by category">
  <button
    class="filter-tab <?php echo strcasecmp($active_category, 'All') === 0 ? 'active' : ''; ?>"
    data-category="All" type="button" role="tab">
    All
  </button>
  <?php foreach ($categories as $cat) : ?>
    <button
      class="filter-tab <?php echo strcasecmp($active_category, $cat) === 0 ? 'active' : ''; ?>"
      data-category="<?php echo htmlspecialchars($cat); ?>" type="button" role="tab">
      <?php echo htmlspecialchars($cat); ?>
    </button>
  <?php endforeach; ?>
</div>

<p class="results-count" id="results-count"><?php echo count($products); ?> piece<?php echo count($products) === 1 ? '' : 's'; ?> found</p>

<section id="product-grid" class="product-grid">
  <?php if (empty($products)) : ?>
    <p class="empty-state">No pieces match those filters — try widening your search.</p>
  <?php else : ?>
    <?php foreach ($products as $product) : ?>
      <?php include __DIR__ . '/includes/product-card.php'; ?>
    <?php endforeach; ?>
  <?php endif; ?>
</section>

<?php
$extra_js = ['assets/js/shop.js'];
require_once __DIR__ . '/includes/footer.php';
?>
