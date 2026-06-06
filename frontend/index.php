<?php
require_once ("../backend/include/initialize.php");

// Intercept language switching
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
    $redirect_url = strtok($_SERVER["REQUEST_URI"], '?');
    $params = $_GET;
    unset($params['lang']);
    if (!empty($params)) {
        $redirect_url .= '?' . http_build_query($params);
    }
    redirect($redirect_url);
}

// Intercept currency switching
if (isset($_GET['currency'])) {
    $_SESSION['currency'] = $_GET['currency'];
    $redirect_url = strtok($_SERVER["REQUEST_URI"], '?');
    $params = $_GET;
    unset($params['currency']);
    if (!empty($params)) {
        $redirect_url .= '?' . http_build_query($params);
    }
    redirect($redirect_url);
}

$content='home.php';
$view = (isset($_GET['q']) && $_GET['q'] != '') ? $_GET['q'] : '';




switch ($view) {
 

	case 'product' :
        $title="Products";	
		$content='menu.php';		
		break;
	case 'aishopper' :
        $title="AI Personal Shopper";	
		$content='aishopper.php';		
		break;
 	case 'cart' :
        $title="Cart List";	
		$content='cart.php';		
		break;
 	case 'profile' :
        $title="Profile";	
		$content='../backend/customer/profile.php';		
		break;

	case 'trackorder' :
        $title="Track Order";	
		$content='../backend/customer/trackorder.php';		
		break;

	case 'orderdetails' :  

         If(!isset($_SESSION['orderdetails'])){
         $_SESSION['orderdetails'] = "Order Details";
		} 
		$content='../backend/customer/orderdetails.php';	


	if( isset($_SESSION['orderdetails'])){
      if (is_array($_SESSION['orderdetails']) ? count($_SESSION['orderdetails'])>0 : !empty($_SESSION['orderdetails'])){
        	$title = 'Cart List' . '| <a href="">Order Details</a>';
		      }
		    } 	
		break;

	case 'billing' : 	
	 If(!isset($_SESSION['billingdetails'])){
         $_SESSION['billingdetails'] = "Order Details";
		} 
		$content='../backend/customer/customerbilling.php';	
		if( isset($_SESSION['billingdetails'])){
      if (is_array($_SESSION['billingdetails']) ? count($_SESSION['billingdetails'])>0 : !empty($_SESSION['billingdetails'])){
        	$title = 'Cart List' . '| <a href="">Billing Details</a>';
		      }
		    } 	
		break;

	case 'contact' :
        $title="Contact Us";	
		$content='contact.php';		
		break;
	case 'about' :
        $title="About Us";	
		$content='about.php';		
		break;
	case 'terms' :
        $title="Terms of Use";	
		$content='terms.php';		
		break;
	case 'privacy' :
        $title="Privacy Policy";	
		$content='privacy.php';		
		break;
	case 'refund' :
        $title="Refund Policy";	
		$content='refund.php';		
		break;
	case 'delivery' :
        $title="Delivery Information";	
		$content='delivery.php';		
		break;
	case 'careers' :
        $title="Careers at H-Mart";	
		$content='careers.php';		
		break;
	case 'location' :
        $title="Store Location";	
		$content='location.php';		
		break;
	case 'affiliate' :
        $title="Affiliate Program";	
		$content='affiliate.php';		
		break;
 	case 'single-item' :
        $title = 'Product Details';
		$content = 'product-detail.php';
		break;

	case 'recoverpassword' :
        $title="Recover Password";	
		$content='passwordrecover.php';		
		break;

	case 'login' :
        $title="Login";	
		$content='login_page.php';		
		break;

	case 'signup' :
        $title="Sign Up";	
		$content='signup_page.php';		
		break;

	default :
	    $title="Home";	
		$content ='home.php';		

}

       
    
 
require_once("theme/templates.php");
 

?>

