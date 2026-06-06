<?php
require_once("../include/initialize.php");

header('Content-Type: application/json');

if (!isset($_SESSION['compare_products'])) {
    $_SESSION['compare_products'] = [];
}

$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($action === 'add') {
    if ($product_id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid product ID.']);
        exit;
    }
    
    if (in_array($product_id, $_SESSION['compare_products'])) {
        echo json_encode(['status' => 'success', 'message' => 'Product is already in comparison list.', 'items' => $_SESSION['compare_products']]);
        exit;
    }
    
    if (count($_SESSION['compare_products']) >= 3) {
        echo json_encode(['status' => 'error', 'message' => 'You can compare a maximum of 3 products at a time. Please remove an item first.']);
        exit;
    }
    
    $_SESSION['compare_products'][] = $product_id;
    echo json_encode(['status' => 'success', 'message' => 'Product added to comparison list.', 'items' => $_SESSION['compare_products']]);
    exit;
}

if ($action === 'remove') {
    if (($key = array_search($product_id, $_SESSION['compare_products'])) !== false) {
        unset($_SESSION['compare_products'][$key]);
        $_SESSION['compare_products'] = array_values($_SESSION['compare_products']);
    }
    echo json_encode(['status' => 'success', 'message' => 'Product removed from comparison list.', 'items' => $_SESSION['compare_products']]);
    exit;
}

if ($action === 'list') {
    global $mydb;
    $results = [];
    
    if (!empty($_SESSION['compare_products'])) {
        $ids = implode(',', array_map('intval', $_SESSION['compare_products']));
        $mydb->setQuery("SELECT * FROM `tblproduct` p, `tblcategory` c WHERE p.CATEGID = c.CATEGID AND p.PROID IN ({$ids})");
        $items = $mydb->loadResultList();
        
        if ($items) {
            foreach ($items as $item) {
                $results[] = [
                    'product_id' => $item->PROID,
                    'name' => $item->PRODESC,
                    'price' => convert_price($item->PROPRICE),
                    'category' => $item->CATEGORIES,
                    'image' => str_replace('frontend/', '', web_root) . 'admin/products/' . $item->IMAGES,
                    'stock' => $item->PROQTY > 0 ? 'In Stock' : 'Out of Stock',
                    'ingredients' => $item->INGREDIENTS ? $item->INGREDIENTS : 'Standard Quality Ingredients'
                ];
            }
        }
    }
    
    echo json_encode(['status' => 'success', 'items' => $results]);
    exit;
}
?>
