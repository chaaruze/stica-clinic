<?php require APPROOT . '/views/layouts/header.php'; ?>
<?php require APPROOT . '/views/layouts/navbar.php'; ?>
<?php require APPROOT . '/views/layouts/sidebar.php'; ?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-sti-blue text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-sync-alt me-2"></i>System Update</h5>
                </div>
                <div class="card-body text-center py-5">
                    <div id="updateStatusIcon" class="mb-4">
                        <i class="fas fa-cloud-download-alt fa-5x text-muted"></i>
                    </div>
                    
                    <h4 id="updateStatusTitle" class="fw-bold mb-3">Check for System Updates</h4>
                    <p id="updateStatusDesc" class="text-muted mb-4">
                        Keep your STICA Clinic system up to date with the latest features and security fixes.
                    </p>

                    <div id="terminalOutput" class="alert alert-secondary text-start d-none mb-4 font-monospace" style="max-height: 300px; overflow-y: auto; white-space: pre-wrap; font-size: 0.85rem;"></div>

                    <div class="d-grid gap-2 d-md-block">
                        <button id="checkBtn" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-search me-2"></i>Check for Updates
                        </button>
                        <button id="updateBtn" class="btn btn-success btn-lg px-5 d-none">
                            <i class="fas fa-download me-2"></i>Update Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layouts/footer.php'; ?>

<script>
    $(document).ready(function() {
        const checkBtn = $('#checkBtn');
        const updateBtn = $('#updateBtn');
        const icon = $('#updateStatusIcon i');
        const title = $('#updateStatusTitle');
        const desc = $('#updateStatusDesc');
        const terminal = $('#terminalOutput');

        function setIdle() {
            icon.attr('class', 'fas fa-cloud-download-alt fa-5x text-muted');
            title.text('Check for System Updates');
            desc.text('Keep your STICA Clinic system up to date.');
            checkBtn.show().prop('disabled', false).html('<i class="fas fa-search me-2"></i>Check for Updates');
            updateBtn.addClass('d-none');
        }

        checkBtn.click(function() {
            // Loading state
            checkBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Checking...');
            terminal.addClass('d-none').text('');

            $.ajax({
                url: '<?= URLROOT ?>/system/checkForUpdates', // Ensure route exists
                method: 'GET',
                dataType: 'json',
                success: function(res) {
                    terminal.removeClass('d-none').text(res.details);
                    
                    if (res.status === 'available') {
                        icon.attr('class', 'fas fa-exclamation-circle fa-5x text-warning');
                        title.text('Update Available!');
                        desc.text('A new version of the system is available.');
                        checkBtn.hide();
                        updateBtn.removeClass('d-none');
                    } else if (res.status === 'uptodate') {
                        icon.attr('class', 'fas fa-check-circle fa-5x text-success');
                        title.text('System is Up to Date');
                        desc.text('You are running the latest version.');
                        checkBtn.prop('disabled', false).html('<i class="fas fa-redo me-2"></i>Check Again');
                    } else {
                        icon.attr('class', 'fas fa-times-circle fa-5x text-danger');
                        title.text('Status Unknown');
                        desc.text(res.message);
                        checkBtn.prop('disabled', false).html('<i class="fas fa-search me-2"></i>Check for Updates');
                    }
                },
                error: function(err) {
                    console.error(err);
                    terminal.removeClass('d-none').text('Error connecting to server.');
                    checkBtn.prop('disabled', false).html('<i class="fas fa-search me-2"></i>Retry');
                }
            });
        });

        updateBtn.click(function() {
            Swal.fire({
                title: 'Update System?',
                text: "This will pull the latest code and reload the page.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update now!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Update state
                    updateBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Updating...');
                    
                    $.ajax({
                        url: '<?= URLROOT ?>/system/performUpdate',
                        method: 'POST',
                        dataType: 'json',
                        success: function(res) {
                            terminal.removeClass('d-none').text(terminal.text() + "\n\n" + res.output);
                            
                            if (res.status === 'success') {
                                Swal.fire(
                                    'Updated!',
                                    'System updated successfully.',
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Error', res.message, 'error');
                                updateBtn.prop('disabled', false).html('<i class="fas fa-download me-2"></i>Update Now');
                            }
                        },
                        error: function(err) {
                            console.error(err);
                            Swal.fire('Error', 'Update failed. Check console.', 'error');
                            updateBtn.prop('disabled', false).html('<i class="fas fa-download me-2"></i>Update Now');
                        }
                    });
                }
            });
        });
    });
</script>
