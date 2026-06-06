<?php
require_once("../../backend/include/initialize.php");
if (!isset($_SESSION['USERID'])){
    redirect(web_root."admin/index.php");
}

$view = (isset($_GET['view']) && $_GET['view'] != '') ? $_GET['view'] : 'list';
$title = "Multi-Site & Regional Settings Manager";

if ($view === 'add') {
    if (isset($_POST['save'])) {
        global $mydb;
        $site_name = $_POST['site_name'];
        $country_code = strtoupper($_POST['country_code']);
        $currency_code = strtoupper($_POST['currency_code']);
        $language_code = strtolower($_POST['language_code']);
        $tax_rate = floatval($_POST['tax_rate']);
        $timezone = $_POST['timezone'];
        
        $mydb->setQuery("INSERT INTO `sites` (`site_name`, `country_code`, `currency_code`, `language_code`, `tax_rate`, `timezone`, `status`) 
                         VALUES ('{$site_name}', '{$country_code}', '{$currency_code}', '{$language_code}', {$tax_rate}, '{$timezone}', 'Active')");
        $mydb->executeQuery();
        
        log_audit_action("create", "sites", "Added site: {$site_name}", json_encode($_POST));
        
        $_SESSION['message'] = "Regional site registered successfully.";
        $_SESSION['msgtype'] = "success";
    }
    redirect("index.php");
}

if ($view === 'edit') {
    if (isset($_POST['save_edit'])) {
        global $mydb;
        $id = intval($_POST['site_id']);
        $site_name = $_POST['site_name'];
        $tax_rate = floatval($_POST['tax_rate']);
        $timezone = $_POST['timezone'];
        $status = $_POST['status'];
        
        $mydb->setQuery("SELECT * FROM `sites` WHERE `site_id` = {$id}");
        $old_row = $mydb->loadSingleResult();
        
        $mydb->setQuery("UPDATE `sites` SET `site_name` = '{$site_name}', `tax_rate` = {$tax_rate}, `timezone` = '{$timezone}', `status` = '{$status}' WHERE `site_id` = {$id}");
        $mydb->executeQuery();
        
        log_audit_action("update", "sites", "Updated site details for ID: {$id}", json_encode($old_row), json_encode($_POST));
        
        $_SESSION['message'] = "Site details updated successfully.";
        $_SESSION['msgtype'] = "success";
    }
    redirect("index.php");
}

if ($view === 'delete') {
    $id = intval($_GET['id']);
    global $mydb;
    
    $mydb->setQuery("SELECT * FROM `sites` WHERE `site_id` = {$id}");
    $old_row = $mydb->loadSingleResult();
    
    if ($old_row) {
        $mydb->setQuery("DELETE FROM `sites` WHERE `site_id` = {$id}");
        $mydb->executeQuery();
        
        log_audit_action("delete", "sites", "Deleted site: " . ($old_row->site_name ?? $id), json_encode($old_row));
        
        $_SESSION['message'] = "Regional site configuration deleted.";
        $_SESSION['msgtype'] = "success";
    }
    redirect("index.php");
}

$content = 'list.php';
require_once("../theme/templates.php");
?>
