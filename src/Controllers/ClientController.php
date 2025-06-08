<?php

namespace App\Controllers;

use App\Models\Client;

class ClientController
{
    private $clientModel;

    /**
     * Constructor - Initialize the client model
     */
    public function __construct()
    {
        $this->clientModel = new Client();
    }

    /**
     * Display a listing of all clients
     *
     * Fetches all clients from the database and adds balance information
     * to each client before rendering the index view.
     *
     * @return void
     */
    public function index(): void
    {
        $clients = $this->clientModel->findAll();

        foreach ($clients as &$c) {
            $c['balance'] = $this->clientModel->getClientBalance($c['id']);
        }

        view('clients/index', ['clients' => $clients]);
    }

    /**
     * Display a specific client
     *
     * Finds a client by ID and displays their details including balance.
     * Redirects to clients list if client is not found.
     *
     * @param int $id The client ID
     * @return void
     */
    public function show(int $id): void
    {
        $client = $this->clientModel->findById($id);

        if (!$client) {
            $_SESSION['error'] = 'Client not found';
            header('Location: /clients');
            exit;
        }

        $client['balance'] = $this->clientModel->getClientBalance($id);

        view('clients/view', ['client' => $client]);
    }

    /**
     * Show form for creating a new client or handle client creation
     *
     * On GET request: displays the create form
     * On POST request: validates and creates a new client, then redirects
     *
     * @return void
     */
    public function create(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'address' => $_POST['address'] ?? ''
            ];

            if ($this->validateClientData($data)) {
                $clientId = $this->clientModel->create($data);
                $_SESSION['success'] = 'Client created successfully';
                header("Location: /clients/{$clientId}");
                exit;
            }
        }

        view('clients/create');
    }

    /**
     * Show form for editing a client or handle client update
     *
     * On GET request: displays the edit form with existing client data
     * On POST request: validates and updates the client, then redirects
     *
     * @param int $id The client ID to edit
     * @return void
     */
    public function edit(int $id): void
    {
        $client = $this->clientModel->findById($id);

        if (!$client) {
            $_SESSION['error'] = 'Client not found';
            header('Location: /clients');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'address' => $_POST['address'] ?? ''
            ];

            if ($this->validateClientData($data)) {
                $this->clientModel->update($id, $data);
                $_SESSION['success'] = 'Client updated successfully';
                header("Location: /clients/{$id}");
                exit;
            }
        }

        view('clients/edit', ['client' => $client]);
    }

    /**
     * Delete a specific client
     *
     * Finds the client by ID and deletes them from the database.
     * Sets success or error message and redirects to clients list.
     *
     * @param int $id The client ID to delete
     * @return void
     */
    public function delete(int $id): void
    {
        $client = $this->clientModel->findById($id);

        if (!$client) {
            $_SESSION['error'] = 'Client not found';
            header('Location: /clients');
            exit;
        }

        if ($this->clientModel->delete($id)) {
            $_SESSION['success'] = 'Client deleted successfully';
        } else {
            $_SESSION['error'] = 'Failed to delete client';
        }

        header('Location: /clients');
        exit;
    }

    /**
     * Validate client data
     *
     * Validates required fields and email format for client data.
     * Sets validation errors in session if validation fails.
     *
     * @param array $data The client data to validate
     * @return bool True if validation passes, false otherwise
     */
    private function validateClientData(array $data): bool
    {
        $errors = [];

        if (empty($data['name'])) {
            $errors[] = 'Name is required';
        }

        if (empty($data['email'])) {
            $errors[] = 'Email is required';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            return false;
        }

        return true;
    }
}
