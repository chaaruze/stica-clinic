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
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="container-fluid mt-4">
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0 fw-bold"><i class="fas fa-user-graduate me-2"></i>Student Records</h5>
        </div>
        <div class="card-body bg-light">
            <div class="row g-3 align-items-center justify-content-end">
                <div class="col-md-6 col-lg-8">
                    <!-- File input styled -->
                    <div class="input-group">
                        <input type="file" id="excelFile" class="form-control" accept=".xlsx, .xls">
                        <button id="uploadBtn" class="btn btn-primary"><i class="fas fa-file-upload me-1"></i> Import
                            Excel</button>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 text-end">
                    <button id="deleteSelectedBtn" class="btn btn-danger fw-bold text-white shadow-sm me-2" style="display: none;" onclick="deleteSelectedStudents()">
                        <i class="fas fa-trash me-1"></i> Delete Selected (<span id="selectedCount">0</span>)
                    </button>
                    <button id="addStudentBtn" class="btn btn-success fw-bold text-white shadow-sm"
                        data-bs-toggle="modal" data-bs-target="#addStudentModal">
                        <i class="fas fa-plus-circle me-1"></i> Add New Student
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table id="dataTable" class="table table-hover table-bordered align-middle text-center">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="py-3 text-center" style="width: 50px;">
                                <input type="checkbox" class="form-check-input" id="selectAll" onclick="toggleSelectAll(this)">
                            </th>
                            <th class="py-3">Student Number</th>
                            <th class="py-3">Last Name</th>
                            <th class="py-3">First Name</th>
                            <th class="py-3">Middle Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['students'] as $student): ?>
                            <tr>
                                <td onclick="event.stopPropagation();">
                                    <input type="checkbox" class="form-check-input student-checkbox" value="<?= $student->{'student number'} ?>" onchange="updateSelectedCount()">
                                </td>
                                <td class="fw-bold text-sti-blue" style="cursor: pointer;" onclick="window.location.href='<?= URLROOT ?>/students/details/<?= $student->{'student number'} ?>'"><?= $student->{'student number'} ?></td>
                                <td style="cursor: pointer;" onclick="window.location.href='<?= URLROOT ?>/students/details/<?= $student->{'student number'} ?>'"><?= $student->{'last name'} ?></td>
                                <td style="cursor: pointer;" onclick="window.location.href='<?= URLROOT ?>/students/details/<?= $student->{'student number'} ?>'"><?= $student->{'first name'} ?></td>
                                <td style="cursor: pointer;" onclick="window.location.href='<?= URLROOT ?>/students/details/<?= $student->{'student number'} ?>'"><?= $student->{'middle name'} ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-sti-blue text-white">
                <h5 class="modal-title" id="addStudentModalLabel">Add Student</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="addStudentForm" action="" method="post">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Student Number <small class="text-muted fw-normal">(e.g., 2024-00123)</small></label>
                        <input type="text" class="form-control" name="student_number" placeholder="Enter Student Number"
                            onkeypress="return /[a-z 0-9-]/i.test(event.key)" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Last Name</label>
                            <input type="text" class="form-control" name="last_name" placeholder="Enter Last Name"
                                onkeypress="return /[a-z ]/i.test(event.key)" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">First Name</label>
                            <input type="text" class="form-control" name="first_name" placeholder="Enter First Name"
                                onkeypress="return /[a-z ]/i.test(event.key)" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Middle Name <small class="text-muted fw-normal">(Optional)</small></label>
                        <input type="text" class="form-control" name="middle_name" placeholder="Enter Middle Name"
                            onkeypress="return /[a-z ]/i.test(event.key)">
                    </div>
                    <hr class="my-3">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Age</label>
                            <input type="number" class="form-control" name="age" placeholder="Enter Age" min="1" max="100">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Gender</label>
                            <select class="form-select" name="sex">
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Contact Number</label>
                            <input type="text" class="form-control" name="phone_number" placeholder="e.g., 09171234567"
                                onkeypress="return /[0-9]/i.test(event.key)">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Course/Year</label>
                            <input type="text" class="form-control" name="course" placeholder="e.g., BSIT 2nd Year">
                        </div>
                    </div>
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary fw-bold" name="submit">SAVE RECORD</button>
                        <button type="button" class="btn btn-light text-muted" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        var table = $('#dataTable').DataTable({
            "lengthMenu": [10, 25, 50, 75, 100],
            "columnDefs": [
                { "orderable": false, "targets": 0 }
            ],
            "order": [[1, 'asc']],
            "language": {
                "search": "_INPUT_",
                "searchPlaceholder": "Search Student ID or Name..."
            }
        });

        $('div.dataTables_filter input').addClass('form-control');
        $('div.dataTables_length select').addClass('form-select');

        $('#uploadBtn').click(function () {
            var fileInput = document.getElementById('excelFile');
            var file = fileInput.files[0];

            if (file) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var data = new Uint8Array(e.target.result);
                    var workbook = XLSX.read(data, { type: 'array' });
                    var firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                    var excelRows = XLSX.utils.sheet_to_json(firstSheet, { header: 1 });
                    
                    // Get headers from first row
                    var headers = excelRows[0] || [];
                    
                    // Normalize headers for matching (lowercase, trim)
                    var normalizedHeaders = headers.map(h => (h || '').toString().toLowerCase().trim());
                    
                    // Define possible column name variations for each field
                    var columnMappings = {
                        student_number: ['student number', 'student_number', 'studentnumber', 'student no', 'student id', 'id', 'no', 'number'],
                        last_name: ['last name', 'last_name', 'lastname', 'surname', 'family name'],
                        first_name: ['first name', 'first_name', 'firstname', 'given name', 'name'],
                        middle_name: ['middle name', 'middle_name', 'middlename', 'middle'],
                        age: ['age'],
                        sex: ['sex', 'gender'],
                        phone_number: ['phone number', 'phone_number', 'phone', 'contact', 'contact number', 'mobile'],
                        course: ['course', 'course/year', 'course year', 'program', 'strand', 'section']
                    };
                    
                    // Find column index for each field
                    function findColumnIndex(possibleNames) {
                        for (var i = 0; i < normalizedHeaders.length; i++) {
                            for (var j = 0; j < possibleNames.length; j++) {
                                if (normalizedHeaders[i] === possibleNames[j] || normalizedHeaders[i].includes(possibleNames[j])) {
                                    return i;
                                }
                            }
                        }
                        return -1;
                    }
                    
                    var columnIndices = {
                        student_number: findColumnIndex(columnMappings.student_number),
                        last_name: findColumnIndex(columnMappings.last_name),
                        first_name: findColumnIndex(columnMappings.first_name),
                        middle_name: findColumnIndex(columnMappings.middle_name),
                        age: findColumnIndex(columnMappings.age),
                        sex: findColumnIndex(columnMappings.sex),
                        phone_number: findColumnIndex(columnMappings.phone_number),
                        course: findColumnIndex(columnMappings.course)
                    };
                    
                    console.log('Detected columns:', columnIndices);
                    console.log('Headers found:', headers);
                    
                    // Map data rows using detected column indices
                    var rowData = excelRows.slice(1).filter(row => row.length > 0).map(row => {
                        return {
                            student_number: columnIndices.student_number >= 0 ? (row[columnIndices.student_number] || '') : '',
                            last_name: columnIndices.last_name >= 0 ? (row[columnIndices.last_name] || '') : '',
                            first_name: columnIndices.first_name >= 0 ? (row[columnIndices.first_name] || '') : '',
                            middle_name: columnIndices.middle_name >= 0 ? (row[columnIndices.middle_name] || '') : '',
                            age: columnIndices.age >= 0 ? (row[columnIndices.age] || '') : '',
                            sex: columnIndices.sex >= 0 ? (row[columnIndices.sex] || '') : '',
                            phone_number: columnIndices.phone_number >= 0 ? (row[columnIndices.phone_number] || '') : '',
                            course: columnIndices.course >= 0 ? (row[columnIndices.course] || '') : ''
                        };
                    });
                    
                    // Validate required fields
                    if (columnIndices.student_number < 0) {
                        alert('Could not find "Student Number" column. Please check your Excel headers.');
                        return;
                    }

                    $.ajax({
                        url: '<?= URLROOT ?>/students/import',
                        method: 'POST',
                        data: { data: JSON.stringify(rowData) },
                        dataType: 'json',
                        success: function (response) {
                            if (response.status === 'success') {
                                alert('Import successful! ' + rowData.length + ' records processed.');
                                location.reload();
                            } else {
                                alert('Failed to import.');
                            }
                        },
                        error: function (err) {
                            console.error(err);
                            alert('Failed to save data. Check console for details.');
                        }
                    });
                };
                reader.readAsArrayBuffer(file);
            } else {
                alert('Please select an Excel file first.');
            }
        });

        $('#addStudentForm').submit(function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            // Show loading or disable button here if needed
            $.ajax({
                type: 'POST',
                url: '<?= URLROOT ?>/students/add',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        $('#addStudentModal').modal('hide');
                        location.reload();
                    } else {
                        alert('Error adding student. Please check inputs.');
                    }
                },
                error: function () {
                    alert("An error occurred. Please try again.");
                }
            });
        });
    });

    // Multi-select delete functions
    function toggleSelectAll(source) {
        const checkboxes = document.querySelectorAll('.student-checkbox');
        checkboxes.forEach(cb => cb.checked = source.checked);
        updateSelectedCount();
    }

    function updateSelectedCount() {
        const checked = document.querySelectorAll('.student-checkbox:checked');
        const count = checked.length;
        document.getElementById('selectedCount').textContent = count;
        document.getElementById('deleteSelectedBtn').style.display = count > 0 ? 'inline-block' : 'none';
        
        // Update select all checkbox state
        const allCheckboxes = document.querySelectorAll('.student-checkbox');
        document.getElementById('selectAll').checked = allCheckboxes.length > 0 && checked.length === allCheckboxes.length;
    }

    function deleteSelectedStudents() {
        const checked = document.querySelectorAll('.student-checkbox:checked');
        const ids = Array.from(checked).map(cb => cb.value);
        
        if (ids.length === 0) return;
        
        if (!confirm(`Are you sure you want to delete ${ids.length} student(s)? This will also delete their visit history.`)) {
            return;
        }

        fetch('<?= URLROOT ?>/students/deleteMultiple', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ ids: ids })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                alert(`${ids.length} student(s) deleted successfully!`);
                location.reload();
            } else {
                alert('Error deleting students.');
            }
        });
    }
</script>

<?php require APPROOT . '/views/layouts/footer.php'; ?>