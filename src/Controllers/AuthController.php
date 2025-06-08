<?php

namespace App\Controllers;

use App\Models\Admin;

class AuthController
{
    private $adminModel;

    public function __construct()
    {
        $this->adminModel = new Admin();
    }

    /**
     * Display the login form
     *
     * Checks if an admin is already logged in and redirects to clients if so.
     * Otherwise, displays the login form view.
     *
     * @return void
     */
    public function showLogin(): void
    {
        if (isset($_SESSION['admin_id'])) {
            header('Location: /clients');
            exit;
        }

        view('auth/login');
    }

    /**
     * Process admin login request
     *
     * Validates the request method, extracts username and password from POST data,
     * authenticates the admin using the Admin model, and sets session variables
     * on successful authentication. Redirects to appropriate pages based on result.
     *
     * @return void
     */
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $_SESSION['error'] = 'Username and password are required';
            header('Location: /login');
            exit;
        }

        $admin = $this->adminModel->authenticate($username, $password);

        if ($admin) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header('Location: /clients');
        } else {
            $_SESSION['error'] = 'Invalid credentials';
            header('Location: /login');
        }

        exit;
    }

    /**
     * Log out the current admin user
     *
     * Destroys the current session to log out the admin user
     * and redirects to the login page.
     *
     * @return void
     */
    public function logout(): void
    {
        session_destroy();
        header('Location: /login');

        exit;
    }
}
