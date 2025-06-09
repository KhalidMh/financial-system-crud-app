<?php

namespace App\Models;

class Admin
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Authenticate an admin using username and password
     */
    public function authenticate(string $username, string $password): ?array
    {
        $stmt = $this->db->query(
            "SELECT * FROM admins WHERE username = ?",
            [$username]
        );

        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            unset($admin['password']);

            return $admin;
        }

        return null;
    }

    /**
     * Find an admin by their ID
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->query(
            "SELECT id, username, email, created_at FROM admins WHERE id = ?",
            [$id]
        );

        return $stmt->fetch() ?: null;
    }
}
