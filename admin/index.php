<?php 
require_once("../backend/include/initialize.php");
	 if (!isset($_SESSION['USERID'])){
      redirect(web_root."admin/login.php");
     } 

$content='home.php';
$view = (isset($_GET['page']) && $_GET['page'] != '') ? $_GET['page'] : '';
switch ($view) {
	case '1' :
        $title="Products";	
		$content='products/';		
		break;	
	default :
	redirect("dashboard/index.php");
}
require_once("theme/templates.php");
?>