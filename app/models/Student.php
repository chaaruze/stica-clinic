<?php
class Student
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
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
        $this->db->query("INSERT INTO `student details` (`student number`, `last name`, `first name`, `middle name`, `age`, `sex`, `phone number`, `course`) VALUES (:id, :lname, :fname, :mname, :age, :sex, :phone, :course)");
        $this->db->bind(':id', $data['student_number']);
        $this->db->bind(':lname', $data['last_name']);
        $this->db->bind(':fname', $data['first_name']);
        $this->db->bind(':mname', $data['middle_name']);
        $this->db->bind(':age', $data['age'] ?: null);
        $this->db->bind(':sex', $data['sex'] ?: null);
        $this->db->bind(':phone', $data['phone_number'] ?: null);
        $this->db->bind(':course', $data['course'] ?: null);

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
        
        // Update student details
        $this->db->query("UPDATE `student details` SET 
            `student number` = :new_id,
            `last name` = :lname,
            `first name` = :fname,
            `middle name` = :mname,
            `age` = :age, 
            `sex` = :sex, 
            `phone number` = :phone, 
            `course` = :course 
            WHERE `student number` = :id");
        $this->db->bind(':id', $whereId);
        $this->db->bind(':new_id', $newId);
        $this->db->bind(':lname', $data['last_name'] ?? '');
        $this->db->bind(':fname', $data['first_name'] ?? '');
        $this->db->bind(':mname', $data['middle_name'] ?? '');
        $this->db->bind(':age', $data['age'] ?: null);
        $this->db->bind(':sex', $data['sex'] ?: null);
        $this->db->bind(':phone', $data['phone_number'] ?: null);
        $this->db->bind(':course', $data['course'] ?: null);

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
        $sql = "INSERT INTO `student details` (`student number`, `last name`, `first name`, `middle name`) VALUES (:id, :lname, :fname, :mname)";
        $this->db->prepare($sql);

        foreach ($rows as $row) {
            $this->db->bind(':id', $row['student_number']);
            $this->db->bind(':lname', $row['last_name']);
            $this->db->bind(':fname', $row['first_name']);
            $this->db->bind(':mname', $row['middle_name']);

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
