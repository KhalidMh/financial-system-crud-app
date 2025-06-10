<?php view('layouts/header', ['title' => 'Edit Client - Financial System']); ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Edit Client</h1>
            <div>
                <a href="/clients/<?= $client['id'] ?>" class="btn btn-info">
                    <i class="fas fa-eye"></i> View Client
                </a>
                <a href="/clients" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Clients
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name *</label>
                        <input type="text" class="form-control" id="name" name="name" required 
                               value="<?= htmlspecialchars($_POST['name'] ?? $client['name']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="email" name="email" required 
                               value="<?= htmlspecialchars($_POST['email'] ?? $client['email']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" 
                               value="<?= htmlspecialchars($_POST['phone'] ?? $client['phone']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3"><?= htmlspecialchars($_POST['address'] ?? $client['address']) ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Client
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php view('layouts/footer'); ?>