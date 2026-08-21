<?php
require_once "config.php";

$sql = "SELECT u.id, u.name, u.email, t.department, t.phone, t.subjects 
        FROM users u 
        LEFT JOIN teachers t ON u.id = t.user_id 
        WHERE u.role = 'teacher'
        ORDER BY u.id DESC";

$result = $conn->query($sql);

$teachers = [];
while ($row = $result->fetch_assoc()) {
    $teachers[] = $row;
}

echo json_encode(["success" => true, "teachers" => $teachers]);
?>
