<?php
// Restricted utility file for CLI or local setup only.
if (php_sapi_name() !== 'cli' && ($_SERVER['REMOTE_ADDR'] ?? '') !== '127.0.0.1' && ($_SERVER['REMOTE_ADDR'] ?? '') !== '::1') {
    http_response_code(403);
    echo "403 Forbidden: Hash generator is disabled on live servers.";
    exit;
}

$pw = $_GET["pw"] ?? "123456";
echo "Password: " . htmlspecialchars($pw) . "<br>";
echo "Hash: " . password_hash($pw, PASSWORD_DEFAULT);
?>
