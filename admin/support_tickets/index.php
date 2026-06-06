<?php
require_once("../../backend/include/initialize.php");
if (!isset($_SESSION['USERID'])){
    redirect(web_root."admin/index.php");
}

$view = (isset($_GET['view']) && $_GET['view'] != '') ? $_GET['view'] : 'list';
$title = "Customer Support & Ticketing Center";

if ($view === 'assign') {
    if (isset($_POST['assign_agent'])) {
        global $mydb;
        $ticket_id = intval($_POST['ticket_id']);
        $agent_id = intval($_POST['agent_id']);
        
        // Remove existing assignment if any
        $mydb->setQuery("DELETE FROM `ticket_assignments` WHERE `ticket_id` = {$ticket_id}");
        $mydb->executeQuery();
        
        // Insert new assignment
        $mydb->setQuery("INSERT INTO `ticket_assignments` (`ticket_id`, `agent_id`) VALUES ({$ticket_id}, {$agent_id})");
        $mydb->executeQuery();
        
        // Update ticket status
        $mydb->setQuery("UPDATE `support_tickets` SET `status` = 'Assigned', `updated_at` = NOW() WHERE `ticket_id` = {$ticket_id}");
        $mydb->executeQuery();
        
        log_audit_action("update", "support_tickets", "Assigned ticket ID {$ticket_id} to agent ID {$agent_id}");
        
        $_SESSION['message'] = "Ticket assigned successfully.";
        $_SESSION['msgtype'] = "success";
    }
    redirect("index.php?view=detail&id=" . $_POST['ticket_id']);
}

if ($view === 'reply') {
    if (isset($_POST['send_reply'])) {
        global $mydb;
        $ticket_id = intval($_POST['ticket_id']);
        $admin_id = $_SESSION['USERID'];
        $message = $_POST['message_body'];
        
        $mydb->setQuery("INSERT INTO `ticket_replies` (`ticket_id`, `sender_type`, `sender_id`, `message_body`) 
                         VALUES ({$ticket_id}, 'Admin', {$admin_id}, '" . $mydb->escape_value($message) . "')");
        $mydb->executeQuery();
        
        // Update ticket updated timestamp
        $mydb->setQuery("UPDATE `support_tickets` SET `updated_at` = NOW() WHERE `ticket_id` = {$ticket_id}");
        $mydb->executeQuery();
        
        log_audit_action("create", "ticket_replies", "Admin reply to ticket ID {$ticket_id}");
        
        $_SESSION['message'] = "Reply posted successfully.";
        $_SESSION['msgtype'] = "success";
    }
    redirect("index.php?view=detail&id=" . $_POST['ticket_id']);
}

if ($view === 'update_status') {
    $ticket_id = intval($_GET['id']);
    $status = $_GET['status'];
    global $mydb;
    
    $mydb->setQuery("UPDATE `support_tickets` SET `status` = '" . $mydb->escape_value($status) . "', `updated_at` = NOW() WHERE `ticket_id` = {$ticket_id}");
    $mydb->executeQuery();
    
    log_audit_action("update", "support_tickets", "Set status to {$status} for ticket ID {$ticket_id}");
    
    $_SESSION['message'] = "Ticket status updated to " . htmlspecialchars($status) . ".";
    $_SESSION['msgtype'] = "success";
    redirect("index.php?view=detail&id=" . $ticket_id);
}

$content = 'list.php';
if ($view === 'detail') {
    $content = 'detail.php';
}
require_once("../theme/templates.php");
?>
