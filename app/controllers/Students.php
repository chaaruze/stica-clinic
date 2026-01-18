<?php
class Students extends Controller
{
    private $studentModel;

    private $logModel;

    public function __construct()
    {

        if (!isset($_SESSION['id'])) {
            header('location: ' . URLROOT . '/home/login');
            exit;
        }
        $this->studentModel = $this->model('Student');
        $this->logModel = $this->model('Log');
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
            $data = [
                'student_number' => trim($_POST['student_number']),
                'last_name' => trim($_POST['last_name']),
                'first_name' => trim($_POST['first_name']),
                'middle_name' => trim($_POST['middle_name']),
                'birthdate' => trim($_POST['birthdate'] ?? ''),
                'sex' => trim($_POST['sex'] ?? ''),
                'phone_number' => trim($_POST['phone_number'] ?? ''),
                'course' => trim($_POST['course'] ?? '')
            ];

            if ($this->studentModel->addStudent($data)) {
                $this->logModel->add('Add Student', "Added student {$data['student_number']} - {$data['last_name']}");
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
            $original_student_number = trim($_POST['original_student_number'] ?? '');
            
            $data = [
                'original_student_number' => ($original_student_number !== '') ? $original_student_number : null,
                'student_number' => trim($_POST['student_number']),
                'last_name' => trim($_POST['last_name']),
                'first_name' => trim($_POST['first_name']),
                'middle_name' => trim($_POST['middle_name']),
                'birthdate' => trim($_POST['birthdate'] ?? '') ?: null,
                'sex' => trim($_POST['sex'] ?? '') ?: null,
                'phone_number' => trim($_POST['phone_number'] ?? '') ?: null,
                'course' => trim($_POST['course'] ?? '') ?: null
            ];
            
            if ($this->studentModel->updateStudent($data)) {
                $this->logModel->add('Update Student', "Updated student {$data['student_number']}");
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
                $this->logModel->add('Import Students', "Imported batch: " . count($data) . " records");
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
                    $this->logModel->add('Delete Students', "Deleted IDs: " . implode(', ', $data['ids']));
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
