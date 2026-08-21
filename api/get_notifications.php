<?php
require_once "config.php";

$sql = "SELECT n.*, u.name as sent_by_name 
        FROM notifications n 
        LEFT JOIN users u ON n.sent_by = u.id 
        ORDER BY n.id DESC";

$result = $conn->query($sql);

$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}

echo json_encode(["success" => true, "notifications" => $notifications]);
?>
