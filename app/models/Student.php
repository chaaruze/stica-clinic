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
        $this->db->query("SELECT * FROM student_details");
        return $this->db->resultSet();
    }

    public function getStudentById($id)
    {
        $this->db->query("SELECT * FROM student_details WHERE student_number = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addStudent($data)
    {
        $this->db->query("INSERT INTO student_details (student_number, last_name, first_name, middle_name, birthdate, sex, phone_number, course, emergency_contact_name, emergency_contact_phone) VALUES (:id, :lname, :fname, :mname, :birthdate, :sex, :phone, :course, :ec_name, :ec_phone)");
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
        $whereId = $data['original_student_number'] ?? $data['student_number'];
        $newId = $data['student_number'];

        $this->db->query("UPDATE student_details SET 
            student_number = :new_id,
            last_name = :lname,
            first_name = :fname,
            middle_name = :mname,
            birthdate = :birthdate, 
            sex = :sex, 
            phone_number = :phone, 
            course = :course,
            emergency_contact_name = :ec_name,
            emergency_contact_phone = :ec_phone
            WHERE student_number = :id");
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
            if ($whereId !== $newId) {
                $this->db->query("UPDATE student_history SET student_number = :new_id WHERE student_number = :old_id");
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
        $sql = "INSERT INTO student_details 
        (student_number, last_name, first_name, middle_name, birthdate, sex, phone_number, course) 
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
                // Continue on duplicate
            }
        }
        return true;
    }

    public function getStudentHistory($id)
    {
        $this->db->query("SELECT * FROM student_history WHERE student_number = :id ORDER BY date_visit DESC, time_visit DESC");
        $this->db->bind(':id', $id);
        return $this->db->resultSet();
    }

    public function deleteStudents($ids)
    {
        if (empty($ids))
            return false;

        $placeholders = [];
        foreach ($ids as $key => $id) {
            $placeholders[] = ":id$key";
        }
        $inClause = implode(',', $placeholders);

        // Delete history first
        $this->db->query("DELETE FROM student_history WHERE student_number IN ($inClause)");
        foreach ($ids as $key => $id) {
            $this->db->bind(":id$key", $id);
        }
        $this->db->execute();

        // Delete students
        $this->db->query("DELETE FROM student_details WHERE student_number IN ($inClause)");
        foreach ($ids as $key => $id) {
            $this->db->bind(":id$key", $id);
        }
        return $this->db->execute();
    }
}
