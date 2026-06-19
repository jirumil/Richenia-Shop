<?php
$page_title = 'Home';
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/models/Product.php';

$featured = Product::featured(4);
?>

<section class="hero">
  <div class="hero-copy">
    <p class="eyebrow">Est. Atelier — Richenia</p>
    <h1>Quiet luxury,<br>built to last.</h1>
    <p class="hero-sub">Considered menswear cut from honest materials — designed for the wardrobe you'll still wear in ten years, not ten weeks.</p>
    <a href="<?php echo BASE_URL; ?>shop.php" class="btn-primary">Enter the Shop</a>
  </div>
  <div class="hero-image">
    <img src="https://placehold.co/900x1100/161512/F2EEE6?text=RICHENIA" alt="Richenia menswear, studio still life">
  </div>
</section>

<section class="manifesto">
  <p class="manifesto-text">We are not interested in seasons. <span>Richenia</span> is built around a small number of pieces, made well, worn often — an antidote to disposable fashion.</p>
</section>

<section class="featured">
  <div class="section-heading">
    <div>
      <p class="eyebrow">Selected</p>
      <h2>The Edit</h2>
    </div>
    <a href="<?php echo BASE_URL; ?>shop.php" class="view-all">View All &rarr;</a>
  </div>

  <div class="product-grid">
    <?php foreach ($featured as $product) : ?>
      <?php include __DIR__ . '/includes/product-card.php'; ?>
    <?php endforeach; ?>
  </div>
</section>

<section class="newsletter-banner">
  <p class="eyebrow">Stay Close</p>
  <h2>Join the List</h2>
  <p>First access to new arrivals, archive re-releases, and limited runs.</p>
  <form class="newsletter-form" onsubmit="return false;">
    <input type="email" placeholder="Your email address" required>
    <button type="submit">Subscribe</button>
  </form>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
