<?php
/**
 * Application Configuration
 * General application settings
 */

// Application settings
define('APP_NAME', 'PDPA Assessment System');
define('APP_VERSION', '1.0.0');

// Security settings
define('SESSION_LIFETIME', 3600); // 1 hour in seconds

// Paths
define('BASE_PATH', dirname(__DIR__));
define('PUBLIC_PATH', BASE_PATH . '/public');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set timezone
date_default_timezone_set('Asia/Bangkok');
