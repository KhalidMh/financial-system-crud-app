<?php

namespace App\Models;

use PDO;
use PDOException;

class Database
{
    private static $instance = null;
    private $connection;
    private $config;

    /**
     * Private constructor to prevent direct instantiation.
     * Loads database configuration and establishes connection.
     */
    private function __construct()
    {
        $this->config = require __DIR__ . '/../../config/database.php';
        $this->connect();
    }

    /**
     * Get the singleton instance of the Database class.
     * Creates a new instance if one doesn't exist, otherwise returns the existing instance.
     *
     * @return self The Database singleton instance
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Establishes a connection to the MySQL database.
     * Uses PDO with the configuration loaded from database.php
     *
     * @throws PDOException If database connection fails
     * @return void
     */
    private function connect(): void
    {
        try {
            $dsn = "mysql:host={$this->config['host']};dbname={$this->config['dbname']};charset={$this->config['charset']}";

            $this->connection = new PDO(
                $dsn,
                $this->config['username'],
                $this->config['password'],
                $this->config['options']
            );
        } catch (PDOException $e) {
            throw new PDOException("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * Execute a prepared SQL statement with optional parameters.
     * Prepares the statement, binds parameters, and executes it.
     *
     * @param string $sql The SQL query to execute
     * @param array $params Optional array of parameters to bind to the statement
     * @return \PDOStatement The prepared and executed statement
     * @throws PDOException If the query preparation or execution fails
     */
    public function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }

    /**
     * Get the ID of the last inserted record.
     *
     * @return string The ID of the last inserted record
     */
    public function lastInsertId(): string
    {
        return $this->connection->lastInsertId();
    }
}
