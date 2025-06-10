<?php

require_once __DIR__ . '/../../src/bootstrap.php';

use App\Models\Transaction;

// set the content type to JSON
header('Content-Type: application/json');

// Check authentication
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

try {
    $transactionModel = new Transaction();

    $clientId = $_GET['client_id'] ?? null;
    $limit = (int)($_GET['limit'] ?? 50);
    $offset = (int)($_GET['offset'] ?? 0);

    if ($clientId) {
        $transactions = $transactionModel->findByClient((int)$clientId);
    } else {
        $transactions = $transactionModel->findAll();
    }

    // Apply limit and offset
    $transactions = array_slice($transactions, $offset, $limit);

    echo json_encode([
        'success' => true,
        'data' => $transactions,
        'count' => count($transactions)
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred while fetching transactions',
    ]);
}
