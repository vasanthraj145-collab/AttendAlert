<?php
require_once "config.php";

// Expected JSON body:
// { "date": "2026-08-06", "marked_by": 1,
//   "records": [ {"student_id": 1, "status": "P"}, {"student_id": 2, "status": "A"} ] }

$data = json_decode(file_get_contents("php://input"), true);
$date = $data["date"] ?? date("Y-m-d");
$marked_by = $data["marked_by"] ?? null;
$records = $data["records"] ?? [];

if (empty($records)) {
    echo json_encode(["success" => false, "message" => "No attendance records received"]);
    exit;
}

// SECURITY CHECK: Verify marked_by user is Teacher or Admin, NOT Student
if ($marked_by) {
    $rchk = $conn->prepare("SELECT role FROM users WHERE id = ?");
    $rchk->bind_param("i", $marked_by);
    $rchk->execute();
    $res = $rchk->get_result();
    if ($row = $res->fetch_assoc()) {
        if ($row["role"] === "student") {
            http_response_code(403);
            echo json_encode(["success" => false, "message" => "Access Denied: Students are not allowed to mark attendance! Only Teachers and Admin can mark attendance."]);
            exit;
        }
    }
}

require_once "sms_helper.php";

$stmt = $conn->prepare(
    "INSERT INTO attendance (student_id, att_date, status, marked_by)
     VALUES (?, ?, ?, ?)
     ON DUPLICATE KEY UPDATE status = VALUES(status), marked_by = VALUES(marked_by)"
);

$absent_phones = [];
$pst = $conn->prepare("SELECT name, parent_phone FROM students WHERE id = ?");

foreach ($records as $rec) {
    $student_id = $rec["student_id"];
    $status = $rec["status"];
    $stmt->bind_param("isis", $student_id, $date, $status, $marked_by);
    $stmt->execute();

    if ($status === "A") {
        $pst->bind_param("i", $student_id);
        $pst->execute();
        $pres = $pst->get_result();
        if ($prow = $pres->fetch_assoc()) {
            if (!empty($prow["parent_phone"])) {
                $absent_phones[] = $prow["parent_phone"];
                $msg = "Dear Parent, your child {$prow['name']} was ABSENT on {$date}. Please contact college office. — Sri College";
                sendRealSMS($prow["parent_phone"], $msg);
            }
        }
    }
}

echo json_encode(["success" => true, "message" => "Attendance saved", "count" => count($records), "absent_sms_count" => count($absent_phones)]);
