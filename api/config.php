<?php
// ================================================
// AttendAlert Production Database Config
// ================================================
mysqli_report(MYSQLI_REPORT_OFF);

$DB_HOST = getenv("DB_HOST") ?: "localhost";
$DB_USER = getenv("DB_USER") ?: "root";
$DB_PASS = getenv("DB_PASS") !== false ? getenv("DB_PASS") : "";          // default XAMPP MySQL password
$DB_NAME = getenv("DB_NAME") ?: "attendalert";

try {
    $conn = @new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
    if ($conn && !$conn->connect_error) {
        $dbConnected = true;
    } else {
        $dbConnected = false;
        $conn = null;
    }
} catch (Throwable $e) {
    $dbConnected = false;
    $conn = null;
}

if (!headers_sent()) {
    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
}

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    if (!headers_sent()) http_response_code(200);
    exit;
}
?>
