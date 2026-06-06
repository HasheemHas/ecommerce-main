<?php
/**
 * AJAX helper to add products to the H-Mart cart asynchronously.
 */
require_once("../backend/include/initialize.php");

header('Content-Type: application/json');

// Check if customer is logged in
if (!isset($_SESSION['CUSID'])) {
    echo json_encode([
        'success' => false,
        'error' => 'login_required',
        'message' => 'Please login first to add items to your cart.'
    ]);
    exit;
}

// Get JSON post input
$input = file_get_contents('php://input');
$data = json_decode($input, true);

$pid = isset($data['productId']) ? (int)$data['productId'] : 0;
$price = isset($data['price']) ? (float)$data['price'] : 0.0;
$qty = isset($data['qty']) ? (int)$data['qty'] : 1;

if ($pid <= 0 || $price <= 0.0) {
    echo json_encode([
        'success' => false,
        'error' => 'invalid_input',
        'message' => 'Invalid product or price specified.'
    ]);
    exit;
}

// Check if product exists in database and has stock
global $mydb;
$sql = "SELECT * FROM `tblproduct` WHERE `PROID` = {$pid} AND PROQTY >= {$qty}";
$mydb->setQuery($sql);
$row = $mydb->loadSingleResult();

if (!$row) {
    echo json_encode([
        'success' => false,
        'error' => 'out_of_stock',
        'message' => 'Product is unavailable or out of stock.'
    ]);
    exit;
}

// Calculate total price
$tot = $price * $qty;

// Add to cart
addtocart($pid, $qty, $tot);

// Count active items in cart
$cartCount = 0;
if (isset($_SESSION['gcCart']) && is_array($_SESSION['gcCart'])) {
    $cartCount = count($_SESSION['gcCart']);
}

echo json_encode([
    'success' => true,
    'cartCount' => $cartCount,
    'message' => 'Product added to cart successfully!'
]);
exit;
?>
