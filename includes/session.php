<?php
/**
 * Starts the PHP session exactly once per request.
 * Every entry point (pages + api endpoints) requires this
 * before any HTML/JSON is echoed.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
