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
        $this->db->query("SELECT * FROM employee_details");
        return $this->db->resultSet();
    }

    public function getEmployeeById($id)
    {
        $this->db->query("SELECT * FROM employee_details WHERE employee_number = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addEmployee($data)
    {
        $this->db->query("INSERT INTO employee_details (employee_number, last_name, first_name, middle_name, birthdate, sex, phone_number, position, emergency_contact_name, emergency_contact_phone) VALUES (:id, :lname, :fname, :mname, :birthdate, :sex, :phone, :position, :ec_name, :ec_phone)");
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
        $whereId = $data['original_employee_number'] ?? $data['employee_number'];
        $newId = $data['employee_number'];

        $this->db->query("UPDATE employee_details SET 
            employee_number = :new_id,
            last_name = :lname,
            first_name = :fname,
            middle_name = :mname,
            birthdate = :birthdate, 
            sex = :sex, 
            phone_number = :phone, 
            position = :position,
            emergency_contact_name = :ec_name,
            emergency_contact_phone = :ec_phone
            WHERE employee_number = :id");
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
            if ($whereId !== $newId) {
                $this->db->query("UPDATE employee_history SET employee_number = :new_id WHERE employee_number = :old_id");
                $this->db->bind(':new_id', $newId);
                $this->db->bind(':old_id', $whereId);
                $this->db->execute();
            }
            return true;
        } else {
            return false;
        }
    }

    public function addEmployeesBatch($rows)
    {
        $sql = "INSERT INTO employee_details 
        (employee_number, last_name, first_name, middle_name, birthdate, sex, phone_number, position) 
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
                // Continue on duplicate
            }
        }
        return true;
    }

    public function getEmployeeHistory($id)
    {
        $this->db->query("SELECT * FROM employee_history WHERE employee_number = :id ORDER BY date_visit DESC, time_visit DESC");
        $this->db->bind(':id', $id);
        return $this->db->resultSet();
    }

    public function deleteEmployees($ids)
    {
        if (empty($ids))
            return false;

        $placeholders = [];
        foreach ($ids as $key => $id) {
            $placeholders[] = ":id$key";
        }
        $inClause = implode(',', $placeholders);

        $this->db->query("DELETE FROM employee_history WHERE employee_number IN ($inClause)");
        foreach ($ids as $key => $id) {
            $this->db->bind(":id$key", $id);
        }
        $this->db->execute();

        $this->db->query("DELETE FROM employee_details WHERE employee_number IN ($inClause)");
        foreach ($ids as $key => $id) {
            $this->db->bind(":id$key", $id);
        }
        return $this->db->execute();
    }
}
