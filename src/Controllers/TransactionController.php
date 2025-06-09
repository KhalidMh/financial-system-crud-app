<?php

namespace App\Controllers;

use App\Models\Transaction;
use App\Models\Client;

class TransactionController
{
    private $transactionModel;
    private $clientModel;

    public function __construct()
    {
        $this->transactionModel = new Transaction();
        $this->clientModel = new Client();
    }

    /**
     * Display transactions index page.
     *
     * Shows all transactions or filters by client_id if provided in query parameters.
     * Loads the transactions index view with the retrieved data.
     *
     * @return void
     */
    public function index(): void
    {
        $clientId = $_GET['client_id'] ?? null;

        if ($clientId) {
            $transactions = $this->transactionModel->findByClient($clientId);
            $client = $this->clientModel->findById($clientId);
        } else {
            $transactions = $this->transactionModel->findAll();
            $client = null;
        }

        view('transactions/index', ['transactions' => $transactions, 'client' => $client]);
    }

    /**
     * Handle transaction creation.
     *
     * Displays the create transaction form and processes form submission.
     * Validates submitted data and creates a new transaction if valid.
     * Redirects to transactions list on successful creation.
     *
     * @return void
     */
    public function create(): void
    {
        $clients = $this->clientModel->findAll();
        $clientId = $_GET['client_id'] ?? null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'client_id' => $_POST['client_id'] ?? '',
                'type' => $_POST['type'] ?? '',
                'amount' => $_POST['amount'] ?? '',
                'description' => $_POST['description'] ?? '',
                'transaction_date' => $_POST['transaction_date'] ?? date('Y-m-d')
            ];

            if ($this->validateTransactionData($data)) {
                $transactionId = $this->transactionModel->create($data);
                $_SESSION['success'] = 'Transaction created successfully';
                header("Location: /transactions?client_id={$data['client_id']}");
                exit;
            }
        }

        view('transactions/create', ['clients' => $clients, 'clientId' => $clientId]);
    }

    /**
     * Delete a transaction by ID.
     *
     * Finds the transaction by ID and deletes it from the database.
     * Sets appropriate success or error messages and redirects to transactions list.
     *
     * @param int $id The ID of the transaction to delete
     * @return void
     */
    public function delete(int $id): void
    {
        $transaction = $this->transactionModel->findById($id);

        if (!$transaction) {
            $_SESSION['error'] = 'Transaction not found';
            header('Location: /transactions');
            exit;
        }

        if ($this->transactionModel->delete($id)) {
            $_SESSION['success'] = 'Transaction deleted successfully';
        } else {
            $_SESSION['error'] = 'Failed to delete transaction';
        }

        $clientId = $transaction['client_id'];
        header("Location: /transactions?client_id={$clientId}");
        exit;
    }

    /**
     * Validate transaction form data.
     *
     * Performs comprehensive validation on transaction data including:
     * - Client ID presence
     * - Transaction type (income/expense)
     * - Amount (numeric and positive)
     * - Transaction date presence
     *
     * Sets validation errors in session if any are found.
     *
     * @param array $data The transaction data to validate
     * @return bool True if validation passes, false otherwise
     */
    private function validateTransactionData(array $data): bool
    {
        $errors = [];

        if (empty($data['client_id'])) {
            $errors[] = 'Client is required';
        }

        if (empty($data['type']) || !in_array($data['type'], ['income', 'expense'])) {
            $errors[] = 'Valid transaction type is required';
        }

        if (empty($data['amount']) || !is_numeric($data['amount']) || $data['amount'] <= 0) {
            $errors[] = 'Valid amount is required';
        }

        if (empty($data['transaction_date'])) {
            $errors[] = 'Transaction date is required';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            return false;
        }

        return true;
    }
}
