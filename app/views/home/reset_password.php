<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - <?php echo SITENAME; ?></title>

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
        .btn-auth {
            background-color: var(--sti-blue);
            color: white;
            font-weight: bold;
            width: 100%;
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
            <h4 class="fw-bold" style="color: var(--sti-blue);">Reset Password</h4>
            <p class="text-muted small">Enter your new password.</p>
        </div>

        <form action="<?php echo URLROOT; ?>/home/reset_password?token=<?= $data['token'] ?>" method="POST">
            <div class="mb-3">
                <label class="form-label fw-bold small text-uppercase text-muted">New Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bx bx-lock-alt text-muted"></i></span>
                    <input type="password" name="password" class="form-control border-start-0 ps-0 <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password']; ?>" placeholder="New password">
                </div>
                <span class="invalid-feedback d-block"><?php echo $data['password_err']; ?></span>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold small text-uppercase text-muted">Confirm Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bx bx-lock-alt text-muted"></i></span>
                    <input type="password" name="confirm_password" class="form-control border-start-0 ps-0 <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['confirm_password']; ?>" placeholder="Confirm password">
                </div>
                <span class="invalid-feedback d-block"><?php echo $data['confirm_password_err']; ?></span>
            </div>

            <button type="submit" class="btn btn-auth mb-3">RESET PASSWORD</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
