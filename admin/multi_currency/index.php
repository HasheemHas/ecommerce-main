<?php
require_once("../../backend/include/initialize.php");
if (!isset($_SESSION['USERID'])){
    redirect(web_root."admin/index.php");
}

$view = (isset($_GET['view']) && $_GET['view'] != '') ? $_GET['view'] : 'list';
$title = "Multi-Currency Exchange Manager";

if ($view === 'add') {
    if (isset($_POST['save'])) {
        global $mydb;
        $code = strtoupper($_POST['currency_code']);
        $symbol = $_POST['currency_symbol'];
        $rate = floatval($_POST['exchange_rate']);
        
        $mydb->setQuery("INSERT INTO `currencies` (`currency_code`, `currency_symbol`, `exchange_rate`, `is_base`, `status`) 
                         VALUES ('{$code}', '{$symbol}', {$rate}, 0, 'Active')");
        $mydb->executeQuery();
        
        log_audit_action("create", "currencies", "Added currency: {$code}", json_encode($_POST));
        
        $_SESSION['message'] = "Currency added successfully.";
        $_SESSION['msgtype'] = "success";
    }
    redirect("index.php");
}

if ($view === 'set_base') {
    $id = intval($_GET['id']);
    global $mydb;
    
    // Fetch currency code being set to base
    $mydb->setQuery("SELECT * FROM `currencies` WHERE `currency_id` = {$id}");
    $currency = $mydb->loadSingleResult();
    
    if ($currency) {
        // Reset all base currencies to 0
        $mydb->setQuery("UPDATE `currencies` SET `is_base` = 0");
        $mydb->executeQuery();
        
        // Set this currency as base with rate = 1.0
        $mydb->setQuery("UPDATE `currencies` SET `is_base` = 1, `exchange_rate` = 1.0 WHERE `currency_id` = {$id}");
        $mydb->executeQuery();
        
        log_audit_action("update", "currencies", "Set base currency to: {$currency->currency_code}", null, json_encode($currency));
        
        $_SESSION['message'] = "Base currency set to " . $currency->currency_code . ". All other rates must be relative to this base.";
        $_SESSION['msgtype'] = "success";
    }
    redirect("index.php");
}

if ($view === 'edit') {
    if (isset($_POST['save_edit'])) {
        global $mydb;
        $id = intval($_POST['currency_id']);
        $rate = floatval($_POST['exchange_rate']);
        $status = $_POST['status'];
        
        $mydb->setQuery("SELECT * FROM `currencies` WHERE `currency_id` = {$id}");
        $old_row = $mydb->loadSingleResult();
        
        $mydb->setQuery("UPDATE `currencies` SET `exchange_rate` = {$rate}, `status` = '{$status}' WHERE `currency_id` = {$id}");
        $mydb->executeQuery();
        
        // Also log inside exchange_rates history table if applicable
        if ($old_row) {
            $mydb->setQuery("INSERT INTO `exchange_rates` (`currency_code`, `rate`) VALUES ('{$old_row->currency_code}', {$rate})");
            $mydb->executeQuery();
        }
        
        log_audit_action("update", "currencies", "Updated exchange rate for currency ID: {$id}", json_encode($old_row), json_encode($_POST));
        
        $_SESSION['message'] = "Currency details updated successfully.";
        $_SESSION['msgtype'] = "success";
    }
    redirect("index.php");
}

if ($view === 'delete') {
    $id = intval($_GET['id']);
    global $mydb;
    
    $mydb->setQuery("SELECT * FROM `currencies` WHERE `currency_id` = {$id}");
    $old_row = $mydb->loadSingleResult();
    
    if ($old_row && $old_row->is_base) {
        $_SESSION['message'] = "Cannot delete the base currency. Set another currency as base first.";
        $_SESSION['msgtype'] = "danger";
    } else {
        $mydb->setQuery("DELETE FROM `currencies` WHERE `currency_id` = {$id}");
        $mydb->executeQuery();
        
        log_audit_action("delete", "currencies", "Deleted currency: " . ($old_row->currency_code ?? $id), json_encode($old_row));
        
        $_SESSION['message'] = "Currency deleted.";
        $_SESSION['msgtype'] = "success";
    }
    redirect("index.php");
}

$content = 'list.php';
require_once("../theme/templates.php");
?>
