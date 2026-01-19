<?php require APPROOT . '/views/layouts/header.php'; ?>
<?php require APPROOT . '/views/layouts/navbar.php'; ?>
<?php require APPROOT . '/views/layouts/sidebar.php'; ?>

<!-- DataTables CSS (Potentially conflicting, re-adding at bottom or relying on generic) -->
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" /> -->

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
    .input-group.custom-search-container {
        border: 1px solid #ced4da;
        border-radius: 5px;
        overflow: hidden;
    }
    .input-group.custom-search-container:focus-within {
        border-color: var(--sti-blue);
        box-shadow: none;
    }
    .input-group.custom-search-container .input-group-text,
    .input-group.custom-search-container .form-control {
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
    
    /* Pagination Alignment - Bottom Right */
    .dataTables_wrapper .dataTables_paginate {
        float: right;
        margin-top: 1rem;
    }
    .dataTables_wrapper .dataTables_info {
        float: left;
        margin-top: 1rem;
        padding-top: 0.5rem;
    }
    
    /* Center 'No data available' */
    td.dataTables_empty {
        text-align: center !important;
    }
</style>

<div class="container-fluid mt-4">
    <!-- Header Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0 fw-bold"><i class="fas fa-pills me-2"></i>Medicine Inventory</h5>
            <button class="btn btn-success fw-bold text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#addMedModal">
                <i class="fas fa-plus-circle me-1"></i> Add Medicine
            </button>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card shadow-sm">
        <div class="card-body position-relative" style="min-height: 500px;">
            <!-- Toolbar: Search & Length adjacent -->
            <div class="row justify-content-center mb-4" style="position: relative; z-index: 2;">
                <div class="col-md-8 d-flex align-items-center justify-content-center gap-3">
                    <!-- Search Bar -->
                    <div class="input-group custom-search-container" style="max-width: 500px;">
                         <span class="input-group-text bg-white"><i class="fas fa-search custom-search-icon"></i></span>
                         <input type="text" id="customSearch" class="form-control" placeholder="Search Medicine, Stock, or Status...">
                    </div>
                    
                    <!-- Length Menu Container -->
                    <div class="custom-length-container">
                        <!-- DataTables length will be moved here -->
                    </div>
                </div>
            </div>

            <!-- Watermark Logo -->
            <div style="position: absolute; top: 65%; left: 50%; transform: translate(-50%, -50%); opacity: 0.15; z-index: 0; pointer-events: none;">
                <img src="<?= URLROOT ?>/assets/images/logo.png" alt="Watermark" style="width: 400px; filter: grayscale(100%);">
            </div>
        
            <div style="position: relative; z-index: 1;">
                <table class="table table-hover align-middle mb-0 w-100" id="medsTable">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="py-3">Medicine Name</th>
                            <th class="py-3">Unit</th>
                            <th class="py-3">Stock Level</th>
                            <th class="py-3">Expiration</th>
                            <th class="py-3">Status</th>
                            <th class="py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['medicines'] as $med): ?>
                            <?php 
                                $statusClass = 'success';
                                $statusText = 'In Stock';
                                $statusBadge = 'bg-success';
                                $stockClass = 'text-dark';
                                
                                if ($med->stock <= 0) {
                                    $statusBadge = 'bg-danger';
                                    $stockClass = 'text-danger fw-bold';
                                    $statusText = 'Out of Stock';
                                } elseif ($med->stock <= 10) {
                                    $statusBadge = 'bg-warning text-dark';
                                    $stockClass = 'text-warning fw-bold';
                                    $statusText = 'Low Stock';
                                }
                                
                                $isExpired = false;
                                if ($med->expiration_date) {
                                    $expiry = new DateTime($med->expiration_date);
                                    $now = new DateTime();
                                    $days = $now->diff($expiry)->format('%r%a');
                                    
                                    if ($days < 0) {
                                        $isExpired = true;
                                        $statusBadge = 'bg-dark';
                                        $statusText = 'Expired';
                                    } elseif ($days < 30 && $med->stock > 0) {
                                        $statusBadge = 'bg-info text-dark';
                                        $statusText = 'Expiring Soon';
                                    }
                                }
                            ?>
                            <tr>
                                <td class="fw-bold"><?= htmlspecialchars($med->name) ?></td>
                                <td class="text-muted"><?= htmlspecialchars($med->unit) ?></td>
                                <td class="<?= $stockClass ?>"><?= $med->stock ?></td>
                                <td class="<?= $isExpired ? 'text-danger fw-bold' : '' ?>">
                                    <?php if ($med->expiration_date): ?>
                                        <?= date('M d, Y', strtotime($med->expiration_date)) ?>
                                    <?php else: ?>
                                        <span class="text-muted small">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td><span class="badge <?= $statusBadge ?>"><?= $statusText ?></span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary me-1" onclick='editMed(<?= json_encode($med) ?>)'>
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteMed(<?= $med->id ?>)">
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

<!-- Modals Moved to Bottom -->
<!-- Add Modal -->
<div class="modal fade" id="addMedModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-sti-blue text-white">
                <h5 class="modal-title fw-bold">Add Medicine</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="addMedForm">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Medicine Name</label>
                        <input type="text" class="form-control" name="name" required placeholder="e.g. Paracetamol">
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">Unit</label>
                            <input type="text" class="form-control" name="unit" required placeholder="e.g. Tablet">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">Initial Stock</label>
                            <input type="number" class="form-control" name="stock" required min="0" value="0">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Expiration Date (Optional)</label>
                        <input type="date" class="form-control" name="expiration_date">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold">SAVE MEDICINE</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editMedModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">Update Medicine</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="editMedForm">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Medicine Name</label>
                        <input type="text" class="form-control" name="name" id="edit_name" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">Unit</label>
                            <input type="text" class="form-control" name="unit" id="edit_unit" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">Stock</label>
                            <input type="number" class="form-control" name="stock" id="edit_stock" required min="0">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Expiration Date</label>
                        <input type="date" class="form-control" name="expiration_date" id="edit_expiry">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold">UPDATE MEDICINE</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layouts/footer.php'; ?>

<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        var table = $('#medsTable').DataTable();
        
        // Move DataTables Length
        $('.dataTables_length').appendTo('.custom-length-container');

        // Connect Custom Search
        $('#customSearch').on('keyup', function() {
            table.search(this.value).draw();
        });
        
        // Match table styling inputs
        $('div.dataTables_filter input').addClass('form-control');
        $('div.dataTables_length select').addClass('form-select');
        
        // Add Logic
        $('#addMedForm').on('submit', function(e) {
            e.preventDefault();
            const formArgs = $(this).serialize();
            
            // Show loading
            Swal.fire({title: 'Saving...', didOpen: () => Swal.showLoading()});
            
            $.post('<?= URLROOT ?>/medicines/add', formArgs)
             .done(function(res) {
                try {
                    const data = JSON.parse(res);
                    if(data.status === 'success') {
                        $('#addMedModal').modal('hide');
                        Swal.fire('Success', 'Medicine added successfully!', 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Error', data.message || 'Failed to add medicine', 'error');
                    }
                } catch(e) {
                    console.error("Parse error:", e, res);
                    Swal.fire('Error', 'Invalid server response. Check console.', 'error');
                }
             })
             .fail(function() {
                 Swal.fire('Error', 'Network error occurred', 'error');
             });
        });

        // Edit Logic
        $('#editMedForm').on('submit', function(e) {
            e.preventDefault();
            const formArgs = $(this).serialize();

             // Show loading
            Swal.fire({title: 'Updating...', didOpen: () => Swal.showLoading()});

            $.post('<?= URLROOT ?>/medicines/update', formArgs)
             .done(function(res) {
                 try {
                     const data = JSON.parse(res);
                     if(data.status === 'success') {
                         $('#editMedModal').modal('hide');
                         Swal.fire('Success', 'Medicine updated successfully!', 'success')
                             .then(() => location.reload());
                     } else {
                         Swal.fire('Error', data.message || 'Failed to update medicine', 'error');
                     }
                 } catch(e) {
                     console.error("Parse error:", e, res);
                     Swal.fire('Error', 'Invalid server response. Check console.', 'error');
                 }
             })
             .fail(function() {
                 Swal.fire('Error', 'Network error occurred', 'error');
             });
        });
    });

    function editMed(med) {
        $('#edit_id').val(med.id);
        $('#edit_name').val(med.name);
        $('#edit_unit').val(med.unit);
        $('#edit_stock').val(med.stock);
        $('#edit_expiry').val(med.expiration_date);
        $('#editMedModal').modal('show');
    }

    function deleteMed(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= URLROOT ?>/medicines/delete/' + id,
                    type: 'DELETE',
                    success: function(res) {
                         try {
                            const data = JSON.parse(res);
                            if(data.status === 'success') {
                                Swal.fire('Deleted!', 'Medicine has been deleted.', 'success').then(() => location.reload());
                            } else {
                                Swal.fire('Error', 'Failed to delete.', 'error');
                            }
                         } catch(e) {
                             location.reload(); // Fallback
                         }
                    }
                });
            }
        });
    }
</script>
