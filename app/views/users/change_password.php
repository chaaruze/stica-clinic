<?php require APPROOT . '/views/layouts/header.php'; ?>
<?php require APPROOT . '/views/layouts/navbar.php'; ?>
<?php require APPROOT . '/views/layouts/sidebar.php'; ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content mt-4">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header" style="background-color: var(--sti-blue);">
                            <h3 class="card-title"><i class="fas fa-lock me-2"></i>Update Password</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="<?php echo URLROOT; ?>/users/change_password" method="POST">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Current Password</label>
                                    <input type="password" name="current_password" class="form-control <?php echo (!empty($data['current_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['current_password']; ?>">
                                    <span class="invalid-feedback"><?php echo $data['current_password_err']; ?></span>
                                </div>
                                <div class="form-group">
                                    <label>New Password</label>
                                    <input type="password" name="new_password" class="form-control <?php echo (!empty($data['new_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['new_password']; ?>">
                                    <span class="invalid-feedback"><?php echo $data['new_password_err']; ?></span>
                                </div>
                                <div class="form-group">
                                    <label>Confirm New Password</label>
                                    <input type="password" name="confirm_new_password" class="form-control <?php echo (!empty($data['confirm_new_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['confirm_new_password']; ?>">
                                    <span class="invalid-feedback"><?php echo $data['confirm_new_password_err']; ?></span>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary" style="background-color: var(--sti-blue); border-color: var(--sti-blue);">Update Password</button>
                                <a href="<?= URLROOT ?>/dashboard" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require APPROOT . '/views/layouts/footer.php'; ?>
