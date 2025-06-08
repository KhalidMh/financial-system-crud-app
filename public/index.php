<?php

session_start();

// Autoload classes
require_once __DIR__ . '/../vendor/autoload.php';

// Load environment variables from .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

use App\Middleware\AuthMiddleware;
use App\Controllers\AuthController;
use App\Controllers\ClientController;
use App\Controllers\ReportController;
use App\Controllers\TransactionController;

// Simple router
$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Remove trailing slash
$path = rtrim($path, '/');
if (empty($path)) {
    $path = '/';
}

try {
    switch ($path) {
        case '/login':
            $controller = new AuthController();
            $method === 'POST' ? $controller->login() : $controller->showLogin();
            break;

        case '/logout':
            $controller = new AuthController();
            $controller->logout();
            break;

        case '/':
        case '/clients':
            AuthMiddleware::requireAuth();
            $controller = new ClientController();
            $controller->index();
            break;

        case '/clients/create':
            AuthMiddleware::requireAuth();
            $controller = new ClientController();
            $controller->create();
            break;

        case (preg_match('/^\/clients\/(\d+)$/', $path, $matches) ? true : false):
            AuthMiddleware::requireAuth();
            $controller = new ClientController();
            $controller->show((int)$matches[1]);
            break;

        case (preg_match('/^\/clients\/(\d+)\/edit$/', $path, $matches) ? true : false):
            AuthMiddleware::requireAuth();
            $controller = new ClientController();
            $controller->edit((int)$matches[1]);
            break;

        case (preg_match('/^\/clients\/(\d+)\/delete$/', $path, $matches) ? true : false):
            AuthMiddleware::requireAuth();
            $controller = new ClientController();
            $controller->delete((int)$matches[1]);
            break;

        case '/transactions':
            AuthMiddleware::requireAuth();
            $controller = new TransactionController();
            $controller->index();
            break;

        case '/transactions/create':
            AuthMiddleware::requireAuth();
            $controller = new TransactionController();
            $controller->create();
            break;

        case (preg_match('/^\/transactions\/(\d+)\/delete$/', $path, $matches) ? true : false):
            AuthMiddleware::requireAuth();
            $controller = new TransactionController();
            $controller->delete((int)$matches[1]);
            break;

        case '/reports':
            AuthMiddleware::requireAuth();
            $controller = new ReportController();
            $controller->index();
            break;

        default:
            http_response_code(404);
            echo '<h1>404 - Page Not Found</h1>';
            break;
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo '<h1>500 - Internal Server Error</h1>';
    if (ini_get('display_errors')) {
        echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
    }
}
