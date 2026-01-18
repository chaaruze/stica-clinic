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
                    <button type="button" id="deleteSelectedBtn" class="btn btn-danger fw-bold text-white shadow-sm me-2" style="display: none;">
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
        <div class="card-body position-relative" style="min-height: 500px;">
            <!-- Watermark Logo -->
            <div style="position: absolute; top: 65%; left: 50%; transform: translate(-50%, -50%); opacity: 0.15; z-index: 0; pointer-events: none;">
                <img src="<?= URLROOT ?>/assets/images/logo.png" alt="Watermark" style="width: 400px; filter: grayscale(100%);">
            </div>
        
            <div class="table-responsive" style="position: relative; z-index: 1;">
                <table id="studentsTable" class="table table-hover align-middle">
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
                            <label class="form-label fw-bold">Birthdate</label>
                            <input type="date" class="form-control" name="birthdate" required>
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

<?php require APPROOT . '/views/layouts/footer.php'; ?>

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
                    
                    if (!excelRows || excelRows.length === 0) {
                        Swal.fire('Error', 'The Excel file appears to be empty.', 'error');
                        return;
                    }
                    
                    // Normalize function: lowercase, trim, remove extra spaces
                    var normalize = h => (h || '').toString().toLowerCase().trim().replace(/\s+/g, ' ');
                    
                    // ===== AUTO-DETECT HEADER ROW =====
                    // STI rosters often have metadata rows before headers
                    // Look for a row that contains student-related column names
                    var headerRowIndex = 0;
                    var headerKeywords = ['student no', 'student number', 'lrn', 'last name', 'first name', 'lastname', 'firstname'];
                    
                    for (var ri = 0; ri < Math.min(excelRows.length, 15); ri++) {
                        var row = excelRows[ri] || [];
                        var normalizedRow = row.map(normalize);
                        var matchCount = 0;
                        for (var hi = 0; hi < headerKeywords.length; hi++) {
                            for (var ci = 0; ci < normalizedRow.length; ci++) {
                                var cellVal = normalizedRow[ci] || ''; // Handle sparse array holes
                                if (cellVal === headerKeywords[hi] || cellVal.includes(headerKeywords[hi])) {
                                    matchCount++;
                                    break; // Only count once per keyword
                                }
                            }
                        }
                        // If we find at least 2 matching keywords, this is likely the header row
                        if (matchCount >= 2) {
                            headerRowIndex = ri;
                            console.log('Detected header row at index:', ri, 'Headers:', row.filter(h => h));
                            break;
                        }
                    }
                    
                    var headers = excelRows[headerRowIndex] || [];
                    var normalizedHeaders = headers.map(normalize);
                    
                    // ===== COLUMN MAPPINGS (Updated for STI Roster Format) =====
                    var columnMappings = {
                        student_number: ['student no', 'student number', 'student id', 'id number', 'lrn', 'student_number', 'studentno'],
                        last_name: ['last name', 'surname', 'family name', 'lastname'],
                        first_name: ['first name', 'given name', 'forename', 'firstname'],
                        middle_name: ['middle name', 'middle initial', 'middlename', 'middle'],
                        age: ['age'],
                        birthdate: ['birthdate', 'birthday', 'birth date', 'date of birth', 'dob'],
                        sex: ['sex', 'gender'],
                        phone_number: ['phone number', 'contact number', 'mobile number', 'mobile', 'contact', 'cel#', 'cp#'],
                        course: ['course', 'course/year', 'program', 'strand', 'section', 'year level', 'level', 'track']
                    };
                    
                    // Smart Column Finder
                    function findColumnIndex(possibleNames) {
                        // Pass 1: Exact Match (Prioritize Alias Order)
                        for (var j = 0; j < possibleNames.length; j++) {
                            var alias = possibleNames[j];
                            for (var i = 0; i < normalizedHeaders.length; i++) {
                                var headerVal = normalizedHeaders[i] || ''; // Handle sparse array holes
                                if (headerVal === alias) {
                                    return i;
                                }
                            }
                        }
                        
                        // Pass 2: Ends With Match (Prioritize Alias Order)
                        for (var j = 0; j < possibleNames.length; j++) {
                            var alias = possibleNames[j];
                            for (var i = 0; i < normalizedHeaders.length; i++) {
                                var headerVal = normalizedHeaders[i] || ''; // Handle sparse array holes
                                if (headerVal.endsWith(alias)) {
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
                        birthdate: findColumnIndex(columnMappings.birthdate),
                        sex: findColumnIndex(columnMappings.sex),
                        phone_number: findColumnIndex(columnMappings.phone_number),
                        course: findColumnIndex(columnMappings.course)
                    };
                    
                    // Helper function to calculate age from birthdate

                    
                    console.log('Detected column indices:', columnIndices);
                    
                    // Map data rows (skip rows before and including header)
                    var dataRows = excelRows.slice(headerRowIndex + 1);
                    var rowData = dataRows.filter(row => row && row.length > 0).map(row => {
                        // Get age from age column, or calculate from birthdate

                        
                        return {
                            student_number: columnIndices.student_number >= 0 ? String(row[columnIndices.student_number] || '').trim() : '',
                            last_name: columnIndices.last_name >= 0 ? String(row[columnIndices.last_name] || '').trim() : '',
                            first_name: columnIndices.first_name >= 0 ? String(row[columnIndices.first_name] || '').trim() : '',
                            middle_name: columnIndices.middle_name >= 0 ? String(row[columnIndices.middle_name] || '').trim() : '',
                            birthdate: columnIndices.birthdate >= 0 ? (function(val) {
                                // Handle Excel serial numbers or strings
                                if (!val) return null;
                                if (typeof val === 'number' || !isNaN(Number(val))) {
                                    var serial = Number(val);
                                    if (serial > 25000 && serial < 60000) {
                                        var excelEpoch = new Date(1899, 11, 30);
                                        var date = new Date(excelEpoch.getTime() + serial * 24 * 60 * 60 * 1000);
                                        return date.toISOString().split('T')[0];
                                    }
                                }
                                var d = new Date(val);
                                return !isNaN(d.getTime()) ? d.toISOString().split('T')[0] : null;
                            })(row[columnIndices.birthdate]) : null,
                            sex: columnIndices.sex >= 0 ? String(row[columnIndices.sex] || '').trim() : '',
                            phone_number: columnIndices.phone_number >= 0 ? String(row[columnIndices.phone_number] || '').trim() : '',
                            course: columnIndices.course >= 0 ? String(row[columnIndices.course] || '').trim() : ''
                        };
                    });
                    
                    // Filter out rows without ID or Name to avoid empty inserts
                    rowData = rowData.filter(r => r.student_number && (r.last_name || r.first_name));
                    
                    if (columnIndices.student_number < 0) {
                        Swal.fire('Mapping Error', 'Could not find "Student Number" column.<br>Please ensure header is named "Student Number" or "Student ID".', 'error');
                        return;
                    }

                    if (rowData.length === 0) {
                        Swal.fire('Empty Data', 'No valid records found to import.', 'warning');
                        return;
                    }

                    Swal.fire({
                        title: 'Importing...',
                        text: 'Processing ' + rowData.length + ' records.',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    $.ajax({
                        url: '<?= URLROOT ?>/students/import',
                        method: 'POST',
                        data: { data: JSON.stringify(rowData) },
                        dataType: 'json',
                        success: function (response) {
                            if (response.status === 'success') {
                                Swal.fire('Success!', rowData.length + ' records imported successfully.', 'success')
                                    .then(() => location.reload());
                            } else {
                                Swal.fire('Import Failed', 'Server refused the data.', 'error');
                            }
                        },
                        error: function (err) {
                            console.error(err);
                            Swal.fire('Error', 'Failed to save data. Check console.', 'error');
                        }
                    });
                };
                reader.readAsArrayBuffer(file);
            } else {
                Swal.fire('No File', 'Please select an Excel file first.', 'warning');
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
                }
            });
        });



        // Attach delete button click handler using delegation (more robust)
        $(document).on('click', '#deleteSelectedBtn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Delegated click detected!');
            deleteSelectedStudents();
        });
        
        console.log('Students index script loaded and ready.');
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
        // Use jQuery to ensure we catch elements even if DataTables manipulated them
        const checked = $('.student-checkbox:checked'); 
        const ids = [];
        checked.each(function() {
            ids.push($(this).val());
        });
        
        console.log('Delete requested. Found:', ids.length, 'IDs:', ids);
        if (ids.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Selection',
                text: 'Please select at least one student.',
                confirmButtonColor: '#0072BC'
            });
            return;
        }
        
        Swal.fire({
            title: 'Delete ' + ids.length + ' Students?',
            text: "This will also delete their visit history. You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete them!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch('<?= URLROOT ?>/students/deleteMultiple', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ ids: ids })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire(
                            'Deleted!',
                            ids.length + ' student(s) have been deleted.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire(
                            'Error!',
                            'Something went wrong.',
                            'error'
                        );
                    }
                })
                .catch(err => {
                    console.error('Delete error:', err);
                    Swal.fire(
                        'Error!',
                        'Network error. Check console.',
                        'error'
                    );
                });
            }
        });
    }
</script>