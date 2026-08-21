<?php
/**
 * AttendAlert WhatsApp Notification Helper
 * Supports WhatsApp Business API / Twilio / Direct WhatsApp Link Generation
 */

function generateWhatsAppLink($phoneNumber, $messageText) {
    // Clean phone number format (Remove spaces, +, -)
    $cleanPhone = preg_replace('/[^0-9]/', '', $phoneNumber);
    
    // Default to India country code 91 if 10 digits provided
    if (strlen($cleanPhone) === 10) {
        $cleanPhone = "91" . $cleanPhone;
    }

    $encodedMsg = rawurlencode($messageText);
    return "https://api.whatsapp.com/send?phone=" . $cleanPhone . "&text=" . $encodedMsg;
}

function sendWhatsAppAPI($phoneNumber, $messageText) {
    // API integration setup (e.g. UltraMsg or Twilio WhatsApp API)
    $instanceId = getenv("WHATSAPP_INSTANCE_ID") ?: "";
    $token = getenv("WHATSAPP_TOKEN") ?: "";

    if (!empty($instanceId) && !empty($token)) {
        $cleanPhone = preg_replace('/[^0-9]/', '', $phoneNumber);
        if (strlen($cleanPhone) === 10) $cleanPhone = "91" . $cleanPhone;

        $url = "https://api.ultramsg.com/{$instanceId}/messages/chat";
        $params = array(
            'token' => $token,
            'to' => '+' . $cleanPhone,
            'body' => $messageText
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return true;
    }

    return false;
}
?>
