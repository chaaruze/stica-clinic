<?php
class Employee
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getEmployees()
    {
        $this->db->query("SELECT * FROM `employee details`");
        return $this->db->resultSet();
    }

    public function getEmployeeById($id)
    {
        $this->db->query("SELECT * FROM `employee details` WHERE `employee number` = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addEmployee($data)
    {
        $this->db->query("INSERT INTO `employee details` (`employee number`, `last name`, `first name`, `middle name`, `age`, `sex`, `phone number`, `position`) VALUES (:id, :lname, :fname, :mname, :age, :sex, :phone, :position)");
        $this->db->bind(':id', $data['employee_id']);
        $this->db->bind(':lname', $data['last_name']);
        $this->db->bind(':fname', $data['first_name']);
        $this->db->bind(':mname', $data['middle_name']);
        $this->db->bind(':age', $data['age'] ?: null);
        $this->db->bind(':sex', $data['sex'] ?: null);
        $this->db->bind(':phone', $data['phone_number'] ?: null);
        $this->db->bind(':position', $data['position'] ?: null);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function updateEmployee($data)
    {
        // Use original_employee_number if provided (for ID changes), otherwise use employee_number
        $whereId = $data['original_employee_number'] ?? $data['employee_number'];
        $newId = $data['employee_number'];
        
        // Update employee details
        $this->db->query("UPDATE `employee details` SET 
            `employee number` = :new_id,
            `last name` = :lname,
            `first name` = :fname,
            `middle name` = :mname,
            `age` = :age, 
            `sex` = :sex, 
            `phone number` = :phone, 
            `position` = :position 
            WHERE `employee number` = :id");
        $this->db->bind(':id', $whereId);
        $this->db->bind(':new_id', $newId);
        $this->db->bind(':lname', $data['last_name'] ?? '');
        $this->db->bind(':fname', $data['first_name'] ?? '');
        $this->db->bind(':mname', $data['middle_name'] ?? '');
        $this->db->bind(':age', $data['age'] ?: null);
        $this->db->bind(':sex', $data['sex'] ?: null);
        $this->db->bind(':phone', $data['phone_number'] ?: null);
        $this->db->bind(':position', $data['position'] ?: null);

        if ($this->db->execute()) {
            // If ID changed, also update all history records
            if ($whereId !== $newId) {
                $this->db->query("UPDATE `employee history` SET `employee number` = :new_id WHERE `employee number` = :old_id");
                $this->db->bind(':new_id', $newId);
                $this->db->bind(':old_id', $whereId);
                $this->db->execute();
            }
            return true;
        } else {
            return false;
        }
    }

    // Example import logic
    public function addEmployeesBatch($rows)
    {
        // This accepts array of rows [id, lname, fname, mname, age, sex, phone, position]
        $sql = "INSERT INTO `employee details` 
        (`employee number`, `last name`, `first name`, `middle name`, `age`, `sex`, `phone number`, `position`) 
        VALUES (:id, :lname, :fname, :mname, :age, :sex, :phone, :pos)";
        
        $this->db->prepare($sql);

        foreach ($rows as $row) {
            $this->db->bind(':id', $row['employee_id']);
            $this->db->bind(':lname', $row['last_name']);
            $this->db->bind(':fname', $row['first_name']);
            $this->db->bind(':mname', $row['middle_name']);
            $this->db->bind(':age', $row['age'] ?? '');
            $this->db->bind(':sex', $row['sex'] ?? '');
            $this->db->bind(':phone', $row['phone_number'] ?? '');
            $this->db->bind(':pos', $row['position'] ?? '');

            try {
                $this->db->execute();
            } catch (Exception $e) {
                // Continue or log error
            }
        }
        return true;
    }
    public function getEmployeeHistory($id)
    {
        $this->db->query("SELECT * FROM `employee history` WHERE `employee number` = :id ORDER BY `date visit` DESC, `time visit` DESC");
        $this->db->bind(':id', $id);
        return $this->db->resultSet();
    }

    public function deleteEmployees($ids)
    {
        if (empty($ids)) return false;
        
        // Build IN clause with placeholders
        $placeholders = [];
        foreach ($ids as $key => $id) {
            $placeholders[] = ":id$key";
        }
        $inClause = implode(',', $placeholders);
        
        // Delete history first
        $this->db->query("DELETE FROM `employee history` WHERE `employee number` IN ($inClause)");
        foreach ($ids as $key => $id) {
            $this->db->bind(":id$key", $id);
        }
        $this->db->execute();
        
        // Delete employees
        $this->db->query("DELETE FROM `employee details` WHERE `employee number` IN ($inClause)");
        foreach ($ids as $key => $id) {
            $this->db->bind(":id$key", $id);
        }
        return $this->db->execute();
    }
}
