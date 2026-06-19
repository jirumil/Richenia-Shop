<?php
$page_title = 'Our Story';
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/includes/header.php';
?>

<!-- OUR STORY — Editorial About Page -->

<!-- HERO -->
<section class="story-hero">
  <div class="story-hero-copy">
    <p class="eyebrow">Est. — The Atelier</p>
    <h1>Quiet<br><em>luxury,</em><br>considered.</h1>
    <p style="color:var(--color-ink-soft);margin-top:1.5rem;line-height:1.75;max-width:36ch;">
      Richenia began as a protest against the disposable — a belief that clothing should be made once, made right, and worn for the rest of your life.
    </p>
  </div>
  <div class="story-hero-image">
    <img
      src="https://placehold.co/900x1200/2A2720/F2EEE6?text=RICHENIA+ATELIER"
      alt="Richenia atelier — considered menswear">
  </div>
</section>

<!-- PULL-QUOTE -->
<div class="story-pullquote">
  <div class="pullquote-rule"></div>
  <blockquote class="pullquote-text">
    "We are not interested in what is new. We are only interested in what is <em>right</em> — the cut that holds its shape, the cloth that deepens with every wearing."
  </blockquote>
</div>

<!-- PHILOSOPHY GRID -->
<section class="story-philosophy">
  <div class="story-philosophy-inner">
    <div class="story-philosophy-header">
      <div>
        <p class="eyebrow">What We Believe</p>
        <h2>The Richenia<br>Philosophy</h2>
      </div>
      <p>
        Three principles govern every decision we make — from the thread count of a collar to the weight of a button. They are not guidelines. They are the foundation.
      </p>
    </div>

    <div class="philosophy-grid">
      <div class="philosophy-card">
        <p class="philosophy-number">01</p>
        <h3>Material Honesty</h3>
        <p>We work only with natural fibres sourced from mills whose practices we have visited in person. Wool from the Basque highlands. Linen from Belgian river flats. Cotton from single-estate farms in Oaxaca. What goes into the cloth is what you feel on your skin.</p>
      </div>
      <div class="philosophy-card">
        <p class="philosophy-number">02</p>
        <h3>Small Run Production</h3>
        <p>Every Richenia piece is produced in a run of fewer than 150 units. We absorb the premium this demands. Once a run is gone, it is gone — there is no restock, no markdown, no clearance floor. This is a feature, not a limitation.</p>
      </div>
      <div class="philosophy-card">
        <p class="philosophy-number">03</p>
        <h3>Archive First</h3>
        <p>We do not do seasons. Each collection is designed to sit alongside — not replace — what came before. A Richenia coat from five years ago should work with a shirt we release tomorrow. This demands restraint. We find it clarifying.</p>
      </div>
    </div>
  </div>
</section>

<!-- ALTERNATING SECTIONS -->
<section class="story-section">
  <div class="story-section-image">
    <img
      src="https://placehold.co/800x1000/EAE4D8/5A554C?text=THE+ARCHIVE"
      alt="Richenia archive — garment research">
  </div>
  <div class="story-section-copy">
    <p class="eyebrow">The Beginning</p>
    <h2>Born from an archive,<br>not a brief.</h2>
    <p>Richenia was founded in 2018 by a collector and a pattern-cutter. Between them, they held twenty years of research into postwar European workwear — the silhouettes that shaped the bodies they were built for, the construction techniques that outlasted the companies that invented them.</p>
    <p>The first collection was eight pieces. It sold out in eleven days. We have not expanded the line beyond twelve pieces since.</p>
    <div class="story-stat-row">
      <div>
        <p class="story-stat-value">8</p>
        <p class="story-stat-label">Founding Pieces</p>
      </div>
      <div>
        <p class="story-stat-value">11</p>
        <p class="story-stat-label">Days to Sell Out</p>
      </div>
      <div>
        <p class="story-stat-value">12</p>
        <p class="story-stat-label">Max Collection Size</p>
      </div>
    </div>
  </div>
</section>

<section class="story-section reverse">
  <div class="story-section-image">
    <img
      src="https://placehold.co/800x1000/D8D0C0/161512?text=CRAFT+PROCESS"
      alt="Richenia — construction and craft">
  </div>
  <div class="story-section-copy">
    <p class="eyebrow">The Process</p>
    <h2>Every seam is a decision.</h2>
    <p>We work with three family-run workshops in Portugal and one in Japan. None of them produce for fast fashion. All of them have been in continuous operation for more than forty years.</p>
    <p>Patterns are developed over a minimum of eight months. Fit samples travel between our studio and the workshops by hand. We do not sign off on a garment until we have worn a prototype for at least six weeks.</p>
    <p>This is slow. It is meant to be.</p>
    <a href="<?php echo BASE_URL; ?>shop.php" class="btn-primary" style="margin-top:1.5rem;">Explore the Collection</a>
  </div>
</section>

<!-- TEAM -->
<section class="story-team">
  <div class="story-team-inner">
    <div class="story-team-header">
      <div>
        <p class="eyebrow" style="color:rgba(242,238,230,.55);">The People</p>
        <h2>Made by hands<br>we know.</h2>
      </div>
      <p>Every person in the Richenia network is someone we have met, whose work we understand, and whose craft we are proud to carry.</p>
    </div>

    <div class="team-grid">
      <?php
      $team = [
        ['name' => 'Édouard Maris',    'role' => 'Founder & Creative Director', 'img' => '2A2720/F2EEE6'],
        ['name' => 'Sofia Neves',      'role' => 'Head of Pattern & Fit',       'img' => '3A3530/F2EEE6'],
        ['name' => 'Kenji Watanabe',   'role' => 'Production Partner — Kyoto',  'img' => '2A2720/D8D0C0'],
        ['name' => 'Ana Ferreira',     'role' => 'Workshop Lead — Porto',       'img' => '3A3530/D8D0C0'],
      ];
      foreach ($team as $t): ?>
      <div class="team-card">
        <div class="team-card-image">
          <img
            src="https://placehold.co/600x800/<?php echo $t['img']; ?>?text=+"
            alt="<?php echo htmlspecialchars($t['name']); ?>">
        </div>
        <p class="team-card-name"><?php echo htmlspecialchars($t['name']); ?></p>
        <p class="team-card-role"><?php echo htmlspecialchars($t['role']); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- CTA -->
<div class="story-cta">
  <div>
    <p class="eyebrow">What's Next</p>
    <h2>The next collection<br>arrives in small runs.</h2>
    <p>Be first to know. No noise, no promotions — just one email when something new is ready.</p>
  </div>
  <div>
    <a href="<?php echo BASE_URL; ?>contact.php" class="btn-primary">Get in Touch</a>
  </div>
</div>

<!-- NEWSLETTER -->
<div class="page-newsletter">
  <div class="page-newsletter-inner">
    <p class="eyebrow">Stay Close</p>
    <h2>Join the List</h2>
    <p>First access to archive drops and new runs — nothing else.</p>
    <form class="newsletter-form" onsubmit="return false;">
      <input type="email" placeholder="Your email address" required>
      <button type="submit">Subscribe</button>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
