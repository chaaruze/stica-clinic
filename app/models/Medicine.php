<?php
class Medicine {
    private $db;

    public function __construct() {
        $this->db = new Database;
        $this->ensureTableExists();
    }

    private function ensureTableExists() {
        $sql = "CREATE TABLE IF NOT EXISTS `medicines` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            `unit` varchar(50) NOT NULL COMMENT 'e.g., tablet, capsule, syrup',
            `stock` int(11) NOT NULL DEFAULT 0,
            `expiration_date` date DEFAULT NULL,
            `created_at` datetime DEFAULT current_timestamp(),
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        
        $this->db->query($sql);
        $this->db->execute();
    }

    public function getAll() {
        $this->db->query("SELECT * FROM medicines ORDER BY name ASC");
        return $this->db->resultSet();
    }

    public function add($data) {
        $this->db->query("INSERT INTO medicines (name, unit, stock, expiration_date) VALUES (:name, :unit, :stock, :expiry)");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':unit', $data['unit']);
        $this->db->bind(':stock', $data['stock']);
        $this->db->bind(':expiry', $data['expiration_date'] ?: null);
        return $this->db->execute();
    }

    public function update($data) {
        $this->db->query("UPDATE medicines SET name = :name, unit = :unit, stock = :stock, expiration_date = :expiry WHERE id = :id");
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':unit', $data['unit']);
        $this->db->bind(':stock', $data['stock']);
        $this->db->bind(':expiry', $data['expiration_date'] ?: null);
        return $this->db->execute();
    }

    public function delete($id) {
        $this->db->query("DELETE FROM medicines WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getById($id) {
        $this->db->query("SELECT * FROM medicines WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Deduct stock
    public function dispense($id, $quantity) {
        $this->db->query("UPDATE medicines SET stock = stock - :qty WHERE id = :id AND stock >= :qty");
        $this->db->bind(':id', $id);
        $this->db->bind(':qty', $quantity);
        if ($this->db->execute()) {
            return $this->db->rowCount() > 0; // Return true only if a row was actually updated (stock was sufficient)
        }
        return false;
    }
}
