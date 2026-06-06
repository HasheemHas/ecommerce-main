<?php
/**
 * Customer Churn Prediction Controller.
 */
require_once("../../backend/include/initialize.php");

if (!isset($_SESSION['USERID'])) {
    redirect(web_root . "admin/index.php");
}

$view = (isset($_GET['view']) && $_GET['view'] != '') ? $_GET['view'] : '';
$action = (isset($_GET['action']) && $_GET['action'] != '') ? $_GET['action'] : '';

// Handle model actions
if ($action === 'train' || $action === 'batch') {
    $res = AIClient::call('/api/churn/batch', 'POST');
    if (isset($res['status']) && $res['status'] === 'success') {
        message("Customer churn risk scores recalculated successfully!", "success");
    } else {
        message("Churn re-calculation failed.", "error");
    }
    redirect("index.php");
}

$title = "Customer Churn Prediction";
$content = 'view.php';

require_once("../theme/templates.php");
?>
