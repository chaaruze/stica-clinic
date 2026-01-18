<?php require APPROOT . '/views/layouts/header.php'; ?>
<?php require APPROOT . '/views/layouts/navbar.php'; ?>
<?php require APPROOT . '/views/layouts/sidebar.php'; ?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />

<div class="container-fluid mt-4">
    <!-- Topbar/Header -->
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="mb-0 fw-bold text-sti-blue"><i class="fas fa-clipboard-list me-2"></i>Audit Logs</h2>
            <p class="text-muted mb-0">Track system usage and user activities.</p>
        </div>
        <div class="col-auto text-end">
            <div class="text-muted small">
                <i class="fas fa-clock me-1"></i> <?= date('l, F j, Y') ?>
            </div>
        </div>
    </div>

    <!-- Logs Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="logsTable">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th>Date/Time</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Details</th>
                            <th>IP Address</th>
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
                                <td><span class="font-monospace small"><?= $log->ip_address ?></span></td>
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
        $('#logsTable').DataTable({
            order: [[0, 'desc']], // Order by Date desc
            pageLength: 25,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search logs..."
            }
        });
        
        // Match table styling
        $('div.dataTables_filter input').addClass('form-control');
        $('div.dataTables_length select').addClass('form-select');
    });
</script>

<?php require APPROOT . '/views/layouts/footer.php'; ?>
