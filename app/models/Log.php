<?php
class Log
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function add($action, $details = '')
    {
        if (!isset($_SESSION['id']))
            return false;

        $this->db->query("INSERT INTO activity_logs (user_id, user_name, action, details) VALUES (:uid, :uname, :action, :details)");

        $this->db->bind(':uid', $_SESSION['id']);
        $this->db->bind(':uname', $_SESSION['name'] ?? 'Unknown');
        $this->db->bind(':action', $action);
        $this->db->bind(':details', $details);

        return $this->db->execute();
    }

    public function getLogs()
    {
        $this->db->query("SELECT * FROM activity_logs ORDER BY created_at DESC");
        return $this->db->resultSet();
    }
}
