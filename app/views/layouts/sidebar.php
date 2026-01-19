<style>
    .sidebar-logo {
        filter: brightness(0) invert(1);
        /* Make logo white if needed, or keep original */
    }

    .logo-circle {
        width: 50px;
        height: 50px;
        background-color: var(--sti-yellow);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 5px;
    }

    .logo-circle img {
        width: 100%;
        height: auto;
        object-fit: contain;
    }
</style>

<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel"
    data-bs-backdrop="false">
    <div class="offcanvas-header justify-content-between">
        <div class="d-flex align-items-center">
            <h5 class="offcanvas-title fw-bold" id="offcanvasExampleLabel"
                style="font-family: 'Schibsted Grotesk', sans-serif;">STICA Clinic</h5>
        </div>
        <button type="button" class="btn-close btn-close-white text-reset" data-bs-dismiss="offcanvas"
            aria-label="Close"></button>
    </div>
    <div class="offcanvas-body custom-offcanvas d-flex flex-column">
        <nav class="nav flex-column mt-3 flex-grow-1">
            <a class="nav-link custom-nav-link" href="<?= URLROOT ?>/dashboard"><i
                    class="fas fa-tachometer-alt"></i>Dashboard</a>
            <a class="nav-link custom-nav-link" href="<?= URLROOT ?>/students"><i
                    class="fas fa-user-graduate"></i>Students</a>
            <a class="nav-link custom-nav-link" href="<?= URLROOT ?>/employees"><i
                    class="fas fa-users"></i>Employees</a>
            
            <a href="<?= URLROOT ?>/medicines" class="nav-link custom-nav-link">
                <i class="fas fa-pills"></i>Inventory
            </a>
            <a href="<?= URLROOT ?>/logs" class="nav-link custom-nav-link">
                <i class="fas fa-clipboard-list"></i>Audit Logs
            </a>
        </nav>

        <div class="mt-auto">
             <a class="nav-link custom-nav-link mb-2" href="<?= URLROOT ?>/users/change_password"><i
                    class="fas fa-key"></i>Change Password</a>
                    
            <a href="<?= URLROOT ?>/users/logout"
                class="custom-nav-link text-decoration-none text-danger mt-1"
                onclick="return confirm('Are you sure you want to log out?')">
                <i class="fas fa-sign-out-alt fa-lg me-3"></i> Logout
            </a>
            <div class="text-center mt-3">
                <small class="text-white-50">&copy; <?= date('Y') ?> STI College Alabang</small>
            </div>
        </div>
    </div>
</div>