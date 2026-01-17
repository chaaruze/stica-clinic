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
        $sql = "
            (SELECT 
                CONCAT(`student details`.`last name`, ', ', `student details`.`first name`) AS name,
                'Student' AS type,
                `student history`.`student number` AS id,
                `student history`.`date visit` AS date_visit,
                `student history`.`time visit` AS time_visit
            FROM `student history`
            JOIN `student details` ON `student history`.`student number` = `student details`.`student number`)
            UNION ALL
            (SELECT 
                CONCAT(`employee details`.`last name`, ', ', `employee details`.`first name`) AS name,
                'Employee' AS type,
                `employee history`.`employee number` AS id,
                `employee history`.`date visit` AS date_visit,
                `employee history`.`time visit` AS time_visit
            FROM `employee history`
            JOIN `employee details` ON `employee history`.`employee number` = `employee details`.`employee number`)
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
            (SELECT 
                CONCAT(`student details`.`last name`, ', ', `student details`.`first name`) AS name,
                'Student' AS type,
                `student history`.`student number` AS id,
                `student history`.`date visit` AS date_visit,
                `student history`.`time visit` AS time_visit
            FROM `student history`
            JOIN `student details` ON `student history`.`student number` = `student details`.`student number`
            WHERE `student history`.status = 'Ongoing')
            UNION ALL
            (SELECT 
                CONCAT(`employee details`.`last name`, ', ', `employee details`.`first name`) AS name,
                'Employee' AS type,
                `employee history`.`employee number` AS id,
                `employee history`.`time visit` AS time_visit,
                 `employee history`.`date visit` AS date_visit
            FROM `employee history`
            JOIN `employee details` ON `employee history`.`employee number` = `employee details`.`employee number`
            WHERE `employee history`.status = 'Ongoing')
            ORDER BY time_visit DESC
        ";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function getTrafficData($year, $month)
    {
        // If specific month is selected, show daily data for that month
        if ($month !== 'all') {
            $sql = "
                SELECT 
                    DATE_FORMAT(date_visit, '%d') as label, 
                    COUNT(*) as count
                FROM (
                    SELECT `date visit` as date_visit FROM `student history`
                    UNION ALL
                    SELECT `date visit` as date_visit FROM `employee history`
                ) as all_visits
                WHERE YEAR(date_visit) = :year AND MONTH(date_visit) = :month
                GROUP BY date_visit
                ORDER BY date_visit ASC
            ";
            $this->db->query($sql);
            $this->db->bind(':year', $year);
            $this->db->bind(':month', $month);
        } else {
            // "All Months": Show monthly data for the selected year
            $sql = "
                SELECT 
                    DATE_FORMAT(date_visit, '%M') as label, 
                    MONTH(date_visit) as month_num,
                    COUNT(*) as count
                FROM (
                    SELECT `date visit` as date_visit FROM `student history`
                    UNION ALL
                    SELECT `date visit` as date_visit FROM `employee history`
                ) as all_visits
                WHERE YEAR(date_visit) = :year
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
        // Fetch specific visit details based on user ID and timestamp (since no unique visit ID exists)
        $table = ($type == 'Student') ? 'student history' : 'employee history';
        $column = strtolower($type) . ' number';

        $sql = "SELECT * FROM `$table` WHERE `$column` = :id";

        // If we have date/time params
        if (isset($_GET['date']) && isset($_GET['time'])) {
            $sql .= " AND `date visit` = :date AND `time visit` = :time";
            $this->db->query($sql);
            $this->db->bind(':id', $id);
            $this->db->bind(':date', $_GET['date']);
            $this->db->bind(':time', $_GET['time']);
            return $this->db->single();
        }

        // Fallback: Return latest
        $sql .= " ORDER BY `date visit` DESC LIMIT 1";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        return $this->db->single();
    }


    public function findActiveVisit($type, $id)
    {
        $table = ($type == 'Student') ? 'student history' : 'employee history';
        $col = strtolower($type) . ' number';

        $sql = "SELECT * FROM `$table` WHERE `$col` = :id AND status = 'Ongoing' LIMIT 1";
        $this->db->query($sql);
        $this->db->bind(':id', $id);

        return $this->db->single();
    }

    public function startVisit($type, $id)
    {
        $table = ($type == 'Student') ? 'student history' : 'employee history';
        $col = strtolower($type) . ' number';

        $sql = "INSERT INTO `$table` (`$col`, `date visit`, `time visit`, `status`) VALUES (:id, CURDATE(), CURTIME(), 'Ongoing')";
        $this->db->query($sql);
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }

    public function endVisit($data)
    {
        $type = $data['type'] ?? '';
        $id = $data['id'] ?? '';
        $table = ($type == 'Student') ? 'student history' : 'employee history';
        $col = strtolower($type) . ' number';

        // Column names differ between student and employee history tables
        if ($type == 'Student') {
            // Student uses: diagnosis, intervention
            $sql = "UPDATE `$table` SET 
                        status = 'Completed', 
                        time_ended = CURTIME(),
                        blood_pressure = :bp,
                        temperature = :temp,
                        weight = :weight,
                        pulse_rate = :pulse,
                        diagnosis = :reason,
                        intervention = :treatment
                    WHERE `$col` = :id AND status = 'Ongoing'";
        } else {
            // Employee uses: reason / diagnosis, treatment
            $sql = "UPDATE `$table` SET 
                        status = 'Completed', 
                        time_ended = CURTIME(),
                        blood_pressure = :bp,
                        temperature = :temp,
                        weight = :weight,
                        pulse_rate = :pulse,
                        `reason / diagnosis` = :reason,
                        treatment = :treatment
                    WHERE `$col` = :id AND status = 'Ongoing'";
        }

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
        $table = ($type == 'Student') ? 'student history' : 'employee history';
        $col = strtolower($type) . ' number';

        // Prepare statement for multiple deletes
        // We delete one by one or construct a large query. One by one is safer for composite keys.
        $sql = "DELETE FROM `$table` WHERE `$col` = :id AND `date visit` = :date AND `time visit` = :time";
        
        $this->db->prepare($sql);

        foreach ($visits as $visit) {
            $this->db->bind(':id', $id);
            $this->db->bind(':date', $visit['date']);
            $this->db->bind(':time', $visit['time']);
            
            try {
                $this->db->execute();
            } catch (Exception $e) {
                // Continue or log error
            }
        }
        return true;
    }
}
