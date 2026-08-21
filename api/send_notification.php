<?php
require_once "config.php";

$data = json_decode(file_get_contents("php://input"), true);
$type = $data["type"] ?? "general";
$title = trim($data["title"] ?? "");
$message = trim($data["message"] ?? "");
$recipients = trim($data["recipients"] ?? "All");
$dept = trim($data["dept"] ?? "All");
$sms_count = intval($data["sms_count"] ?? 248);
$sent_by = intval($data["sent_by"] ?? 1);

if (!$title || !$message) {
    echo json_encode(["success" => false, "message" => "Title and Message are required"]);
    exit;
}

// SECURITY CHECK: Verify sent_by user is Admin or Teacher, NOT Student
if ($sent_by) {
    $rchk = $conn->prepare("SELECT role FROM users WHERE id = ?");
    $rchk->bind_param("i", $sent_by);
    $rchk->execute();
    $res = $rchk->get_result();
    if ($row = $res->fetch_assoc()) {
        if ($row["role"] === "student") {
            http_response_code(403);
            echo json_encode(["success" => false, "message" => "Access Denied: Students are not allowed to send notifications or SMS alerts!"]);
            exit;
        }
    }
}

require_once "sms_helper.php";

$stmt = $conn->prepare("INSERT INTO notifications (type, title, message, recipients, dept, sms_count, sent_by) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssii", $type, $title, $message, $recipients, $dept, $sms_count, $sent_by);

if ($stmt->execute()) {
    $notif_id = $stmt->insert_id;

    // Collect recipient phone numbers from students table
    $phones = [];
    $pres = $conn->query("SELECT parent_phone FROM students WHERE parent_phone IS NOT NULL AND parent_phone != ''");
    while ($prow = $pres->fetch_assoc()) {
        $phones[] = $prow["parent_phone"];
    }

    if (!empty($phones)) {
        $full_msg = "{$title}: {$message} — Sri College";
        sendRealSMS($phones, $full_msg);
    }

    echo json_encode(["success" => true, "message" => "Notification saved & sent", "id" => $notif_id, "recipients_contacted" => count($phones)]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to save notification: " . $conn->error]);
}
?>
