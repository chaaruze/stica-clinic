<?php
class Logs extends Controller {
    public function __construct() {
        if (!isset($_SESSION['id'])) {
            redirect('users/login');
        }
        $this->logModel = $this->model('Log');
    }

    public function index() {
        $logs = $this->logModel->getLogs();
        $data = [
            'logs' => $logs
        ];
        $this->view('logs/index', $data);
    }
}
