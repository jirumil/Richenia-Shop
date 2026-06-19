<?php
$page_title = 'Sign In';
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/models/User.php';

// Already logged in? Send them where they belong.
if (is_logged_in()) {
    header('Location: ' . BASE_URL . (is_admin() ? 'admin.php' : 'index.php'));
    exit;
}

/**
 * Only ever redirect a freshly-logged-in client to a page we know
 * about — never trust the raw query string as a redirect target.
 */
function safe_client_redirect($requested)
{
    $allowed = ['index.php', 'shop.php', 'cart.php', 'checkout.php', 'orders.php', 'contact.php'];
    $requested = basename((string)$requested);
    return in_array($requested, $allowed, true) ? $requested : 'index.php';
}

$form_errors = [];
$old_login   = '';
$redirect    = $_GET['redirect'] ?? ($_POST['redirect'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $form_errors['general'] = 'Your session expired. Please try again.';
    } else {
        $login    = trim($_POST['login'] ?? '');
        $password = (string)($_POST['password'] ?? '');
        $old_login = $login;

        if ($login === '' || $password === '') {
            $form_errors['general'] = 'Enter both your email/username and password.';
        } else {
            $user = User::attempt($login, $password);

            if (!$user) {
                $form_errors['general'] = 'Those credentials don\'t match our records.';
            } else {
                login_user($user);

                if ($user['role'] === 'admin') {
                    header('Location: ' . BASE_URL . 'admin.php');
                } else {
                    flash_set('success', 'Welcome back, ' . $user['username'] . '.');
                    header('Location: ' . BASE_URL . safe_client_redirect($redirect));
                }
                exit;
            }
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="auth-page">
  <div class="auth-card">
    <p class="eyebrow">Richenia</p>
    <h1>Sign in</h1>
    <p class="auth-sub">Welcome back. Enter your details to continue.</p>

    <?php if (!empty($form_errors['general'])): ?>
      <div class="form-error form-error-banner"><?php echo htmlspecialchars($form_errors['general']); ?></div>
    <?php endif; ?>

    <form method="post" novalidate class="auth-form">
      <?php echo csrf_field(); ?>
      <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>">

      <div class="form-group">
        <label for="login">Email or username</label>
        <input type="text" id="login" name="login" value="<?php echo htmlspecialchars($old_login); ?>" required autofocus>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>

      <button type="submit" class="btn-submit">Sign In</button>
    </form>

    <p class="auth-switch">New to Richenia? <a href="<?php echo BASE_URL; ?>register.php">Create an account</a></p>

    <div class="auth-hint">
      <p><strong>Demo accounts</strong> (after running <code>database/seed.php</code>):</p>
      <p>Admin — admin@richenia.test / Admin123!</p>
      <p>Client — client@richenia.test / Client123!</p>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
