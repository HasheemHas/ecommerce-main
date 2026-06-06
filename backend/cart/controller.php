<?php
require_once ("../include/initialize.php");
  // if (!isset($_SESSION['USERID'])){
  //     redirect("index.php");
  //    }

$action = (isset($_GET['action']) && $_GET['action'] != '') ? $_GET['action'] : '';

switch ($action) {
	case 'add' :
	doInsert();
	break;
	
	case 'edit' :
	doEdit();
	break;
	
	case 'delete' :
	doDelete();
	break;

	// case 'unsetmsg' :
	// unsetmsg();
	// break;
 
	}
   
	function doInsert(){
	 global $mydb;

   if(isset($_POST['btnorder']) || isset($_POST['buynow'])){

    if (!isset($_SESSION['CUSID'])) {
        $_SESSION['login_redirect'] = isset($_POST['redirect_after']) ? $_POST['redirect_after'] : 'index.php?q=cart';
        if (isset($_POST['PROID'])) {
            $_SESSION['pending_cart_product'] = (int) $_POST['PROID'];
        }
        message('Please login first to add items to your cart.', 'error');
        redirect(web_root . 'index.php?q=login');
    }

    $pid = (int) $_POST['PROID'];
    $price = (float) $_POST['PROPRICE'];
    $qty = isset($_POST['item_qty']) ? max(1, (int) $_POST['item_qty']) : 1;

    $sql = "SELECT * FROM `tblproduct` WHERE `PROID` = {$pid} AND PROQTY >= {$qty}";
    $mydb->setQuery($sql);
    $row = $mydb->loadSingleResult();
    if (!$row) {
        message('Product unavailable or insufficient stock.', 'error');
        redirect(web_root . 'index.php?q=product');
    }

    $tot = $price * $qty;
    addtocart($pid, $qty, $tot);

    if (isset($_POST['buynow'])) {
        redirect(web_root . 'index.php?q=cart');
    }
    redirect(web_root . 'index.php?q=cart');
  
}
}
		 

 

	function doEdit(){

	 global $mydb;
  if (isset($_POST['UPPROID'])){   
  

     $max=count($_SESSION['gcCart']);
    for($i=0;$i<$max;$i++){

      $pid=$_SESSION['gcCart'][$i]['productid'];
 
       $qty=intval(isset($_GET['QTY'.$pid]) ? $_GET['QTY'.$_POST['UPPROID']] : "");
       $price=(double)(isset($_GET['TOT'.$pid]) ? $_GET['TOT'.$_POST['UPPROID']] : "");
 
       $sql = "SELECT * FROM `tblproduct` WHERE `PROID` =" . $pid;
       $mydb->setQuery($sql);
	    $result = $mydb->loadResultList();

	    foreach ($result as $row) {
 
       		if($qty>0  && $qty<=999){
		      	// la pa natapos... price

		        $_SESSION['gcCart'][$i]['qty']=$qty;
		        $_SESSION['gcCart'][$i]['price']=$price;
		      }

       	}
       } 
     }

     
   }
   
 

	function doDelete(){
	 
 
		if(isset($_GET['id'])) {
			removetocart($_GET['id']);
			$countcart =isset($_SESSION['gcCart'])? count($_SESSION['gcCart']) : "0";
			if($countcart==0){
				
			unset($_SESSION['orderdetails']);
			unset($_SESSION['gcCart']);
			} 
				message("1 item has been removed in the cart.","success");
				redirect(web_root."index.php?q=cart");
		  
 		}
	
	    }

	// function unsetmsg(){
	// 	if($_POST['summaryid']){
	// 		$summary = New Summary();
	// 		$summary->HVIEW = 1;
	// 		$summary->update($_POST['summaryid']); 
	// 		echo '<script> alert("Get success");</script>';
	// 	}
	// }
?>