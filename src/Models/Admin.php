<?php

namespace App\Models;

class Admin
{
    private $db;

    /**
     * Constructor - Initialize the Admin model with database connection
     */
    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Authenticate an admin using username and password
     *
     * @param string $username The admin's username
     * @param string $password The plain text password to verify
     * @return array|null Returns admin data array without password if authentication successful, null otherwise
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
     *
     * @param int $id The admin's unique identifier
     * @return array|null Returns admin data array (id, username, email, created_at) if found, null otherwise
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
