<?php

namespace App\Middleware;

class AuthMiddleware
{
    /**
     * Requires user authentication to access protected routes.
     *
     * Checks if the user is authenticated by verifying the presence of 'admin_id'
     * in the session. If not authenticated, redirects to the login page.
     *
     * @return void
     */
    public static function requireAuth(): void
    {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: /login');
            exit;
        }
    }

    /**
     * Redirects authenticated users away from auth-related pages.
     *
     * Checks if the user is already authenticated by verifying the presence of
     * 'admin_id' in the session. If authenticated, redirects to the clients.
     * This prevents authenticated users from accessing login/register pages.
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
