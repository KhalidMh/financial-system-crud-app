<?php view('layouts/header', ['title' => 'Reports - Financial System']); ?>

<div class="row">
    <div class="col-12">
        <h1 class="mb-4">Financial Reports</h1>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>Filter Reports</h5>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" 
                               value="<?= htmlspecialchars($_GET['start_date'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" 
                               value="<?= htmlspecialchars($_GET['end_date'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <a href="/reports" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Clear
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h3>$<?= number_format($totalIncome, 2) ?></h3>
                <p>Total Income</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <h3>$<?= number_format($totalExpenses, 2) ?></h3>
                <p>Total Expenses</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-<?= $totalBalance >= 0 ? 'info' : 'warning' ?> text-white">
            <div class="card-body text-center">
                <h3>$<?= number_format($totalBalance, 2) ?></h3>
                <p>Net Balance</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>Client Summary</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="reportsTable">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Transactions</th>
                                <th>Total Income</th>
                                <th>Total Expenses</th>
                                <th>Balance</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reportData as $row): ?>
                            <tr>
                                <td>
                                    <a href="/clients/<?= $row['client_id'] ?>">
                                        <?= htmlspecialchars($row['client_name']) ?>
                                    </a>
                                </td>
                                <td><?= $row['total_transactions'] ?></td>
                                <td class="text-success">$<?= number_format($row['total_income'], 2) ?></td>
                                <td class="text-danger">$<?= number_format($row['total_expenses'], 2) ?></td>
                                <td class="<?= $row['balance'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                    $<?= number_format($row['balance'], 2) ?>
                                </td>
                                <td>
                                    <a href="/transactions?client_id=<?= $row['client_id'] ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-list"></i> View Transactions
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php view('layouts/footer'); ?>