<?php
class Student
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
        $this->db->query("ALTER TABLE `student details` ADD COLUMN IF NOT EXISTS `emergency_contact_name` VARCHAR(255)");
        $this->db->execute();
        $this->db->query("ALTER TABLE `student details` ADD COLUMN IF NOT EXISTS `emergency_contact_phone` VARCHAR(50)");
        $this->db->execute();
    }

    public function getStudents()
    {
        $this->db->query("SELECT * FROM `student details`");
        return $this->db->resultSet();
    }

    public function getStudentById($id)
    {
        $this->db->query("SELECT * FROM `student details` WHERE `student number` = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addStudent($data)
    {
        $this->db->query("INSERT INTO `student details` (`student number`, `last name`, `first name`, `middle name`, `birthdate`, `sex`, `phone number`, `course`, `emergency_contact_name`, `emergency_contact_phone`) VALUES (:id, :lname, :fname, :mname, :birthdate, :sex, :phone, :course, :ec_name, :ec_phone)");
        $this->db->bind(':id', $data['student_number']);
        $this->db->bind(':lname', $data['last_name']);
        $this->db->bind(':fname', $data['first_name']);
        $this->db->bind(':mname', $data['middle_name']);
        $this->db->bind(':birthdate', $data['birthdate'] ?: null);
        $this->db->bind(':sex', $data['sex'] ?: null);
        $this->db->bind(':phone', $data['phone_number'] ?: null);
        $this->db->bind(':course', $data['course'] ?: null);
        $this->db->bind(':ec_name', $data['emergency_contact_name'] ?? null);
        $this->db->bind(':ec_phone', $data['emergency_contact_phone'] ?? null);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function updateStudent($data)
    {
        // Use original_student_number if provided (for ID changes), otherwise use student_number
        $whereId = $data['original_student_number'] ?? $data['student_number'];
        $newId = $data['student_number'];
        
        // Update student details including emergency contact
        $this->db->query("UPDATE `student details` SET 
            `student number` = :new_id,
            `last name` = :lname,
            `first name` = :fname,
            `middle name` = :mname,
            `birthdate` = :birthdate, 
            `sex` = :sex, 
            `phone number` = :phone, 
            `course` = :course,
            `emergency_contact_name` = :ec_name,
            `emergency_contact_phone` = :ec_phone
            WHERE `student number` = :id");
        $this->db->bind(':id', $whereId);
        $this->db->bind(':new_id', $newId);
        $this->db->bind(':lname', $data['last_name'] ?? '');
        $this->db->bind(':fname', $data['first_name'] ?? '');
        $this->db->bind(':mname', $data['middle_name'] ?? '');
        $this->db->bind(':birthdate', $data['birthdate'] ?: null);
        $this->db->bind(':sex', $data['sex'] ?: null);
        $this->db->bind(':phone', $data['phone_number'] ?: null);
        $this->db->bind(':course', $data['course'] ?: null);
        $this->db->bind(':ec_name', $data['emergency_contact_name'] ?? null);
        $this->db->bind(':ec_phone', $data['emergency_contact_phone'] ?? null);

        if ($this->db->execute()) {
            // If ID changed, also update all history records
            if ($whereId !== $newId) {
                $this->db->query("UPDATE `student history` SET `student number` = :new_id WHERE `student number` = :old_id");
                $this->db->bind(':new_id', $newId);
                $this->db->bind(':old_id', $whereId);
                $this->db->execute();
            }
            return true;
        } else {
            return false;
        }
    }

    public function addStudentsBatch($rows)
    {
        $sql = "INSERT INTO `student details` 
        (`student number`, `last name`, `first name`, `middle name`, `birthdate`, `sex`, `phone number`, `course`) 
        VALUES (:id, :lname, :fname, :mname, :birthdate, :sex, :phone, :course)";
        
        $this->db->prepare($sql);

        foreach ($rows as $row) {
            $this->db->bind(':id', $row['student_number']);
            $this->db->bind(':lname', $row['last_name']);
            $this->db->bind(':fname', $row['first_name']);
            $this->db->bind(':mname', $row['middle_name']);
            $this->db->bind(':birthdate', $row['birthdate'] ?? null);
            $this->db->bind(':sex', $row['sex'] ?? '');
            $this->db->bind(':phone', $row['phone_number'] ?? '');
            $this->db->bind(':course', $row['course'] ?? '');

            try {
                $this->db->execute();
            } catch (Exception $e) {
                // Continue
            }
        }
        return true;
    }
    public function getStudentHistory($id)
    {
        $this->db->query("SELECT * FROM `student history` WHERE `student number` = :id ORDER BY `date visit` DESC, `time visit` DESC");
        $this->db->bind(':id', $id);
        return $this->db->resultSet();
    }

    public function deleteStudents($ids)
    {
        if (empty($ids)) return false;
        
        // Build IN clause with placeholders
        $placeholders = [];
        foreach ($ids as $key => $id) {
            $placeholders[] = ":id$key";
        }
        $inClause = implode(',', $placeholders);
        
        // Delete history first
        $this->db->query("DELETE FROM `student history` WHERE `student number` IN ($inClause)");
        foreach ($ids as $key => $id) {
            $this->db->bind(":id$key", $id);
        }
        $this->db->execute();
        
        // Delete students
        $this->db->query("DELETE FROM `student details` WHERE `student number` IN ($inClause)");
        foreach ($ids as $key => $id) {
            $this->db->bind(":id$key", $id);
        }
        return $this->db->execute();
    }
}
