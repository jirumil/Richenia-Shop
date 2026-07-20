<?php
/**
 * config/app.php
 *
 * BASE_URL is fixed to root ('/') because the app is served from the
 * domain root on Vercel via the api/index.php front controller — there's
 * no nested folder path to auto-detect anymore, unlike the old XAMPP
 * setup where the project could sit at /Project_Shop/richenia/.
 */

if (!defined('BASE_URL')) {
    define('BASE_URL', '/');
}

// --- Database-backed sessions ---
// Must run before any output and before session_start() is called
// anywhere else in the app. This file is required first by every page,
// so this is the right place for it.
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/DbSessionHandler.php';

if (session_status() === PHP_SESSION_NONE) {
    session_set_save_handler(new DbSessionHandler(Database::getConnection()), true);
    session_start();
}