<?php
require_once(dirname(__DIR__) . "/include/initialize.php");

header('Content-Type: application/json');

if (!isset($_SESSION['CUSID'])) {
    echo json_encode(['status' => 'error', 'message' => 'Please login first.']);
    exit;
}

$customer_id = (int)$_SESSION['CUSID'];
$action = isset($_GET['action']) ? $_GET['action'] : 'add';

if ($action === 'add') {
    $product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
    $wishlist_id = isset($_GET['wishlist_id']) ? (int)$_GET['wishlist_id'] : 0;
    
    if ($product_id <= 0 || $wishlist_id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid parameters.']);
        exit;
    }
    
    global $mydb;
    // Check if wishlist belongs to customer
    $mydb->setQuery("SELECT * FROM `customer_wishlists` WHERE `wishlist_id` = {$wishlist_id} AND `customer_id` = {$customer_id}");
    $wl = $mydb->loadSingleResult();
    
    if (!$wl) {
        echo json_encode(['status' => 'error', 'message' => 'Wishlist not found.']);
        exit;
    }
    
    // Check if already in wishlist
    $mydb->setQuery("SELECT * FROM `wishlist_items` WHERE `wishlist_id` = {$wishlist_id} AND `product_id` = {$product_id}");
    $item = $mydb->loadSingleResult();
    
    if ($item) {
        echo json_encode(['status' => 'success', 'message' => 'Product is already in your wishlist.']);
        exit;
    }
    
    $mydb->setQuery("INSERT INTO `wishlist_items` (`wishlist_id`, `product_id`) VALUES ({$wishlist_id}, {$product_id})");
    $mydb->executeQuery();
    
    echo json_encode(['status' => 'success', 'message' => 'Product added to wishlist successfully.']);
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
?>
