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
}
