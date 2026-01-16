@echo off
TITLE STICA Clinic Launcher
COLOR 0B

echo.
echo  =======================================================
echo   STICA Clinic Management System Auto-Launcher
echo  =======================================================
echo.

echo  [1/3] Checking XAMPP status...

:: Check if standard local XAMPP paths exist
if exist "C:\xampp\apache_start.bat" (
    echo  [2/3] Attempting to start Apache and MySQL...
    
    :: Start Apache minimized
    if exist "C:\xampp\apache\bin\httpd.exe" (
        tasklist /FI "IMAGENAME eq httpd.exe" 2>NUL | find /I /N "httpd.exe">NUL
        if "%ERRORLEVEL%"=="0" (
            echo        - Apache is already running.
        ) else (
            echo        - Starting Apache...
            start /min "" "C:\xampp\apache_start.bat"
        )
    )

    :: Start MySQL minimized
    if exist "C:\xampp\mysql\bin\mysqld.exe" (
        tasklist /FI "IMAGENAME eq mysqld.exe" 2>NUL | find /I /N "mysqld.exe">NUL
        if "%ERRORLEVEL%"=="0" (
            echo        - MySQL is already running.
        ) else (
            echo        - Starting MySQL...
            start /min "" "C:\xampp\mysql_start.bat"
        )
    )
    
    :: Wait for servers to initialize
    echo.
    echo        Waiting for servers to initialize...
    timeout /t 5 /nobreak >nul
) else (
    echo.
    echo  [!] XAMPP start scripts not found at C:\xampp.
    echo      Please make sure your server is running manually.
    timeout /t 2 /nobreak >nul
)

echo.
echo  [3/3] Launching Application in Browser...
start http://localhost/stica-clinic

echo.
echo  Done! You can minimize this window.
echo.
timeout /t 3 >nul
exit
