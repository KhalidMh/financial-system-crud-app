<?php view('layouts/header', ['title' => 'Clients - Financial System']); ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Clients</h1>
            <a href="/clients/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Client
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="clientsTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Balance</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($clients as $client): ?>
                            <tr>
                                <td><?= htmlspecialchars($client['name']) ?></td>
                                <td><?= htmlspecialchars($client['email']) ?></td>
                                <td><?= htmlspecialchars($client['phone'] ?? '-') ?></td>
                                <td class="<?= $client['balance'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                    $<?= number_format($client['balance'], 2) ?>
                                </td>
                                <td><?= date('M d, Y', strtotime($client['created_at'])) ?></td>
                                <td>
                                    <a href="/clients/<?= $client['id'] ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="/clients/<?= $client['id'] ?>/edit" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $client['id'] ?>)">
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
function confirmDelete(clientId) {
    if (confirm('Are you sure you want to delete this client? All associated transactions will also be deleted.')) {
        window.location.href = '/clients/' + clientId + '/delete';
    }
}
</script>

<?php view('layouts/footer'); ?>