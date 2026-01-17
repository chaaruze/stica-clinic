    // Forgot Username
    public function forgot_username()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data = [
                'email' => trim($_POST['email']),
                'email_err' => ''
            ];

            // Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            } else {
                $user = $this->userModel->findUserByEmail($data['email']);
                if (!$user) {
                    $data['email_err'] = 'No account found with this email';
                }
            }

            if (empty($data['email_err'])) {
                $user = $this->userModel->findUserByEmail($data['email']);
                
                // Send Email
                $subject = "Your Username - STICA Clinic";
                $message = "
                    <h3>Forgot Username?</h3>
                    <p>You requested to retrieve your username for the STICA Clinic system.</p>
                    <p>Your username is: <strong>" . $user->username . "</strong></p>
                    <p>You can now login using this username.</p>
                    <p><a href='" . URLROOT . "/home/login'>Go to Login</a></p>
                ";
                
                require_once APPROOT . '/libraries/Mail.php';
                if (Mail::send($data['email'], $subject, $message)) {
                    // Set session for Toast
                    $_SESSION['login_msg'] = 'Username sent to your email!';
                    $_SESSION['login_icon'] = 'success';
                    redirect('home/login');
                } else {
                     flash('login_msg', 'Something went wrong sending email.', 'alert alert-danger');
                     redirect('home/login');
                }
            } else {
                $this->view('home/forgot_username', $data);
            }

        } else {
            $data = [
                'email' => '',
                'email_err' => ''
            ];
            $this->view('home/forgot_username', $data);
        }
    }
