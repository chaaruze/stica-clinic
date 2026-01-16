<?php
class Students extends Controller
{
    private $studentModel;

    public function __construct()
    {

        if (!isset($_SESSION['id'])) {
            header('location: ' . URLROOT . '/home/login');
            exit;
        }
        $this->studentModel = $this->model('Student');
    }

    public function index()
    {
        $students = $this->studentModel->getStudents();
        $data = [
            'students' => $students
        ];
        $this->view('students/index', $data);
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'student_number' => trim($_POST['student_number']),
                'last_name' => trim($_POST['last_name']),
                'first_name' => trim($_POST['first_name']),
                'middle_name' => trim($_POST['middle_name']),
                'age' => trim($_POST['age'] ?? ''),
                'sex' => trim($_POST['sex'] ?? ''),
                'phone_number' => trim($_POST['phone_number'] ?? ''),
                'course' => trim($_POST['course'] ?? '')
            ];

            if ($this->studentModel->addStudent($data)) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error']);
            }
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // In a real app, sanitize and validate
            $data = $_POST;
            if ($this->studentModel->updateStudent($data)) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error']);
            }
        }
    }

    public function import()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $json = $_POST['data'];
            $data = json_decode($json, true);

            if ($this->studentModel->addStudentsBatch($data)) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error']);
            }
        }
    }

    public function details($id)
    {
        $student = $this->studentModel->getStudentById($id);
        // Add history fetching logic here
        // $history = $this->visitModel->getHistoryByStudentId($id); 
        // For now, passing empty/mock history or implementing getHistory
        // I need to add getStudentHistory to Student Model first or Visit Model

        // Let's assume we add it to Student Model for cohesion or use direct query if needed for speed
        $history = $this->studentModel->getStudentHistory($id);

        $data = [
            'student' => $student,
            'history' => $history
        ];
        $this->view('students/details', $data);
    }

    public function deleteMultiple()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            
            if (!empty($data['ids'])) {
                if ($this->studentModel->deleteStudents($data['ids'])) {
                    echo json_encode(['status' => 'success']);
                } else {
                    echo json_encode(['status' => 'error']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No IDs provided']);
            }
        }
    }
}
