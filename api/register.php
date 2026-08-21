<?php
require_once "config.php";

$data = json_decode(file_get_contents("php://input"), true);
$name = trim($data["name"] ?? "");
$email = trim(strtolower($data["email"] ?? ""));
$password = trim($data["password"] ?? "");
$role = trim($data["role"] ?? "student");
if (!in_array($role, ["student", "teacher"])) {
    $role = "student";
}

$roll_no = trim($data["roll_no"] ?? "");
$class_name = trim($data["class_name"] ?? "BCA I");
$parent_phone = trim($data["parent_phone"] ?? "");
$phone = trim($data["phone"] ?? "");
$subjects = trim($data["subjects"] ?? "");

if (!$name || !$email || !$password) {
    echo json_encode(["success" => false, "message" => "Name, Email, and Password are required"]);
    exit;
}

// Check if email already exists
$chk = $conn->prepare("SELECT id FROM users WHERE email = ?");
$chk->bind_param("s", $email);
$chk->execute();
if ($chk->get_result()->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Email address '$email' is already registered!"]);
    exit;
}

// Check if Roll No already exists (for students)
if ($role === "student" && !empty($roll_no)) {
    $rchk = $conn->prepare("SELECT id, name FROM students WHERE roll_no = ?");
    $rchk->bind_param("s", $roll_no);
    $rchk->execute();
    $rres = $rchk->get_result();
    if ($rrow = $rres->fetch_assoc()) {
        echo json_encode(["success" => false, "message" => "Roll No '$roll_no' is already assigned to student '{$rrow['name']}'!"]);
        exit;
    }
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $hash, $role);

if ($stmt->execute()) {
    $user_id = $stmt->insert_id;

    if ($role === "student") {
        $st = $conn->prepare("INSERT INTO students (user_id, name, roll_no, class_name, parent_phone) VALUES (?, ?, ?, ?, ?)");
        $st->bind_param("issss", $user_id, $name, $roll_no, $class_name, $parent_phone);
        $st->execute();

        // Send Email Verification / Welcome Alert
        require_once "email_helper.php";
        require_once "whatsapp_helper.php";
        $emailMsg = "Welcome to AttendAlert Smart Attendance Portal!\n\nYour student account has been registered successfully.\nRoll No: $roll_no\nClass: $class_name\nEmail: $email";
        sendAlertEmail($email, $name, "🎓 Student Account Registration Verification — AttendAlert", $emailMsg);

        $waMsg = "Hello $name, Your Student Account (Roll No: $roll_no) has been successfully created on AttendAlert Smart Portal!";
        $waLink = generateWhatsAppLink($parent_phone, $waMsg);
    } else if ($role === "teacher") {
        $tc = $conn->prepare("INSERT INTO teachers (user_id, department, phone, subjects) VALUES (?, 'BCA', ?, ?)");
        $tc->bind_param("iss", $user_id, $phone, $subjects);
        $tc->execute();
        $waLink = "";
    }

    echo json_encode([
        "success" => true,
        "message" => "Registration successful! Welcome verification email & WhatsApp notification generated.",
        "user_id" => $user_id,
        "whatsapp_link" => $waLink ?? ""
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to create user: " . ($conn ? $conn->error : "DB Connection Error")]);
}
?>
