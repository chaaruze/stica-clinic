# 🏥 STICA Clinic Management System

A modern, responsive web-based clinic management system built for STI College Alabang. Streamlines student and employee health visit tracking, consultations, and medical records management.

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![SQLite](https://img.shields.io/badge/SQLite-003B57?style=for-the-badge&logo=sqlite&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

---

## ✨ Features

### 👥 Patient Management
- **Student Records** - Manage student information with ID, name, age, gender, contact, and course/year
- **Employee Records** - Track employee details including position and contact information
- **Emergency Contacts** - Dedicated fields for emergency contact name and phone number
- **Excel Import** - Bulk import students/employees from Excel files
- **Multi-Select Delete** - Select and delete multiple records at once

### 🩺 Consultation Tracking
- **Real-time Timer** - Track consultation duration with live timer
- **Vital Signs Recording** - Blood pressure, temperature, weight, pulse rate (with color-coded limit alerts)
- **Smart Quick-Picks** - One-click common diagnoses and treatments (e.g., Hot/Cold Compress, Paracetamol)
- **Diagnosis & Treatment** - Document reasons, diagnoses, and interventions
- **Visit History** - Complete history of all clinic visits per patient

### 📊 Dashboard
- **Daily Statistics** - Today's visits, active consultations, total patients
- **Visual Charts** - Weekly visit trends with Chart.js
- **Clinic Activity Feed** - Real-time activity tracking
- **Audit Trails** - Track system usage, user logins, and record changes

### 💊 Medicine Inventory
- **Stock Management** - Track medicine quantities and units
- **Expiration Tracking** - Monitor medicine expiration dates
- **Quick Updates** - Easy stock adjustments

### 🚀 Quick Actions & Tools
- **Consultation Print** - One-click print for consultation records (A4 format)
- **Instant Search** - DataTables-powered search and filtering
- **Responsive Design** - Works on desktop and mobile devices

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|------------|
| Backend | PHP 7.4+ (Custom MVC Framework) |
| Database | SQLite (portable, no setup required) |
| Frontend | HTML5, CSS3, JavaScript |
| UI Framework | Bootstrap 5.3 |
| Charts | Chart.js |
| Tables | DataTables |
| Icons | Font Awesome 6 |

---

## 📦 Installation

### Prerequisites
- PHP 7.4 or higher
- No additional database server required (uses SQLite)

### Quick Start (Recommended)

1. **Clone the repository**
   ```bash
   git clone https://github.com/chaaruze/stica-clinic.git
   cd stica-clinic
   ```

2. **Initialize the database**
   ```bash
   php database/migrate.php
   ```

3. **Start the development server**
   ```bash
   # Using the included batch file (Windows)
   start.bat
   
   # Or manually with PHP built-in server
   php -S localhost:8080 router.php
   ```

4. **Access the application**
   ```
   http://localhost:8080
   ```

### Alternative: XAMPP/WAMP Setup

1. **Move to web server directory**
   ```bash
   # For XAMPP
   mv stica-clinic C:/xampp/htdocs/
   ```

2. **Configure URL root** (if needed)
   - Edit `app/config/config.php`
   ```php
   define('URLROOT', 'http://localhost/stica-clinic');
   ```

3. **Access the application**
   ```
   http://localhost/stica-clinic
   ```

---

## 📁 Project Structure

```
stica-clinic/
├── app/
│   ├── config/          # Configuration files
│   ├── controllers/     # MVC Controllers (10 controllers)
│   │   ├── Dashboard.php
│   │   ├── Employees.php
│   │   ├── Home.php     # Authentication
│   │   ├── Logs.php
│   │   ├── Maintenance.php
│   │   ├── Medicines.php
│   │   ├── Students.php
│   │   ├── System.php
│   │   ├── Users.php
│   │   └── Visits.php
│   ├── core/            # Core framework classes
│   │   ├── App.php      # URL Router
│   │   ├── Controller.php
│   │   └── Database.php # PDO wrapper
│   ├── helpers/         # Helper functions
│   ├── libraries/       # PHPMailer for emails
│   ├── models/          # Database models (6 models)
│   └── views/           # View templates (12 directories)
├── public/
│   ├── index.php        # Entry point
│   └── assets/          # CSS, JS, Images
├── database/
│   ├── clinic.sqlite    # SQLite database
│   └── migrate.php      # Schema migration
└── router.php           # PHP dev server router
```

---

## 🗄️ Database Schema

| Table | Description |
|-------|-------------|
| `nurses` | Staff/admin login credentials |
| `student_details` | Student information (ID, name, contact, course) |
| `employee_details` | Employee information (ID, name, contact, position) |
| `student_history` | Student clinic visit records with vitals |
| `employee_history` | Employee clinic visit records with vitals |
| `medicines` | Medicine inventory (name, stock, expiration) |
| `activity_logs` | Audit trail of all user actions |
| `login_logs` | Login history tracking |
| `remember_tokens` | Persistent login tokens |

---

## 🎨 Screenshots

<img width="1919" height="907" alt="Dashboard" src="https://github.com/user-attachments/assets/cba10e96-8e3b-4ff8-beeb-786936c47e51" />
<img width="1919" height="907" alt="Student Records" src="https://github.com/user-attachments/assets/47efd5eb-fc1e-41a2-921c-0de6d7f860cf" />
<img width="1919" height="907" alt="Consultation" src="https://github.com/user-attachments/assets/d752d55e-7175-4db3-ac0f-955766120389" />
<img width="1902" height="906" alt="Visit History" src="https://github.com/user-attachments/assets/8d4d5120-58bc-4895-98f0-cc4023eba6a1" />

---

## 👨‍💻 Author

**chaaruze**

- GitHub: [@chaaruze](https://github.com/chaaruze)

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

<p align="center">Made with ❤️ for STI College Alabang</p>
