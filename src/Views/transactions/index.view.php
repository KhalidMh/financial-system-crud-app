<?php view('layouts/header', ['title' => 'Transactions - Financial System']); ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                Transactions
                <?php if ($client): ?>
                    - <?= htmlspecialchars($client['name']) ?>
                <?php endif; ?>
            </h1>
            <div>
                <a href="/transactions/create<?= $client ? '?client_id=' . $client['id'] : '' ?>" class="btn btn-success">
                    <i class="fas fa-plus"></i> Add Transaction
                </a>
                <?php if ($client): ?>
                    <a href="/clients/<?= $client['id'] ?>" class="btn btn-info">
                        <i class="fas fa-user"></i> View Client
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="transactionsTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Client</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $transaction): ?>
                            <tr>
                                <td><?= date('M d, Y', strtotime($transaction['transaction_date'])) ?></td>
                                <td>
                                    <a href="/clients/<?= $transaction['client_id'] ?>">
                                        <?= htmlspecialchars($transaction['client_name']) ?>
                                    </a>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $transaction['type'] === 'income' ? 'success' : 'danger' ?>">
                                        <i class="fas fa-<?= $transaction['type'] === 'income' ? 'arrow-up' : 'arrow-down' ?>"></i>
                                        <?= ucfirst($transaction['type']) ?>
                                    </span>
                                </td>
                                <td class="text-<?= $transaction['type'] === 'income' ? 'success' : 'danger' ?>">
                                    $<?= number_format($transaction['amount'], 2) ?>
                                </td>
                                <td><?= htmlspecialchars($transaction['description'] ?: '-') ?></td>
                                <td>
                                    <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $transaction['id'] ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
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

<script>
function confirmDelete(transactionId) {
    if (confirm('Are you sure you want to delete this transaction?')) {
        window.location.href = '/transactions/' + transactionId + '/delete';
    }
}
</script>

<?php view('layouts/footer'); ?>