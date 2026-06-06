<?php
/**
 * Shipping Tracker Controller.
 */
require_once("../../backend/include/initialize.php");

if (!isset($_SESSION['USERID'])) {
    redirect(web_root . "admin/index.php");
}

$view = (isset($_GET['view']) && $_GET['view'] != '') ? $_GET['view'] : '';
$action = (isset($_GET['action']) && $_GET['action'] != '') ? $_GET['action'] : '';

global $mydb;

if ($action === 'update') {
    $trackingId = isset($_POST['tracking_id']) ? (int)$_POST['tracking_id'] : 0;
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    $lat = isset($_POST['current_lat']) ? (float)$_POST['current_lat'] : 0.0;
    $lng = isset($_POST['current_lng']) ? (float)$_POST['current_lng'] : 0.0;
    $details = isset($_POST['details']) ? trim($_POST['details']) : '';
    
    if ($trackingId > 0 && !empty($status)) {
        // Update main tracking
        $actualClause = "";
        if ($status === 'Delivered') {
            $actualClause = ", `actual_delivery` = CURRENT_TIMESTAMP";
        }
        $mydb->setQuery("
            UPDATE `shipping_tracking` 
            SET `status` = '{$status}', `current_lat` = {$lat}, `current_lng` = {$lng} {$actualClause}
            WHERE `tracking_id` = {$trackingId}
        ");
        $mydb->executeQuery();
        
        // Add update details log
        if (!empty($details)) {
            $mydb->setQuery("
                INSERT INTO `shipping_updates` (`tracking_id`, `location`, `status_details`)
                VALUES ({$trackingId}, 'Transit Coordinate: {$lat}, {$lng}', '{$details}')
            ");
            $mydb->executeQuery();
        }
        message("Shipping package location and status updated successfully!", "success");
    }
    redirect("index.php");
}

$title = "Shipping & Logistics Tracker";
$content = 'view.php';

require_once("../theme/templates.php");
?>
