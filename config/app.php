<?php
/**
 * config/app.php
 *
 * Auto-detects the project's base URL so assets and AJAX calls work
 * regardless of how deep the project sits inside htdocs.
 *
 * Example path:  C:\xampp\htdocs\Project_Shop\richenia\index.php
 * SCRIPT_NAME:   /Project_Shop/richenia/index.php
 * BASE_URL:       /Project_Shop/richenia/
 *
 * You never need to edit this file — it adapts automatically.
 */

if (!defined('BASE_URL')) {
    // Normalize backslashes (Windows) and strip the filename
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    // Ensure it always ends with exactly one slash
    define('BASE_URL', rtrim($scriptDir, '/') . '/');
}
