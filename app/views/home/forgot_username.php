<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Username - <?php echo SITENAME; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Boxicons CSS -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: var(--bg-light);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            border: none;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 10px;
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

        .btn-primary {
            background-color: var(--sti-blue);
            border-color: var(--sti-blue);
            padding: 10px;
            font-weight: bold;
        }
        .btn-primary:hover {
            background-color: var(--sti-yellow);
            color: var(--sti-blue);
            border-color: var(--sti-yellow);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card p-4">
                    <div class="text-center mb-4">
                        <img src="<?= URLROOT ?>/assets/images/logo.png" alt="Logo" width="80" class="mb-3">
                        <h4 class="fw-bold" style="color: var(--sti-blue);">Forgot Username?</h4>
                        <p class="text-muted small">Enter your email and we'll send your username.</p>
                    </div>

                    <form action="<?php echo URLROOT; ?>/home/forgot_username" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase text-muted">Email Address</label>
                            <div class="custom-input-group bg-light">
                                <span class="input-group-text"><i class="bx bx-envelope text-muted"></i></span>
                                <input type="email" name="email" class="form-control bg-transparent <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>" placeholder="Enter registered email">
                            </div>
                            <span class="invalid-feedback d-block"><?php echo $data['email_err']; ?></span>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Send Username</button>
                            <a href="<?= URLROOT ?>/home/login" class="btn btn-light text-muted">Back to Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
