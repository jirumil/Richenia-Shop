<?php
/**
 * admin_coupons_save.php
 */
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/models/Coupon.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf($_POST['csrf_token'] ?? '')) {
    flash_set('error', 'Invalid request. Please try again.');
    header('Location: ' . BASE_URL . 'admin.php?tab=coupons');
    exit;
}

$code      = trim($_POST['code'] ?? '');
$type      = ($_POST['type'] ?? '') === 'fixed' ? 'fixed' : 'percentage';
$value     = $_POST['value'] ?? '';
$max_uses  = trim($_POST['max_uses'] ?? '');
$expires   = trim($_POST['expires_at'] ?? '');

$errors = [];
if ($code === '' || !preg_match('/^[A-Za-z0-9_-]{3,50}$/', $code)) {
    $errors[] = 'Code must be 3-50 letters, numbers, dashes or underscores.';
} elseif (Coupon::codeExists(strtoupper($code))) {
    $errors[] = 'A coupon with that code already exists.';
}
if (!is_numeric($value) || (float)$value <= 0) {
    $errors[] = 'Value must be a positive number.';
} elseif ($type === 'percentage' && (float)$value > 100) {
    $errors[] = 'A percentage discount cannot exceed 100.';
}

if (!empty($errors)) {
    flash_set('error', implode(' ', $errors));
    header('Location: ' . BASE_URL . 'admin.php?tab=coupons');
    exit;
}

Coupon::create($code, $type, (float)$value, $max_uses !== '' ? (int)$max_uses : null, $expires !== '' ? $expires : null);
flash_set('success', 'Coupon "' . strtoupper($code) . '" created.');

header('Location: ' . BASE_URL . 'admin.php?tab=coupons');
exit;
