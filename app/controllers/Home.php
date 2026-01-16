<?php
class Home extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = $this->model('User');
    }

    public function index()
    {
        // Check if any users exist (First Run)
        if ($this->userModel->getUserCount() == 0) {
            header('location: ' . URLROOT . '/home/register');
            exit;
        }

        // Init data
        $data = [
            'username' => '',
            'password' => '',
            'username_err' => '',
            'password_err' => ''
        ];

        // Load view
        $this->view('home/login', $data);
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data = [
                'username' => trim($_POST['username']),
                'password' => trim($_POST['password']),
                'username_err' => '',
                'password_err' => ''
            ];

            // Validate Email
            if (empty($data['username'])) {
                $data['username_err'] = 'User Name is required';
            }

            // Validate Password
            if (empty($data['password'])) {
                $data['password_err'] = 'Password is required';
            }

            // Make sure errors are empty
            if (empty($data['username_err']) && empty($data['password_err'])) {
                // Validated
                // Check and set logged in user
                $loggedInUser = $this->userModel->login($data['username'], $data['password']);

                if ($loggedInUser) {
                    // Check for Remember Me
                    if (isset($_POST['remember_me'])) {
                        $token = bin2hex(random_bytes(32));
                        $this->userModel->storeRememberToken($loggedInUser->id, $token);
                        // Set cookie for 30 days
                        setcookie('remember_me', $loggedInUser->id . ':' . $token, time() + (86400 * 30), "/");
                    }

                    // Create Session
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['password_err'] = 'Incorrect username or password';

                    $this->view('home/login', $data);
                }
            } else {
                // Load view with errors
                $this->view('home/login', $data);
            }

        } else {
            // Init data
            $data = [
                'username' => '',
                'password' => '',
                'username_err' => '',
                'password_err' => ''
            ];

            // Load view
            $this->view('home/login', $data);
        }
    }

    public function register()
    {
        // Only allow if no users exist
        if ($this->userModel->getUserCount() > 0) {
            header('location: ' . URLROOT . '/home/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'username' => trim($_POST['username']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'name_err' => '',
                'email_err' => '',
                'username_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // Validate Name
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter name';
            }

            // Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            }

            // Validate Username
            if (empty($data['username'])) {
                $data['username_err'] = 'Please enter username';
            } else {
                if ($this->userModel->findUserByUsername($data['username'])) {
                    $data['username_err'] = 'Username is already taken';
                }
            }

            // Validate Password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            } elseif (strlen($data['password']) < 6) {
                $data['password_err'] = 'Password must be at least 6 characters';
            }

            // Validate Confirm Password
            if (empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Please confirm password';
            } else {
                if ($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Passwords do not match';
                }
            }

            // Make sure errors are empty
            if (empty($data['name_err']) && empty($data['email_err']) && empty($data['username_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
                
                // Hash Password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                // Register User
                if ($this->userModel->register($data)) {
                    // Redirect to login
                    header('location: ' . URLROOT . '/home/login');
                } else {
                    die('Something went wrong');
                }

            } else {
                // Load view with errors
                $this->view('home/register', $data);
            }

        } else {
            // Init data
            $data = [
                'name' => '',
                'email' => '',
                'username' => '',
                'password' => '',
                'confirm_password' => '',
                'name_err' => '',
                'email_err' => '',
                'username_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // Load view
            $this->view('home/register', $data);
        }
    }

    public function createUserSession($user)
    {
        $_SESSION['username'] = $user->username;
        $_SESSION['name'] = $user->name;
        $_SESSION['id'] = $user->id;
        header('location: ' . URLROOT . '/dashboard');
        exit; // Important to prevent further execution
    }

    public function logout()
    {
        unset($_SESSION['username']);
        unset($_SESSION['name']);
        unset($_SESSION['id']);
        session_destroy();
        header('location: ' . URLROOT . '/home/login');
    }

    // Forgot Password
    public function forgot_password()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data = [
                'email' => trim($_POST['email']),
                'email_err' => ''
            ];

            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            } else {
                if (!$this->userModel->findUserByEmail($data['email'])) {
                    $data['email_err'] = 'No account found with this email';
                }
            }

            if (empty($data['email_err'])) {
                // Generate token
                $token = bin2hex(random_bytes(32));
                
                // Save token
                if ($this->userModel->setResetToken($data['email'], $token)) {
                    // Send Email (Simulated)
                    $resetLink = URLROOT . "/home/reset_password?token=" . $token;
                    $subject = "Reset Your Password";
                    $message = "Please click the following link to reset your password: " . $resetLink;
                    
                    // In a real app, use mail() or PHPMailer
                    // For now, save to file
                    $logFile = 'email_log.txt';
                    $logMessage = "[" . date('Y-m-d H:i:s') . "] To: " . $data['email'] . "\nSubject: " . $subject . "\nMessage: " . $message . "\n\n------------------------\n\n";
                    file_put_contents($logFile, $logMessage, FILE_APPEND);

                    flash('login_msg', 'Reset link sent! Check email_log.txt in project root.');
                    redirect('home/login');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('home/forgot_password', $data);
            }

        } else {
            $data = [
                'email' => '',
                'email_err' => ''
            ];
            $this->view('home/forgot_password', $data);
        }
    }

    // Reset Password
    public function reset_password()
    {
        $token = $_GET['token'] ?? '';
        
        if (empty($token)) {
            redirect('home/login');
        }

        // Verify token
        $user = $this->userModel->verifyResetToken($token);

        if (!$user) {
            flash('login_msg', 'Invalid or expired reset token', 'alert alert-danger');
            redirect('home/login');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'token' => $token,
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // Validate
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            } elseif (strlen($data['password']) < 6) {
                $data['password_err'] = 'Password must be at least 6 characters';
            }

            if (empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Please confirm password';
            } else {
                if ($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Passwords do not match';
                }
            }

            if (empty($data['password_err']) && empty($data['confirm_password_err'])) {
                // Hash Password
                $password_hash = password_hash($data['password'], PASSWORD_DEFAULT);

                // Update Password
                if ($this->userModel->updatePassword($user->id, $password_hash)) {
                    // Clear token
                    $this->userModel->clearResetToken($user->id);
                    
                    flash('login_msg', 'Password reset successfully! You can now login.');
                    redirect('home/login');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('home/reset_password', $data);
            }

        } else {
            $data = [
                'token' => $token,
                'password' => '',
                'confirm_password' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];
            $this->view('home/reset_password', $data);
        }
    }
}
