<?php

namespace App\Middleware;

class AuthMiddleware
{
    /**
     * Requires user authentication to access protected routes.
     *
     * @return void
     */
    public static function requireAuth(): void
    {
        if (!isset($_SESSION['admin_id'])) {
            $_SESSION['error'] = 'You must be logged in to access this page.';
            header('Location: /login');
            exit;
        }
    }

    /**
     * Redirects authenticated users away from auth-related pages.
     *
     * @return void
     */
    public static function redirectIfAuthenticated(): void
    {
        if (isset($_SESSION['admin_id'])) {
            header('Location: /clients');
            exit;
        }
    }
}
