<?php
/**
 * Vendor Directory Controller.
 */
require_once("../../backend/include/initialize.php");

if (!isset($_SESSION['USERID'])) {
    redirect(web_root . "admin/index.php");
}

$view = (isset($_GET['view']) && $_GET['view'] != '') ? $_GET['view'] : '';
$action = (isset($_GET['action']) && $_GET['action'] != '') ? $_GET['action'] : '';

global $mydb;

if ($action === 'add') {
    $name = isset($_POST['vendor_name']) ? trim($_POST['vendor_name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    
    if (!empty($name)) {
        $mydb->setQuery("
            INSERT INTO `vendors` (`vendor_name`, `email`, `phone`, `address`, `status`)
            VALUES ('{$name}', '{$email}', '{$phone}', '{$address}', 'Active')
        ");
        if ($mydb->executeQuery()) {
            message("Vendor '{$name}' added successfully!", "success");
        } else {
            message("Failed to add vendor.", "error");
        }
    }
    redirect("index.php");
}

if ($action === 'po_add') {
    $vendorId = isset($_POST['vendor_id']) ? (int)$_POST['vendor_id'] : 0;
    $amount = isset($_POST['total_amount']) ? (float)$_POST['total_amount'] : 0.0;
    $delivery = isset($_POST['expected_delivery']) ? $_POST['expected_delivery'] : date('Y-m-d', strtotime('+7 days'));
    
    if ($vendorId > 0 && $amount > 0) {
        $poNumber = 'PO-' . date('Y') . '-' . rand(1000, 9999);
        // Create PO
        $mydb->setQuery("
            INSERT INTO `purchase_orders` (`po_number`, `vendor_id`, `status`, `total_amount`, `expected_delivery`)
            VALUES ('{$poNumber}', {$vendorId}, 'Sent', {$amount}, '{$delivery}')
        ");
        
        if ($mydb->executeQuery()) {
            $poId = $mydb->insert_id();
            // Also create payout record
            $mydb->setQuery("
                INSERT INTO `vendor_payouts` (`vendor_id`, `po_id`, `amount`, `status`)
                VALUES ({$vendorId}, {$poId}, {$amount}, 'Unpaid')
            ");
            $mydb->executeQuery();
            
            message("Purchase Order '{$poNumber}' issued successfully!", "success");
        } else {
            message("Failed to create Purchase Order.", "error");
        }
    }
    redirect("index.php");
}

if ($action === 'payout') {
    $payoutId = isset($_GET['payout_id']) ? (int)$_GET['payout_id'] : 0;
    if ($payoutId > 0) {
        $mydb->setQuery("UPDATE `vendor_payouts` SET `status` = 'Paid', `processed_at` = CURRENT_TIMESTAMP WHERE `payout_id` = {$payoutId}");
        $mydb->executeQuery();
        message("Vendor payout processed and marked as Paid.", "success");
    }
    redirect("index.php");
}

$title = "Vendor Management Directory";
$content = 'view.php';

require_once("../theme/templates.php");
?>
