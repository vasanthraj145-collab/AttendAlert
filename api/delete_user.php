<?php
require_once "config.php";

$data = json_decode(file_get_contents("php://input"), true);
$type = $data["type"] ?? "student";
$id = intval($data["id"] ?? 0);

if (!$id) {
    echo json_encode(["success" => false, "message" => "Invalid ID"]);
    exit;
}

if ($type === "student") {
    // Delete student record (and linked user account if exists)
    $stmt1 = $conn->prepare("SELECT user_id FROM students WHERE id = ?");
    $stmt1->bind_param("i", $id);
    $stmt1->execute();
    $res = $stmt1->get_result();
    $uid = ($row = $res->fetch_assoc()) ? $row["user_id"] : null;

    $stmt2 = $conn->prepare("DELETE FROM students WHERE id = ?");
    $stmt2->bind_param("i", $id);
    $stmt2->execute();

    if ($uid) {
        $stmt3 = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt3->bind_param("i", $uid);
        $stmt3->execute();
    }
    echo json_encode(["success" => true, "message" => "Student deleted successfully"]);
} else if ($type === "teacher") {
    $stmt4 = $conn->prepare("DELETE FROM users WHERE id = ? AND role = 'teacher'");
    $stmt4->bind_param("i", $id);
    $stmt4->execute();
    echo json_encode(["success" => true, "message" => "Teacher deleted successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid type"]);
}
?>
