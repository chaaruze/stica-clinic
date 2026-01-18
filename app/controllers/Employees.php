<?php
class Employees extends Controller
{
    private $employeeModel;

    private $logModel;

    public function __construct()
    {

        if (!isset($_SESSION['id'])) {
            header('location: ' . URLROOT . '/home/login');
            exit;
        }
        $this->employeeModel = $this->model('Employee');
        $this->logModel = $this->model('Log');
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
            $data = [
                'employee_id' => trim($_POST['employee_id']),
                'last_name' => trim($_POST['last_name']),
                'first_name' => trim($_POST['first_name']),
                'middle_name' => trim($_POST['middle_name']),
                'birthdate' => trim($_POST['birthdate'] ?? ''),
                'sex' => trim($_POST['sex'] ?? ''),
                'phone_number' => trim($_POST['phone_number'] ?? ''),
                'position' => trim($_POST['position'] ?? '')
            ];

            if ($this->employeeModel->addEmployee($data)) {
                $this->logModel->add('Add Employee', "Added employee {$data['employee_id']} - {$data['last_name']}");
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error']);
            }
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $original_employee_number = trim($_POST['original_employee_number'] ?? '');

            $data = [
                'original_employee_number' => ($original_employee_number !== '') ? $original_employee_number : null,
                'employee_number' => trim($_POST['employee_number']),
                'last_name' => trim($_POST['last_name']),
                'first_name' => trim($_POST['first_name']),
                'middle_name' => trim($_POST['middle_name']),
                'birthdate' => trim($_POST['birthdate'] ?? '') ?: null,
                'sex' => trim($_POST['sex'] ?? '') ?: null,
                'phone_number' => trim($_POST['phone_number'] ?? '') ?: null,
                'position' => trim($_POST['position'] ?? '') ?: null
            ];

            if ($this->employeeModel->updateEmployee($data)) {
                $this->logModel->add('Update Employee', "Updated employee {$data['employee_number']}");
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
                $this->logModel->add('Import Employees', "Imported user batch: " . count($data) . " records");
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
                    $this->logModel->add('Delete Employees', "Deleted IDs: " . implode(', ', $data['ids']));
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
