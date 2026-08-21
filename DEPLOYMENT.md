# AttendAlert — Production Deployment Guide

This guide details the step-by-step instructions for deploying **AttendAlert** to a production web server (Apache, Nginx, cPanel, or Cloud VPS).

---

## 📋 System Requirements
- **Web Server**: Apache 2.4+ / Nginx
- **PHP**: PHP 8.0 or higher (with `mysqli` extension enabled)
- **Database**: MySQL 5.7+ / MariaDB 10.3+
- **Browser**: Modern desktop & mobile browsers (Chrome, Edge, Firefox, Safari)

---

## 🚀 Step 1: Database Setup (MySQL)

1. Open **phpMyAdmin** or your MySQL command line client.
2. Create a new database named `attendalert`:
   ```sql
   CREATE DATABASE attendalert CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
3. Import the complete database schema from [`database/schema.sql`](file:///c:/xampp/htdocs/AttendAlert/database/schema.sql):
   ```bash
   mysql -u root -p attendalert < database/schema.sql
   ```
4. *(Optional)* Execute [`api/seed_data.php`](file:///c:/xampp/htdocs/AttendAlert/api/seed_data.php) once in your browser to automatically seed initial sample records.

---

## ⚙️ Step 2: Configure Database Credentials

Edit `api/config.php` to set your production database parameters or set system Environment Variables (`DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`):

```php
$DB_HOST = getenv("DB_HOST") ?: "localhost";
$DB_USER = getenv("DB_USER") ?: "your_db_username";
$DB_PASS = getenv("DB_PASS") !== false ? getenv("DB_PASS") : "your_db_password";
$DB_NAME = getenv("DB_NAME") ?: "attendalert";
```

---

## 🌐 Step 3: Web Server Upload

1. Upload all project files into your web root directory (e.g. `/var/www/html/AttendAlert` or `public_html/AttendAlert`).
2. Ensure correct directory permissions:
   - PHP files & directories: `755`
   - File uploads directory (if any): `775`

---

## 🔒 Step 4: Production Security Checklist
- [x] Passwords are encrypted using `password_hash()` (Bcrypt).
- [x] Prepared SQL Statements (`mysqli::prepare`) are used across all API endpoints to prevent SQL injection.
- [x] CORS headers are configured in `api/config.php`.
- [x] Disable default testing links in `index.html` prior to final public deployment.

---

## 🔑 Default Production Admin & User Accounts

| Role | Email | Password |
|---|---|---|
| 🛡️ **Administrator** | `admin@college.edu` | `admin123` |
| 👨‍🏫 **Teacher** | `teacher@college.edu` | `teach123` |
| 🎓 **Student** | `student@college.edu` | `stud123` |
