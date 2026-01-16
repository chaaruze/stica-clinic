<?php
class Users extends Controller
{
    private $userModel;

    public function __construct()
    {
        if (!isLoggedIn()) {
            redirect('home/login');
        }
        $this->userModel = $this->model('User');
    }

    public function change_password()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'current_password' => trim($_POST['current_password']),
                'new_password' => trim($_POST['new_password']),
                'confirm_new_password' => trim($_POST['confirm_new_password']),
                'current_password_err' => '',
                'new_password_err' => '',
                'confirm_new_password_err' => ''
            ];

            // Validate Current Password
            if (empty($data['current_password'])) {
                $data['current_password_err'] = 'Please enter current password';
            } else {
                // Verify with db
                $user = $this->userModel->login($_SESSION['username'], $data['current_password']);
                if (!$user) {
                    $data['current_password_err'] = 'Incorrect password';
                }
            }

            // Validate New Password
            if (empty($data['new_password'])) {
                $data['new_password_err'] = 'Please enter new password';
            } elseif (strlen($data['new_password']) < 6) {
                $data['new_password_err'] = 'Password must be at least 6 characters';
            }

            // Validate Confirm New Password
            if (empty($data['confirm_new_password'])) {
                $data['confirm_new_password_err'] = 'Please confirm new password';
            } else {
                if ($data['new_password'] != $data['confirm_new_password']) {
                    $data['confirm_new_password_err'] = 'Passwords do not match';
                }
            }

            if (empty($data['current_password_err']) && empty($data['new_password_err']) && empty($data['confirm_new_password_err'])) {
                // Hash new password
                $password_hash = password_hash($data['new_password'], PASSWORD_DEFAULT);

                // Update
                if ($this->userModel->updatePassword($_SESSION['id'], $password_hash)) {
                    flash('profile_msg', 'Password updated successfully');
                    redirect('dashboard');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('users/change_password', $data);
            }

        } else {
            $data = [
                'current_password' => '',
                'new_password' => '',
                'confirm_new_password' => '',
                'current_password_err' => '',
                'new_password_err' => '',
                'confirm_new_password_err' => ''
            ];
            $this->view('users/change_password', $data);
        }
    }
}
