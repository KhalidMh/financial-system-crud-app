<?php

namespace App\Models;

class Transaction
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Find all transactions with client information
     *
     * @return array Array of all transactions with joined client names, ordered by date descending
     */
    public function findAll(): array
    {
        $stmt = $this->db->query(
            "SELECT t.*, c.name as client_name 
            FROM transactions t 
            JOIN clients c ON t.client_id = c.id 
            ORDER BY t.transaction_date DESC"
        );

        return $stmt->fetchAll();
    }

    /**
     * Find all transactions for a specific client
     *
     * @param int $clientId The ID of the client to find transactions for
     * @return array Array of transactions for the specified client with client name, ordered by date descending
     */
    public function findByClient(int $clientId): array
    {
        $stmt = $this->db->query(
            "SELECT t.*, c.name as client_name 
            FROM transactions t 
            JOIN clients c ON t.client_id = c.id 
            WHERE t.client_id = ? 
            ORDER BY t.transaction_date DESC",
            [$clientId]
        );

        return $stmt->fetchAll();
    }

    /**
     * Find a single transaction by its ID
     *
     * @param int $id The ID of the transaction to find
     * @return array|null Transaction data with client name, or null if not found
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->query(
            "SELECT t.*, c.name as client_name 
            FROM transactions t 
            JOIN clients c ON t.client_id = c.id 
            WHERE t.id = ?",
            [$id]
        );

        return $stmt->fetch() ?: null;
    }

    /**
     * Create a new transaction
     *
     * @param array $data Transaction data containing:
     *                    - client_id: int - The ID of the client
     *                    - type: string - Transaction type ('income' or 'expense')
     *                    - amount: float - Transaction amount
     *                    - description: string - Transaction description
     *                    - transaction_date: string - Transaction date (Y-m-d format)
     * @return int The ID of the newly created transaction
     */
    public function create(array $data): int
    {
        $stmt = $this->db->query(
            "INSERT INTO transactions (client_id, type, amount, description, transaction_date) 
            VALUES (?, ?, ?, ?, ?)",
            [$data['client_id'], $data['type'], $data['amount'], $data['description'], $data['transaction_date']]
        );

        return (int) $this->db->lastInsertId();
    }

    /**
     * Update an existing transaction
     *
     * @param int $id The ID of the transaction to update
     * @param array $data Transaction data containing:
     *                    - client_id: int - The ID of the client
     *                    - type: string - Transaction type ('income' or 'expense')
     *                    - amount: float - Transaction amount
     *                    - description: string - Transaction description
     *                    - transaction_date: string - Transaction date (Y-m-d format)
     * @return bool True if the transaction was updated successfully, false otherwise
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->query(
            "UPDATE transactions 
            SET client_id = ?, type = ?, amount = ?, description = ?, transaction_date = ? 
            WHERE id = ?",
            [$data['client_id'], $data['type'], $data['amount'], $data['description'], $data['transaction_date'], $id]
        );

        return $stmt->rowCount() > 0;
    }

    /**
     * Delete a transaction by its ID
     *
     * @param int $id The ID of the transaction to delete
     * @return bool True if the transaction was deleted successfully, false otherwise
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->query("DELETE FROM transactions WHERE id = ?", [$id]);

        return $stmt->rowCount() > 0;
    }

    /**
     * Get report data with client financial summaries
     *
     * @param string|null $startDate Optional start date filter (Y-m-d format)
     * @param string|null $endDate Optional end date filter (Y-m-d format)
     * @return array Array of client report data containing:
     *               - client_id: int - Client ID
     *               - client_name: string - Client name
     *               - total_transactions: int - Total number of transactions
     *               - total_income: float - Sum of all income transactions
     *               - total_expenses: float - Sum of all expense transactions
     *               - balance: float - Net balance (income - expenses)
     */
    public function getReportData(?string $startDate = null, ?string $endDate = null): array
    {
        $sql = "SELECT 
                    c.id as client_id,
                    c.name as client_name,
                    COUNT(t.id) as total_transactions,
                    COALESCE(SUM(CASE WHEN t.type = 'income' THEN t.amount ELSE 0 END), 0) as total_income,
                    COALESCE(SUM(CASE WHEN t.type = 'expense' THEN t.amount ELSE 0 END), 0) as total_expenses,
                    COALESCE(SUM(CASE WHEN t.type = 'income' THEN t.amount ELSE -t.amount END), 0) as balance
                FROM clients c
                LEFT JOIN transactions t ON c.id = t.client_id";

        $params = [];

        if ($startDate && $endDate) {
            $sql .= " WHERE t.transaction_date BETWEEN ? AND ?";
            $params = [$startDate, $endDate];
        } elseif ($startDate) {
            $sql .= " WHERE t.transaction_date >= ?";
            $params = [$startDate];
        } elseif ($endDate) {
            $sql .= " WHERE t.transaction_date <= ?";
            $params = [$endDate];
        }

        $sql .= " GROUP BY c.id, c.name ORDER BY c.name";

        $stmt = $this->db->query($sql, $params);

        return $stmt->fetchAll();
    }
}
