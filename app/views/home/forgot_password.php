<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - <?php echo SITENAME; ?></title>

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
        .auth-card {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            padding: 2rem;
        }

        /* Custom Input Group Styling */
        .custom-input-group {
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            display: flex;
            align-items: center;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .custom-input-group:focus-within {
            border-color: var(--sti-blue);
            box-shadow: 0 0 0 0.2rem rgba(0, 114, 188, 0.25);
        }

        .custom-input-group .input-group-text {
            border: none;
            background-color: transparent;
        }

        .custom-input-group .form-control {
            border: none;
            box-shadow: none;
        }

        .btn-auth {
            background-color: var(--sti-blue);
            color: white;
            font-weight: bold;
            width: 100%;
            padding: 10px;
            border-radius: 8px;
        }
        .btn-auth:hover {
            background-color: var(--sti-yellow);
            color: var(--sti-blue);
        }
    </style>
</head>
<body>

    <div class="auth-card">
        <div class="text-center mb-4">
            <img src="<?= URLROOT ?>/assets/images/logo.png" alt="STI Logo" width="80" class="mb-3">
            <h4 class="fw-bold" style="color: var(--sti-blue);">Forgot Password</h4>
            <p class="text-muted small">Enter your email to receive a reset link.</p>
        </div>

        <form action="<?php echo URLROOT; ?>/home/forgot_password" method="POST">
            <div class="mb-3">
                <label class="form-label fw-bold small text-uppercase text-muted">Email Address</label>
                <div class="custom-input-group bg-light">
                    <span class="input-group-text"><i class="bx bx-envelope text-muted"></i></span>
                    <input type="email" name="email" class="form-control bg-transparent <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>" placeholder="Enter registered email">
                </div>
                <span class="invalid-feedback d-block"><?php echo $data['email_err']; ?></span>
            </div>

            <button type="submit" class="btn btn-auth mb-3">SEND RESET LINK</button>
            
            <div class="text-center">
                <a href="<?= URLROOT ?>/home/login" class="text-decoration-none small text-muted">Back to Login</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
