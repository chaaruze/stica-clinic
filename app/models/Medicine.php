<?php
class Medicine
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAll()
    {
        $this->db->query("SELECT * FROM medicines ORDER BY name ASC");
        return $this->db->resultSet();
    }

    public function add($data)
    {
        $this->db->query("INSERT INTO medicines (name, unit, stock, expiration_date) VALUES (:name, :unit, :stock, :expiry)");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':unit', $data['unit']);
        $this->db->bind(':stock', $data['stock']);
        $this->db->bind(':expiry', $data['expiration_date'] ?: null);
        return $this->db->execute();
    }

    public function update($data)
    {
        $this->db->query("UPDATE medicines SET name = :name, unit = :unit, stock = :stock, expiration_date = :expiry WHERE id = :id");
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':unit', $data['unit']);
        $this->db->bind(':stock', $data['stock']);
        $this->db->bind(':expiry', $data['expiration_date'] ?: null);
        return $this->db->execute();
    }

    public function delete($id)
    {
        $this->db->query("DELETE FROM medicines WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getById($id)
    {
        $this->db->query("SELECT * FROM medicines WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function dispense($id, $quantity)
    {
        $this->db->query("UPDATE medicines SET stock = stock - :qty WHERE id = :id AND stock >= :qty");
        $this->db->bind(':id', $id);
        $this->db->bind(':qty', $quantity);
        if ($this->db->execute()) {
            return $this->db->rowCount() > 0;
        }
        return false;
    }
}
