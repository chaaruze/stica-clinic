@echo off
title STICA Clinic - Portable Launcher
cd /d "%~dp0"

echo ============================================
echo           STICA Clinic Launcher
echo ============================================
echo.

REM Set PHP path - adjust if PHP is installed elsewhere
set PHP_PATH=C:\xampp\php\php.exe

REM Check if PHP exists
if not exist "%PHP_PATH%" (
    echo [ERROR] PHP not found at %PHP_PATH%
    echo [INFO] Please install XAMPP or update PHP_PATH in this file
    pause
    exit /b 1
)

REM Check if database exists, if not run migration
if not exist "database\clinic.sqlite" (
    echo [INFO] First run detected. Creating database...
    "%PHP_PATH%" database\migrate.php
    echo.
)

echo [INFO] Starting web server on http://localhost:8080
echo [INFO] Press Ctrl+C to stop the server
echo.

REM Open browser
start http://localhost:8080

REM Start PHP built-in server with router.php
"%PHP_PATH%" -S localhost:8080 router.php
