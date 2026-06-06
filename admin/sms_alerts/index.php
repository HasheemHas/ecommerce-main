<?php
require_once("../../backend/include/initialize.php");
if (!isset($_SESSION['USERID'])){
    redirect(web_root."admin/index.php");
}

$view = (isset($_GET['view']) && $_GET['view'] != '') ? $_GET['view'] : 'list';
$title = "SMS Alerts Center";

if ($view === 'update_config') {
    if (isset($_POST['save_config'])) {
        global $mydb;
        $recipient_phone = $_POST['recipient_phone'];
        $alert_types = ['high_value_order', 'fraud', 'critical_stock', 'back_in_stock'];
        
        foreach ($alert_types as $type) {
            $enabled = isset($_POST['enabled_' . $type]) ? 1 : 0;
            $phone = isset($recipient_phone[$type]) ? $recipient_phone[$type] : '';
            
            // Check if config exists
            $mydb->setQuery("SELECT * FROM `sms_alerts_config` WHERE `alert_type` = '{$type}'");
            $cur = $mydb->executeQuery();
            if ($mydb->num_rows($cur) > 0) {
                $mydb->setQuery("UPDATE `sms_alerts_config` SET `enabled` = {$enabled}, `recipient_phone` = '{$phone}' WHERE `alert_type` = '{$type}'");
            } else {
                $mydb->setQuery("INSERT INTO `sms_alerts_config` (`alert_type`, `enabled`, `recipient_phone`) VALUES ('{$type}', {$enabled}, '{$phone}')");
            }
            $mydb->executeQuery();
        }
        
        // Log action in audit trail
        log_audit_action("update", "sms_alerts_config", "Bulk SMS configurations", json_encode($_POST));
        
        $_SESSION['message'] = "SMS Alert configurations updated successfully.";
        $_SESSION['msgtype'] = "success";
    }
    redirect("index.php");
}

if ($view === 'send_test') {
    if (isset($_POST['send_sms'])) {
        global $mydb;
        $phone = $_POST['phone'];
        $message = $_POST['message'];
        
        // Mock sending SMS via Twilio
        $status = "Sent";
        $error_message = "";
        
        // If phone doesn't contain a valid prefix or empty, mock failure
        if (empty($phone) || strlen($phone) < 8) {
            $status = "Failed";
            $error_message = "Invalid phone number format.";
        }
        
        $mydb->setQuery("INSERT INTO `sms_logs` (`phone_number`, `message_body`, `status`, `error_message`) 
                         VALUES ('{$phone}', '{$message}', '{$status}', '" . $mydb->escape_value($error_message) . "')");
        $mydb->executeQuery();
        
        log_audit_action("create", "sms_logs", "Manual SMS simulation", json_encode(["phone" => $phone, "status" => $status]));
        
        if ($status === "Sent") {
            $_SESSION['message'] = "Test SMS sent successfully (Simulated Twilio delivery).";
            $_SESSION['msgtype'] = "success";
        } else {
            $_SESSION['message'] = "Failed to send SMS: " . $error_message;
            $_SESSION['msgtype'] = "danger";
        }
    }
    redirect("index.php");
}

$content = 'list.php';
require_once("../theme/templates.php");
?>
