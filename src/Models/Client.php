<?php

namespace App\Models;

class Client
{
    private $db;

    /**
     * Constructor - Initialize the Client model with database connection
     */
    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Retrieve all clients from the database
     *
     * @return array Array of all client records ordered by name ascending
     */
    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM clients ORDER BY name ASC");

        return $stmt->fetchAll();
    }

    /**
     * Find a client by their ID
     *
     * @param int $id The client's unique identifier
     * @return array|null Returns client data array if found, null otherwise
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->query(
            "SELECT * FROM clients WHERE id = ?",
            [$id]
        );

        return $stmt->fetch() ?: null;
    }

    /**
     * Create a new client in the database
     *
     * @param array $data Client data containing name, email, phone, and address
     * @return int The ID of the newly created client
     */
    public function create(array $data): int
    {
        $stmt = $this->db->query(
            "INSERT INTO clients (name, email, phone, address) VALUES (?, ?, ?, ?)",
            [$data['name'], $data['email'], $data['phone'], $data['address']]
        );

        return (int) $this->db->lastInsertId();
    }

    /**
     * Update an existing client's information
     *
     * @param int $id The client's unique identifier
     * @param array $data Updated client data containing name, email, phone, and address
     * @return bool True if the client was updated successfully, false otherwise
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->query(
            "UPDATE clients SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?",
            [$data['name'], $data['email'], $data['phone'], $data['address'], $id]
        );

        return $stmt->rowCount() > 0;
    }

    /**
     * Delete a client from the database
     *
     * @param int $id The client's unique identifier
     * @return bool True if the client was deleted successfully, false otherwise
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->query("DELETE FROM clients WHERE id = ?", [$id]);

        return $stmt->rowCount() > 0;
    }

    /**
     * Calculate the current balance for a client based on their transactions
     *
     * Balance is calculated as: sum of income transactions minus sum of expense transactions
     *
     * @param int $clientId The client's unique identifier
     * @return float The client's current balance (positive for credit, negative for debt)
     */
    public function getClientBalance(int $clientId): float
    {
        $stmt = $this->db->query(
            "SELECT 
                COALESCE(SUM(CASE WHEN type = 'income' THEN amount ELSE -amount END), 0) as balance
            FROM transactions 
            WHERE client_id = ?",
            [$clientId]
        );

        $result = $stmt->fetch();

        return (float) $result['balance'];
    }
}
