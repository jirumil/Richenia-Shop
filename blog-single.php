<?php
require_once __DIR__ . '/config/app.php';

// Hardcoded post data — mirrors what's in blog.php
$all_posts = [
  'archive-fashion-what-it-means' => [
    'id'       => 1,
    'slug'     => 'archive-fashion-what-it-means',
    'category' => 'Archive',
    'title'    => 'Archive Fashion: What It Actually Means',
    'date'     => 'June 12, 2025',
    'author'   => 'Édouard Maris',
    'author_role' => 'Founder & Creative Director',
    'read_min' => 6,
    'image'    => 'https://placehold.co/1200x800/2A2720/F2EEE6?text=ARCHIVE+FASHION',
    'tags'     => ['Archive', 'Philosophy', 'Considered Fashion'],
    'excerpt'  => 'The word "archive" is everywhere now. Brands use it to mean old stock. Resellers use it to mean rare. We use it to mean something different.',
    'body'     => [
      'type' => 'html',
      'content' => '
        <p>The word "archive" is everywhere now. Brands use it to mean old stock sitting in a warehouse. Resellers use it to mean rare — something that has become rare simply because time has passed. Stylists use it to mean vintage with a better story. We use it to mean something different.</p>

        <p>For Richenia, archive fashion begins with a question: <em>Is this garment worth keeping?</em> Not for financial value. Not for cultural cachet. But because the object itself — the cut, the cloth, the construction — rewards continued use. Because it is better than what replaced it.</p>

        <h2>What makes a garment archival?</h2>

        <p>Three qualities, in our view. First: construction that reveals itself slowly. A jacket that seems simple on the rack but, over months of wearing, shows you how its collar sits differently from anything made since. How the chest seam allows movement in a particular, considered way. Second: material that ages rather than degrades. Wool that thickens. Linen that softens. Cotton that takes on the specific colour of the light in the rooms where it has lived. Third: a cut that belongs to no particular moment — or rather, one whose moment is perennial.</p>

        <blockquote>The archive is not a museum. It is a wardrobe that keeps getting dressed.</blockquote>

        <p>These qualities are not accidents. They are the result of decisions — by a pattern-cutter, a mill, a workshop — that were made with the long term in mind. Which is to say: they were made expensively, in time and attention, at a moment when someone believed that the garment in question deserved to last.</p>

        <h2>Why it matters now</h2>

        <p>We are surrounded by objects designed to be replaced. Garments that fall apart in eight washes. Silhouettes that are already wrong by the time they reach the shop floor. This is not inevitable — it is a choice, made by the industry, in pursuit of volume. The archive is the opposite of that choice.</p>

        <p>When we build a piece at Richenia, we are asking a simple question: in thirty years, will someone be glad they kept this? If the answer is yes, we proceed. If the answer is uncertain, we go back to the pattern.</p>

        <p>This is a slow way to work. It means small runs. It means months of development for a collar. It means accepting that most ideas do not survive the test. But it is the only honest way we know to make things.</p>

        <h2>How to shop archivally</h2>

        <p>A few practical principles, for those who want to build a wardrobe rather than a collection of moments:</p>

        <p><strong>Buy fewer things, more slowly.</strong> The purchase of a single considered garment — researched, tried, understood — will serve you better than ten impulse buys at a tenth of the price.</p>

        <p><strong>Learn the names of materials.</strong> A 14-micron merino does not behave like a 20-micron merino. A 200-gram linen is not a 280-gram linen. These differences are not trivial. They determine how a garment wears, ages, and ultimately survives.</p>

        <p><strong>Resist the new.</strong> Not always. Not absolutely. But the instinct toward the new is the enemy of the archive. The question is never "what is interesting now?" but "what will still be interesting when interesting is no longer the point?"</p>
      ',
    ],
  ],
  'on-portuguese-workshops' => [
    'id'       => 2,
    'slug'     => 'on-portuguese-workshops',
    'category' => 'Craft',
    'title'    => 'Inside Our Portuguese Workshops',
    'date'     => 'May 28, 2025',
    'author'   => 'Sofia Neves',
    'author_role' => 'Head of Pattern & Fit',
    'read_min' => 8,
    'image'    => 'https://placehold.co/1200x800/EAE4D8/5A554C?text=WORKSHOP+PORTO',
    'tags'     => ['Craft', 'Production', 'Portugal'],
    'excerpt'  => 'We spent three weeks in Porto last spring. Here is what we saw, what we learned, and why we are proud to call this place our second home.',
    'body'     => [
      'type' => 'html',
      'content' => '
        <p>We arrived in Porto in early April, three weeks blocked in the calendar — a luxury that a smaller team allows. The workshop had been expecting us. The same family has been cutting and sewing on this street for forty-three years.</p>

        <p>What you notice first, walking in, is the quiet. Not silence — the machines are always running — but a particular kind of purposeful quiet. Everyone in the room knows what they are doing. The work is not watched. It does not need to be.</p>

        <h2>What we make there</h2>

        <p>Our structured pieces — the coats, the tailored trousers, the heavier-weight jackets — are built here. The workshop specialises in construction that involves many pattern pieces and requires experience to execute properly. A coat might have thirty-seven components. A lapel alone requires seven.</p>

        <blockquote>Craft is not about doing things by hand. It is about doing things with attention.</blockquote>

        <p>The team lead, António, has been cutting patterns since 1987. He reads a pattern the way a conductor reads a score — understanding not just what is written but what is intended. When we bring a new sample, he will often make suggestions that we had not considered: a small adjustment to the hem allowance, a change in the grain line. We take them seriously. He has never been wrong.</p>

        <h2>Why we do not use intermediaries</h2>

        <p>Every factory visit we have made through an agent has felt different from a visit we made alone. Not worse, necessarily — but different. The relationship is mediated. The conversation is translated, in both senses of the word.</p>

        <p>We insist on direct relationships because craft is a conversation. The adjustments that make a Richenia piece what it is are not things you can specify in a brief. They emerge from back-and-forth, from trust built over years, from the willingness of both parties to admit uncertainty and work through it together.</p>
      ',
    ],
  ],
];

// Fallback for unimplemented slugs
$default_post = [
  'category' => 'Journal',
  'title'    => 'Essay Coming Soon',
  'date'     => 'Richenia Journal',
  'author'   => 'The Richenia Team',
  'author_role' => '',
  'read_min' => 3,
  'image'    => 'https://placehold.co/1200x800/2A2720/F2EEE6?text=RICHENIA',
  'tags'     => ['Richenia'],
  'excerpt'  => 'This essay is coming soon.',
  'body'     => ['type' => 'html', 'content' => '<p>This essay will be published shortly. <a href="' . BASE_URL . 'blog.php">Return to the Journal →</a></p>'],
];

$slug = trim($_GET['slug'] ?? '');
$post = $all_posts[$slug] ?? $default_post;

$page_title = $post['title'];

// Related posts (all others, max 3)
$related = [];
foreach ($all_posts as $s => $p) {
    if ($s !== $slug) $related[] = $p;
    if (count($related) >= 3) break;
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- ARTICLE HERO -->
<div class="article-hero">
  <div class="article-hero-copy">
    <p class="eyebrow"><?php echo htmlspecialchars($post['category']); ?></p>
    <h1><?php echo htmlspecialchars($post['title']); ?></h1>
    <p class="blog-meta" style="margin-top:1rem;">
      <?php echo htmlspecialchars($post['date']); ?> &nbsp;·&nbsp;
      <?php echo (int)$post['read_min']; ?> min read &nbsp;·&nbsp;
      <?php echo htmlspecialchars($post['author']); ?>
    </p>
  </div>
  <div class="article-hero-image">
    <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
  </div>
</div>

<!-- ARTICLE BODY + SIDEBAR -->
<div class="article-body-wrap">

  <!-- Body -->
  <article class="article-body">
    <?php echo $post['body']['content']; ?>

    <!-- Author byline -->
    <div style="margin-top:3rem;padding-top:2rem;border-top:1px solid var(--color-line);display:flex;align-items:center;gap:1rem;">
      <div style="width:44px;height:44px;background:var(--color-bg-alt);border-radius:50%;overflow:hidden;flex-shrink:0;">
        <img src="https://placehold.co/88x88/2A2720/F2EEE6?text=+" alt="<?php echo htmlspecialchars($post['author']); ?>" style="width:100%;height:100%;object-fit:cover;">
      </div>
      <div>
        <p style="font-size:0.9rem;font-weight:500;"><?php echo htmlspecialchars($post['author']); ?></p>
        <?php if (!empty($post['author_role'])): ?>
        <p style="font-size:0.76rem;color:var(--color-ink-soft);letter-spacing:0.06em;text-transform:uppercase;"><?php echo htmlspecialchars($post['author_role']); ?></p>
        <?php endif; ?>
      </div>
    </div>
  </article>

  <!-- Sidebar -->
  <aside class="article-sidebar">
    <!-- Tags -->
    <div class="article-tags">
      <p class="article-tags-title">Filed Under</p>
      <div class="article-tag-list">
        <?php foreach ($post['tags'] as $tag): ?>
        <span class="article-tag"><?php echo htmlspecialchars($tag); ?></span>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Related Posts -->
    <?php if (!empty($related)): ?>
    <div>
      <p class="related-posts-title">Also in the Journal</p>
      <?php foreach ($related as $rel): ?>
      <a href="<?php echo BASE_URL; ?>blog-single.php?slug=<?php echo urlencode($rel['slug']); ?>" class="related-post-mini">
        <div class="related-post-thumb">
          <img src="<?php echo htmlspecialchars($rel['image']); ?>" alt="<?php echo htmlspecialchars($rel['title']); ?>">
        </div>
        <div>
          <h4><?php echo htmlspecialchars($rel['title']); ?></h4>
          <p><?php echo htmlspecialchars($rel['date']); ?></p>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Share block -->
    <div style="margin-top:2rem;padding:1.25rem;background:var(--color-bg-alt);border:1px solid var(--color-line);">
      <p style="font-size:0.72rem;letter-spacing:0.1em;text-transform:uppercase;color:var(--color-ink-soft);margin-bottom:0.75rem;">Share this essay</p>
      <div style="display:flex;gap:0.75rem;">
        <button onclick="navigator.clipboard.writeText(window.location.href);this.textContent='Copied!';" style="background:var(--color-ink);color:var(--color-bg);border:none;padding:0.55rem 0.9rem;font-size:0.72rem;font-weight:500;letter-spacing:0.06em;text-transform:uppercase;cursor:pointer;transition:opacity 0.2s;">Copy Link</button>
      </div>
    </div>
  </aside>
</div>

<!-- ARTICLE NAV -->
<nav class="article-nav" aria-label="Article navigation">
  <a href="<?php echo BASE_URL; ?>blog.php" class="article-nav-link">← Back to Journal</a>
  <?php if (!empty($related[0])): ?>
  <a href="<?php echo BASE_URL; ?>blog-single.php?slug=<?php echo urlencode($related[0]['slug']); ?>" class="article-nav-link">
    Next: <?php echo htmlspecialchars($related[0]['title']); ?> →
  </a>
  <?php endif; ?>
</nav>

<!-- NEWSLETTER -->
<div class="page-newsletter">
  <div class="page-newsletter-inner">
    <p class="eyebrow">Stay Close</p>
    <h2>More from the Journal</h2>
    <p>New essays delivered when they're ready — no schedule, no noise.</p>
    <form class="newsletter-form" onsubmit="return false;">
      <input type="email" placeholder="Your email address" required>
      <button type="submit">Subscribe</button>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
