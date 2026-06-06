<?php
/**
 * Dynamic Pricing Controller.
 */
require_once("../../backend/include/initialize.php");

if (!isset($_SESSION['USERID'])) {
    redirect(web_root . "admin/index.php");
}

$view = (isset($_GET['view']) && $_GET['view'] != '') ? $_GET['view'] : '';
$action = (isset($_GET['action']) && $_GET['action'] != '') ? $_GET['action'] : '';

global $mydb;

// Action handlers
if ($action === 'optimize') {
    $res = AIClient::call('/api/pricing/optimize', 'POST');
    if (isset($res['status']) && $res['status'] === 'success') {
        message("Dynamic pricing optimized for active inventory!", "success");
    } else {
        message("Optimization failed.", "error");
    }
    redirect("index.php");
}

if ($action === 'apply') {
    $suggestionId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    if ($suggestionId > 0) {
        // Fetch suggested price details
        $mydb->setQuery("SELECT * FROM dynamic_pricing_suggestions WHERE id = {$suggestionId} LIMIT 1");
        $sug = $mydb->loadSingleResult();
        
        if ($sug) {
            $productId = $sug->product_id;
            $suggestedPrice = $sug->suggested_price;
            
            // 1. Update tblproduct price
            $mydb->setQuery("UPDATE tblproduct SET PROPRICE = {$suggestedPrice} WHERE PROID = {$productId}");
            $mydb->executeQuery();
            
            // 2. Also check if there's a promo price to sync
            $mydb->setQuery("UPDATE tblpromopro SET PRODISPRICE = {$suggestedPrice} WHERE PROID = {$productId}");
            $mydb->executeQuery();
            
            // 3. Mark suggestion status as applied
            $mydb->setQuery("UPDATE dynamic_pricing_suggestions SET status = 'applied' WHERE id = {$suggestionId}");
            $mydb->executeQuery();
            
            message("Suggested price of ₹" . number_format($suggestedPrice, 2) . " applied successfully to product!", "success");
        }
    }
    redirect("index.php");
}

if ($action === 'reject') {
    $suggestionId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($suggestionId > 0) {
        $mydb->setQuery("UPDATE dynamic_pricing_suggestions SET status = 'rejected' WHERE id = {$suggestionId}");
        $mydb->executeQuery();
        message("Price suggestion rejected.", "info");
    }
    redirect("index.php");
}

if ($action === 'abtest') {
    $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $priceA = isset($_POST['price_a']) ? (float)$_POST['price_a'] : 0.0;
    $priceB = isset($_POST['price_b']) ? (float)$_POST['price_b'] : 0.0;
    $startDate = isset($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-d');
    $endDate = isset($_POST['end_date']) ? $_POST['end_date'] : date('Y-m-d', strtotime('+14 days'));
    
    if ($productId > 0 && $priceA > 0 && $priceB > 0) {
        $mydb->setQuery("
            INSERT INTO price_ab_tests (product_id, price_a, price_b, start_date, end_date, status)
            VALUES ({$productId}, {$priceA}, {$priceB}, '{$startDate}', '{$endDate}', 'running')
        ");
        if ($mydb->executeQuery()) {
            message("A/B Price Test successfully launched!", "success");
        } else {
            message("Failed to launch A/B test.", "error");
        }
    }
    redirect("index.php");
}

if ($action === 'abtest_stop') {
    $testId = isset($_GET['test_id']) ? (int)$_GET['test_id'] : 0;
    if ($testId > 0) {
        $mydb->setQuery("UPDATE price_ab_tests SET status = 'completed', end_date = CURRENT_DATE WHERE id = {$testId}");
        $mydb->executeQuery();
        message("A/B Price Test stopped.", "info");
    }
    redirect("index.php");
}

$title = "Dynamic Pricing Engine";
$content = 'view.php';

require_once("../theme/templates.php");
?>
