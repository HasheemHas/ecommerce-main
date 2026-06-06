<?php
/**
 * Coupon Manager Controller.
 */
require_once("../../backend/include/initialize.php");

if (!isset($_SESSION['USERID'])) {
    redirect(web_root . "admin/index.php");
}

$view = (isset($_GET['view']) && $_GET['view'] != '') ? $_GET['view'] : '';
$action = (isset($_GET['action']) && $_GET['action'] != '') ? $_GET['action'] : '';

global $mydb;

if ($action === 'add') {
    $code = isset($_POST['coupon_code']) ? trim(strtoupper($_POST['coupon_code'])) : '';
    $type = isset($_POST['type']) ? $_POST['type'] : 'percent';
    $value = isset($_POST['value']) ? (float)$_POST['value'] : 0.0;
    $startDate = isset($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-d');
    $endDate = isset($_POST['expiry_date']) ? $_POST['expiry_date'] : date('Y-m-d', strtotime('+30 days'));
    $limit = isset($_POST['usage_limit']) ? (int)$_POST['usage_limit'] : 100;
    $minSpend = isset($_POST['min_spend']) ? (float)$_POST['min_spend'] : 0.0;
    $targetSegment = isset($_POST['target_segment']) ? $_POST['target_segment'] : 'All';
    
    if (!empty($code) && $value > 0) {
        $mydb->setQuery("
            INSERT INTO `coupons` 
            (`coupon_code`, `type`, `value`, `start_date`, `expiry_date`, `usage_limit`, `status`, `min_spend`, `target_segment`)
            VALUES 
            ('{$code}', '{$type}', {$value}, '{$startDate}', '{$endDate}', {$limit}, 'active', {$minSpend}, '{$targetSegment}')
        ");
        if ($mydb->executeQuery()) {
            message("Coupon '{$code}' created successfully!", "success");
        } else {
            message("Failed to create coupon: " . $mydb->error_msg, "error");
        }
    }
    redirect("index.php");
}

if ($action === 'toggle') {
    $couponId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($couponId > 0) {
        $mydb->setQuery("SELECT status FROM coupons WHERE coupon_id = {$couponId} LIMIT 1");
        $c = $mydb->loadSingleResult();
        if ($c) {
            $newStatus = $c->status === 'active' ? 'disabled' : 'active';
            $mydb->setQuery("UPDATE coupons SET status = '{$newStatus}' WHERE coupon_id = {$couponId}");
            $mydb->executeQuery();
            message("Coupon status updated to {$newStatus}.", "success");
        }
    }
    redirect("index.php");
}

if ($action === 'delete') {
    $couponId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($couponId > 0) {
        $mydb->setQuery("DELETE FROM coupons WHERE coupon_id = {$couponId}");
        $mydb->executeQuery();
        message("Coupon deleted successfully.", "success");
    }
    redirect("index.php");
}

$title = "Coupon Manager";
$content = 'view.php';

require_once("../theme/templates.php");
?>
