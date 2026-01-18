<?php
class System extends Controller
{
    public function __construct()
    {
        // Ideally verify admin privileges here
        // if (!isAdmin()) redirect('/users/login');
    }

    public function update()
    {
        $data = [
            'status' => 'idle',
            'message' => 'Ready to check for updates.',
            'output' => ''
        ];
        $this->view('system/update', $data);
    }

    public function checkForUpdates()
    {
        // IMPORTANT: Ensure the web server user can run git commands
        // This might require git safe.directory config or SSH keys
        
        $output = [];
        $return_var = 0;
        
        // Navigate to project root
        // Assuming this file is in app/controllers, we need to go up two levels to root
        $projectRoot = dirname(dirname(dirname(__FILE__)));
        chdir($projectRoot);

        // Fetch latest info
        exec("git fetch origin main 2>&1", $output, $return_var);
        
        // Compare HEAD with origin/main
        exec("git status -uno", $status_output, $status_return);
        
        $isBehind = false;
        foreach($status_output as $line) {
            if (strpos($line, 'Your branch is behind') !== false) {
                $isBehind = true;
                break;
            }
        }

        header('Content-Type: application/json');
        echo json_encode([
            'status' => $isBehind ? 'available' : 'uptodate',
            'message' => $isBehind ? 'New updates are available!' : 'System is up to date.',
            'details' => implode("\n", $output) . "\n" . implode("\n", $status_output)
        ]);
    }

    public function performUpdate()
    {
        $output = [];
        $return_var = 0;
        
        $projectRoot = dirname(dirname(dirname(__FILE__)));
        chdir($projectRoot);
        
        // Run git pull
        // Using --rebase can handle local harmless changes better sometimes, but standard pull is safer for now
        exec("git pull origin main 2>&1", $output, $return_var);
        
        $success = ($return_var === 0);

        header('Content-Type: application/json');
        echo json_encode([
            'status' => $success ? 'success' : 'error',
            'message' => $success ? 'Update completed successfully.' : 'Update failed. Check output for details.',
            'output' => implode("\n", $output)
        ]);
    }
}
