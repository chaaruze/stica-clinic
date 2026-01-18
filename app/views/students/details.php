<?php require APPROOT . '/views/layouts/header.php'; ?>
<?php require APPROOT . '/views/layouts/navbar.php'; ?>
<?php require APPROOT . '/views/layouts/sidebar.php'; ?>

<div class="container-fluid mt-4">
    <?php if (!$data['student']): ?>
        <div class="alert alert-danger">
            <h4 class="alert-heading">Student Not Found</h4>
            <p>The requested student record could not be found. It may have been deleted or the ID is incorrect.</p>
            <hr>
            <a href="<?= URLROOT ?>/students" class="btn btn-outline-danger">Back to List</a>
        </div>
    <?php else: ?>
    <div class="row">
        <!-- Student Profile Card -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header text-white text-center py-4" style="background-color: var(--sti-blue);">
                    <div class="mb-3">
                        <i class="fas fa-user-graduate fa-4x border rounded-circle p-3 bg-white text-sti-blue"></i>
                    </div>
                    <h4 class="fw-bold mb-0">
                        <?= $data['student']->{'last name'} . ', ' . $data['student']->{'first name'} . ' ' . $data['student']->{'middle name'} ?>
                    </h4>
                    <p class="mb-0 opacity-75">Student Number:
                        <?= $data['student']->{'student number'} ?>
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
                                <?= !empty($data['student']->birthdate) ? date('F j, Y', strtotime($data['student']->birthdate)) : 'N/A' ?>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Gender</span>
                            <span class="fw-bold">
                                <?= isset($data['student']->sex) ? $data['student']->sex : 'N/A' ?>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Contact</span>
                            <span class="fw-bold">
                                <?= isset($data['student']->{'phone number'}) ? $data['student']->{'phone number'} : 'N/A' ?>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Course/Year</span>
                            <span class="fw-bold">
                                <?= isset($data['student']->course) ? $data['student']->course : 'N/A' ?>
                            </span>
                        </li>
                    </ul>

                    <div class="mt-4 d-grid gap-2">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editStudentModal">
                            <i class="fas fa-edit me-2"></i>Edit Personal Info
                        </button>
                        <a href="<?= URLROOT ?>/students" class="btn btn-outline-secondary"><i
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
                            onclick="window.open('<?= URLROOT ?>/visits/active/Student/<?= $data['student']->{'student number'} ?>', '_blank')">
                            <i class="fas fa-plus me-1"></i>Add Record
                        </button>
                    </div>
                </div>
                <div class="card-body p-0 position-relative">
                    <!-- Watermark Logo -->
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); opacity: 0.15; z-index: 0; pointer-events: none;">
                        <img src="<?= URLROOT ?>/assets/images/logo.png" alt="Watermark" style="width: 200px; filter: grayscale(100%);">
                    </div>

                    <div class="table-responsive" style="position: relative; z-index: 1;">
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
                                                onclick="window.open('<?= URLROOT ?>/visits/details/Student/<?= $data['student']->{'student number'} ?>?date=<?= $visit->{'date visit'} ?>&time=<?= $visit->{'time visit'} ?>', '_blank')">
                                                <?= date('M d, Y', strtotime($visit->{'date visit'})) ?>
                                            </td>
                                            <td onclick="window.open('<?= URLROOT ?>/visits/details/Student/<?= $data['student']->{'student number'} ?>?date=<?= $visit->{'date visit'} ?>&time=<?= $visit->{'time visit'} ?>', '_blank')">
                                                <?= date('h:i A', strtotime($visit->{'time visit'})) ?>
                                            </td>
                                            <td onclick="window.open('<?= URLROOT ?>/visits/details/Student/<?= $data['student']->{'student number'} ?>?date=<?= $visit->{'date visit'} ?>&time=<?= $visit->{'time visit'} ?>', '_blank')">
                                                <?= $visit->diagnosis ?? 'N/A' ?>
                                            </td>
                                            <td onclick="window.open('<?= URLROOT ?>/visits/details/Student/<?= $data['student']->{'student number'} ?>?date=<?= $visit->{'date visit'} ?>&time=<?= $visit->{'time visit'} ?>', '_blank')">
                                                <?= $visit->intervention ?? 'N/A' ?>
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
    <?php endif; ?>
</div>

<!-- Edit Student Modal -->
<div class="modal fade" id="editStudentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-sti-blue text-white">
                <h5 class="modal-title">Edit Personal Info</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editStudentForm">
                    <input type="hidden" name="original_student_number" value="<?= $data['student']->{'student number'} ?>">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Student Number</label>
                        <input type="text" class="form-control" name="student_number" value="<?= $data['student']->{'student number'} ?>">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Last Name</label>
                            <input type="text" class="form-control" name="last_name" value="<?= $data['student']->{'last name'} ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">First Name</label>
                            <input type="text" class="form-control" name="first_name" value="<?= $data['student']->{'first name'} ?? '' ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Middle Name</label>
                        <input type="text" class="form-control" name="middle_name" value="<?= $data['student']->{'middle name'} ?? '' ?>">
                    </div>
                    <hr class="my-3">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Birthdate</label>
                            <input type="date" class="form-control" name="birthdate" value="<?= $data['student']->birthdate ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gender</label>
                            <select class="form-select" name="sex">
                                <option value="">Select Gender</option>
                                <option value="Male" <?= ($data['student']->sex ?? '') == 'Male' ? 'selected' : '' ?>>Male</option>
                                <option value="Female" <?= ($data['student']->sex ?? '') == 'Female' ? 'selected' : '' ?>>Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Contact Number</label>
                            <input type="text" class="form-control" name="phone_number" value="<?= $data['student']->{'phone number'} ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Course/Year</label>
                            <input type="text" class="form-control" name="course" value="<?= $data['student']->course ?? '' ?>">
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
        // Edit Student Form Handler (Existing)
        const editForm = document.getElementById('editStudentForm');
        if(editForm) {
            editForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(this);
                const newStudentNumber = formData.get('student_number');

                fetch('<?= URLROOT ?>/students/update', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            // Redirect to the new student number URL in case it was changed
                            window.location.href = '<?= URLROOT ?>/students/details/' + newStudentNumber;
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
                        id: '<?= $data['student']->{'student number'} ?>',
                        type: 'Student',
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