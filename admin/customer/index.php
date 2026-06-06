<?php
require_once("../../backend/include/initialize.php");
if(!isset($_SESSION['USERID'])){
	redirect(web_root."admin/index.php");
}

$view = (isset($_GET['view']) && $_GET['view'] != '') ? $_GET['view'] : '';
$title="Customers"; 
$header=$view; 
switch ($view) {
	case 'list' :
		$content    = 'list.php';		
		break;

	case 'edit' :
		$content    = 'edit.php';		
		break;

	default :
		$content    = 'list.php';		
}
require_once ("../theme/templates.php");
?>
