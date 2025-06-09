<?php

require_once __DIR__ . '/../bootstrap.php';

use App\Router;

// Initialize and run the router
$router = new Router();
$router->route();
