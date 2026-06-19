<?php
/**
 * includes/auth.php
 *
 * Session-based authentication & role management.
 * $_SESSION['user'] holds only the minimal identity needed on every
 * page (id, username, email, role) — never the password hash.
 *
 * Require this file after includes/session.php (header.php already
 * loads session.php, so just require this on top of that).
 */
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/flash.php';

/** @return array|null The logged-in user's session data, or null if a guest. */
function current_user()
{
    return $_SESSION['user'] ?? null;
}

/** @return bool */
function is_logged_in()
{
    return isset($_SESSION['user']['id']);
}

/** @return bool */
function is_admin()
{
    return is_logged_in() && $_SESSION['user']['role'] === 'admin';
}

/**
 * Stores the authenticated user in the session and rotates the
 * session ID to prevent session fixation attacks.
 *
 * @param array $userRow A row from the `users` table (must include
 *                        id, username, email, role).
 */
function login_user(array $userRow)
{
    session_regenerate_id(true);
    $_SESSION['user'] = [
        'id'       => (int)$userRow['id'],
        'username' => $userRow['username'],
        'email'    => $userRow['email'],
        'role'     => $userRow['role'],
    ];
}

/** Destroys the authenticated session (cart and flash messages are preserved). */
function logout_user()
{
    unset($_SESSION['user']);
    session_regenerate_id(true);
}

/**
 * Redirects guests to the login page, preserving where they were
 * trying to go via a `redirect` query parameter.
 */
function require_login()
{
    if (!is_logged_in()) {
        $current = basename($_SERVER['SCRIPT_NAME']);
        $qs      = $_SERVER['QUERY_STRING'] ?? '';
        $target  = $current . ($qs !== '' ? '?' . $qs : '');
        header('Location: ' . BASE_URL . 'login.php?redirect=' . urlencode($target));
        exit;
    }
}

/**
 * Guards admin-only pages. Guests are sent to login; logged-in
 * clients are bounced back to the storefront with a flash notice.
 */
function require_admin()
{
    if (!is_logged_in()) {
        header('Location: ' . BASE_URL . 'login.php?redirect=admin.php');
        exit;
    }
    if (!is_admin()) {
        flash_set('error', 'You do not have permission to view that page.');
        header('Location: ' . BASE_URL . 'index.php');
        exit;
    }
}

/* =========================================================
   CSRF protection
   ========================================================= */

/** @return string The current CSRF token, generating one if needed. */
function csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/** @return string A ready-to-echo hidden input carrying the CSRF token. */
function csrf_field()
{
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token()) . '">';
}

/** @return bool Whether the supplied token matches the session's token. */
function verify_csrf($token)
{
    return isset($_SESSION['csrf_token']) && is_string($token) && hash_equals($_SESSION['csrf_token'], $token);
}
