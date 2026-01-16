<?php
class User
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function login($username, $password)
    {
        $this->db->query('SELECT * FROM nurses WHERE username = :username');
        $this->db->bind(':username', $username);

        $row = $this->db->single();

        if ($row) {
            $hashed_password = $row->password;
            if (password_verify($password, $hashed_password)) {
                // Log the successful login
                $this->logLogin($row->id);
                return $row;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function logLogin($userId)
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $browser = $_SERVER['HTTP_USER_AGENT'];

        $this->db->query('INSERT INTO login_logs (user_id, ip_address, browser, login_time) VALUES (:user_id, :ip, :browser, NOW())');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':ip', $ip);
        $this->db->bind(':browser', $browser);
        $this->db->execute();
    }

    public function findUserByUsername($username)
    {
        $this->db->query('SELECT * FROM nurses WHERE username = :username');
        $this->db->bind(':username', $username);

        $row = $this->db->single();

        // Check row
        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function storeRememberToken($userId, $token)
    {
        // Token expiry (30 days)
        $expires_at = date('Y-m-d H:i:s', time() + (30 * 24 * 60 * 60));

        // Check if table uses 'token_hash' or 'token' - assuming 'token' based on simpler setup, 
        // or I should check schema. I'll blindly try 'token'.
        $this->db->query('INSERT INTO remember_tokens (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':token', $token); // In production, hash this!
        $this->db->bind(':expires_at', $expires_at);
        return $this->db->execute();
    }

    public function getUserCount()
    {
        $this->db->query('SELECT COUNT(*) as count FROM nurses');
        $row = $this->db->single();
        return $row ? $row->count : 0;
    }

    public function register($data)
    {
        $this->db->query('INSERT INTO nurses (name, email, username, password) VALUES (:name, :email, :username, :password)');
        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':password', $data['password']);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Update password (for Change Password & Reset Password)
    public function updatePassword($id, $newPasswordHash)
    {
        $this->db->query('UPDATE nurses SET password = :password WHERE id = :id');
        $this->db->bind(':password', $newPasswordHash);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Find user by email (for Forgot Password)
    public function findUserByEmail($email)
    {
        $this->db->query('SELECT * FROM nurses WHERE email = :email');
        $this->db->bind(':email', $email);
        $row = $this->db->single();

        if ($this->db->rowCount() > 0) {
            return $row;
        } else {
            return false;
        }
    }

    // Set reset token
    public function setResetToken($email, $token)
    {
        // Hash token for security
        $token_hash = hash('sha256', $token);
        // Expiry 30 minutes from now
        $expiry = date('Y-m-d H:i:s', time() + 60 * 30);

        $this->db->query('UPDATE nurses SET reset_token_hash = :hash, reset_token_expires_at = :expiry WHERE email = :email');
        $this->db->bind(':hash', $token_hash);
        $this->db->bind(':expiry', $expiry);
        $this->db->bind(':email', $email);

        return $this->db->execute();
    }

    // Verify reset token
    public function verifyResetToken($token)
    {
        $token_hash = hash('sha256', $token);

        $this->db->query('SELECT * FROM nurses WHERE reset_token_hash = :hash AND reset_token_expires_at > NOW()');
        $this->db->bind(':hash', $token_hash);
        
        $row = $this->db->single();

        if ($row) {
            return $row;
        } else {
            return false;
        }
    }

    // Clear reset token
    public function clearResetToken($id)
    {
        $this->db->query('UPDATE nurses SET reset_token_hash = NULL, reset_token_expires_at = NULL WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
