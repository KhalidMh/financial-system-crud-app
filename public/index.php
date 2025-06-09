<?php

// Autoload classes
require_once __DIR__ . '/../vendor/autoload.php';

use App\Router;
use Dotenv\Dotenv;

// Start the session
session_start();

// Load environment variables from .env file
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Initialize and run the router
$router = new Router();
$router->route();
