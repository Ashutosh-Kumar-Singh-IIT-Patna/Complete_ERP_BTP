<?php
// config.php

// Database Configuration
define('DB_HOST', 'mysql'); 
define('DB_USER', 'your_user'); 
define('DB_PASS', 'your_password'); 
define('DB_NAME', 'your_database');

// CSRF Token Storage and Initialization
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Error Reporting Configuration
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/php_error.log');

// Log File Path
define('LOG_FILE', __DIR__ . '/../logs/log.txt');
?>
