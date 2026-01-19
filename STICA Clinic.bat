@echo off
echo Starting XAMPP Services...

:: Start Apache and MySQL using XAMPP control
start "" "C:\xampp\xampp_start.exe"

:: Wait a moment for services to initialize
timeout /t 3 /nobreak > nul

echo Starting STICA Clinic...
start chrome --app=http://localhost/stica-clinic/public/dashboard
exit
