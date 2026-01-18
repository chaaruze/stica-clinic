<?php
class Log {
    private $db;

    public function __construct() {
        $this->db = new Database;
        $this->ensureTableExists();
    }

    // Lazy initialization of the table
    private function ensureTableExists() {
        $sql = "CREATE TABLE IF NOT EXISTS `activity_logs` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `user_id` int(11) NOT NULL,
            `user_name` varchar(255) NOT NULL,
            `action` varchar(255) NOT NULL,
            `details` text,
            `ip_address` varchar(45),
            `created_at` datetime DEFAULT current_timestamp(),
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        
        $this->db->query($sql);
        $this->db->execute();
    }

    public function add($action, $details = '') {
        if (!isset($_SESSION['id'])) return false;

        $this->db->query("INSERT INTO activity_logs (user_id, user_name, action, details, ip_address) VALUES (:uid, :uname, :action, :details, :ip)");
        
        $this->db->bind(':uid', $_SESSION['id']);
        $this->db->bind(':uname', $_SESSION['name'] ?? 'Unknown');
        $this->db->bind(':action', $action);
        $this->db->bind(':details', $details);
        $this->db->bind(':ip', $_SERVER['REMOTE_ADDR']);

        return $this->db->execute();
    }
    public function getLogs() {
        $this->db->query("SELECT * FROM activity_logs ORDER BY created_at DESC");
        return $this->db->resultSet();
    }
}
