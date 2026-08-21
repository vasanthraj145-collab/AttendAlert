<?php
/**
 * AttendAlert Email Notification Helper
 * Supports PHP mail() and SMTP configuration
 */

function sendAlertEmail($toEmail, $toName, $subject, $messageBody) {
    if (empty($toEmail)) return false;

    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: AttendAlert Portal <no-reply@attendalert.college.edu>\r\n";
    $headers .= "Reply-To: support@attendalert.college.edu\r\n";

    $htmlContent = "
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: 'Segoe UI', Helvetica, Arial, sans-serif; background: #f8fafc; color: #1e293b; padding: 20px; }
            .card { background: #ffffff; max-width: 600px; margin: 0 auto; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; }
            .head { background: linear-gradient(135deg, #0284c7, #2563eb); padding: 24px; text-align: center; color: #ffffff; }
            .head h2 { margin: 0; font-size: 22px; font-weight: 700; }
            .body { padding: 24px; font-size: 15px; line-height: 1.6; }
            .foot { background: #f1f5f9; padding: 16px; text-align: center; font-size: 12px; color: #64748b; }
            .badge { display: inline-block; padding: 4px 10px; border-radius: 6px; font-weight: 600; background: #e0f2fe; color: #0369a1; }
        </style>
    </head>
    <body>
        <div class='card'>
            <div class='head'>
                <h2>📋 AttendAlert System Notification</h2>
            </div>
            <div class='body'>
                <p>Dear <strong>" . htmlspecialchars($toName) . "</strong>,</p>
                <div>" . nl2br($messageBody) . "</div>
                <br>
                <p>If you have any questions, please contact your Class Coordinator or HOD.</p>
            </div>
            <div class='foot'>
                Sri College of Arts & Science • Smart Attendance Portal
            </div>
        </div>
    </body>
    </html>";

    // Attempt PHP native mail send
    return @mail($toEmail, $subject, $htmlContent, $headers);
}
?>
