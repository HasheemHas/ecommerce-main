<?php
/**
 * Returns & Refunds Controller.
 */
require_once("../../backend/include/initialize.php");

if (!isset($_SESSION['USERID'])) {
    redirect(web_root . "admin/index.php");
}

$view = (isset($_GET['view']) && $_GET['view'] != '') ? $_GET['view'] : '';
$action = (isset($_GET['action']) && $_GET['action'] != '') ? $_GET['action'] : '';

global $mydb;

if ($action === 'approve') {
    $returnId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($returnId > 0) {
        // Approve return request
        $mydb->setQuery("UPDATE `returns` SET `return_status` = 'Approved' WHERE `return_id` = {$returnId}");
        $mydb->executeQuery();
        
        // Add record in refunds table
        $transactionRef = 'REF-' . date('Ymd') . '-' . rand(100, 999);
        $mydb->setQuery("
            INSERT INTO `refunds` (`return_id`, `transaction_reference`, `refund_method`, `refund_status`)
            VALUES ({$returnId}, '{$transactionRef}', 'Cash on Delivery', 'Pending')
        ");
        $mydb->executeQuery();
        
        message("Return request approved and refund transaction registered as Pending.", "success");
    }
    redirect("index.php");
}

if ($action === 'reject') {
    $returnId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($returnId > 0) {
        $mydb->setQuery("UPDATE `returns` SET `return_status` = 'Rejected' WHERE `return_id` = {$returnId}");
        $mydb->executeQuery();
        message("Return request rejected.", "info");
    }
    redirect("index.php");
}

if ($action === 'refund') {
    $returnId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($returnId > 0) {
        // Mark refund as completed
        $mydb->setQuery("UPDATE `refunds` SET `refund_status` = 'Success', `processed_at` = CURRENT_TIMESTAMP WHERE `return_id` = {$returnId}");
        $mydb->executeQuery();
        
        // Mark return as refunded
        $mydb->setQuery("UPDATE `returns` SET `return_status` = 'Refunded' WHERE `return_id` = {$returnId}");
        $mydb->executeQuery();
        
        // Restock returned items to inventory
        $mydb->setQuery("SELECT product_id, quantity FROM `return_items` WHERE `return_id` = {$returnId}");
        $items = $mydb->loadResultList();
        if ($items) {
            foreach ($items as $item) {
                $mydb->setQuery("UPDATE `tblproduct` SET `PROQTY` = `PROQTY` + {$item->quantity} WHERE `PROID` = {$item->product_id}");
                $mydb->executeQuery();
            }
        }
        
        message("Refund completed successfully! Stock items returned to active inventory.", "success");
    }
    redirect("index.php");
}

if ($action === 'export') {
    // Generate CSV file
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=hmart_returns_export_' . date('Ymd') . '.csv');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Return ID', 'Customer Name', 'Order Number', 'Request Date', 'Reason', 'Refund Amount (INR)', 'Status']);
    
    $mydb->setQuery("
        SELECT r.return_id, CONCAT(c.FNAME, ' ', c.LNAME) as customer_name, r.order_number, r.request_date, r.reason_summary, r.refund_amount, r.return_status 
        FROM `returns` r
        JOIN `tblcustomer` c ON r.customer_id = c.CUSTOMERID
        ORDER BY r.return_id DESC
    ");
    $rows = $mydb->loadResultList();
    if ($rows) {
        foreach ($rows as $row) {
            fputcsv($output, [
                $row->return_id,
                $row->customer_name,
                $row->order_number,
                $row->request_date,
                $row->reason_summary,
                $row->refund_amount,
                $row->return_status
            ]);
        }
    }
    fclose($output);
    exit;
}

$title = "Returns & Refunds Management";
$content = 'view.php';

require_once("../theme/templates.php");
?>
