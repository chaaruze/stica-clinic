<?php require APPROOT . '/views/layouts/header.php'; ?>
<?php require APPROOT . '/views/layouts/navbar.php'; ?>
<?php require APPROOT . '/views/layouts/sidebar.php'; ?>

<div class="container-fluid mt-4">
    <div class="row">
        <!-- Employee Profile Card -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header text-white text-center py-4" style="background-color: var(--sti-blue);">
                    <div class="mb-3">
                        <i class="fas fa-user-tie fa-4x border rounded-circle p-3 bg-white text-sti-blue"></i>
                    </div>
                    <h4 class="fw-bold mb-0">
                        <?= $data['employee']->{'last name'} . ', ' . $data['employee']->{'first name'} . ' ' . $data['employee']->{'middle name'} ?>
                    </h4>
                    <p class="mb-0 opacity-75">Employee ID:
                        <?= $data['employee']->{'employee number'} ?>
                    </p>
                </div>
                <div class="card-body position-relative">
                    <!-- Watermark Logo -->
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); opacity: 0.15; z-index: 0; pointer-events: none;">
                        <img src="<?= URLROOT ?>/assets/images/logo.png" alt="Watermark" style="width: 200px; filter: grayscale(100%);">
                    </div>

                    <div style="position: relative; z-index: 1;">
                        <h5 class="text-sti-blue fw-bold mb-3"><i class="fas fa-info-circle me-2"></i>Personal Info</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Birthday</span>
                            <span class="fw-bold">
                                <?= !empty($data['employee']->birthdate) ? date('F j, Y', strtotime($data['employee']->birthdate)) : 'N/A' ?>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Gender</span>
                            <span class="fw-bold">
                                <?= isset($data['employee']->sex) ? $data['employee']->sex : 'N/A' ?>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Contact</span>
                            <span class="fw-bold">
                                <?= isset($data['employee']->{'phone number'}) ? $data['employee']->{'phone number'} : 'N/A' ?>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Position</span>
                            <span class="fw-bold">
                                <?= isset($data['employee']->position) ? $data['employee']->position : 'N/A' ?>
                            </span>
                        </li>
                    </ul>

                    <div class="mt-4 d-grid gap-2">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editEmployeeModal">
                            <i class="fas fa-edit me-2"></i>Edit Personal Info
                        </button>
                        <a href="<?= URLROOT ?>/employees" class="btn btn-outline-secondary"><i
                                class="fas fa-arrow-left me-2"></i>Back to List</a>
                    </div>
                    </div> <!-- Closing position-relative wrapper -->
                </div>
            </div>
        </div>

        <!-- Visit History -->
        <div class="col-md-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-sti-blue text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="fas fa-history me-2"></i>Clinic Visit History
                    </h5>
                    <div>
                        <button class="btn btn-sm btn-danger me-2 d-none" id="deleteSelectedBtn">
                            <i class="fas fa-trash me-1"></i>Delete Selected (<span id="selectedCount">0</span>)
                        </button>
                        <button class="btn btn-sm btn-warning"
                            onclick="window.open('<?= URLROOT ?>/visits/active/Employee/<?= $data['employee']->{'employee number'} ?>', '_blank')">
                            <i class="fas fa-plus me-1"></i>Add Record
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="historyTable">
                            <thead class="bg-light text-secondary">
                                <tr>
                                    <th class="ps-4" style="width: 40px;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAllHistory">
                                        </div>
                                    </th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Reason / Diagnosis</th>
                                    <th>Treatment</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($data['history'])): ?>
                                    <?php foreach ($data['history'] as $visit): ?>
                                        <tr style="cursor: pointer;">
                                            <td class="ps-4" onclick="event.stopPropagation()">
                                                <div class="form-check">
                                                    <input class="form-check-input history-checkbox" type="checkbox" 
                                                           value="<?= $visit->{'date visit'} ?>|<?= $visit->{'time visit'} ?>">
                                                </div>
                                            </td>
                                            <td class="fw-bold text-sti-blue"
                                                onclick="window.open('<?= URLROOT ?>/visits/details/Employee/<?= $data['employee']->{'employee number'} ?>?date=<?= $visit->{'date visit'} ?>&time=<?= $visit->{'time visit'} ?>', '_blank')">
                                                <?= date('M d, Y', strtotime($visit->{'date visit'})) ?>
                                            </td>
                                            <td onclick="window.open('<?= URLROOT ?>/visits/details/Employee/<?= $data['employee']->{'employee number'} ?>?date=<?= $visit->{'date visit'} ?>&time=<?= $visit->{'time visit'} ?>', '_blank')">
                                                <?= date('h:i A', strtotime($visit->{'time visit'})) ?>
                                            </td>
                                            <td onclick="window.open('<?= URLROOT ?>/visits/details/Employee/<?= $data['employee']->{'employee number'} ?>?date=<?= $visit->{'date visit'} ?>&time=<?= $visit->{'time visit'} ?>', '_blank')">
                                                <?= $visit->{'reason / diagnosis'} ?? 'N/A' ?>
                                            </td>
                                            <td onclick="window.open('<?= URLROOT ?>/visits/details/Employee/<?= $data['employee']->{'employee number'} ?>?date=<?= $visit->{'date visit'} ?>&time=<?= $visit->{'time visit'} ?>', '_blank')">
                                                <?= $visit->treatment ?? 'N/A' ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="fas fa-folder-open fa-2x mb-3 opacity-50"></i><br>
                                            No visit history found.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Employee Modal -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-sti-blue text-white">
                <h5 class="modal-title">Edit Personal Info</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editEmployeeForm">
                    <input type="hidden" name="original_employee_number" value="<?= $data['employee']->{'employee number'} ?>">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Employee Number</label>
                        <input type="text" class="form-control" name="employee_number" value="<?= $data['employee']->{'employee number'} ?>">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Last Name</label>
                            <input type="text" class="form-control" name="last_name" value="<?= $data['employee']->{'last name'} ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">First Name</label>
                            <input type="text" class="form-control" name="first_name" value="<?= $data['employee']->{'first name'} ?? '' ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Middle Name</label>
                        <input type="text" class="form-control" name="middle_name" value="<?= $data['employee']->{'middle name'} ?? '' ?>">
                    </div>
                    <hr class="my-3">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Birthdate</label>
                            <input type="date" class="form-control" name="birthdate" value="<?= $data['employee']->birthdate ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gender</label>
                            <select class="form-select" name="sex">
                                <option value="">Select Gender</option>
                                <option value="Male" <?= ($data['employee']->sex ?? '') == 'Male' ? 'selected' : '' ?>>Male</option>
                                <option value="Female" <?= ($data['employee']->sex ?? '') == 'Female' ? 'selected' : '' ?>>Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Contact Number</label>
                            <input type="text" class="form-control" name="phone_number" value="<?= $data['employee']->{'phone number'} ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Position</label>
                            <input type="text" class="form-control" name="position" value="<?= $data['employee']->position ?? '' ?>">
                        </div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Edit Employee Form Handler (Existing)
        const editForm = document.getElementById('editEmployeeForm');
        if(editForm) {
            editForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(this);
                const newEmployeeNumber = formData.get('employee_number');

                fetch('<?= URLROOT ?>/employees/update', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            // Redirect to the new employee number URL in case it was changed
                            window.location.href = '<?= URLROOT ?>/employees/details/' + newEmployeeNumber;
                        } else {
                            alert('Error updating profile');
                        }
                    });
            });
        }

        // History Checkbox Logic
        const selectAllHistory = document.getElementById('selectAllHistory');
        const historyCheckboxes = document.querySelectorAll('.history-checkbox');
        const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
        const selectedCountSpan = document.getElementById('selectedCount');

        if(deleteSelectedBtn && selectedCountSpan) {
            function updateDeleteButton() {
                const checkedCount = document.querySelectorAll('.history-checkbox:checked').length;
                selectedCountSpan.textContent = checkedCount;
                if (checkedCount > 0) {
                    deleteSelectedBtn.classList.remove('d-none');
                } else {
                    deleteSelectedBtn.classList.add('d-none');
                }
            }

            if(selectAllHistory) {
                selectAllHistory.addEventListener('change', function() {
                    historyCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                    updateDeleteButton();
                });
            }

            historyCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateDeleteButton();
                    // Update Select All state
                    if(selectAllHistory) {
                        selectAllHistory.checked = document.querySelectorAll('.history-checkbox:checked').length === historyCheckboxes.length;
                    }
                });
            });

            // Attach delegated click handler for delete button
            $(document).on('click', '#deleteSelectedBtn', function(e) {
                e.preventDefault();
                deleteSelectedHistory();
            });
        }
    });

    function deleteSelectedHistory() {
        const selected = [];
        document.querySelectorAll('.history-checkbox:checked').forEach(cb => {
            const parts = cb.value.split('|');
            selected.push({
                date: parts[0],
                time: parts[1]
            });
        });

        if(selected.length === 0) return;

        Swal.fire({
            title: 'Delete ' + selected.length + ' Records?',
            text: "This cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete them!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Deleting...',
                    didOpen: () => Swal.showLoading()
                });

                fetch('<?= URLROOT ?>/visits/delete_history', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        id: '<?= $data['employee']->{'employee number'} ?>',
                        type: 'Employee',
                        visits: selected
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.status === 'success') {
                        Swal.fire('Deleted!', 'Records deleted successfully', 'success')
                            .then(() => window.location.reload());
                    } else {
                        Swal.fire('Error!', 'Error deleting records', 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire('Error!', 'Network error', 'error');
                });
            }
        });
    }
</script>

<?php require APPROOT . '/views/layouts/footer.php'; ?>