<?php
$page_title = 'Contact';
require_once __DIR__ . '/config/app.php';

// Handle form submission
$form_success = false;
$form_errors  = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_submit'])) {
    $name    = trim($_POST['name']    ?? '');
    $email   = trim($_POST['email']   ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (strlen($name) < 2)                 $form_errors['name']    = 'Please enter your full name.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $form_errors['email']   = 'Please enter a valid email address.';
    if (strlen($subject) < 2)              $form_errors['subject'] = 'Please select a subject.';
    if (strlen($message) < 10)             $form_errors['message'] = 'Please write a message (at least 10 characters).';

    if (empty($form_errors)) {
        // In production: mail($to, $subject, $message, $headers);
        // For localhost, we simply mark as success.
        $form_success = true;
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- CONTACT HERO -->
<section class="contact-hero">
  <div class="contact-hero-left">
    <p class="eyebrow">Get in Touch</p>
    <h1>We read<br>every message.</h1>
    <div class="contact-info-chips">
      <div class="contact-chip">
        <span class="contact-chip-icon">✉</span>
        <span>hello@richenia.com</span>
      </div>
      <div class="contact-chip">
        <span class="contact-chip-icon">◷</span>
        <span>Mon – Fri, 10am – 6pm CET</span>
      </div>
      <div class="contact-chip">
        <span class="contact-chip-icon">↩</span>
        <span>We reply within 48 hours</span>
      </div>
    </div>
  </div>
  <div class="contact-hero-right">
    <p>Richenia is a small team. We do not have a call centre. When you write to us, you write to a person — someone who knows the collection, the process, and the craft behind every piece.</p>
    <p>Whether you have a question about sizing, a press enquiry, or simply want to know more about how we work, we welcome the conversation.</p>
  </div>
</section>

<!-- MAIN CONTENT: FORM + ASIDE -->
<div class="contact-main">

  <!-- CONTACT FORM -->
  <div class="contact-form-wrap">
    <h2 class="contact-form-title">Send a Message</h2>
    <p class="contact-form-subtitle">All fields are required. We keep your details private — see our <a href="<?php echo BASE_URL; ?>privacy-policy.php" style="border-bottom:1px solid currentColor;">Privacy Policy</a>.</p>

    <?php if ($form_success): ?>
    <div class="form-success" role="alert">
      ✦ Thank you, your message has been received. We will reply within 48 hours.
    </div>
    <?php endif; ?>

    <form method="POST" action="#" novalidate>
      <div class="form-row">
        <div class="form-group">
          <label for="cf-name">Full Name</label>
          <input
            type="text"
            id="cf-name"
            name="name"
            value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
            placeholder="Your name"
            autocomplete="name">
          <?php if (!empty($form_errors['name'])): ?>
            <span class="form-error"><?php echo $form_errors['name']; ?></span>
          <?php endif; ?>
        </div>
        <div class="form-group">
          <label for="cf-email">Email Address</label>
          <input
            type="email"
            id="cf-email"
            name="email"
            value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
            placeholder="you@example.com"
            autocomplete="email">
          <?php if (!empty($form_errors['email'])): ?>
            <span class="form-error"><?php echo $form_errors['email']; ?></span>
          <?php endif; ?>
        </div>
      </div>

      <div class="form-group">
        <label for="cf-subject">Enquiry Type</label>
        <select id="cf-subject" name="subject">
          <option value="" disabled <?php echo empty($_POST['subject']) ? 'selected' : ''; ?>>Select a subject</option>
          <option value="Order &amp; Shipping"    <?php echo (($_POST['subject'] ?? '') === 'Order & Shipping')    ? 'selected' : ''; ?>>Order &amp; Shipping</option>
          <option value="Sizing &amp; Fit"        <?php echo (($_POST['subject'] ?? '') === 'Sizing & Fit')        ? 'selected' : ''; ?>>Sizing &amp; Fit</option>
          <option value="Returns &amp; Exchanges" <?php echo (($_POST['subject'] ?? '') === 'Returns & Exchanges') ? 'selected' : ''; ?>>Returns &amp; Exchanges</option>
          <option value="Press &amp; Wholesale"   <?php echo (($_POST['subject'] ?? '') === 'Press & Wholesale')   ? 'selected' : ''; ?>>Press &amp; Wholesale</option>
          <option value="General Enquiry"         <?php echo (($_POST['subject'] ?? '') === 'General Enquiry')     ? 'selected' : ''; ?>>General Enquiry</option>
        </select>
        <?php if (!empty($form_errors['subject'])): ?>
          <span class="form-error"><?php echo $form_errors['subject']; ?></span>
        <?php endif; ?>
      </div>

      <div class="form-group">
        <label for="cf-message">Message</label>
        <textarea
          id="cf-message"
          name="message"
          rows="5"
          placeholder="Tell us how we can help…"><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
        <?php if (!empty($form_errors['message'])): ?>
          <span class="form-error"><?php echo $form_errors['message']; ?></span>
        <?php endif; ?>
      </div>

      <button type="submit" name="contact_submit" class="btn-submit">
        Send Message &nbsp;→
      </button>
    </form>
  </div>

  <!-- ASIDE: Support Channels -->
  <div class="contact-aside">

    <div class="support-card">
      <div class="support-card-icon">◈</div>
      <h3>Customer Support</h3>
      <p>Questions about your order, sizing, shipping status, or returns? We handle all customer enquiries personally — no bots, no scripts.</p>
      <a href="mailto:support@richenia.com">support@richenia.com</a>
    </div>

    <div class="support-card">
      <div class="support-card-icon">✦</div>
      <h3>Press &amp; Editorial</h3>
      <p>For interview requests, look-book access, product loans, and wholesale conversations. Please include your publication and lead time.</p>
      <a href="mailto:press@richenia.com">press@richenia.com</a>
    </div>

    <div class="support-card">
      <div class="support-card-icon">◻</div>
      <h3>Wholesale Enquiries</h3>
      <p>We work with a very limited number of retail partners who share our values around curation and experience. We prioritise independent stores.</p>
      <a href="mailto:trade@richenia.com">trade@richenia.com</a>
    </div>

  </div>
</div>

<!-- FAQ -->
<section class="faq-section">
  <div class="faq-header">
    <div>
      <p class="eyebrow">Common Questions</p>
      <h2>Frequently Asked</h2>
    </div>
    <p>If your question isn't answered here, please write to us — we are happy to help.</p>
  </div>

  <div class="faq-list">
    <?php
    $faqs = [
      [
        'q' => 'How do I know which size to order?',
        'a' => 'Every Richenia garment page includes a detailed measurements table in centimetres. Our pieces are designed with a considered, relaxed fit — if you are between sizes, we typically recommend sizing down for a cleaner silhouette, though this varies by style. If in doubt, write to us with your measurements and we will advise directly.',
      ],
      [
        'q' => 'Do you restock sold-out pieces?',
        'a' => 'No. Each piece is produced in a limited run of fewer than 150 units. When a run is complete, it is archived. This is intentional — we believe in the integrity of the object and the archive. Some earlier pieces occasionally surface through our returns programme; join the mailing list to be notified.',
      ],
      [
        'q' => 'What is your returns policy?',
        'a' => 'We accept returns on unworn, unaltered items within 14 days of delivery. Items must be in original condition with all tags attached. Return shipping is the responsibility of the customer, except in cases of manufacturing defect. To initiate a return, write to support@richenia.com with your order number.',
      ],
      [
        'q' => 'How long does shipping take?',
        'a' => 'Orders are dispatched within 2 business days. Domestic delivery (where applicable) takes 3–5 business days. International orders typically arrive within 7–14 business days, depending on destination and customs. Complimentary shipping is available on all orders over $300.',
      ],
      [
        'q' => 'Are your garments ethically produced?',
        'a' => 'Yes. We work exclusively with workshops we have visited, whose conditions and practices we have assessed personally. Our primary production partners are in Portugal and Japan — both family-run operations that have been in continuous operation for over four decades. We do not use intermediaries.',
      ],
      [
        'q' => 'How should I care for my Richenia piece?',
        'a' => 'Care instructions are stitched into every garment. As a general rule: cold wash on a gentle cycle or dry-clean for structured pieces; lay flat to dry; press under a damp cloth. Natural fibres improve with careful, considered use — please do not over-wash.',
      ],
    ];
    foreach ($faqs as $i => $faq): ?>
    <div class="faq-item" id="faq-<?php echo $i; ?>">
      <button class="faq-question" aria-expanded="false" aria-controls="faq-answer-<?php echo $i; ?>">
        <?php echo htmlspecialchars($faq['q']); ?>
        <span class="faq-icon" aria-hidden="true">+</span>
      </button>
      <div class="faq-answer" id="faq-answer-<?php echo $i; ?>" role="region">
        <p class="faq-answer-inner"><?php echo htmlspecialchars($faq['a']); ?></p>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
