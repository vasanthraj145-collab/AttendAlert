# 📋 AttendAlert — Enterprise Attendance & Academic Marks Management System

![AttendAlert](https://img.shields.io/badge/Status-Production%20Ready-brightgreen)
![PHP](https://img.shields.io/badge/PHP-8.0%2B-blue)
![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-orange)
![License](https://img.shields.io/badge/License-MIT-purple)

**AttendAlert** is an enterprise-grade, full-stack college web application designed to automate student attendance tracking, auto-trigger SMS alerts to parents for absences, manage semester-wise exam mark sheets (CIA-1, CIA-2, Model, Semester), provide target attendance calculators for students, and deliver interactive Chart.js analytics for college administrators.

---

## 🌟 Key Features & Modules

### 🛡️ 1. Administrator Dashboard
- **Real-Time College Metrics**: Total active students, teachers, classes, and daily attendance percentages.
- **User Management**: Add, edit, or remove student & teacher accounts directly synced with MySQL database.
- **Notification Hub**: Send instant SMS/email announcements (Exams, Events, Emergency Closure).
- **Interactive Analytics**: Chart.js Subject Performance Bar Chart, Risk Distribution Donut Chart, and Monthly Attendance Trend Line Chart.
- **Export Capabilities**: One-click **PDF Report Export** and **Excel / CSV Data Download**.

### 👨‍🏫 2. Teacher Portal
- **Daily Attendance Marking Grid**: Mark students Present (P), Absent (A), or Late (L) per period.
- **Automated SMS Alerts**: Auto-dispatches SMS notifications to parents for absent students.
- **Exam Mark Result Upload**: Upload results via Excel/CSV file or manual input grid with **Automatic Register Number Mapping**.
- **OD / Leave Approval Panel**: Review and approve/reject student On-Duty and Medical Leave applications.

### 🎓 3. Student Portal
- **Semester Exam Mark Sheet**: Tabbed navigation for **Semester 1 through 6** and exam type pills (**CIA-1, CIA-2, MODEL, SEMESTER**) showing subject scores, grades (O, A+, A, B), and SGPA summary.
- **75% Attendance & Bunk Calculator**: Calculates allowed absences or required consecutive classes to maintain 75% exam eligibility.
- **OD / Leave Application**: Online submission system for On-Duty (Symposium, Culturals, Sports) and Medical Leave.
- **Color-Coded Monthly Calendar**: Visual date-by-date attendance record (Green = Present, Red = Absent, Yellow = OD/Leave, Blue = Holiday).

---

## 📁 Repository Directory Structure

```
AttendAlert/
├── api/
│   ├── config.php                 # Database connection & CORS configuration
│   ├── login.php                  # Secure authentication API with Bcrypt
│   ├── register.php               # Student & Teacher registration API
│   ├── get_students.php           # Retrieve student list
│   ├── get_teachers.php           # Retrieve teacher list
│   ├── save_attendance.php        # Attendance recording API
│   ├── upload_marks.php           # Exam results upload API
│   ├── get_marks.php              # Student mark sheet API
│   ├── send_notification.php      # Bulk announcement SMS API
│   └── get_notifications.php      # Notification feed API
├── database/
│   ├── schema.sql                 # Complete MySQL schema & initial seed data
├── index.html                     # Responsive single-page app frontend (HTML5/CSS3/JS)
├── test_app.php                   # Automated SDLC & E2E Test Suite
├── DEPLOYMENT.md                  # Web server deployment guide
├── GITHUB_DEPLOYMENT.md           # GitHub repository push guide
├── sample_result_upload.csv       # Sample CSV result file for upload testing
└── .gitignore                     # Git ignore rules
```

---

## 🧪 Automated SDLC Testing

Run the automated E2E test runner to verify system integrity before deployment:

```bash
php test_app.php
```

---

## 🚀 GitHub Deployment Instructions

1. **Initialize Git Repository**:
   ```bash
   git init
   git add .
   git commit -m "Initial production release of AttendAlert v1.0.0"
   ```
2. **Connect to Remote GitHub Repository**:
   ```bash
   git remote add origin https://github.com/YOUR_USERNAME/AttendAlert.git
   git branch -M main
   git push -u origin main
   ```

---

## 📄 License
This project is released under the MIT License.
