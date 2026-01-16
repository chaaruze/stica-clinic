<!-- Quick Ball - Floating Action Button -->
<style>
    .quick-ball-container {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 9999;
    }

    .quick-ball {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0072BC 0%, #004C7F 100%);
        border: none;
        box-shadow: 0 4px 15px rgba(0, 114, 188, 0.4);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        color: white;
        font-size: 1.5rem;
    }

    .quick-ball:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(0, 114, 188, 0.5);
    }

    .quick-ball.active {
        transform: rotate(45deg);
        background: linear-gradient(135deg, #dc3545 0%, #a71d2a 100%);
    }

    .quick-menu {
        position: absolute;
        bottom: 70px;
        right: 0;
        display: flex;
        flex-direction: column;
        gap: 12px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(20px) scale(0.8);
        transition: all 0.3s ease;
    }

    .quick-menu.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0) scale(1);
    }

    .quick-menu-item {
        display: flex;
        align-items: center;
        gap: 10px;
        background: white;
        padding: 10px 16px;
        border-radius: 25px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
        text-decoration: none;
        color: #333;
        font-weight: 500;
        font-size: 0.9rem;
        white-space: nowrap;
        transition: all 0.2s ease;
        cursor: pointer;
        border: none;
    }

    .quick-menu-item:hover {
        transform: translateX(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        color: #0072BC;
    }

    .quick-menu-item i {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
    }

    .quick-menu-item.student i {
        background: linear-gradient(135deg, #FFF200 0%, #F5E100 100%);
        color: #0072BC;
    }

    .quick-menu-item.employee i {
        background: linear-gradient(135deg, #0072BC 0%, #004C7F 100%);
        color: white;
    }

    .quick-menu-item.dashboard i {
        background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
        color: white;
    }

    .quick-menu-item.emergency i {
        background: linear-gradient(135deg, #dc3545 0%, #a71d2a 100%);
        color: white;
    }

    /* Backdrop when menu is open */
    .quick-ball-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.2);
        z-index: 9998;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .quick-ball-backdrop.show {
        opacity: 1;
        visibility: visible;
    }
</style>

<div class="quick-ball-backdrop" id="quickBallBackdrop" onclick="toggleQuickBall()"></div>
<div class="quick-ball-container">
    <div class="quick-menu" id="quickMenu">
        <button class="quick-menu-item student" onclick="openQuickStudentModal()">
            <i class="fas fa-user-graduate"></i>
            <span>Add Student</span>
        </button>
        <button class="quick-menu-item employee" onclick="openQuickEmployeeModal()">
            <i class="fas fa-user-tie"></i>
            <span>Add Employee</span>
        </button>
    </div>
    <button class="quick-ball" id="quickBall" onclick="toggleQuickBall()" title="Quick Actions">
        <i class="fas fa-plus" id="quickBallIcon"></i>
    </button>
</div>

<!-- Quick Add Student Modal -->
<div class="modal fade" id="quickStudentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #0072BC 0%, #004C7F 100%);">
                <h5 class="modal-title"><i class="fas fa-user-graduate me-2"></i>Quick Add Student</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="quickStudentForm">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Student Number</label>
                        <input type="text" class="form-control" name="student_number" placeholder="e.g., 2024-00123" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Last Name</label>
                            <input type="text" class="form-control" name="last_name" placeholder="Last Name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">First Name</label>
                            <input type="text" class="form-control" name="first_name" placeholder="First Name" required>
                        </div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Save Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Quick Add Employee Modal -->
<div class="modal fade" id="quickEmployeeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #0072BC 0%, #004C7F 100%);">
                <h5 class="modal-title"><i class="fas fa-user-tie me-2"></i>Quick Add Employee</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="quickEmployeeForm">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Employee ID</label>
                        <input type="text" class="form-control" name="employee_id" placeholder="e.g., EMP-001" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Last Name</label>
                            <input type="text" class="form-control" name="last_name" placeholder="Last Name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">First Name</label>
                            <input type="text" class="form-control" name="first_name" placeholder="First Name" required>
                        </div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Save Employee</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Quick Ball Toggle
    function toggleQuickBall() {
        const menu = document.getElementById('quickMenu');
        const ball = document.getElementById('quickBall');
        const backdrop = document.getElementById('quickBallBackdrop');
        
        menu.classList.toggle('show');
        ball.classList.toggle('active');
        backdrop.classList.toggle('show');
    }

    // Open Quick Student Modal
    function openQuickStudentModal() {
        toggleQuickBall();
        const modal = new bootstrap.Modal(document.getElementById('quickStudentModal'));
        modal.show();
    }

    // Open Quick Employee Modal
    function openQuickEmployeeModal() {
        toggleQuickBall();
        const modal = new bootstrap.Modal(document.getElementById('quickEmployeeModal'));
        modal.show();
    }

    // Quick Student Form Submit
    document.getElementById('quickStudentForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('middle_name', '');

        fetch('<?= URLROOT ?>/students/add', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                bootstrap.Modal.getInstance(document.getElementById('quickStudentModal')).hide();
                this.reset();
                alert('Student added successfully!');
                if (window.location.href.includes('/students')) {
                    location.reload();
                }
            } else {
                alert('Error adding student');
            }
        });
    });

    // Quick Employee Form Submit
    document.getElementById('quickEmployeeForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('middle_name', '');

        fetch('<?= URLROOT ?>/employees/add', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                bootstrap.Modal.getInstance(document.getElementById('quickEmployeeModal')).hide();
                this.reset();
                alert('Employee added successfully!');
                if (window.location.href.includes('/employees')) {
                    location.reload();
                }
            } else {
                alert('Error adding employee');
            }
        });
    });

    // Close menu on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const menu = document.getElementById('quickMenu');
            if (menu.classList.contains('show')) {
                toggleQuickBall();
            }
        }
    });

    // Listen for consultation ended signal from other tabs/windows
    window.addEventListener('storage', function(e) {
        if (e.key === 'consultation_ended') {
            // Refresh the page when a consultation is completed in another window
            location.reload();
        }
    });
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>