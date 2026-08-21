-- ================================================
-- AttendAlert Complete Production Schema
-- Import this inside your selected database in phpMyAdmin
-- ================================================

-- ---------------------------------
-- USERS (login: admin / teacher / student)
-- ---------------------------------
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,   -- stored hashed password (password_hash)
  role ENUM('admin','teacher','student') NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------
-- STUDENTS (linked to user account if available)
-- ---------------------------------
CREATE TABLE IF NOT EXISTS students (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  name VARCHAR(100) NOT NULL,
  roll_no VARCHAR(20) NOT NULL,
  class_name VARCHAR(50) NOT NULL,
  parent_phone VARCHAR(15) NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ---------------------------------
-- TEACHERS (linked to user account)
-- ---------------------------------
CREATE TABLE IF NOT EXISTS teachers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  department VARCHAR(50) DEFAULT 'BCA',
  phone VARCHAR(15),
  subjects VARCHAR(255),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ---------------------------------
-- ATTENDANCE (one row per student per day)
-- ---------------------------------
CREATE TABLE IF NOT EXISTS attendance (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  att_date DATE NOT NULL,
  status ENUM('P','A','L') NOT NULL,   -- Present / Absent / Late
  marked_by INT,                        -- teacher user id
  sms_status ENUM('pending','sent','failed') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
  FOREIGN KEY (marked_by) REFERENCES users(id) ON DELETE SET NULL,
  UNIQUE KEY unique_att (student_id, att_date)
);

-- ---------------------------------
-- NOTIFICATIONS / SMS ALERTS
-- ---------------------------------
CREATE TABLE IF NOT EXISTS notifications (
  id INT AUTO_INCREMENT PRIMARY KEY,
  type VARCHAR(50) NOT NULL,
  title VARCHAR(150) NOT NULL,
  message TEXT NOT NULL,
  recipients VARCHAR(100) NOT NULL,
  dept VARCHAR(50) DEFAULT 'All',
  sms_count INT DEFAULT 0,
  sent_by INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (sent_by) REFERENCES users(id) ON DELETE SET NULL
);

-- ---------------------------------
-- DEFAULT SAMPLE USERS
-- Password for all below defaults is: admin123 / teach123 / stud123
-- ---------------------------------
INSERT INTO users (id, name, email, password, role) VALUES
(1, 'Admin User', 'admin@college.edu', '$2y$10$n0DC5GFCxe391N1IVXdWQ.39uvifixfxLhNju0dewD9mdGj5JUhE2', 'admin'),
(2, 'Mr. Senthil Kumar', 'teacher@college.edu', '$2y$10$PijDnMCawd6rh1BT4V45cOB1VbS1y5f1gCcXRjJckt8weEbTr7Yim', 'teacher'),
(3, 'Mrs. Kavitha R', 'kavitha@college.edu', '$2y$10$PijDnMCawd6rh1BT4V45cOB1VbS1y5f1gCcXRjJckt8weEbTr7Yim', 'teacher'),
(4, 'Ravi Kumar', 'student@college.edu', '$2y$10$7ENfEtYNtyn2.oTtbnhQKekWzBUDQzDVXys/jNFcW5.V0UUjch.L.', 'student'),
(5, 'Meena S', 'meena@college.edu', '$2y$10$7ENfEtYNtyn2.oTtbnhQKekWzBUDQzDVXys/jNFcW5.V0UUjch.L.', 'student'),
(6, 'Arjun J', 'arjun@college.edu', '$2y$10$7ENfEtYNtyn2.oTtbnhQKekWzBUDQzDVXys/jNFcW5.V0UUjch.L.', 'student')
ON DUPLICATE KEY UPDATE password=VALUES(password), role=VALUES(role);

INSERT INTO teachers (user_id, department, phone, subjects) VALUES
(2, 'BCA', '9876543210', 'Operating Systems, DBMS'),
(3, 'BCA', '8765432109', 'Web Technology, Java')
ON DUPLICATE KEY UPDATE department=VALUES(department);

INSERT INTO students (id, user_id, name, roll_no, class_name, parent_phone) VALUES
(1, 4, 'Ravi Kumar', '045', 'BCA III', '9876543210'),
(2, 5, 'Meena S', '028', 'BCA II', '9765432109'),
(3, 6, 'Arjun J', '012', 'BCA I', '9654321098'),
(4, NULL, 'Priya M', '029', 'BCA II', '9543210987'),
(5, NULL, 'Karthik R', '067', 'BCA III', '9432109876'),
(6, NULL, 'Surya K', '068', 'BCA III', '9321098765')
ON DUPLICATE KEY UPDATE roll_no=VALUES(roll_no);

INSERT INTO notifications (type, title, message, recipients, dept, sms_count, sent_by) VALUES
('exam', 'Internal Exam Schedule', 'Internal Exam scheduled Nov 10-14. Hall tickets at office.', 'Students+Teachers', 'All', 248, 1),
('event', 'Annual Day Oct 28', 'College Annual Day celebration at Main Auditorium.', 'Students+Teachers', 'All', 248, 1),
('holiday', 'Diwali Holiday Notice', 'Diwali holiday from Oct 31 to Nov 3. Happy Diwali!', 'All', 'All', 312, 1);

-- ---------------------------------
-- EXAM MARKS (Semester-wise result uploads)
-- ---------------------------------
CREATE TABLE IF NOT EXISTS exam_marks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_roll_no VARCHAR(20) NOT NULL,
  semester INT NOT NULL DEFAULT 5,
  exam_type VARCHAR(50) NOT NULL DEFAULT 'CIA-1',
  subject_name VARCHAR(100) NOT NULL,
  marks_obtained INT NOT NULL,
  max_marks INT DEFAULT 100,
  uploaded_by INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default Sample Exam Marks for Roll No 045 (Ravi Kumar)
INSERT INTO exam_marks (student_roll_no, semester, exam_type, subject_name, marks_obtained, max_marks) VALUES
('045', 5, 'CIA-1', 'Operating Systems', 78, 100),
('045', 5, 'CIA-1', 'DBMS', 85, 100),
('045', 5, 'CIA-1', 'Web Technology', 92, 100),
('045', 5, 'CIA-1', 'Java Programming', 88, 100),
('045', 5, 'CIA-1', 'Software Engineering', 74, 100),
('045', 5, 'CIA-2', 'Operating Systems', 82, 100),
('045', 5, 'CIA-2', 'DBMS', 89, 100),
('045', 5, 'MODEL', 'Operating Systems', 84, 100),
('045', 5, 'MODEL', 'DBMS', 91, 100),
('045', 5, 'SEMESTER', 'Operating Systems', 86, 100),
('045', 5, 'SEMESTER', 'DBMS', 93, 100);



