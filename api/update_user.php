<?php
require_once "config.php";

$data = json_decode(file_get_contents("php://input"), true);
$type = $data["type"] ?? "student";
$id = intval($data["id"] ?? 0);
$name = trim($data["name"] ?? "");
$email = trim(strtolower($data["email"] ?? ""));
$roll_no = trim($data["roll_no"] ?? "");
$class_name = trim($data["class_name"] ?? "");
$parent_phone = trim($data["parent_phone"] ?? "");
$phone = trim($data["phone"] ?? "");
$subjects = trim($data["subjects"] ?? "");

if (!$id || !$name) {
    echo json_encode(["success" => false, "message" => "ID and Name are required"]);
    exit;
}

if ($type === "student") {
    // Check for duplicate Roll No on other students
    if (!empty($roll_no)) {
        $rchk = $conn->prepare("SELECT id, name FROM students WHERE roll_no = ? AND id != ?");
        $rchk->bind_param("si", $roll_no, $id);
        $rchk->execute();
        if ($rrow = $rchk->get_result()->fetch_assoc()) {
            echo json_encode(["success" => false, "message" => "Roll No '$roll_no' is already assigned to '{$rrow['name']}'!"]);
            exit;
        }
    }

    // Update students table
    $st = $conn->prepare("UPDATE students SET name = ?, roll_no = ?, class_name = ?, parent_phone = ? WHERE id = ?");
    $st->bind_param("ssssi", $name, $roll_no, $class_name, $parent_phone, $id);
    $st->execute();

    // Get linked user_id
    $uchk = $conn->prepare("SELECT user_id FROM students WHERE id = ?");
    $uchk->bind_param("i", $id);
    $uchk->execute();
    $ures = $uchk->get_result();
    if ($urow = $ures->fetch_assoc()) {
        $uid = $urow["user_id"];
        if ($uid && $email) {
            $uup = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
            $uup->bind_param("ssi", $name, $email, $uid);
            $uup->execute();
        }
    }
    echo json_encode(["success" => true, "message" => "Student details updated successfully!"]);
} else if ($type === "teacher") {
    // Update users and teachers
    $uup = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
    $uup->bind_param("ssi", $name, $email, $id);
    $uup->execute();

    $tup = $conn->prepare("UPDATE teachers SET phone = ?, subjects = ? WHERE user_id = ?");
    $tup->bind_param("ssi", $phone, $subjects, $id);
    $tup->execute();

    echo json_encode(["success" => true, "message" => "Teacher details updated successfully!"]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid type"]);
}
?>
