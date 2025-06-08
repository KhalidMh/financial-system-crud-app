<?php

namespace App\Controllers;

use App\Models\Transaction;

class ReportController
{
    private $transactionModel;

    /**
     * Initialize the ReportController with required models.
     *
     * Creates an instance of the Transaction model for generating
     * financial reports and retrieving report data.
     */
    public function __construct()
    {
        $this->transactionModel = new Transaction();
    }

    /**
     * Display the financial reports index page.
     *
     * Generates and displays comprehensive financial reports for all clients
     * with optional date range filtering. Calculates totals across all clients
     * including total income, expenses, and net balance.
     *
     * Accepts optional query parameters:
     * - start_date: Filter transactions from this date (Y-m-d format)
     * - end_date: Filter transactions up to this date (Y-m-d format)
     *
     * The report data includes per-client summaries with:
     * - Client ID and name
     * - Total number of transactions
     * - Total income amount
     * - Total expenses amount
     * - Net balance (income - expenses)
     *
     * Also calculates system-wide totals for display in the report view.
     *
     * @return void
     */
    public function index(): void
    {
        $startDate = $_GET['start_date'] ?? null;
        $endDate = $_GET['end_date'] ?? null;

        $reportData = $this->transactionModel->getReportData($startDate, $endDate);

        // Calculate totals
        $totalIncome = array_sum(array_column($reportData, 'total_income'));
        $totalExpenses = array_sum(array_column($reportData, 'total_expenses'));
        $totalBalance = $totalIncome - $totalExpenses;

        view('reports/index', [
            'reportData' => $reportData,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses,
            'totalBalance' => $totalBalance
        ]);
    }
}
