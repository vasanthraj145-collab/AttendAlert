<?php
require_once "config.php";

$data = json_decode(file_get_contents("php://input"), true);
$email = $data["email"] ?? "";
$password = $data["password"] ?? "";
$role = $data["role"] ?? "";

if (!$email || !$password || !$role) {
    echo json_encode(["success" => false, "message" => "Missing fields"]);
    exit;
}

$stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ? AND role = ?");
$stmt->bind_param("ss", $email, $role);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "Invalid email or password"]);
    exit;
}

$user = $result->fetch_assoc();

// password_verify checks the plain password against the hashed one in DB
if (!password_verify($password, $user["password"])) {
    echo json_encode(["success" => false, "message" => "Invalid email or password"]);
    exit;
}

$roll_no = "";
if ($user["role"] === "student") {
    $stStmt = $conn->prepare("SELECT roll_no FROM students WHERE user_id = ?");
    $stStmt->bind_param("i", $user["id"]);
    $stStmt->execute();
    $stRes = $stStmt->get_result();
    if ($stRow = $stRes->fetch_assoc()) {
        $roll_no = $stRow["roll_no"];
    }
}

echo json_encode([
    "success" => true,
    "user" => [
        "id" => $user["id"],
        "name" => $user["name"],
        "role" => $user["role"],
        "roll_no" => $roll_no
    ]
]);
?>
