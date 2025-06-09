<?php

namespace App;

use App\Middleware\AuthMiddleware;
use App\Controllers\AuthController;
use App\Controllers\ClientController;
use App\Controllers\ReportController;
use App\Controllers\TransactionController;

class Router
{
    private string $request;
    private string $path;
    private string $method;

    public function __construct()
    {
        $this->request = $_SERVER['REQUEST_URI'];
        $this->path = parse_url($this->request, PHP_URL_PATH);
        $this->method = $_SERVER['REQUEST_METHOD'];

        // Remove trailing slash
        $this->path = rtrim($this->path, '/');
        if (empty($this->path)) {
            $this->path = '/';
        }
    }

    /**
     * Route the request to the appropriate controller and method.
     *
     * @return void
     */
    public function route(): void
    {
        try {
            switch ($this->path) {
                case '/login':
                    $controller = new AuthController();
                    $this->method === 'POST' ? $controller->login() : $controller->showLogin();
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

                case (preg_match('/^\/clients\/(\d+)$/', $this->path, $matches) ? true : false):
                    AuthMiddleware::requireAuth();
                    $controller = new ClientController();
                    $controller->show((int)$matches[1]);
                    break;

                case (preg_match('/^\/clients\/(\d+)\/edit$/', $this->path, $matches) ? true : false):
                    AuthMiddleware::requireAuth();
                    $controller = new ClientController();
                    $controller->edit((int)$matches[1]);
                    break;

                case (preg_match('/^\/clients\/(\d+)\/delete$/', $this->path, $matches) ? true : false):
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

                case (preg_match('/^\/transactions\/(\d+)\/delete$/', $this->path, $matches) ? true : false):
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
        } catch (\Exception $e) {
            error_log($e->getMessage());
            http_response_code(500);
            echo '<h1>500 - Internal Server Error</h1>';
            if (ini_get('display_errors')) {
                echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
            }
        }
    }
}
