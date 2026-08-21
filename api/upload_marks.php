<?php
require_once "config.php";

// Allow JSON POST requests
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    $data = $_POST;
}

$semester = intval($data['semester'] ?? 5);
$exam_type = trim($data['exam_type'] ?? 'CIA-1');
$marks_list = $data['marks'] ?? [];

if (empty($marks_list)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "No marks data provided."]);
    exit;
}

$successCount = 0;
$stmt = $conn->prepare("INSERT INTO exam_marks (student_roll_no, semester, exam_type, subject_name, marks_obtained, max_marks) VALUES (?, ?, ?, ?, ?, ?)");

foreach ($marks_list as $row) {
    $roll_no = trim($row['roll_no'] ?? '');
    $subject = trim($row['subject_name'] ?? 'General');
    $marks = intval($row['marks_obtained'] ?? 0);
    $max = intval($row['max_marks'] ?? 100);

    if (!empty($roll_no)) {
        $stmt->bind_param("sissii", $roll_no, $semester, $exam_type, $subject, $marks, $max);
        if ($stmt->execute()) {
            $successCount++;
        }
    }
}

$stmt->close();
$conn->close();

echo json_encode([
    "success" => true,
    "message" => "Successfully updated marks for {$successCount} student records!",
    "count" => $successCount
]);
?>
