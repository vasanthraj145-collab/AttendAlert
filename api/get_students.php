<?php
require_once "config.php";

$sql = "SELECT s.id, s.name, s.roll_no, s.class_name, s.parent_phone, u.email 
        FROM students s 
        LEFT JOIN users u ON s.user_id = u.id 
        ORDER BY s.id DESC";

$result = $conn->query($sql);

$students = [];
while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}

echo json_encode(["success" => true, "students" => $students]);
?>
