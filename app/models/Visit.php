<?php
class Visit
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getCombinedRecentVisits($limit = 15)
    {
        // SQLite: Use || for string concatenation instead of CONCAT()
        $sql = "
            SELECT * FROM (
                SELECT 
                    (student_details.last_name || ', ' || student_details.first_name) AS name,
                    'Student' AS type,
                    student_history.student_number AS id,
                    student_history.date_visit AS date_visit,
                    student_history.time_visit AS time_visit
                FROM student_history
                JOIN student_details ON student_history.student_number = student_details.student_number
                UNION ALL
                SELECT 
                    (employee_details.last_name || ', ' || employee_details.first_name) AS name,
                    'Employee' AS type,
                    employee_history.employee_number AS id,
                    employee_history.date_visit AS date_visit,
                    employee_history.time_visit AS time_visit
                FROM employee_history
                JOIN employee_details ON employee_history.employee_number = employee_details.employee_number
            )
            ORDER BY date_visit DESC, time_visit DESC
            LIMIT :limit
        ";

        $this->db->query($sql);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    public function getActiveVisits()
    {
        $sql = "
            SELECT * FROM (
                SELECT 
                    (student_details.last_name || ', ' || student_details.first_name) AS name,
                    'Student' AS type,
                    student_history.student_number AS id,
                    student_history.date_visit AS date_visit,
                    student_history.time_visit AS time_visit
                FROM student_history
                JOIN student_details ON student_history.student_number = student_details.student_number
                WHERE student_history.status = 'Ongoing'
                UNION ALL
                SELECT 
                    (employee_details.last_name || ', ' || employee_details.first_name) AS name,
                    'Employee' AS type,
                    employee_history.employee_number AS id,
                    employee_history.date_visit AS date_visit,
                    employee_history.time_visit AS time_visit
                FROM employee_history
                JOIN employee_details ON employee_history.employee_number = employee_details.employee_number
                WHERE employee_history.status = 'Ongoing'
            )
            ORDER BY time_visit DESC
        ";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function getTrafficData($year, $month)
    {
        // SQLite: Use strftime() instead of DATE_FORMAT(), YEAR(), MONTH()
        if ($month !== 'all') {
            $sql = "
                SELECT 
                    strftime('%d', date_visit) as label, 
                    COUNT(*) as count
                FROM (
                    SELECT date_visit FROM student_history
                    UNION ALL
                    SELECT date_visit FROM employee_history
                ) as all_visits
                WHERE strftime('%Y', date_visit) = :year AND strftime('%m', date_visit) = :month
                GROUP BY date_visit
                ORDER BY date_visit ASC
            ";
            $this->db->query($sql);
            $this->db->bind(':year', $year);
            $this->db->bind(':month', str_pad($month, 2, '0', STR_PAD_LEFT));
        } else {
            $sql = "
                SELECT 
                    CASE strftime('%m', date_visit)
                        WHEN '01' THEN 'January'
                        WHEN '02' THEN 'February'
                        WHEN '03' THEN 'March'
                        WHEN '04' THEN 'April'
                        WHEN '05' THEN 'May'
                        WHEN '06' THEN 'June'
                        WHEN '07' THEN 'July'
                        WHEN '08' THEN 'August'
                        WHEN '09' THEN 'September'
                        WHEN '10' THEN 'October'
                        WHEN '11' THEN 'November'
                        WHEN '12' THEN 'December'
                    END as label, 
                    strftime('%m', date_visit) as month_num,
                    COUNT(*) as count
                FROM (
                    SELECT date_visit FROM student_history
                    UNION ALL
                    SELECT date_visit FROM employee_history
                ) as all_visits
                WHERE strftime('%Y', date_visit) = :year
                GROUP BY month_num
                ORDER BY month_num ASC
            ";
            $this->db->query($sql);
            $this->db->bind(':year', $year);
        }

        return $this->db->resultSet();
    }

    public function getVisitById($type, $id)
    {
        $table = ($type == 'Student') ? 'student_history' : 'employee_history';
        $column = ($type == 'Student') ? 'student_number' : 'employee_number';

        $sql = "SELECT * FROM $table WHERE $column = :id";

        if (isset($_GET['date']) && isset($_GET['time'])) {
            $sql .= " AND date_visit = :date AND time_visit = :time";
            $this->db->query($sql);
            $this->db->bind(':id', $id);
            $this->db->bind(':date', $_GET['date']);
            $this->db->bind(':time', $_GET['time']);
            return $this->db->single();
        }

        $sql .= " ORDER BY date_visit DESC LIMIT 1";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function findActiveVisit($type, $id)
    {
        $table = ($type == 'Student') ? 'student_history' : 'employee_history';
        $col = ($type == 'Student') ? 'student_number' : 'employee_number';

        $sql = "SELECT * FROM $table WHERE $col = :id AND status = 'Ongoing' LIMIT 1";
        $this->db->query($sql);
        $this->db->bind(':id', $id);

        return $this->db->single();
    }

    public function startVisit($type, $id)
    {
        $table = ($type == 'Student') ? 'student_history' : 'employee_history';
        $col = ($type == 'Student') ? 'student_number' : 'employee_number';

        // SQLite: Use date('now', 'localtime') and time('now', 'localtime')
        $sql = "INSERT INTO $table ($col, date_visit, time_visit, status) VALUES (:id, date('now', 'localtime'), time('now', 'localtime'), 'Ongoing')";
        $this->db->query($sql);
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }

    public function endVisit($data)
    {
        $type = $data['type'] ?? '';
        $id = $data['id'] ?? '';
        $table = ($type == 'Student') ? 'student_history' : 'employee_history';
        $col = ($type == 'Student') ? 'student_number' : 'employee_number';

        // Simplified: using unified column names (defined in migration)
        $sql = "UPDATE $table SET 
                    status = 'Completed', 
                    time_out = time('now', 'localtime'),
                    bp = :bp,
                    temperature = :temp,
                    weight = :weight,
                    pulse_rate = :pulse,
                    diagnosis = :reason,
                    treatment = :treatment
                WHERE $col = :id AND status = 'Ongoing'";

        $this->db->query($sql);
        $this->db->bind(':id', $id);
        $this->db->bind(':bp', $data['blood_pressure'] ?? '');
        $this->db->bind(':temp', $data['temperature'] ?? '');
        $this->db->bind(':weight', $data['weight'] ?? '');
        $this->db->bind(':pulse', $data['pulse_rate'] ?? '');
        $this->db->bind(':reason', $data['diagnosis'] ?? '');
        $this->db->bind(':treatment', $data['treatment'] ?? '');

        return $this->db->execute();
    }

    public function deleteVisits($type, $id, $visits)
    {
        $table = ($type == 'Student') ? 'student_history' : 'employee_history';
        $col = ($type == 'Student') ? 'student_number' : 'employee_number';

        $sql = "DELETE FROM $table WHERE $col = :id AND date_visit = :date AND time_visit = :time";

        $this->db->prepare($sql);

        foreach ($visits as $visit) {
            $this->db->bind(':id', $id);
            $this->db->bind(':date', $visit['date']);
            $this->db->bind(':time', $visit['time']);

            try {
                $this->db->execute();
            } catch (Exception $e) {
                // Continue
            }
        }
        return true;
    }
}
