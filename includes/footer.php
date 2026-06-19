  </main>

  <footer class="site-footer">
    <div class="footer-top">
      <p class="footer-statement">Richenia is built around a small number of pieces, made well — an antidote to disposable fashion.</p>
    </div>

    <div class="footer-grid">
      <div class="footer-col footer-brand">
        <p class="logo footer-logo">RICHENIA</p>
        <p class="footer-tagline">Considered menswear, made in small runs.</p>
      </div>

      <div class="footer-col">
        <p class="footer-heading">Shop</p>
        <a href="<?php echo BASE_URL; ?>shop.php">All Products</a>
        <a href="<?php echo BASE_URL; ?>shop.php?category=Menswear">Menswear</a>
        <a href="<?php echo BASE_URL; ?>shop.php?category=Accessories">Accessories</a>
        <a href="<?php echo BASE_URL; ?>cart.php">Your Bag</a>
      </div>

      <div class="footer-col">
        <p class="footer-heading">Company</p>
        <a href="<?php echo BASE_URL; ?>our-story.php">Our Story</a>
        <a href="<?php echo BASE_URL; ?>blog.php">Journal</a>
        <a href="<?php echo BASE_URL; ?>contact.php">Contact</a>
        <a href="<?php echo BASE_URL; ?>privacy-policy.php">Privacy Policy</a>
      </div>
    </div>

    <div class="footer-bottom">
      <p>&copy; <?php echo date('Y'); ?> Richenia. All rights reserved.</p>
    </div>
  </footer>

  <?php require_once __DIR__ . '/cart-drawer.php'; ?>

  <script src="<?php echo BASE_URL; ?>assets/js/main.js"></script>
  <?php if (!empty($extra_js)) : foreach ($extra_js as $js) : ?>
    <script src="<?php echo BASE_URL . htmlspecialchars($js); ?>"></script>
  <?php endforeach; endif; ?>
</body>
</html>
