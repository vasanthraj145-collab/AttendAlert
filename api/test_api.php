<?php
$data = json_encode([
    'date' => '2026-08-07',
    'marked_by' => 2,
    'records' => [
        ['student_id' => 1, 'status' => 'P'],
        ['student_id' => 5, 'status' => 'A']
    ]
]);
$opts = [
    'http' => [
        'method'  => 'POST',
        'header'  => "Content-Type: application/json\r\n",
        'content' => $data
    ]
];
$context  = stream_context_create($opts);
$result = file_get_contents('http://localhost/AttendAlert/api/save_attendance.php', false, $context);
echo $result;
?>