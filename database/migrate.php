<?php
/**
 * SQLite Database Migration Script
 * Run this once to initialize the database: php database/migrate.php
 */

$dbPath = __DIR__ . '/clinic.sqlite';

echo "STICA Clinic - Database Migration\n";
echo "==================================\n\n";

// Create/connect to SQLite database
try {
    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "[OK] Connected to SQLite database\n";
} catch (PDOException $e) {
    die("[ERROR] Could not create database: " . $e->getMessage() . "\n");
}

// ================================================================
// USERS TABLE (nurses)
// ================================================================
$db->exec("CREATE TABLE IF NOT EXISTS nurses (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    reset_token_hash VARCHAR(255),
    reset_token_expires_at DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");
echo "[OK] Created 'nurses' table\n";

// ================================================================
// STUDENT DETAILS
// ================================================================
$db->exec("CREATE TABLE IF NOT EXISTS student_details (
    student_number VARCHAR(50) PRIMARY KEY,
    last_name VARCHAR(100) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100),
    birthdate DATE,
    sex VARCHAR(10),
    phone_number VARCHAR(50),
    course VARCHAR(100),
    emergency_contact_name VARCHAR(255),
    emergency_contact_phone VARCHAR(50),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");
echo "[OK] Created 'student_details' table\n";

// ================================================================
// EMPLOYEE DETAILS
// ================================================================
$db->exec("CREATE TABLE IF NOT EXISTS employee_details (
    employee_number VARCHAR(50) PRIMARY KEY,
    last_name VARCHAR(100) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100),
    birthdate DATE,
    sex VARCHAR(10),
    phone_number VARCHAR(50),
    position VARCHAR(100),
    emergency_contact_name VARCHAR(255),
    emergency_contact_phone VARCHAR(50),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");
echo "[OK] Created 'employee_details' table\n";

// ================================================================
// STUDENT HISTORY (Visits)
// ================================================================
$db->exec("CREATE TABLE IF NOT EXISTS student_history (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    student_number VARCHAR(50) NOT NULL,
    date_visit DATE,
    time_visit TIME,
    time_out TIME,
    duration VARCHAR(50),
    reason TEXT,
    diagnosis TEXT,
    treatment TEXT,
    bp VARCHAR(20),
    temperature VARCHAR(20),
    weight VARCHAR(20),
    pulse_rate VARCHAR(20),
    status VARCHAR(50) DEFAULT 'Active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_number) REFERENCES student_details(student_number)
)");
echo "[OK] Created 'student_history' table\n";

// ================================================================
// EMPLOYEE HISTORY (Visits)
// ================================================================
$db->exec("CREATE TABLE IF NOT EXISTS employee_history (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    employee_number VARCHAR(50) NOT NULL,
    date_visit DATE,
    time_visit TIME,
    time_out TIME,
    duration VARCHAR(50),
    reason TEXT,
    diagnosis TEXT,
    treatment TEXT,
    bp VARCHAR(20),
    temperature VARCHAR(20),
    weight VARCHAR(20),
    pulse_rate VARCHAR(20),
    status VARCHAR(50) DEFAULT 'Active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_number) REFERENCES employee_details(employee_number)
)");
echo "[OK] Created 'employee_history' table\n";

// ================================================================
// MEDICINES (Inventory)
// ================================================================
$db->exec("CREATE TABLE IF NOT EXISTS medicines (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) NOT NULL,
    unit VARCHAR(50) NOT NULL,
    stock INTEGER NOT NULL DEFAULT 0,
    expiration_date DATE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");
echo "[OK] Created 'medicines' table\n";

// ================================================================
// ACTIVITY LOGS (Audit Trail)
// ================================================================
$db->exec("CREATE TABLE IF NOT EXISTS activity_logs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    user_name VARCHAR(255) NOT NULL,
    action VARCHAR(255) NOT NULL,
    details TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");
echo "[OK] Created 'activity_logs' table\n";

// ================================================================
// LOGIN LOGS
// ================================================================
$db->exec("CREATE TABLE IF NOT EXISTS login_logs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    ip_address VARCHAR(50),
    browser TEXT,
    login_time DATETIME DEFAULT CURRENT_TIMESTAMP
)");
echo "[OK] Created 'login_logs' table\n";

// ================================================================
// REMEMBER TOKENS
// ================================================================
$db->exec("CREATE TABLE IF NOT EXISTS remember_tokens (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    token VARCHAR(255) NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");
echo "[OK] Created 'remember_tokens' table\n";

echo "\n==================================\n";
echo "Migration complete!\n";
echo "Database location: $dbPath\n";
echo "\nYou can now run the application.\n";
echo "On first launch, you'll be prompted to create an admin account.\n";

