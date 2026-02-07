@echo off
title STICA Clinic - Portable Launcher
cd /d "%~dp0"

echo ============================================
echo           STICA Clinic Launcher
echo ============================================
echo.

REM Check if database exists, if not run migration
if not exist "database\clinic.sqlite" (
    echo [INFO] First run detected. Creating database...
    php database\migrate.php
    echo.
)

echo [INFO] Starting web server on http://localhost:8080
echo [INFO] Press Ctrl+C to stop the server
echo.

REM Open browser
start http://localhost:8080

REM Start PHP built-in server
php -S localhost:8080 -t public
