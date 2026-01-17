<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo SITENAME; ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Boxicons CSS -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= URLROOT ?>/assets/css/CSS.css" rel="stylesheet">

    <style>
        body {
            background-color: var(--bg-light);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .login-card {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 900px;
            display: flex;
        }

        .login-left {
            flex: 1;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-right {
            flex: 1;
            background-color: var(--sti-blue);
            background-image: url('<?= URLROOT ?>/assets/images/login-background(1).png');
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .login-right::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 114, 188, 0.5);
        }

        /* Custom Input Group Styling for Unified Focus */
        .custom-input-group {
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            display: flex;
            align-items: center;
            padding: 0.5rem 0.75rem; /* Adjusted vertical padding */
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            background-color: #f8f9fa; /* Explicit light background */
        }

        .custom-input-group:focus-within {
            border-color: var(--sti-blue);
            box-shadow: 0 0 0 0.2rem rgba(0, 114, 188, 0.25);
            background-color: #fff; /* White on focus */
        }

        .custom-input-group .input-group-text {
            border: none;
            background-color: transparent;
            padding-right: 12px; /* Reduced specific Right padding */
            padding-left: 0;
            color: #6c757d;
            display: flex; /* Ensure icon centers vertically */
            align-items: center;
        }

        .custom-input-group .form-control {
            border: none;
            box-shadow: none; 
            padding-left: 0;
            background-color: transparent; /* Transparent so parent color shows */
            padding-top: 0;
            padding-bottom: 0; /* Let flexbox center it */
            height: auto; /* Allow auto height */
        }
        
        /* Checkbox Alignment Fix */
        .form-check {
            display: flex;
            align-items: center; /* Vertically center items */
            padding-left: 0; 
            margin-bottom: 0; 
            min-height: auto; /* Remove bootstrap min-height */
        }
        
        .form-check-input {
            margin-top: 0;
            margin-right: 0.5rem;
            float: none;
            margin-left: 0;
            width: 1.1em;
            height: 1.1em;
            cursor: pointer;
        }

        .form-check-label {
            cursor: pointer;
            padding-top: 1px; /* Micro-adjustment for visual center */
        }
        
        .forgot-link {
            color: var(--sti-blue);
            text-decoration: none;
            font-size: 0.875em;
            transition: color 0.3s;
        }
        
        .forgot-link:hover {
            color: var(--sti-yellow);
            text-decoration: underline;
        }

        .btn-login {
            background-color: var(--sti-blue);
            color: white;
            font-weight: bold;
            padding: 12px;
            width: 100%;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-login:hover {
            background-color: var(--sti-yellow);
            color: var(--sti-blue);
        }

        @media (max-width: 768px) {
            .login-card {
                flex-direction: column;
                max-width: 400px;
            }
            .login-right {
                display: none;
            }
            .login-left {
                padding: 30px;
            }
        }
    </style>
</head>

<body>

    <div class="login-card">
        <div class="login-left">
            <div class="text-center mb-4">
                <img src="<?= URLROOT ?>/assets/images/logo.png" alt="STI Logo" width="100" class="mb-3">
                <h3 class="fw-bold" style="color: var(--sti-blue);">Log In</h3>
                <p class="text-muted">Welcome to STI Clinic</p>
            </div>

            <?php if (!empty($data['username_err']) || !empty($data['password_err'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bx bx-error-circle me-1"></i>
                    <?php echo !empty($data['username_err']) ? $data['username_err'] : $data['password_err']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="<?php echo URLROOT; ?>/home/login" method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold small text-uppercase text-muted">Username</label>
                    <div class="custom-input-group bg-light">
                        <span class="input-group-text"><i class="bx bx-user text-muted"></i></span>
                        <input type="text" name="username" class="form-control bg-transparent"
                            placeholder="Enter your username" value="<?php echo $data['username']; ?>">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold small text-uppercase text-muted">Password</label>
                    <div class="custom-input-group bg-light">
                        <span class="input-group-text"><i class="bx bx-lock-alt text-muted"></i></span>
                        <input type="password" name="password" class="form-control bg-transparent"
                            placeholder="Enter your password" value="<?php echo $data['password']; ?>">
                    </div>
                </div>

                <div class="mb-4 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember_me" id="rememberMe">
                        <label class="form-check-label text-muted small" for="rememberMe">
                            Remember me
                        </label>
                    </div>
                    <div class="d-flex flex-column align-items-end">
                        <a href="<?= URLROOT ?>/home/forgot_password" class="forgot-link mb-1">Forgot Password?</a>
                        <a href="<?= URLROOT ?>/home/forgot_username" class="forgot-link">Forgot Username?</a>
                    </div>
                </div>

                <button type="submit" class="btn btn-login shadow-sm">LOG IN</button>
            </form>
        </div>
        <div class="login-right d-none d-md-block">
            <!-- Background Image Area -->
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        <?php if (!empty($_SESSION['login_msg'])): ?>
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: '<?= !empty($_SESSION['login_icon']) ? $_SESSION['login_icon'] : 'info' ?>',
                title: '<?= $_SESSION['login_msg'] ?>'
            });
            <?php 
                unset($_SESSION['login_msg']); 
                unset($_SESSION['login_icon']);
                // Also clear flash data just in case
                if(isset($_SESSION['login_msg_class'])) unset($_SESSION['login_msg_class']); 
            ?>
        <?php endif; ?>
    </script>
</body>

</html>