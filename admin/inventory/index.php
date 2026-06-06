<?php
require_once('../../backend/include/initialize.php');
if (!isset($_SESSION['USERID'])) {
    redirect(web_root . 'admin/login.php');
}

InventoryAnalytics::refreshAlerts();

$title = 'Smart Inventory';
$lowStock = InventoryAnalytics::getLowStockProducts();
$movement = InventoryAnalytics::getMovementReport();
$alerts = InventoryAnalytics::getRecentAlerts(40);

$content = __DIR__ . '/inventory_content.php';
require_once('../theme/templates.php');
