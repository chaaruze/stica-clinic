<?php require APPROOT . '/views/layouts/header.php'; ?>
<?php require APPROOT . '/views/layouts/navbar.php'; ?>
<?php require APPROOT . '/views/layouts/sidebar.php'; ?>

<div class="container-fluid mt-4">
    <div class="row">
        <!-- Error Logs Card -->
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>System Error Logs</h5>
                    <button id="clearLogsBtn" class="btn btn-sm btn-light text-danger fw-bold">
                        <i class="fas fa-trash-alt me-1"></i>Clear Logs
                    </button>
                </div>
                <div class="card-body p-0">
                    <textarea class="form-control border-0 font-monospace p-3" 
                              style="height: 400px; resize: none; background-color: #f8f9fa; font-size: 0.85rem;" 
                              readonly><?= htmlspecialchars($data['logs']) ?></textarea>
                </div>
                <div class="card-footer text-muted small">
                    Path: <?= APPROOT . '/logs/error.log' ?>
                </div>
            </div>
        </div>

        <!-- System Actions Card -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-server me-2"></i>Database Maintenance</h5>
                </div>
                <div class="card-body text-center py-4">
                    <i class="fas fa-database fa-4x text-success mb-3 opacity-50"></i>
                    <p class="card-text text-muted mb-4">
                        Optimize your database tables to reclaim unused space and defragment data files for maximum performance.
                    </p>
                    <button id="optimizeBtn" class="btn btn-success w-100">
                        <i class="fas fa-magic me-2"></i>Optimize Database
                    </button>
                </div>
            </div>

            <!-- Quick Info -->
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>System Info</h5>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">PHP Version</span>
                        <span class="fw-bold"><?= phpversion() ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Server Software</span>
                        <span class="fw-bold"><?= $_SERVER['SERVER_SOFTWARE'] ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">App Environment</span>
                        <span class="fw-bold">Production</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layouts/footer.php'; ?>

<script>
    $(document).ready(function() {
        // Clear Logs
        $('#clearLogsBtn').click(function() {
            Swal.fire({
                title: 'Clear Error Logs?',
                text: "This will permanently delete all log entries.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, clear it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.get('<?= URLROOT ?>/maintenance/clear_logs', function(data) {
                        const res = JSON.parse(data);
                        if(res.status === 'success') {
                            Swal.fire('Cleared!', res.message, 'success')
                                .then(() => location.reload());
                        }
                    });
                }
            });
        });

        // Optimize DB
        $('#optimizeBtn').click(function() {
             const btn = $(this);
             btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Optimizing...');
             
             $.get('<?= URLROOT ?>/maintenance/optimize_db', function(data) {
                 const res = JSON.parse(data);
                 btn.prop('disabled', false).html('<i class="fas fa-magic me-2"></i>Optimize Database');
                 
                 if(res.status === 'success') {
                     Swal.fire('Success!', res.message, 'success');
                 } else {
                     Swal.fire('Error!', res.message, 'error');
                 }
             }).fail(function() {
                 btn.prop('disabled', false).html('<i class="fas fa-magic me-2"></i>Optimize Database');
                 Swal.fire('Error!', 'Request failed', 'error');
             });
        });
    });
</script>
