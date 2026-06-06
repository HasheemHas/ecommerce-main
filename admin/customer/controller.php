<?php
require_once ("../../backend/include/initialize.php");
if (!isset($_SESSION['USERID'])){
    redirect(web_root."admin/index.php");
}

$action = (isset($_GET['action']) && $_GET['action'] != '') ? $_GET['action'] : '';

switch ($action) {
	case 'edit' :
	doEdit();
	break;
	
	case 'delete' :
	doDelete();
	break;
}

function doEdit(){
	if(isset($_POST['save'])){
        $customerid = $_POST['CUSTOMERID'];
        
        $customer = New Customer(); 
        $customer->FNAME = $_POST['FNAME'];
        $customer->LNAME = $_POST['LNAME'];
        $customer->MNAME = $_POST['MNAME'];
        $customer->CUSUNAME = $_POST['CUSUNAME'];
        $customer->PHONE = $_POST['PHONE'];
        $customer->TERMS = $_POST['TERMS'];
        
        $customer->CUSHOMENUM = $_POST['CUSHOMENUM'];
        $customer->STREETADD = $_POST['STREETADD'];
        $customer->BRGYADD = $_POST['BRGYADD'];
        $customer->CITYADD = $_POST['CITYADD'];
        $customer->PROVINCE = $_POST['PROVINCE'];
        $customer->ZIPCODE = $_POST['ZIPCODE'];

        $customer->update($customerid);

        message("Customer [". $_POST['FNAME'] . " " . $_POST['LNAME'] ."] details updated successfully!", "success");
        redirect("index.php");
	}
}

function doDelete(){
    if(isset($_GET['id'])) {
        $customerid = $_GET['id'];
        
        $customer = New Customer();
        $customer->delete($customerid);
        
        message("Customer account successfully deleted!","info");
    }
    redirect('index.php');
}
?>
