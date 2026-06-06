<?php
/**
 * Demand Forecasting Controller for H-Mart Admin.
 */
require_once("../../backend/include/initialize.php");

if (!isset($_SESSION['USERID'])) {
    redirect(web_root . "admin/index.php");
}

$view = (isset($_GET['view']) && $_GET['view'] != '') ? $_GET['view'] : '';
$action = (isset($_GET['action']) && $_GET['action'] != '') ? $_GET['action'] : '';

// Handle actions
if ($action === 'train') {
    $productId = isset($_GET['product_id']) ? (int)$_GET['product_id'] : null;
    $res = AIClient::call('/api/forecast/train', 'POST', ['product_id' => $productId]);
    if (isset($res['status']) && $res['status'] === 'success') {
        message("Demand forecasting model successfully trained!", "success");
    } else {
        message("Training failed: " . (isset($res['error']) ? $res['error'] : 'Unknown error'), "error");
    }
    redirect("index.php" . ($productId ? "?product_id=" . $productId : ""));
}

if ($action === 'batch') {
    $res = AIClient::call('/api/forecast/batch', 'POST');
    if (isset($res['status']) && $res['status'] === 'success') {
        message("Batch demand forecast simulation completed!", "success");
    } else {
        message("Batch forecast failed.", "error");
    }
    redirect("index.php");
}

$title = "Demand Forecasting AI";
$content = 'view.php';

require_once("../theme/templates.php");
?>
