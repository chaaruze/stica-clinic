<?php
// Load Config
require_once 'config/config.php';

// Error Logging Configuration
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/logs/error.log');
error_reporting(E_ALL);

// Load Libraries
// Core Libraries
require_once 'core/App.php';
require_once 'core/Controller.php';
require_once 'core/Database.php';

// Load Helpers
require_once 'helpers/url_helper.php';
require_once 'helpers/session_helper.php';

// Auto-Login Logic
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id']) && isset($_COOKIE['remember_me'])) {
    // Format: user_id:token
    $parts = explode(':', $_COOKIE['remember_me']);
    if (count($parts) === 2) {
        $user_id = $parts[0];
        $token = $parts[1];

        $db = new Database();

        // SQLite: use datetime('now') instead of NOW()
        $db->query("SELECT * FROM remember_tokens WHERE user_id = :uid AND token = :token AND expires_at > datetime('now')");
        $db->bind(':uid', $user_id);
        $db->bind(':token', $token);

        if ($db->single()) {
            // Valid token, log in user
            $db->query("SELECT * FROM nurses WHERE id = :uid");
            $db->bind(':uid', $user_id);
            $user = $db->single();

            if ($user) {
                $_SESSION['id'] = $user->id;
                $_SESSION['username'] = $user->username;
                $_SESSION['name'] = $user->name ?? $user->username;
            }
        }
    }
}
