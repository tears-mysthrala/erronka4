<?php

declare(strict_types=1);

// Define ROOT_PATH constant for tests
define('ROOT_PATH', dirname(__DIR__));

// Load Composer autoloader
require_once ROOT_PATH . '/vendor/autoload.php';

// Load environment variables for testing
$dotenv = Dotenv\Dotenv::createImmutable(ROOT_PATH);
$dotenv->safeLoad();

// Set testing environment
$_ENV['APP_ENV'] = 'testing';

// Initialize test database if needed
// You can add database seeding or test data initialization here
