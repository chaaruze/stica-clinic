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

        // SQLite: use datetime('now', 'localtime') instead of NOW()
        $this->db->query("INSERT INTO login_logs (user_id, ip_address, browser, login_time) VALUES (:user_id, :ip, :browser, datetime('now', 'localtime'))");
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

        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function storeRememberToken($userId, $token)
    {
        $expires_at = date('Y-m-d H:i:s', time() + (30 * 24 * 60 * 60));

        $this->db->query('INSERT INTO remember_tokens (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':token', $token);
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
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':password', $data['password']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function updatePassword($id, $newPasswordHash)
    {
        $this->db->query('UPDATE nurses SET password = :password WHERE id = :id');
        $this->db->bind(':password', $newPasswordHash);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

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

    public function setResetToken($email, $token)
    {
        $token_hash = hash('sha256', $token);
        $expiry = date('Y-m-d H:i:s', time() + 60 * 30);

        $this->db->query('UPDATE nurses SET reset_token_hash = :hash, reset_token_expires_at = :expiry WHERE email = :email');
        $this->db->bind(':hash', $token_hash);
        $this->db->bind(':expiry', $expiry);
        $this->db->bind(':email', $email);

        return $this->db->execute();
    }

    public function verifyResetToken($token)
    {
        $token_hash = hash('sha256', $token);

        $this->db->query('SELECT * FROM nurses WHERE reset_token_hash = :hash');
        $this->db->bind(':hash', $token_hash);

        $row = $this->db->single();

        if ($row) {
            if (strtotime($row->reset_token_expires_at) > time()) {
                return $row;
            }
        }

        return false;
    }

    public function clearResetToken($id)
    {
        $this->db->query('UPDATE nurses SET reset_token_hash = NULL, reset_token_expires_at = NULL WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
