<?php
$_SERVER['DOCUMENT_ROOT'] = 'C:/xampp/htdocs';
require_once(dirname(__DIR__) . "/include/initialize.php");

header('Content-Type: application/json');

if (isset($_GET['query'])) {
    $searchTerm = $mydb->escape_value($_GET['query']);
    
    // Search for products matching the query
    $query = "SELECT pr.`PROID`, p.`PRODESC`, pr.`PRODISPRICE`, p.`IMAGES`, c.`CATEGORIES`
              FROM `tblpromopro` pr , `tblproduct` p , `tblcategory` c
              WHERE pr.`PROID`=p.`PROID` AND p.`CATEGID` = c.`CATEGID` AND PROQTY>0 
              AND ( p.`PRODESC` LIKE '%{$searchTerm}%' OR c.`CATEGORIES` LIKE '%{$searchTerm}%')
              LIMIT 5";
              
    $mydb->setQuery($query);
    $results = $mydb->loadResultList();
    
    $suggestions = [];
    foreach ($results as $row) {
        $suggestions[] = [
            'id' => $row->PROID,
            'name' => $row->PRODESC,
            'category' => $row->CATEGORIES,
            'price' => number_format($row->PRODISPRICE, 2),
            'image' => product_image_url($row->IMAGES, $row->PRODESC)
        ];
    }
    
    echo json_encode($suggestions);
} else {
    echo json_encode([]);
}
?>
