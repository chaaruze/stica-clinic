<?php
class Dashboard extends Controller
{
    public function __construct()
    {

        if (!isset($_SESSION['id'])) {
            header('location: ' . URLROOT . '/home/login');
            exit;
        }
    }

    public function index()
    {
        $visitModel = $this->model('Visit');

        $data = [
            'recentVisits' => $visitModel->getCombinedRecentVisits(20),
            'activeVisits' => $visitModel->getActiveVisits() // Implement this in Model
        ];

        $this->view('dashboard/index', $data);
    }

    public function chart_data()
    {
        $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
        $month = isset($_GET['month']) ? $_GET['month'] : 'all';

        $visitModel = $this->model('Visit');
        $traffic = $visitModel->getTrafficData($year, $month);

        $labels = [];
        $visits = [];

        // Pre-fill labels if needed or just use results
        // For simplicity, just mapping results. Chart.js adapts.
        foreach ($traffic as $row) {
            $labels[] = $row->label;
            $visits[] = $row->count;
        }

        echo json_encode([
            'labels' => $labels,
            'visits' => $visits
        ]);
    }
}
