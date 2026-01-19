<?php
class Employee
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
        $this->ensureEmergencyColumns();
    }

    // Auto-migrate: Add emergency contact columns if they don't exist
    private function ensureEmergencyColumns()
    {
        $this->db->query("ALTER TABLE `employee details` ADD COLUMN IF NOT EXISTS `emergency_contact_name` VARCHAR(255)");
        $this->db->execute();
        $this->db->query("ALTER TABLE `employee details` ADD COLUMN IF NOT EXISTS `emergency_contact_phone` VARCHAR(50)");
        $this->db->execute();
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
        $this->db->query("INSERT INTO `employee details` (`employee number`, `last name`, `first name`, `middle name`, `birthdate`, `sex`, `phone number`, `position`, `emergency_contact_name`, `emergency_contact_phone`) VALUES (:id, :lname, :fname, :mname, :birthdate, :sex, :phone, :position, :ec_name, :ec_phone)");
        $this->db->bind(':id', $data['employee_id']);
        $this->db->bind(':lname', $data['last_name']);
        $this->db->bind(':fname', $data['first_name']);
        $this->db->bind(':mname', $data['middle_name']);
        $this->db->bind(':birthdate', $data['birthdate'] ?: null);
        $this->db->bind(':sex', $data['sex'] ?: null);
        $this->db->bind(':phone', $data['phone_number']);
        $this->db->bind(':position', $data['position']);
        $this->db->bind(':ec_name', $data['emergency_contact_name'] ?? null);
        $this->db->bind(':ec_phone', $data['emergency_contact_phone'] ?? null);

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
        
        // Update employee details including emergency contact
        $this->db->query("UPDATE `employee details` SET 
            `employee number` = :new_id,
            `last name` = :lname,
            `first name` = :fname,
            `middle name` = :mname,
            `birthdate` = :birthdate, 
            `sex` = :sex, 
            `phone number` = :phone, 
            `position` = :position,
            `emergency_contact_name` = :ec_name,
            `emergency_contact_phone` = :ec_phone
            WHERE `employee number` = :id");
        $this->db->bind(':id', $whereId);
        $this->db->bind(':new_id', $newId);
        $this->db->bind(':lname', $data['last_name'] ?? '');
        $this->db->bind(':fname', $data['first_name'] ?? '');
        $this->db->bind(':mname', $data['middle_name'] ?? '');
        $this->db->bind(':birthdate', $data['birthdate'] ?: null);
        $this->db->bind(':sex', $data['sex'] ?: null);
        $this->db->bind(':phone', $data['phone_number']);
        $this->db->bind(':position', $data['position']);
        $this->db->bind(':ec_name', $data['emergency_contact_name'] ?? null);
        $this->db->bind(':ec_phone', $data['emergency_contact_phone'] ?? null);

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
        // This accepts array of rows [id, lname, fname, mname, birthdate, sex, phone, position]
        $sql = "INSERT INTO `employee details` 
        (`employee number`, `last name`, `first name`, `middle name`, `birthdate`, `sex`, `phone number`, `position`) 
        VALUES (:id, :lname, :fname, :mname, :birthdate, :sex, :phone, :pos)";
        
        $this->db->prepare($sql);

        foreach ($rows as $row) {
            $this->db->bind(':id', $row['employee_id']);
            $this->db->bind(':lname', $row['last_name']);
            $this->db->bind(':fname', $row['first_name']);
            $this->db->bind(':mname', $row['middle_name']);
            $this->db->bind(':birthdate', $row['birthdate'] ?? null);
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
