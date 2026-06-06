<?php
require_once("../../backend/include/initialize.php");
if (!isset($_SESSION['USERID'])){
    redirect(web_root."admin/index.php");
}
$view = (isset($_GET['view']) && $_GET['view'] != '') ? $_GET['view'] : 'list';
$title = "Push Notification Center";

global $mydb;

if (isset($_POST['btn_broadcast_push'])) {
    $seg = $_POST['target_segment'];
    $title_p = $_POST['title'];
    $body = $_POST['body'];
    
    $seg_esc = $mydb->escape_value($seg);
    $title_esc = $mydb->escape_value($title_p);
    $body_esc = $mydb->escape_value($body);
    
    $query = "INSERT INTO `push_notifications_log` (`target_segment`, `title`, `body`) 
              VALUES ('{$seg_esc}', '{$title_esc}', '{$body_esc}')";
    $mydb->setQuery($query);
    if ($mydb->executeQuery()) {
        log_audit_action("broadcast", "push_notifications_log", null, ["title" => $title_p, "segment" => $seg]);
        message("Push notification broadcasted successfully!", "success");
    } else {
        message("Failed to broadcast push notification.", "error");
    }
    redirect("index.php");
}

$content = 'list.php';
require_once("../theme/templates.php");
?>
