<?php
require_once "config.php";

$roll_no = trim($_GET['roll_no'] ?? '');
$semester = isset($_GET['semester']) ? intval($_GET['semester']) : 0;
$exam_type = trim($_GET['exam_type'] ?? '');

$sql = "SELECT id, student_roll_no, semester, exam_type, subject_name, marks_obtained, max_marks, created_at FROM exam_marks WHERE 1=1";
$params = [];
$types = "";

if (!empty($roll_no)) {
    $sql .= " AND student_roll_no = ?";
    $params[] = $roll_no;
    $types .= "s";
}

if ($semester > 0) {
    $sql .= " AND semester = ?";
    $params[] = $semester;
    $types .= "i";
}

if (!empty($exam_type)) {
    $sql .= " AND exam_type = ?";
    $params[] = $exam_type;
    $types .= "s";
}

$sql .= " ORDER BY semester ASC, exam_type ASC, subject_name ASC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$marks = [];
while ($row = $result->fetch_assoc()) {
    $marks[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode([
    "success" => true,
    "count" => count($marks),
    "marks" => $marks
]);
?>
