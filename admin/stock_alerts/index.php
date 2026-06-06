<?php
/**
 * Proactive Low Stock Alerts Controller.
 */
require_once("../../backend/include/initialize.php");

if (!isset($_SESSION['USERID'])) {
    redirect(web_root . "admin/index.php");
}

$view = (isset($_GET['view']) && $_GET['view'] != '') ? $_GET['view'] : '';
$action = (isset($_GET['action']) && $_GET['action'] != '') ? $_GET['action'] : '';

global $mydb;

if ($action === 'set_threshold') {
    $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $threshold = isset($_POST['threshold']) ? (int)$_POST['threshold'] : 0;
    
    if ($productId > 0 && $threshold >= 0) {
        // Fetch current stock from tblproduct
        $mydb->setQuery("SELECT PROQTY FROM tblproduct WHERE PROID = {$productId}");
        $p = $mydb->loadSingleResult();
        $currentStock = $p ? (int)$p->PROQTY : 0;
        
        // Check if alert already exists for this product
        $mydb->setQuery("SELECT * FROM low_stock_alerts WHERE product_id = {$productId}");
        $exist = $mydb->loadSingleResult();
        
        if ($exist) {
            $status = ($currentStock <= $threshold) ? 'Active' : 'Resolved';
            $mydb->setQuery("
                UPDATE low_stock_alerts 
                SET threshold = {$threshold}, current_stock = {$currentStock}, status = '{$status}'
                WHERE product_id = {$productId}
            ");
            if ($mydb->executeQuery()) {
                message("Stock threshold for product ID {$productId} updated to {$threshold}.", "success");
            } else {
                message("Failed to update stock threshold.", "error");
            }
        } else {
            $status = ($currentStock <= $threshold) ? 'Active' : 'Resolved';
            $mydb->setQuery("
                INSERT INTO low_stock_alerts (product_id, threshold, current_stock, status)
                VALUES ({$productId}, {$threshold}, {$currentStock}, '{$status}')
            ");
            if ($mydb->executeQuery()) {
                message("Stock alert created successfully with threshold {$threshold}.", "success");
            } else {
                message("Failed to create stock alert threshold.", "error");
            }
        }
    } else {
        message("Invalid product selection or threshold value.", "error");
    }
    redirect("index.php");
}

if ($action === 'notify') {
    $alertId = isset($_GET['alert_id']) ? (int)$_GET['alert_id'] : 0;
    if ($alertId > 0) {
        $mydb->setQuery("UPDATE low_stock_alerts SET notified_at = CURRENT_TIMESTAMP WHERE alert_id = {$alertId}");
        if ($mydb->executeQuery()) {
            message("Mock notification (Email/SMS) sent successfully to Procurement Officer.", "success");
        } else {
            message("Failed to record notification dispatch.", "error");
        }
    }
    redirect("index.php");
}

if ($action === 'autoprocure') {
    $alertId = isset($_GET['alert_id']) ? (int)$_GET['alert_id'] : 0;
    if ($alertId > 0) {
        // Get alert details
        $mydb->setQuery("SELECT * FROM low_stock_alerts WHERE alert_id = {$alertId}");
        $alert = $mydb->loadSingleResult();
        
        if ($alert) {
            $productId = $alert->product_id;
            
            // Find lowest cost vendor mapping
            $mydb->setQuery("
                SELECT vp.*, v.vendor_name 
                FROM vendor_products vp 
                LEFT JOIN vendors v ON vp.vendor_id = v.vendor_id 
                WHERE vp.product_id = {$productId} AND v.status = 'Active' 
                ORDER BY vp.cost_price ASC 
                LIMIT 1
            ");
            $vendorProduct = $mydb->loadSingleResult();
            
            if ($vendorProduct) {
                $vendorId = $vendorProduct->vendor_id;
                $costPrice = $vendorProduct->cost_price;
                $leadTime = $vendorProduct->lead_time_days;
                
                // Calculate reorder quantity
                $reorderQty = max($alert->threshold * 3, 15);
                $totalAmount = $costPrice * $reorderQty;
                $expectedDelivery = date('Y-m-d', strtotime("+{$leadTime} days"));
                $poNumber = 'PO-AUTO-' . date('Y') . '-' . rand(1000, 9999);
                
                // 1. Insert Purchase Order
                $mydb->setQuery("
                    INSERT INTO purchase_orders (po_number, vendor_id, status, total_amount, expected_delivery)
                    VALUES ('{$poNumber}', {$vendorId}, 'Sent', {$totalAmount}, '{$expectedDelivery}')
                ");
                
                if ($mydb->executeQuery()) {
                    $poId = $mydb->insert_id();
                    
                    // 2. Insert Vendor Payout
                    $mydb->setQuery("
                        INSERT INTO vendor_payouts (vendor_id, po_id, amount, status)
                        VALUES ({$vendorId}, {$poId}, {$totalAmount}, 'Unpaid')
                    ");
                    $mydb->executeQuery();
                    
                    // 3. Update stock quantity in tblproduct
                    $mydb->setQuery("UPDATE tblproduct SET PROQTY = PROQTY + {$reorderQty} WHERE PROID = {$productId}");
                    $mydb->executeQuery();
                    
                    // 4. Resolve Alert
                    $newStock = $alert->current_stock + $reorderQty;
                    $mydb->setQuery("
                        UPDATE low_stock_alerts 
                        SET status = 'Resolved', current_stock = {$newStock}, resolved_at = CURRENT_TIMESTAMP 
                        WHERE alert_id = {$alertId}
                    ");
                    $mydb->executeQuery();
                    
                    message("Auto-procurement issued! Created Purchase Order {$poNumber} with {$vendorProduct->vendor_name} for {$reorderQty} units. Product inventory replenished.", "success");
                } else {
                    message("Failed to record Purchase Order.", "error");
                }
            } else {
                message("No active vendor is mapped to this product in the supplier products directory. Please map a supplier first.", "error");
            }
        } else {
            message("Low stock alert not found.", "error");
        }
    }
    redirect("index.php");
}

if ($action === 'check_stock') {
    $mydb->setQuery("SELECT PROID, PROQTY FROM tblproduct");
    $products = $mydb->loadResultList();
    
    $alertsCreated = 0;
    $alertsUpdated = 0;
    
    foreach ($products as $p) {
        $productId = $p->PROID;
        $qty = $p->PROQTY;
        
        $mydb->setQuery("SELECT * FROM low_stock_alerts WHERE product_id = {$productId}");
        $alert = $mydb->loadSingleResult();
        
        if ($alert) {
            $threshold = $alert->threshold;
            $status = ($qty <= $threshold) ? 'Active' : 'Resolved';
            
            if ($alert->status === 'Resolved' && $status === 'Active') {
                $mydb->setQuery("
                    UPDATE low_stock_alerts 
                    SET current_stock = {$qty}, status = 'Active', resolved_at = NULL, notified_at = NULL 
                    WHERE product_id = {$productId}
                ");
            } else {
                $mydb->setQuery("
                    UPDATE low_stock_alerts 
                    SET current_stock = {$qty}, status = '{$status}' 
                    WHERE product_id = {$productId}
                ");
            }
            $mydb->executeQuery();
            $alertsUpdated++;
        } else {
            $defaultThreshold = 10;
            if ($qty <= $defaultThreshold) {
                $mydb->setQuery("
                    INSERT INTO low_stock_alerts (product_id, threshold, current_stock, status)
                    VALUES ({$productId}, {$defaultThreshold}, {$qty}, 'Active')
                ");
                $mydb->executeQuery();
                $alertsCreated++;
            }
        }
    }
    message("Inventory scan completed! Newly generated alerts: {$alertsCreated}, refreshed states: {$alertsUpdated}.", "success");
    redirect("index.php");
}

$title = "Proactive Stock Alerts";
$content = 'view.php';

require_once("../theme/templates.php");
?>
