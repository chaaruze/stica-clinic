<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>First Time Setup - <?php echo SITENAME; ?></title>

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

        .form-control:focus {
            border-color: var(--sti-blue);
            box-shadow: 0 0 0 0.2rem rgba(0, 114, 188, 0.25);
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
                <h3 class="fw-bold" style="color: var(--sti-blue);">Welcome!</h3>
                <p class="text-muted">Create your first admin account.</p>
            </div>

            <form action="<?php echo URLROOT; ?>/home/register" method="POST">
                <!-- Name Field -->
                <div class="mb-3">
                    <label class="form-label fw-bold small text-uppercase text-muted">Full Name</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bx bx-id-card text-muted"></i></span>
                        <input type="text" name="name" class="form-control border-start-0 ps-0 <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['name']; ?>" placeholder="Enter your full name">
                    </div>
                    <span class="invalid-feedback d-block"><?php echo $data['name_err']; ?></span>
                </div>

                <!-- Username Field -->
                <div class="mb-3">
                    <label class="form-label fw-bold small text-uppercase text-muted">Username</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bx bx-user text-muted"></i></span>
                        <input type="text" name="username" class="form-control border-start-0 ps-0 <?php echo (!empty($data['username_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['username']; ?>" placeholder="Choose a username">
                    </div>
                    <span class="invalid-feedback d-block"><?php echo $data['username_err']; ?></span>
                </div>

                <!-- Password Field -->
                <div class="mb-3">
                    <label class="form-label fw-bold small text-uppercase text-muted">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bx bx-lock-alt text-muted"></i></span>
                        <input type="password" name="password" class="form-control border-start-0 ps-0 <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password']; ?>" placeholder="Create a password">
                    </div>
                    <span class="invalid-feedback d-block"><?php echo $data['password_err']; ?></span>
                </div>

                <!-- Confirm Password Field -->
                <div class="mb-4">
                    <label class="form-label fw-bold small text-uppercase text-muted">Confirm Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bx bx-lock-alt text-muted"></i></span>
                        <input type="password" name="confirm_password" class="form-control border-start-0 ps-0 <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['confirm_password']; ?>" placeholder="Confirm your password">
                    </div>
                    <span class="invalid-feedback d-block"><?php echo $data['confirm_password_err']; ?></span>
                </div>

                <div class="alert alert-info py-2 small">
                    <i class="bx bx-info-circle me-1"></i> Since this is the first account, it will have admin privileges.
                </div>

                <button type="submit" class="btn btn-login shadow-sm">CREATE ACCOUNT</button>
            </form>
        </div>
        <div class="login-right d-none d-md-block">
            <!-- Background Image Area -->
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
