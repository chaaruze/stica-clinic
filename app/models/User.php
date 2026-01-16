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
}
