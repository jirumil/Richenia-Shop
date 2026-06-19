<?php
/**
 * includes/flash.php
 *
 * Tiny one-time flash message helper, used after redirects (e.g. after
 * login, after an admin action) to show a single banner that clears
 * itself once read — the classic Post/Redirect/Get pattern.
 */
require_once __DIR__ . '/session.php';

/**
 * @param string $type  'success' | 'error' | 'info'
 * @param string $message
 */
function flash_set($type, $message)
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

/**
 * Reads and clears the pending flash message, if any.
 * @return array{type:string,message:string}|null
 */
function flash_get()
{
    if (empty($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

/** Echoes the flash banner markup (if a message is pending). Call inside <main>. */
function flash_render()
{
    $flash = flash_get();
    if (!$flash) {
        return;
    }
    $type = htmlspecialchars($flash['type']);
    $msg  = htmlspecialchars($flash['message']);
    echo "<div class=\"flash-banner flash-{$type}\" role=\"alert\">{$msg}</div>";
}
