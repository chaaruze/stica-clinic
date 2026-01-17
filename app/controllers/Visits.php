<?php
class Visits extends Controller
{
    public function __construct()
    {
        if (!isset($_SESSION['id'])) {
            header('location: ' . URLROOT . '/home/login');
            exit;
        }
    }

    public function active($type, $id)
    {
        // Check if there's already an ONGOING visit for this user
        $visitModel = $this->model('Visit');
        $existing = $visitModel->findActiveVisit($type, $id);

        if (!$existing) {
            // Create new ongoing visit
            $visitModel->startVisit($type, $id);
            $existing = $visitModel->findActiveVisit($type, $id);
        }

        // Fetch User Details to display on the active page
        $userModel = ($type == 'Student') ? $this->model('Student') : $this->model('Employee');
        $userMethod = ($type == 'Student') ? 'getStudentById' : 'getEmployeeById';
        $user = $userModel->$userMethod($id);

        $data = [
            'visit' => $existing,
            'user' => $user,
            'type' => ucfirst($type)
        ];

        $this->view('visits/active', $data);
    }

    public function save_visit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $visitModel = $this->model('Visit');
            // Assuming POST data contains visit_id, type, id, vitals, etc.
            if ($visitModel->endVisit($_POST)) {
                // Redirect to details or show success? 
                // User wants it to save.
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error']);
            }
        }
    }

    public function details($type, $id)
    {
        $visitModel = $this->model('Visit');

        // Fetch specific visit details
        // Note: We need to add getVisitById to Visit model
        $visit = $visitModel->getVisitById($type, $id);

        $data = [
            'visit' => $visit,
            'type' => ucfirst($type)
        ];

        $this->view('visits/details', $data);
    }
    public function delete_history()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            $id = $data['id'] ?? '';
            $type = $data['type'] ?? '';
            $visits = $data['visits'] ?? []; // Array of {date: '', time: ''}

            if (!empty($id) && !empty($type) && !empty($visits)) {
                $visitModel = $this->model('Visit');
                if ($visitModel->deleteVisits($type, $id, $visits)) {
                    echo json_encode(['status' => 'success']);
                } else {
                    echo json_encode(['status' => 'error']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
            }
        }
    }
}
