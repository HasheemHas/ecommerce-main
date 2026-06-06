<?php
require_once("../backend/include/initialize.php");

header('Content-Type: application/json');

if (!isset($_SESSION['USERID'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

global $mydb;

$notifications = [];

// 1. Pending Orders
$query = "SELECT ORDERNO FROM tblsummary WHERE ORDEREDSTATS = 'Pending' ORDER BY ORDEREDNUM DESC LIMIT 5";
$mydb->setQuery($query);
$pending_orders = $mydb->loadResultList();

if ($pending_orders) {
    foreach ($pending_orders as $o) {
        $notifications[] = [
            'type' => 'order',
            'title' => 'New Pending Order',
            'message' => 'Order #' . $o->ORDERNO . ' is awaiting processing.',
            'url' => web_root . 'admin/orders/index.php',
            'time' => 'Just now',
            'icon' => 'fa-shopping-cart'
        ];
    }
}

// 2. Low Stock Alerts
$query = "SELECT PRODESC, PROQTY FROM tblproduct WHERE PROQTY < 10 LIMIT 5";
$mydb->setQuery($query);
$low_stock = $mydb->loadResultList();

if ($low_stock) {
    foreach ($low_stock as $p) {
        $notifications[] = [
            'type' => 'stock',
            'title' => 'Low Stock Alert',
            'message' => $p->PRODESC . ' is running low (' . $p->PROQTY . ' left).',
            'url' => web_root . 'admin/products/index.php',
            'time' => 'System',
            'icon' => 'fa-exclamation-triangle'
        ];
    }
}

echo json_encode([
    'count' => count($notifications),
    'items' => $notifications
]);
?>
