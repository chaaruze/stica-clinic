<?php
class Employees extends Controller
{
    private $employeeModel;

    public function __construct()
    {

        if (!isset($_SESSION['id'])) {
            header('location: ' . URLROOT . '/home/login');
            exit;
        }
        $this->employeeModel = $this->model('Employee');
    }

    public function index()
    {
        $employees = $this->employeeModel->getEmployees();
        $data = [
            'employees' => $employees
        ];
        $this->view('employees/index', $data);
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'employee_id' => trim($_POST['employee_id']),
                'last_name' => trim($_POST['last_name']),
                'first_name' => trim($_POST['first_name']),
                'middle_name' => trim($_POST['middle_name']),
                'age' => trim($_POST['age'] ?? ''),
                'sex' => trim($_POST['sex'] ?? ''),
                'phone_number' => trim($_POST['phone_number'] ?? ''),
                'position' => trim($_POST['position'] ?? '')
            ];

            if ($this->employeeModel->addEmployee($data)) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error']);
            }
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $_POST;
            if ($this->employeeModel->updateEmployee($data)) {
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

            if ($this->employeeModel->addEmployeesBatch($data)) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error']);
            }
        }
    }

    public function details($id)
    {
        $employee = $this->employeeModel->getEmployeeById($id);
        $history = $this->employeeModel->getEmployeeHistory($id);
        $data = [
            'employee' => $employee,
            'history' => $history
        ];
        $this->view('employees/details', $data);
    }

    public function deleteMultiple()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            
            if (!empty($data['ids'])) {
                if ($this->employeeModel->deleteEmployees($data['ids'])) {
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
