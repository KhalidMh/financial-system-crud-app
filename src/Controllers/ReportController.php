<?php

namespace App\Controllers;

use App\Models\Transaction;

class ReportController
{
    private $transactionModel;

    public function __construct()
    {
        $this->transactionModel = new Transaction();
    }

    /**
     * Display the financial reports page.
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
