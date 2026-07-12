<?php
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['DOCUMENT_ROOT'] = 'C:/xampp/htdocs';
$_SERVER['SCRIPT_NAME'] = '/ecommerce-main/check_admin.php';

require_once 'backend/include/initialize.php';
global $mydb;

echo "--- tbluseraccount entries ---\n";
$mydb->setQuery("SELECT * FROM tbluseraccount");
$res = $mydb->loadResultList();
foreach ($res as $row) {
    print_r($row);
}
?>
