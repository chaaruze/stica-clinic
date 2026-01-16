# 🏥 STICA Clinic Management System

A modern, responsive web-based clinic management system built for STI College Alabang. Streamlines student and employee health visit tracking, consultations, and medical records management.

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

---

## ✨ Features

### 👥 Patient Management
- **Student Records** - Manage student information with ID, name, age, gender, contact, and course/year
- **Employee Records** - Track employee details including position and contact information
- **Excel Import** - Bulk import students/employees from Excel files
- **Multi-Select Delete** - Select and delete multiple records at once

### 🩺 Consultation Tracking
- **Real-time Timer** - Track consultation duration with live timer
- **Vital Signs Recording** - Blood pressure, temperature, weight, pulse rate
- **Diagnosis & Treatment** - Document reasons, diagnoses, and interventions
- **Visit History** - Complete history of all clinic visits per patient

### 📊 Dashboard
- **Daily Statistics** - Today's visits, active consultations, total patients
- **Visual Charts** - Weekly visit trends with Chart.js
- **Clinic Activity Feed** - Real-time activity tracking
- **Quick Notes** - Persistent notepad for clinic staff

### 🚀 Quick Actions
- **Quick Ball FAB** - Floating action button for fast access to add patients
- **Instant Search** - DataTables-powered search and filtering
- **Responsive Design** - Works on desktop and mobile devices

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|------------|
| Backend | PHP 7.4+ (Custom MVC Framework) |
| Database | MySQL / MariaDB |
| Frontend | HTML5, CSS3, JavaScript |
| UI Framework | Bootstrap 5.3 |
| Charts | Chart.js |
| Tables | DataTables |
| Icons | Font Awesome 6 |

---

## 📦 Installation

### Prerequisites
- XAMPP, WAMP, or any PHP development environment
- PHP 7.4 or higher
- MySQL 5.7+ or MariaDB
- Composer (optional)

### Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/chaaruze/stica-clinic.git
   ```

2. **Move to web server directory**
   ```bash
   # For XAMPP
   mv stica-clinic C:/xampp/htdocs/
   ```

3. **Create the database**
   - Open phpMyAdmin
   - Create a new database named `stica_clinic`
   - Import `database/stica_clinic.sql` (if available) or create tables manually

4. **Configure database connection**
   - Edit `app/config/config.php`
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'stica_clinic');
   ```

5. **Access the application**
   ```
   http://localhost/stica-clinic
   ```
   
   **Or use the Auto-Launcher:**
   - Double-click `LAUNCH_APP.bat` in the project folder to start XAMPP and open the browser automatically.

---

## 📁 Project Structure

```
stica-clinic/
├── app/
│   ├── config/          # Configuration files
│   ├── controllers/     # MVC Controllers
│   ├── core/            # Core framework classes
│   ├── helpers/         # Helper functions
│   ├── models/          # Database models
│   └── views/           # View templates
│       ├── dashboard/
│       ├── employees/
│       ├── home/
│       ├── layouts/
│       ├── students/
│       └── visits/
├── public/
│   └── assets/          # CSS, JS, Images
└── database/            # SQL files
```

---

## 🗄️ Database Schema

### Tables

| Table | Description |
|-------|-------------|
| `student details` | Student information (ID, name, age, gender, contact, course) |
| `employee details` | Employee information (ID, name, age, gender, contact, position) |
| `student history` | Student clinic visit records |
| `employee history` | Employee clinic visit records |
| `users` | Admin/staff login credentials |

---

## 🎨 Screenshots

*Coming soon...*

---

## 👨‍💻 Author

**chaaruze**

- GitHub: [@chaaruze](https://github.com/chaaruze)

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 🙏 Acknowledgments

- STI College Alabang
- Bootstrap Team
- Chart.js Contributors
- DataTables

---

<p align="center">Made with ❤️ for STI College Alabang</p>
