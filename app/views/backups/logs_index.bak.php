<?php require APPROOT . '/views/layouts/header.php'; ?>
<?php require APPROOT . '/views/layouts/navbar.php'; ?>
<?php require APPROOT . '/views/layouts/sidebar.php'; ?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />

<style>
    /* Custom Override for DataTables to match STI Theme */
    .page-item.active .page-link {
        background-color: var(--sti-blue) !important;
        border-color: var(--sti-blue) !important;
    }

    .page-link {
        color: var(--sti-blue);
    }

    thead th {
        background-color: var(--sti-blue) !important;
        color: white !important;
        text-transform: uppercase;
        font-size: 0.9rem;
        letter-spacing: 0.5px;
    }

    .card-header {
        background-color: var(--sti-blue);
        border-bottom: 2px solid var(--sti-yellow);
        color: white;
    }

    /* Hide Default DataTables Search */
    .dataTables_filter {
        display: none;
    }
    
    /* Custom Search Bar Styles */
    .custom-search-container .input-group {
        border: 1px solid #ced4da;
        border-radius: 5px;
        overflow: hidden;
    }
    .custom-search-container .input-group:focus-within {
        border-color: var(--sti-blue);
        box-shadow: none;
    }
    .custom-search-container .input-group-text,
    .custom-search-container .form-control {
        border: none;
    }
    .custom-search-icon {
        color: var(--sti-blue);
    }
    .form-control:focus {
        box-shadow: none;
    }
    
    /* Fix Show Entries Alignment */
    .dataTables_length label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
        margin-bottom: 0;
    }
    .dataTables_length select {
        width: auto !important;
        display: inline-block;
    }
</style>

<div class="container-fluid mt-4">
    <!-- Combined Card: Header + Table -->
    <div class="card shadow-sm">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0 fw-bold"><i class="fas fa-clipboard-list me-2"></i>Audit Logs</h5>
            <div class="text-white-50 small">
                <i class="fas fa-clock me-1"></i> <?= date('l, F j, Y') ?>
            </div>
        </div>
        
        <div class="card-body position-relative" style="min-height: 500px;">

            <!-- Watermark Logo -->
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); opacity: 0.15; z-index: 0; pointer-events: none;">
                <img src="<?= URLROOT ?>/assets/images/logo.png" alt="Watermark" style="width: 400px; filter: grayscale(100%);">
            </div>

            <div class="table-responsive" style="position: relative; z-index: 1;">
                <table class="table table-hover align-middle mb-0" id="logsTable">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="py-3">Date/Time</th>
                            <th class="py-3">User</th>
                            <th class="py-3">Action</th>
                            <th class="py-3">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['logs'] as $log): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold"><?= date('M d, Y', strtotime($log->created_at)) ?></div>
                                    <small class="text-muted"><?= date('h:i A', strtotime($log->created_at)) ?></small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle-sm bg-primary text-white me-2 d-flex align-items-center justify-content-center rounded-circle" style="width: 32px; height: 32px;">
                                            <?= strtoupper(substr($log->user_name, 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold"><?= $log->user_name ?></div>
                                            <small class="text-muted">ID: <?= $log->user_id ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php
                                        $badgeClass = 'bg-secondary';
                                        if (stripos($log->action, 'Add') !== false) $badgeClass = 'bg-success';
                                        if (stripos($log->action, 'Update') !== false) $badgeClass = 'bg-info text-dark';
                                        if (stripos($log->action, 'Delete') !== false) $badgeClass = 'bg-danger';
                                        if (stripos($log->action, 'Import') !== false) $badgeClass = 'bg-warning text-dark';
                                        if (stripos($log->action, 'Login') !== false) $badgeClass = 'bg-primary';
                                    ?>
                                    <span class="badge rounded-pill <?= $badgeClass ?>"><?= $log->action ?></span>
                                </td>
                                <td><?= $log->details ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Helper script for DataTable -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        var table = $('#logsTable').DataTable({
            order: [[0, 'desc']], // Order by Date desc
            pageLength: 25
        });
        
        // Match table styling
        $('div.dataTables_filter input').addClass('form-control');
        $('div.dataTables_length select').addClass('form-select');
    });
</script>

<?php require APPROOT . '/views/layouts/footer.php'; ?>
