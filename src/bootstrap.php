<?php

// Autoload classes
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// Prevents client-side JavaScript from accessing session cookies.
ini_set('session.cookie_httponly', 1);
// Prevents PHP from accepting uninitialized session IDs
ini_set('session.use_strict_mode', 1);

// Load environment variables from .env file
// Handle both Docker and XAMPP environments gracefully
try {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
} catch (Exception $e) {
    // If .env file doesn't exist, continue with default values
    // This allows the app to work with default database configuration
    error_log("Environment file not found, using default configuration: " . $e->getMessage());
}

// Start the session
session_start();
