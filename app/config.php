<?php
declare(strict_types=1);

// Load Composer autoloader and environment variables
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
    if (file_exists(__DIR__ . '/../.env')) {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->safeLoad();
    }
}

const APP_NAME = "SalonEase";

// Database configuration with environment variable fallbacks
define("DB_HOST", $_ENV["DB_HOST"] ?? "127.0.0.1");
define("DB_NAME", $_ENV["DB_NAME"] ?? "salonease");
define("DB_USER", $_ENV["DB_USER"] ?? "root");
define("DB_PASS", $_ENV["DB_PASS"] ?? "");
define("DB_CHARSET", $_ENV["DB_CHARSET"] ?? "utf8mb4");
