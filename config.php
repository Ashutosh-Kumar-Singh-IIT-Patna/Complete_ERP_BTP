<?php
// config.php

// Load .env file
function load_env($path) {
    if (!file_exists($path)) {
        throw new Exception(".env file not found");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        list($key, $value) = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}

// Load environment variables from .env
load_env(__DIR__ . '/.env');

// Define database connection constants
define('DB_HOST', $_ENV['DB_HOST']);
define('DB_USERNAME', $_ENV['DB_USERNAME']);
define('DB_PASSWORD', $_ENV['DB_PASSWORD']);
define('DB_NAME', $_ENV['DB_NAME']);
define('DB_PORT', $_ENV['DB_PORT']);
define('LOG_FILE_PATH', $_ENV['LOG_FILE_PATH']);

// Database connection function
function db_connect() {
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);

    if ($conn->connect_error) {
        error_log("Connection failed: " . $conn->connect_error, 3, LOG_FILE_PATH);
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn; 
}
?>
