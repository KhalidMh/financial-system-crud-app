<?php

// Autoload classes
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// Prevents client-side JavaScript from accessing session cookies.
ini_set('session.cookie_httponly', 1);
// Prevents PHP from accepting uninitialized session IDs
ini_set('session.use_strict_mode', 1);

// Load environment variables from .env file
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Start the session
session_start();
