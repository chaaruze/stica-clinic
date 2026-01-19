<?php
class Medicines extends Controller {
    public function __construct() {
        if (!isset($_SESSION['id'])) {
            redirect('users/login');
        }
        $this->medicineModel = $this->model('Medicine');
        $this->logModel = $this->model('Log');
    }

    public function index() {
        $medicines = $this->medicineModel->getAll();
        $this->view('medicines/index', ['medicines' => $medicines]);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name' => trim($_POST['name']),
                'unit' => trim($_POST['unit']),
                'stock' => (int)$_POST['stock'],
                'expiration_date' => $_POST['expiration_date']
            ];

            if ($this->medicineModel->add($data)) {
                $this->logModel->add('Add Medicine', "Added {$data['name']} ({$data['stock']} {$data['unit']})");
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error']);
            }
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'id' => $_POST['id'],
                'name' => trim($_POST['name']),
                'unit' => trim($_POST['unit']),
                'stock' => (int)$_POST['stock'],
                'expiration_date' => $_POST['expiration_date']
            ];

            if ($this->medicineModel->update($data)) {
                $this->logModel->add('Update Medicine', "Updated {$data['name']} stock to {$data['stock']}");
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error']);
            }
        }
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            $med = $this->medicineModel->getById($id);
            if ($this->medicineModel->delete($id)) {
                $this->logModel->add('Delete Medicine', "Deleted " . ($med->name ?? 'Medicine ID ' . $id));
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error']);
            }
        }
    }

    public function dispense() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? null;
            $qty = $_POST['qty'] ?? 0;

            if ($id && $qty > 0) {
                $med = $this->medicineModel->getById($id);
                if ($this->medicineModel->dispense($id, $qty)) {
                    $this->logModel->add('Dispense Medicine', "Dispensed {$qty} {$med->unit} of {$med->name}");
                    echo json_encode(['status' => 'success']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Insufficient stock or invalid ID']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Invalid parameters']);
            }
        }
    }
    
    // API to get list for dropdown
    public function list() {
        $medicines = $this->medicineModel->getAll();
        echo json_encode($medicines);
    }
}
