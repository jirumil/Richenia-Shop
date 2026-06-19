<?php
$page_title = 'Privacy Policy';
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/includes/header.php';
?>

<!-- PRIVACY POLICY -->
<div class="legal-hero">
  <p class="eyebrow">Legal — Richenia</p>
  <h1>Privacy Policy</h1>
  <div class="legal-hero-meta">
    <span>Last updated: 1 June 2025</span>
    <span>·</span>
    <span>Effective: 1 June 2025</span>
  </div>
  <p style="color:var(--color-ink-soft);max-width:56ch;margin-top:1rem;font-size:0.92rem;line-height:1.7;">
    Richenia ("we", "our", "us") respects your privacy. This policy explains what personal data we collect, why we collect it, how we use and protect it, and the rights you have over it.
  </p>
</div>

<div class="legal-layout">

  <!-- Table of Contents -->
  <nav class="legal-toc" aria-label="Page sections">
    <p class="legal-toc-title">Contents</p>
    <a href="#collection">1. Data We Collect</a>
    <a href="#use">2. How We Use Your Data</a>
    <a href="#cookies">3. Cookies &amp; Local Storage</a>
    <a href="#sharing">4. Data Sharing</a>
    <a href="#retention">5. Data Retention</a>
    <a href="#security">6. Security</a>
    <a href="#rights">7. Your Rights</a>
    <a href="#children">8. Children</a>
    <a href="#changes">9. Changes to This Policy</a>
    <a href="#contact">10. Contact Us</a>
  </nav>

  <!-- Legal content -->
  <div class="legal-content">

    <section class="legal-section" id="collection">
      <h2>1. Data We Collect</h2>
      <p>We collect personal data in the following ways:</p>

      <p><strong>Data you provide directly:</strong></p>
      <ul>
        <li><strong>Account &amp; order information:</strong> Name, email address, billing address, shipping address, telephone number, and payment method (processed by our payment provider — we do not store card numbers).</li>
        <li><strong>Communications:</strong> Any information you include when you contact us via our contact form, email, or otherwise.</li>
        <li><strong>Newsletter subscription:</strong> Your email address, and optionally your name, when you subscribe to our mailing list.</li>
      </ul>

      <p><strong>Data collected automatically:</strong></p>
      <ul>
        <li><strong>Usage data:</strong> Your IP address, browser type and version, pages visited, time spent on pages, referring URLs, and similar technical data collected via our server logs.</li>
        <li><strong>Device data:</strong> Device type, operating system, and screen resolution.</li>
        <li><strong>Session data:</strong> We use PHP sessions to maintain your shopping cart during a browsing session. This data is stored server-side and identified by a session cookie.</li>
      </ul>

      <div class="legal-highlight">
        We do not collect sensitive personal data (such as health information, ethnic origin, or biometric data) and we do not use automated profiling or decision-making.
      </div>
    </section>

    <section class="legal-section" id="use">
      <h2>2. How We Use Your Data</h2>
      <p>We use the personal data we collect for the following purposes:</p>
      <ul>
        <li>To process and fulfil your orders, including sending order confirmation and shipping notifications.</li>
        <li>To communicate with you in response to enquiries submitted via our contact form or by email.</li>
        <li>To send you our journal and promotional communications, where you have opted in (you may unsubscribe at any time).</li>
        <li>To improve the functionality, performance, and content of our website.</li>
        <li>To detect and prevent fraud, unauthorised access, and other harmful activities.</li>
        <li>To comply with legal obligations to which we are subject.</li>
      </ul>

      <p>Our legal basis for processing your data is:</p>
      <ul>
        <li><strong>Contract performance</strong> — for processing orders and communicating about them.</li>
        <li><strong>Legitimate interests</strong> — for improving our service, fraud prevention, and website analytics.</li>
        <li><strong>Consent</strong> — for marketing communications and non-essential cookies.</li>
        <li><strong>Legal obligation</strong> — where required by applicable law.</li>
      </ul>
    </section>

    <section class="legal-section" id="cookies">
      <h2>3. Cookies &amp; Local Storage</h2>
      <p>We use cookies and similar browser-based technologies to operate and improve our website.</p>

      <p><strong>Essential cookies (always active):</strong></p>
      <ul>
        <li><code>PHPSESSID</code> — PHP session cookie. Maintains your shopping cart and session state. Expires when you close your browser.</li>
      </ul>

      <p><strong>Functional technologies:</strong></p>
      <ul>
        <li>We may use browser <code>localStorage</code> to remember your display preferences (e.g. cookie consent state). No personal data is stored in <code>localStorage</code>.</li>
      </ul>

      <p><strong>Analytics cookies (with consent):</strong></p>
      <ul>
        <li>We may use analytics tools to understand how visitors navigate our site. Where this involves cookies, your consent will be requested before any are set.</li>
      </ul>

      <div class="legal-highlight">
        You can control and delete cookies via your browser settings. Note that disabling the session cookie will prevent the shopping cart from functioning correctly.
      </div>

      <p>Our website does not currently serve advertising cookies or share data with advertising networks.</p>
    </section>

    <section class="legal-section" id="sharing">
      <h2>4. Data Sharing</h2>
      <p>We do not sell your personal data. We share it only in the following limited circumstances:</p>
      <ul>
        <li><strong>Payment processors:</strong> Your payment information is processed by a PCI-DSS compliant third-party provider. We do not receive or store your full card details.</li>
        <li><strong>Delivery partners:</strong> We share your name and shipping address with our logistics partners to fulfil your order.</li>
        <li><strong>Email service providers:</strong> We use a third-party platform to send transactional and marketing emails. Your email address and name are shared with this provider.</li>
        <li><strong>Legal compliance:</strong> We may disclose data where required by law, court order, or regulatory authority.</li>
      </ul>
      <p>All third-party providers we use are contractually obligated to handle your data securely and only for the purposes we specify.</p>
    </section>

    <section class="legal-section" id="retention">
      <h2>5. Data Retention</h2>
      <p>We retain your personal data for as long as necessary to fulfil the purposes described in this policy:</p>
      <ul>
        <li><strong>Order data:</strong> Retained for 7 years to comply with tax and accounting obligations.</li>
        <li><strong>Customer account data:</strong> Retained until you request deletion, or for 3 years of account inactivity.</li>
        <li><strong>Contact form submissions:</strong> Retained for 2 years or until the matter is resolved.</li>
        <li><strong>Marketing consent records:</strong> Retained until you withdraw consent, plus an additional 2 years.</li>
        <li><strong>Session data:</strong> Deleted when your browser session ends, or after 24 hours of inactivity.</li>
      </ul>
    </section>

    <section class="legal-section" id="security">
      <h2>6. Security</h2>
      <p>We take reasonable technical and organisational measures to protect your personal data against unauthorised access, loss, or alteration. These include:</p>
      <ul>
        <li>HTTPS encryption for all data transmitted between your browser and our server.</li>
        <li>Secure, hashed storage of account passwords (we never store passwords in plain text).</li>
        <li>Access controls limiting which team members can access personal data.</li>
        <li>Regular review of our data handling practices.</li>
      </ul>
      <p>No method of transmission over the internet is 100% secure. If you believe your data has been compromised, please contact us immediately at <a href="mailto:privacy@richenia.com" style="border-bottom:1px solid currentColor;">privacy@richenia.com</a>.</p>
    </section>

    <section class="legal-section" id="rights">
      <h2>7. Your Rights</h2>
      <p>Depending on your jurisdiction, you may have the following rights regarding your personal data:</p>
      <ul>
        <li><strong>Right of access:</strong> You may request a copy of the personal data we hold about you.</li>
        <li><strong>Right to rectification:</strong> You may request that inaccurate data be corrected.</li>
        <li><strong>Right to erasure:</strong> You may request deletion of your data, subject to legal retention requirements.</li>
        <li><strong>Right to restriction:</strong> You may request that we restrict processing of your data in certain circumstances.</li>
        <li><strong>Right to data portability:</strong> You may request your data in a structured, machine-readable format.</li>
        <li><strong>Right to object:</strong> You may object to processing based on legitimate interests, including direct marketing.</li>
        <li><strong>Right to withdraw consent:</strong> Where processing is based on consent, you may withdraw it at any time without affecting prior lawful processing.</li>
      </ul>
      <p>To exercise any of these rights, please write to us at <a href="mailto:privacy@richenia.com" style="border-bottom:1px solid currentColor;">privacy@richenia.com</a>. We will respond within 30 days. We may need to verify your identity before processing your request.</p>
      <p>If you are unhappy with how we handle your data, you have the right to lodge a complaint with your national data protection authority.</p>
    </section>

    <section class="legal-section" id="children">
      <h2>8. Children</h2>
      <p>Our website is not directed at children under the age of 16 and we do not knowingly collect personal data from children. If you believe a child has provided us with personal data without parental consent, please contact us and we will delete it promptly.</p>
    </section>

    <section class="legal-section" id="changes">
      <h2>9. Changes to This Policy</h2>
      <p>We may update this Privacy Policy from time to time. When we do, we will revise the "Last updated" date at the top of this page. Where changes are material, we will notify registered customers by email. We encourage you to review this policy periodically.</p>
      <p>Your continued use of our website after any changes constitutes your acceptance of the updated policy.</p>
    </section>

    <section class="legal-section" id="contact">
      <h2>10. Contact Us</h2>
      <p>For any questions, requests, or concerns regarding your personal data and this policy, please contact our Privacy Officer:</p>
      <ul>
        <li><strong>Email:</strong> <a href="mailto:privacy@richenia.com" style="border-bottom:1px solid currentColor;">privacy@richenia.com</a></li>
        <li><strong>Post:</strong> Richenia Privacy Officer, Atelier Richenia, [Studio Address]</li>
        <li><strong>Response time:</strong> We aim to respond within 30 days of receipt.</li>
      </ul>
      <p>For general enquiries, please use our <a href="<?php echo BASE_URL; ?>contact.php" style="border-bottom:1px solid currentColor;">contact page</a>.</p>
    </section>

  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
