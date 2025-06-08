<?php $title = 'Add Transaction - Financial System'; ?>
<?php view('layouts/header', ['title' => $title]); ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Add New Transaction</h1>
            <a href="/transactions<?= $clientId ? '?client_id=' . $clientId : '' ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Transactions
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="client_id" class="form-label">Client *</label>
                        <select class="form-select" id="client_id" name="client_id" required>
                            <option value="">Select a client</option>
                            <?php foreach ($clients as $client): ?>
                            <option value="<?= $client['id'] ?>" 
                                    <?= ($clientId == $client['id'] || ($_POST['client_id'] ?? '') == $client['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($client['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Type *</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="">Select type</option>
                            <option value="income" <?= ($_POST['type'] ?? '') === 'income' ? 'selected' : '' ?>>Income</option>
                            <option value="expense" <?= ($_POST['type'] ?? '') === 'expense' ? 'selected' : '' ?>>Expense</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount *</label>
                        <input type="number" step="0.01" min="0" class="form-control" id="amount" name="amount" required 
                               value="<?= htmlspecialchars($_POST['amount'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="transaction_date" class="form-label">Date *</label>
                        <input type="date" class="form-control" id="transaction_date" name="transaction_date" required 
                               value="<?= htmlspecialchars($_POST['transaction_date'] ?? date('Y-m-d')) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Create Transaction
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php view('layouts/footer'); ?>