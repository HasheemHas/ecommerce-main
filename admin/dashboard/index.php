<?php
require_once('../../backend/include/initialize.php');
if (!isset($_SESSION['USERID'])) {
    redirect(web_root . 'admin/login.php');
}

@InventoryAnalytics::refreshAlerts();

$title = 'Analytics Dashboard';
$kpi = AnalyticsDashboard::kpiSummary();
$salesMonths = AnalyticsDashboard::salesByMonth(6);
$orderStatus = AnalyticsDashboard::ordersByStatus();
$paymentMethods = AnalyticsDashboard::paymentMethodBreakdown();
$topProducts = AnalyticsDashboard::topProducts(8);
$customerActivity = AnalyticsDashboard::customerActivityLast7Days();

$salesLabels = [];
$salesData = [];
foreach ($salesMonths as $row) {
    $salesLabels[] = $row->month_label;
    $salesData[] = round((float) $row->total_sales, 2);
}

$statusLabels = [];
$statusData = [];
foreach ($orderStatus as $row) {
    $statusLabels[] = $row->status_label;
    $statusData[] = (int) $row->cnt;
}

$payLabels = [];
$payData = [];
foreach ($paymentMethods as $row) {
    $payLabels[] = $row->method_label;
    $payData[] = (int) $row->cnt;
}

$topLabels = [];
$topData = [];
foreach ($topProducts as $row) {
    $topLabels[] = mb_substr($row->PRODESC, 0, 20) . '…';
    $topData[] = (int) $row->qty;
}

$activityLabels = [];
$activityData = [];
foreach ($customerActivity as $row) {
    $activityLabels[] = $row->day_label;
    $activityData[] = (int) $row->customers;
}

$content = __DIR__ . '/dashboard_content.php';
require_once('../theme/templates.php');
