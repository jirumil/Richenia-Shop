<?php
$page_title = 'Journal';
require_once __DIR__ . '/config/app.php';

// Hardcoded blog post data — replace with DB queries when ready
$posts = [
  [
    'id'       => 1,
    'slug'     => 'archive-fashion-what-it-means',
    'category' => 'Archive',
    'title'    => 'Archive Fashion: What It Actually Means',
    'date'     => 'June 12, 2025',
    'author'   => 'Édouard Maris',
    'excerpt'  => 'The word "archive" is everywhere now. Brands use it to mean old stock. Resellers use it to mean rare. We use it to mean something different — a garment worth keeping, worth understanding, worth passing on.',
    'image'    => 'https://placehold.co/900x700/2A2720/F2EEE6?text=ARCHIVE',
    'featured' => true,
    'read_min' => 6,
  ],
  [
    'id'       => 2,
    'slug'     => 'on-portuguese-workshops',
    'category' => 'Craft',
    'title'    => 'Inside Our Portuguese Workshops',
    'date'     => 'May 28, 2025',
    'author'   => 'Sofia Neves',
    'excerpt'  => 'We spent three weeks in Porto last spring. Here is what we saw, what we learned, and why we are proud to call this place our second home.',
    'image'    => 'https://placehold.co/900x700/EAE4D8/5A554C?text=CRAFT',
    'featured' => false,
    'read_min' => 8,
  ],
  [
    'id'       => 3,
    'slug'     => 'natural-fibres-guide',
    'category' => 'Materials',
    'title'    => 'A Guide to Natural Fibres — What to Look For',
    'date'     => 'May 10, 2025',
    'author'   => 'Kenji Watanabe',
    'excerpt'  => 'Not all wool is the same. Not all linen is the same. We break down what makes a natural fibre worth wearing — and what to avoid.',
    'image'    => 'https://placehold.co/900x700/D8D0C0/161512?text=MATERIALS',
    'featured' => false,
    'read_min' => 7,
  ],
  [
    'id'       => 4,
    'slug'     => 'building-a-wardrobe-you-keep',
    'category' => 'Philosophy',
    'title'    => 'Building a Wardrobe You Keep Forever',
    'date'     => 'April 22, 2025',
    'author'   => 'Édouard Maris',
    'excerpt'  => 'Ten pieces. Worn in rotation for twenty years. The maths of quality over quantity is simple — the practice is harder. Here is our framework.',
    'image'    => 'https://placehold.co/900x700/C9D2C2/161512?text=PHILOSOPHY',
    'featured' => false,
    'read_min' => 5,
  ],
  [
    'id'       => 5,
    'slug'     => 'care-and-longevity',
    'category' => 'Care',
    'title'    => 'How to Make Your Clothes Last a Lifetime',
    'date'     => 'March 15, 2025',
    'author'   => 'Ana Ferreira',
    'excerpt'  => 'The most sustainable garment is the one you already own. A simple guide to washing, storing, and repairing — so the clothes you love can outlast you.',
    'image'    => 'https://placehold.co/900x700/5B6655/F2EEE6?text=CARE',
    'featured' => false,
    'read_min' => 4,
  ],
  [
    'id'       => 6,
    'slug'     => 'colour-in-menswear',
    'category' => 'Design',
    'title'    => 'The Quiet Case for Colour in Menswear',
    'date'     => 'February 4, 2025',
    'author'   => 'Sofia Neves',
    'excerpt'  => 'Not neon. Not trend. A considered argument for introducing colour — slowly, deliberately — into a wardrobe built for the long term.',
    'image'    => 'https://placehold.co/900x700/3A3530/F2EEE6?text=DESIGN',
    'featured' => false,
    'read_min' => 6,
  ],
];

$featured_post = array_shift($posts); // First post is featured

require_once __DIR__ . '/includes/header.php';
?>

<!-- BLOG HERO -->
<div class="blog-hero">
  <p class="eyebrow">Richenia — The Journal</p>
  <h1>Craft, Archive,<br><em>and Considered Living</em></h1>
  <p class="blog-hero-sub">Essays and observations from the Richenia atelier — on materials, making, and wearing things well.</p>
</div>

<!-- FEATURED POST -->
<section class="blog-featured">
  <a href="<?php echo BASE_URL; ?>blog-single.php?slug=<?php echo urlencode($featured_post['slug']); ?>" class="blog-featured-inner">
    <div class="blog-featured-image">
      <img src="<?php echo htmlspecialchars($featured_post['image']); ?>" alt="<?php echo htmlspecialchars($featured_post['title']); ?>">
    </div>
    <div class="blog-featured-copy">
      <p class="eyebrow"><?php echo htmlspecialchars($featured_post['category']); ?> — Featured</p>
      <h2><?php echo htmlspecialchars($featured_post['title']); ?></h2>
      <p class="blog-meta"><?php echo htmlspecialchars($featured_post['date']); ?> · <?php echo (int)$featured_post['read_min']; ?> min read · <?php echo htmlspecialchars($featured_post['author']); ?></p>
      <p class="blog-featured-excerpt"><?php echo htmlspecialchars($featured_post['excerpt']); ?></p>
      <span class="btn-primary">Read the Essay</span>
    </div>
  </a>
</section>

<!-- BLOG GRID -->
<section class="blog-grid-section">
  <div class="section-heading" style="padding-left:0;padding-right:0;">
    <div>
      <p class="eyebrow">Latest</p>
      <h2>From the Journal</h2>
    </div>
  </div>

  <div class="blog-grid">
    <?php foreach ($posts as $post): ?>
    <article class="blog-card">
      <a href="<?php echo BASE_URL; ?>blog-single.php?slug=<?php echo urlencode($post['slug']); ?>" class="blog-card-image">
        <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
      </a>
      <p class="blog-card-category"><?php echo htmlspecialchars($post['category']); ?></p>
      <h3>
        <a href="<?php echo BASE_URL; ?>blog-single.php?slug=<?php echo urlencode($post['slug']); ?>">
          <?php echo htmlspecialchars($post['title']); ?>
        </a>
      </h3>
      <p class="blog-meta"><?php echo htmlspecialchars($post['date']); ?> · <?php echo (int)$post['read_min']; ?> min</p>
      <p class="blog-card-excerpt"><?php echo htmlspecialchars($post['excerpt']); ?></p>
      <a href="<?php echo BASE_URL; ?>blog-single.php?slug=<?php echo urlencode($post['slug']); ?>" class="blog-card-link">Read More →</a>
    </article>
    <?php endforeach; ?>
  </div>
</section>

<!-- NEWSLETTER -->
<div class="page-newsletter">
  <div class="page-newsletter-inner">
    <p class="eyebrow">Never Miss an Essay</p>
    <h2>Subscribe to the Journal</h2>
    <p>New essays delivered when they're ready — never on a schedule, never to fill an inbox.</p>
    <form class="newsletter-form" onsubmit="return false;">
      <input type="email" placeholder="Your email address" required>
      <button type="submit">Subscribe</button>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
