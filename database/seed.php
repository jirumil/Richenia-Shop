<?php
/**
 * database/seed.php
 *
 * One-time setup helper. Visit this file once in your browser
 * (e.g. http://localhost/richenia/database/seed.php) AFTER importing
 * database/schema.sql. It creates:
 *
 *   - A default admin account   (email: admin@richenia.test / password: Admin123!)
 *   - A default client account  (email: client@richenia.test / password: Client123!)
 *   - Three demo coupons        (WELCOME10, SAVE20, FREESHIP)
 *
 * It is safe to refresh this page multiple times — it checks for
 * existing rows before inserting anything, so nothing gets duplicated
 * or overwritten. Delete this file (or move it outside htdocs) once
 * your real admin account exists, since leaving setup scripts on a
 * live server is bad practice — for local development it's harmless.
 */

require_once __DIR__ . '/../config/database.php';

$pdo = Database::getConnection();
$log = [];

/* -----------------------------------------------------------
   Admin account
   ----------------------------------------------------------- */
$stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
$stmt->execute([':email' => 'admin@richenia.test']);

if ($stmt->fetch()) {
    $log[] = ['ok', 'Admin account already exists — skipped.'];
} else {
    $hash = password_hash('Admin123!', PASSWORD_DEFAULT);
    $pdo->prepare(
        'INSERT INTO users (username, email, password_hash, role) VALUES (:u, :e, :p, :r)'
    )->execute([
        ':u' => 'admin',
        ':e' => 'admin@richenia.test',
        ':p' => $hash,
        ':r' => 'admin',
    ]);
    $log[] = ['new', 'Admin account created — admin@richenia.test / Admin123!'];
}

/* -----------------------------------------------------------
   Demo client account (handy for testing checkout/orders)
   ----------------------------------------------------------- */
$stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
$stmt->execute([':email' => 'client@richenia.test']);

if ($stmt->fetch()) {
    $log[] = ['ok', 'Demo client account already exists — skipped.'];
} else {
    $hash = password_hash('Client123!', PASSWORD_DEFAULT);
    $pdo->prepare(
        'INSERT INTO users (username, email, password_hash, role) VALUES (:u, :e, :p, :r)'
    )->execute([
        ':u' => 'demo_client',
        ':e' => 'client@richenia.test',
        ':p' => $hash,
        ':r' => 'client',
    ]);
    $log[] = ['new', 'Demo client created — client@richenia.test / Client123!'];
}

/* -----------------------------------------------------------
   Demo coupons
   ----------------------------------------------------------- */
$coupons = [
    ['code' => 'WELCOME10', 'type' => 'percentage', 'value' => 10.00, 'max_uses' => null],
    ['code' => 'SAVE20',    'type' => 'percentage', 'value' => 20.00, 'max_uses' => 100],
    ['code' => 'FREESHIP',  'type' => 'fixed',       'value' => 18.00, 'max_uses' => null],
];

foreach ($coupons as $c) {
    $stmt = $pdo->prepare('SELECT id FROM coupons WHERE code = :code LIMIT 1');
    $stmt->execute([':code' => $c['code']]);

    if ($stmt->fetch()) {
        $log[] = ['ok', "Coupon {$c['code']} already exists — skipped."];
        continue;
    }

    $pdo->prepare(
        'INSERT INTO coupons (code, type, value, active, max_uses) VALUES (:code, :type, :value, 1, :max_uses)'
    )->execute([
        ':code'     => $c['code'],
        ':type'     => $c['type'],
        ':value'    => $c['value'],
        ':max_uses' => $c['max_uses'],
    ]);
    $log[] = ['new', "Coupon {$c['code']} created ({$c['type']}, {$c['value']})."];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Richenia — Database Seed</title>
<style>
  body { font-family: 'Work Sans', Arial, sans-serif; background:#F2EEE6; color:#161512; max-width:640px; margin:4rem auto; padding:0 1.5rem; line-height:1.6; }
  h1 { font-family: Georgia, serif; font-size:1.6rem; }
  ul { padding-left: 1.1rem; }
  .new { color:#3C6B4E; font-weight:600; }
  .ok  { color:#5A554C; }
  .box { background:#FBF9F4; border:1px solid #D8D0C0; border-radius:10px; padding:1.5rem; margin-top:1.5rem; }
  a { color:#5B6655; }
</style>
</head>
<body>
  <h1>Richenia — Seed Complete</h1>
  <p>The script ran successfully. Here's what happened:</p>
  <ul>
    <?php foreach ($log as $entry): ?>
      <li class="<?php echo $entry[0]; ?>"><?php echo htmlspecialchars($entry[1]); ?></li>
    <?php endforeach; ?>
  </ul>
  <div class="box">
    <p><strong>Admin login:</strong><br>admin@richenia.test / Admin123!</p>
    <p><strong>Demo client login:</strong><br>client@richenia.test / Client123!</p>
    <p style="margin-top:1rem;"><a href="../login.php">Go to login →</a></p>
  </div>
  <p style="margin-top:2rem;font-size:0.85rem;color:#5A554C;">
    For security, delete this file once you've created your own admin account.
    It's harmless on a local XAMPP install, but it shouldn't ship with a real deployment.
  </p>
</body>
</html>
