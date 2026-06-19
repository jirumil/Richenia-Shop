<?php
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/includes/auth.php';

logout_user();
flash_set('success', 'You have been signed out.');
header('Location: ' . BASE_URL . 'index.php');
exit;
