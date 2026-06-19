<?php
$page_title = 'Create Account';
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/models/User.php';

// Already logged in? No reason to see the registration form.
if (is_logged_in()) {
    header('Location: ' . BASE_URL . (is_admin() ? 'admin.php' : 'index.php'));
    exit;
}

$form_errors = [];
$old = ['username' => '', 'email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $form_errors['general'] = 'Your session expired. Please try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = (string)($_POST['password'] ?? '');
        $confirm  = (string)($_POST['confirm_password'] ?? '');

        $old = ['username' => $username, 'email' => $email];

        if (strlen($username) < 3 || strlen($username) > 50) {
            $form_errors['username'] = 'Username must be between 3 and 50 characters.';
        } elseif (!preg_match('/^[A-Za-z0-9_.\-]+$/', $username)) {
            $form_errors['username'] = 'Username can only contain letters, numbers, dots, dashes and underscores.';
        } elseif (User::usernameExists($username)) {
            $form_errors['username'] = 'That username is already taken.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $form_errors['email'] = 'Please enter a valid email address.';
        } elseif (User::emailExists($email)) {
            $form_errors['email'] = 'An account with that email already exists.';
        }

        if (strlen($password) < 8) {
            $form_errors['password'] = 'Password must be at least 8 characters.';
        } elseif ($password !== $confirm) {
            $form_errors['confirm_password'] = 'Passwords do not match.';
        }

        if (empty($form_errors)) {
            $userId = User::create($username, $email, $password, 'client');
            $user   = User::findById($userId);
            login_user($user);
            flash_set('success', 'Welcome to Richenia, ' . $username . '.');
            header('Location: ' . BASE_URL . 'index.php');
            exit;
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="auth-page">
  <div class="auth-card">
    <p class="eyebrow">Richenia</p>
    <h1>Create your account</h1>
    <p class="auth-sub">Track your orders and check out faster next time.</p>

    <?php if (!empty($form_errors['general'])): ?>
      <div class="form-error form-error-banner"><?php echo htmlspecialchars($form_errors['general']); ?></div>
    <?php endif; ?>

    <form method="post" novalidate class="auth-form">
      <?php echo csrf_field(); ?>

      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($old['username']); ?>" required>
        <?php if (!empty($form_errors['username'])): ?><p class="form-error"><?php echo htmlspecialchars($form_errors['username']); ?></p><?php endif; ?>
      </div>

      <div class="form-group">
        <label for="email">Email address</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($old['email']); ?>" required>
        <?php if (!empty($form_errors['email'])): ?><p class="form-error"><?php echo htmlspecialchars($form_errors['email']); ?></p><?php endif; ?>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required minlength="8">
          <?php if (!empty($form_errors['password'])): ?><p class="form-error"><?php echo htmlspecialchars($form_errors['password']); ?></p><?php endif; ?>
        </div>
        <div class="form-group">
          <label for="confirm_password">Confirm password</label>
          <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
          <?php if (!empty($form_errors['confirm_password'])): ?><p class="form-error"><?php echo htmlspecialchars($form_errors['confirm_password']); ?></p><?php endif; ?>
        </div>
      </div>

      <button type="submit" class="btn-submit">Create Account</button>
    </form>

    <p class="auth-switch">Already have an account? <a href="<?php echo BASE_URL; ?>login.php">Sign in</a></p>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
