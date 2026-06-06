<?php
require_once("../../backend/include/initialize.php");
if (!isset($_SESSION['USERID'])){
    redirect(web_root."admin/index.php");
}
$view = (isset($_GET['view']) && $_GET['view'] != '') ? $_GET['view'] : 'list';
$title = "Email Campaigns Manager";

global $mydb;

// Process campaign actions
if (isset($_POST['btn_create_campaign'])) {
    $title_c = $_POST['campaign_title'];
    $subject = $_POST['subject_line'];
    $content = $_POST['content_html'];
    $list_id = (int)$_POST['list_id'];
    
    $title_esc = $mydb->escape_value($title_c);
    $sub_esc = $mydb->escape_value($subject);
    $cont_esc = $mydb->escape_value($content);
    
    $query = "INSERT INTO `email_campaigns` (`campaign_title`, `subject_line`, `content_html`, `status`, `list_id`) 
              VALUES ('{$title_esc}', '{$sub_esc}', '{$cont_esc}', 'Draft', {$list_id})";
    $mydb->setQuery($query);
    if ($mydb->executeQuery()) {
        log_audit_action("create", "email_campaigns", null, ["title" => $title_c]);
        message("Campaign created successfully!", "success");
    } else {
        message("Failed to create campaign.", "error");
    }
    redirect("index.php");
}

if ($view === 'send') {
    $campaignId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($campaignId > 0) {
        // Enqueue campaign emails for customers
        $mydb->setQuery("SELECT * FROM `email_campaigns` WHERE `campaign_id` = {$campaignId}");
        $camp = $mydb->loadSingleResult();
        
        if ($camp && $camp->status === 'Draft') {
            // Fetch customers to enqueue
            $mydb->setQuery("SELECT CUSTOMERID, CUSUNAME FROM `tblcustomer` LIMIT 50");
            $customers = $mydb->loadResultList();
            
            if ($customers) {
                foreach ($customers as $cust) {
                    $emailEsc = $mydb->escape_value($cust->CUSUNAME);
                    $mydb->setQuery("
                        INSERT INTO `email_queue` (`campaign_id`, `customer_id`, `email_address`, `status`)
                        VALUES ({$campaignId}, {$cust->CUSTOMERID}, '{$emailEsc}', 'Pending')
                    ");
                    $mydb->executeQuery();
                }
                
                // Set campaign status as Sent
                $mydb->setQuery("UPDATE `email_campaigns` SET `status` = 'Sent', `sent_at` = NOW() WHERE `campaign_id` = {$campaignId}");
                $mydb->executeQuery();
                
                log_audit_action("update", "email_campaigns", ["status" => "Draft"], ["status" => "Sent"]);
                message("Campaign emails successfully enqueued for delivery!", "success");
            } else {
                message("No subscribers found on the selected target list.", "error");
            }
        }
    }
    redirect("index.php");
}

$content = 'list.php';
require_once("../theme/templates.php");
?>
