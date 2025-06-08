<?php $title = $client['name'] . ' - Financial System'; ?>
<?php view('layouts/header', ['title' => $title]); ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><?= htmlspecialchars($client['name']) ?></h1>
            <div>
                <a href="/transactions/create?client_id=<?= $client['id'] ?>" class="btn btn-success">
                    <i class="fas fa-plus"></i> Add Transaction
                </a>
                <a href="/clients/<?= $client['id'] ?>/edit" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="/clients" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Clients
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Client Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> <?= htmlspecialchars($client['name']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($client['email']) ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($client['phone'] ?? 'N/A') ?></p>
                <p><strong>Address:</strong> <?= htmlspecialchars($client['address'] ?? 'N/A') ?></p>
                <p><strong>Created:</strong> <?= date('M d, Y', strtotime($client['created_at'])) ?></p>
                <hr>
                <p><strong>Current Balance:</strong> 
                    <span class="<?= $client['balance'] >= 0 ? 'text-success' : 'text-danger' ?>">
                        $<?= number_format($client['balance'], 2) ?>
                    </span>
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Recent Transactions</h5>
            </div>
            <div class="card-body">
                <div id="clientTransactions">
                    Loading transactions...
                </div>
                <div class="mt-3">
                    <a href="/transactions?client_id=<?= $client['id'] ?>" class="btn btn-primary">
                        View All Transactions
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    loadClientTransactions(<?= $client['id'] ?>);
});

function loadClientTransactions(clientId) {
    $.get('/api/movements.php?client_id=' + clientId + '&limit=5', function(data) {
        let html = '';
        if (data.success && data.data.length > 0) {
            html = '<div class="table-responsive"><table class="table table-sm">';
            html += '<thead><tr><th>Date</th><th>Type</th><th>Amount</th><th>Description</th></tr></thead><tbody>';
            
            data.data.forEach(function(transaction) {
                let typeClass = transaction.type === 'income' ? 'success' : 'danger';
                let typeIcon = transaction.type === 'income' ? 'arrow-up' : 'arrow-down';
                
                html += '<tr>';
                html += '<td>' + new Date(transaction.transaction_date).toLocaleDateString() + '</td>';
                html += '<td><span class="badge bg-' + typeClass + '"><i class="fas fa-' + typeIcon + '"></i> ' + transaction.type.charAt(0).toUpperCase() + transaction.type.slice(1) + '</span></td>';
                html += '<td class="text-' + typeClass + '">$' + parseFloat(transaction.amount).toFixed(2) + '</td>';
                html += '<td>' + (transaction.description || '-') + '</td>';
                html += '</tr>';
            });
            html += '</tbody></table></div>';
        } else {
            html = '<p class="text-muted">No transactions found for this client.</p>';
        }
        $('#clientTransactions').html(html);
    });
}
</script>

<?php view('layouts/footer'); ?>
