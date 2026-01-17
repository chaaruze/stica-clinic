<style>
    .navbar-logo-circle {
        width: 45px;
        height: 45px;
        background-color: var(--sti-yellow);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 5px;
        margin-right: 10px;
    }

    .navbar-logo-circle img {
        width: 100%;
        height: auto;
        object-fit: contain;
    }
</style>

<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container-fluid">
        <div class="navbar-brand d-flex align-items-center">
            <a class="btn me-2 text-white" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample"
                aria-controls="offcanvasExample">
                <i class="fa-solid fa-bars"></i>
            </a>
            <div class="navbar-logo-circle">
                <img src="<?= URLROOT ?>/assets/images/logo.png" alt="Logo">
            </div>
            <span class="fw-bold text-white d-none d-md-block" style="font-family: 'Schibsted Grotesk', sans-serif;">STICA Clinic</span>
            
            <!-- Navbar Breadcrumb Trail -->
            <div class="d-none d-md-flex align-items-center ms-3 ps-3 border-start border-secondary">
                <a href="<?= URLROOT ?>/dashboard" class="text-white-50 text-decoration-none small">Home</a>
                <?php
                $current_url = $_SERVER['REQUEST_URI'];
                if (strpos($current_url, 'change_password') !== false) {
                    echo '<span class="text-white-50 mx-2">/</span><span class="text-white small">Change Password</span>';
                } elseif (strpos($current_url, 'students') !== false && strpos($current_url, 'details') !== false) {
                     echo '<span class="text-white-50 mx-2">/</span><a href="'.URLROOT.'/students" class="text-white-50 text-decoration-none small">Students</a>';
                     echo '<span class="text-white-50 mx-2">/</span><span class="text-white small">Details</span>';
                } elseif (strpos($current_url, 'students') !== false) {
                    echo '<span class="text-white-50 mx-2">/</span><span class="text-white small">Students</span>';
                } elseif (strpos($current_url, 'employees') !== false && strpos($current_url, 'details') !== false) {
                     echo '<span class="text-white-50 mx-2">/</span><a href="'.URLROOT.'/employees" class="text-white-50 text-decoration-none small">Employees</a>';
                     echo '<span class="text-white-50 mx-2">/</span><span class="text-white small">Details</span>';
                } elseif (strpos($current_url, 'employees') !== false) {
                    echo '<span class="text-white-50 mx-2">/</span><span class="text-white small">Employees</span>';
                }
                ?>
            </div>
        </div>

        <div class="collapse navbar-collapse d-flex align-items-center justify-content-end" id="navbarSupportedContent">
            <ul class="navbar-nav d-flex align-items-center">
                <li class="nav-item">
                    <div class="d-flex align-items-center text-white">
                        <i class="fas fa-user-circle fa-lg me-2"></i>
                        <p class="nav-link m-0 fw-bold text-white p-0">Welcome,
                            <?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'User'; ?></p>
                    </div>
                </li>
                <!-- Original Logout link removed as requested to be in sidebar, but kept here for accessibility or consistency if desired, though often redundant. Keeping it as backup or optional. -->
                <li class="nav-item">
                    <a href="<?= URLROOT ?>/home/logout" class="btn btn-sm btn-outline-warning ms-3 d-lg-none"
                        onclick="return confirmLogout()">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </a>
                </li>
                <script>
                    function confirmLogout() {
                        return confirm("Are you sure you want to log out?");
                    }
                </script>
            </ul>
        </div>
    </div>
</nav>