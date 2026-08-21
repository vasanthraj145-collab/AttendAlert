<?php
// ================================================================
// AttendAlert — Real SMS Gateway Integration Helper
// Supports Fast2SMS (India), Twilio (Global), and Textlocal APIs
// ================================================================

define("SMS_GATEWAY_PROVIDER", "FAST2SMS"); // Options: "FAST2SMS", "TWILIO", "TEXTLOCAL"
define("FAST2SMS_API_KEY", "YOUR_FAST2SMS_API_KEY_HERE");
define("TWILIO_SID", "YOUR_TWILIO_SID");
define("TWILIO_TOKEN", "YOUR_TWILIO_AUTH_TOKEN");
define("TWILIO_FROM", "+1234567890");

/**
 * Send SMS to a single phone number or multiple numbers
 * @param string|array $phones Mobile number(s) (e.g., "9876543210" or ["9876543210", "9765432109"])
 * @param string $message Text message content
 * @return bool True if request succeeded, false otherwise
 */
function sendRealSMS($phones, $message) {
    if (is_array($phones)) {
        $phone_list = implode(",", $phones);
    } else {
        $phone_list = strval($phones);
    }

    if (empty($phone_list) || empty($message)) {
        return false;
    }

    switch (SMS_GATEWAY_PROVIDER) {
        case "FAST2SMS":
            // Fast2SMS API Endpoint (Quick SMS Route)
            $url = "https://www.fast2sms.com/dev/bulkV2";
            $postData = [
                "route" => "q",
                "message" => $message,
                "language" => "english",
                "flash" => "0",
                "numbers" => $phone_list
            ];
            $headers = [
                "authorization: " . FAST2SMS_API_KEY,
                "Content-Type: application/json"
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
            
            $resData = json_decode($response, true);
            return isset($resData["return"]) && $resData["return"] === true;

        case "TWILIO":
            // Twilio REST API
            $url = "https://api.twilio.com/2010-04-01/Accounts/" . TWILIO_SID . "/Messages.json";
            $postData = http_build_query([
                "From" => TWILIO_FROM,
                "To" => "+91" . $phone_list,
                "Body" => $message
            ]);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_USERPWD, TWILIO_SID . ":" . TWILIO_TOKEN);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            $resData = json_decode($response, true);
            return isset($resData["sid"]);

        default:
            return false;
    }
}
?>
