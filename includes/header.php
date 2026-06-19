<?php
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/cart.php';

$cart_count = cart_count();
$current    = basename($_SERVER['SCRIPT_NAME']);
$base       = BASE_URL;
$user       = current_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo isset($page_title) ? htmlspecialchars($page_title) . ' — Richenia' : 'Richenia — Considered Menswear'; ?></title>
<meta name="description" content="Richenia — quiet, considered menswear made in small runs.">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,400;0,9..144,500;0,9..144,600;1,9..144,400;1,9..144,500&family=Work+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

<link rel="stylesheet" href="<?php echo $base; ?>assets/css/style.css">
<link rel="stylesheet" href="<?php echo $base; ?>assets/css/additions.css">

<!-- Base URL injected for JavaScript fetch() calls -->
<script>window.RICHENIA_BASE = '<?php echo $base; ?>';</script>
</head>
<body>

<div class="marquee-bar" aria-hidden="true">
  <div class="marquee-track">
    <span>NEW SEASON ARCHIVE — NOW LIVE</span>
    <span class="marquee-glyph">✦</span>
    <span>COMPLIMENTARY SHIPPING OVER $300</span>
    <span class="marquee-glyph">✦</span>
    <span>MADE IN SMALL RUNS — RICHENIA</span>
    <span class="marquee-glyph">✦</span>
    <span>NEW SEASON ARCHIVE — NOW LIVE</span>
    <span class="marquee-glyph">✦</span>
    <span>COMPLIMENTARY SHIPPING OVER $300</span>
    <span class="marquee-glyph">✦</span>
    <span>MADE IN SMALL RUNS — RICHENIA</span>
    <span class="marquee-glyph">✦</span>
  </div>
</div>

<header class="navbar">
  <div class="navbar-inner">
    <a href="<?php echo $base; ?>index.php" class="logo">RICHENIA</a>

    <nav class="nav-links">
      <a href="<?php echo $base; ?>index.php"        class="<?php echo $current === 'index.php'         ? 'active' : ''; ?>">Home</a>
      <a href="<?php echo $base; ?>shop.php"          class="<?php echo $current === 'shop.php'          ? 'active' : ''; ?>">Shop</a>
      <a href="<?php echo $base; ?>our-story.php"     class="<?php echo $current === 'our-story.php'     ? 'active' : ''; ?>">Our Story</a>
      <a href="<?php echo $base; ?>blog.php"          class="<?php echo $current === 'blog.php'          ? 'active' : ''; ?>">Journal</a>
      <a href="<?php echo $base; ?>contact.php"       class="<?php echo $current === 'contact.php'       ? 'active' : ''; ?>">Contact</a>
    </nav>

    <div class="navbar-actions">
      <?php if ($user): ?>
        <div class="account-menu">
          <button class="account-toggle" type="button" aria-haspopup="true" aria-expanded="false">
            <span class="account-name"><?php echo htmlspecialchars($user['username']); ?></span>
            <?php if ($user['role'] === 'admin'): ?><span class="role-badge role-badge-admin">Admin</span><?php endif; ?>
          </button>
          <div class="account-dropdown">
            <?php if ($user['role'] === 'admin'): ?>
              <a href="<?php echo $base; ?>admin.php">Admin Dashboard</a>
            <?php else: ?>
              <a href="<?php echo $base; ?>orders.php">My Orders</a>
            <?php endif; ?>
            <a href="<?php echo $base; ?>logout.php">Sign Out</a>
          </div>
        </div>
      <?php else: ?>
        <a href="<?php echo $base; ?>login.php" class="nav-auth-link">Sign In</a>
      <?php endif; ?>

      <button id="cart-toggle" class="cart-toggle" type="button" aria-label="Open shopping bag">
        <svg width="20" height="22" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
          <path d="M5 6V5a5 5 0 0 1 10 0v1" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
          <path d="M2.5 6.5h15l-1 14h-13l-1-14Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
        </svg>
        <span id="cart-count" class="cart-count"><?php echo (int)$cart_count; ?></span>
      </button>
    </div>
  </div>
</header>

<main>
<?php flash_render(); ?>
