<?php
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/models/Coupon.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf($_POST['csrf_token'] ?? '')) {
    flash_set('error', 'Invalid request. Please try again.');
    header('Location: ' . BASE_URL . 'admin.php?tab=coupons');
    exit;
}

$coupon_id = isset($_POST['coupon_id']) ? (int)$_POST['coupon_id'] : 0;
$coupon    = $coupon_id > 0 ? Coupon::find($coupon_id) : null;

if ($coupon) {
    Coupon::toggleActive($coupon_id);
    flash_set('success', 'Coupon "' . $coupon['code'] . '" ' . ((int)$coupon['active'] === 1 ? 'deactivated' : 'activated') . '.');
} else {
    flash_set('error', 'That coupon no longer exists.');
}

header('Location: ' . BASE_URL . 'admin.php?tab=coupons');
exit;
