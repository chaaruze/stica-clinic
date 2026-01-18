<?php
class Maintenance extends Controller
{
    public function __construct()
    {
        // Add admin check if needed
    }

    public function index()
    {
        $logFile = APPROOT . '/logs/error.log';
        $logs = file_exists($logFile) ? file_get_contents($logFile) : 'No logs found.';

        $data = [
            'logs' => $logs
        ];

        $this->view('maintenance/index', $data);
    }

    public function clear_logs()
    {
        $logFile = APPROOT . '/logs/error.log';
        if (file_exists($logFile)) {
            file_put_contents($logFile, '');
        }
        
        // Return JSON for AJAX
        echo json_encode(['status' => 'success', 'message' => 'Logs cleared successfully.']);
    }

    public function optimize_db()
    {
        $db = new Database();
        
        // List of main tables to optimize
        $tables = [
            '`student details`',
            '`employee details`',
            '`visits`', 
            '`student history`',
            '`employee history`'
        ];

        try {
            foreach ($tables as $table) {
                // Check if table exists first to avoid error? 
                // Optimize table command
                // NOTE: Using query directly. PDO cannot bind table names.
                // Since these are hardcoded strings, it is safe from injection.
                $db->query("OPTIMIZE TABLE $table");
                $db->execute();
            }
            echo json_encode(['status' => 'success', 'message' => 'Database optimized successfully.']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
