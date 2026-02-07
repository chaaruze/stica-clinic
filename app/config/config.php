<?php
// SQLite Database Path
define('DB_PATH', dirname(dirname(dirname(__FILE__))) . '/database/clinic.sqlite');

// App Root
define('APPROOT', dirname(dirname(__FILE__)));
// URL Root
define('URLROOT', 'http://localhost:8080');
// Site Name
define('SITENAME', 'STICA Clinic');

// SMTP Params (for password reset emails - optional)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USER', '');
define('SMTP_PASS', '');
define('SMTP_PORT', 587);
define('SMTP_FROM_EMAIL', 'no-reply@stica-clinic.com');
define('SMTP_FROM_NAME', 'STICA Clinic');
