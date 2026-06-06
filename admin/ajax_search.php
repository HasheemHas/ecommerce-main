<?php
require_once("../backend/include/initialize.php");

header('Content-Type: application/json');

if (!isset($_SESSION['USERID'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$q = isset($_GET['q']) ? $_GET['q'] : '';

if (strlen(trim($q)) < 2) {
    echo json_encode([]);
    exit;
}

global $mydb;

$results = [];

// 1. Search Products
$query = "SELECT PROID, PRODESC, CATEGID FROM tblproduct WHERE PRODESC LIKE '%{$q}%' LIMIT 5";
$mydb->setQuery($query);
$products = $mydb->loadResultList();
if ($products) {
    foreach ($products as $p) {
        $results[] = [
            'type' => 'Product',
            'title' => $p->PRODESC,
            'url' => web_root . 'admin/products/index.php?view=edit&id=' . $p->PROID,
            'icon' => 'fa-archive'
        ];
    }
}

// 2. Search Orders
$query = "SELECT ORDERNO FROM tblsummary WHERE ORDERNO LIKE '%{$q}%' LIMIT 3";
$mydb->setQuery($query);
$orders = $mydb->loadResultList();
if ($orders) {
    foreach ($orders as $o) {
        $results[] = [
            'type' => 'Order',
            'title' => 'Order #' . $o->ORDERNO,
            'url' => web_root . 'admin/orders/index.php',
            'icon' => 'fa-shopping-cart'
        ];
    }
}

// 3. Search Users (Admin)
$query = "SELECT USERID, U_NAME FROM tblusers WHERE U_NAME LIKE '%{$q}%' LIMIT 2";
$mydb->setQuery($query);
$users = $mydb->loadResultList();
if ($users) {
    foreach ($users as $u) {
        $results[] = [
            'type' => 'User',
            'title' => $u->U_NAME,
            'url' => web_root . 'admin/user/index.php',
            'icon' => 'fa-user'
        ];
    }
}

echo json_encode($results);
?>
