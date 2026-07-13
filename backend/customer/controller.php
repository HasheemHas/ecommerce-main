<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once ("../include/initialize.php");

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

 

	case 'processorder' :
	processorder();
	break;

	case 'addwish' :
	addwishlist();
	break;

	case 'wishlist' :
	processwishlist();
	break;

	case 'photos' :
	doupdateimage();
	break;

	case 'changepassword' :
	doChangePassword();
	break;

	case 'cancelorder' :
	doCancelOrder();
	break;

	case 'requestreturn' :
	doRequestReturn();
	break;

	case 'upgrademembership' :
	doUpgradeMembership();
	break;
	}

   
function doInsert(){
	global $mydb;
	if(isset($_POST['submit'])){
		$email = strtolower(trim($_POST['CUSUNAME']));

		// Check if email already exists
		$mydb->setQuery("SELECT CUSTOMERID FROM tblcustomer WHERE LOWER(CUSUNAME)='" . $mydb->escape_value($email) . "' OR LOWER(EMAILADD)='" . $mydb->escape_value($email) . "' LIMIT 1");
		if ($mydb->loadSingleResult()) {
			message('An account with this email address already exists. Please login instead.', 'error');
			redirect(web_root . 'index.php?q=signup');
		}

		$customer = New Customer(); 
		$customer->FNAME 			= $_POST['FNAME'];
		$customer->LNAME 			= $_POST['LNAME']; 		
		$customer->MNAME 			= '';
		$customer->CUSHOMENUM 		= '';
		$customer->STREETADD		= '';
		$customer->BRGYADD 			= '';
		$customer->CITYADD  		= 'Not specified'; 
		$customer->PROVINCE 		= '';
		$customer->COUNTRY 			= '';
		$customer->DBIRTH 			= '2000-01-01';
		$customer->GENDER 			= 'Male';
		$customer->PHONE 			= $_POST['PHONE']; 
		$customer->EMAILADD 		= $_POST['CUSUNAME'];
		$customer->ZIPCODE 			= 0;
		$customer->CUSUNAME			= $_POST['CUSUNAME'];
		$customer->CUSPASS			= password_hash($_POST['CUSPASS'], PASSWORD_BCRYPT);
		$customer->CUSPHOTO 		= '';
		$customer->DATEJOIN 		= date('Y-m-d H:i:s');
		$customer->TERMS 			= 1; // Auto-verified (no email verification required on InfinityFree)

		if ($customer->create()) {
			message("Registration successful! You can now log in.", "success");
			redirect(web_root . 'index.php?q=login');
		} else {
			message("Registration failed. Please try again.", "error");
			redirect(web_root . 'index.php?q=signup');
		}
	}
}
 
	function doEdit(){
		if(isset($_POST['save'])){


			
			$customer = New Customer();
			// $customer->CUSTOMERID 		= $_POST['CUSTOMERID'];
			$customer->FNAME 			= $_POST['FNAME'];
			$customer->LNAME 			= $_POST['LNAME'];
			// $customer->MNAME 			= $_POST['MNAME'];
			// $customer->CUSHOMENUM 		= $_POST['CUSHOMENUM'];
			// $customer->STREETADD		= $_POST['STREETADD'];
			// $customer->BRGYADD 			= $_POST['BRGYADD'] ;			
			$customer->CITYADD  		= $_POST['CITYADD'];
			// $customer->PROVINCE 		= $_POST['PROVINCE'];
			// $customer->COUNTRY 			= $_POST['COUNTRY'];
			$customer->GENDER 			= $_POST['GENDER'];
		 	$customer->PHONE 			= $_POST['PHONE'];
			// $customer->ZIPCODE 			= $_POST['ZIPCODE']; 
			$customer->CUSUNAME			= $_POST['CUSUNAME'];
			$customer->update($_SESSION['CUSID']);


			message("Accounts has been updated!", "success");
			redirect(web_root.'index.php?q=profile');
		}
	}


	function doDelete(){

		if(isset($_SESSION['U_ROLE'])=='Customer'){

			if (isset($_POST['selector'])==''){
			message("Select the records first before you delete!","error");
			redirect(web_root.'index.php?page=9');
			}else{
		
			$id = $_POST['selector'];
			$key = count($id);

			for($i=0;$i<$key;$i++){ 

			$order = New Order();
			$order->delete($id[$i]);
 
			message("Order has been Deleted!","info");
			redirect(web_root."index.php?q='product'"); 


		} 


		}
	}else{

		if (isset($_POST['selector'])==''){
			message("Select the records first before you delete!","error");
			redirect('index.php');
			}else{

			$id = $_POST['selector'];
			$key = count($id);

			for($i=0;$i<$key;$i++){ 

			$customer = New Customer();
			$customer->delete($id[$i]);

			$user = New User();
			$user->delete($id[$i]);

			message("Customer has been Deleted!","info");
			redirect('index.php');

			}
		}

	}
		
	}

	 
		function processorder(){
			if (!isset($_SESSION['CUSID'])) {
				message('Please log in to place an order.', 'error');
				redirect(web_root . 'index.php?q=login');
			}
			$orderTotal = isset($_POST['alltot']) ? (float) $_POST['alltot'] : 0;
			$fraudCheck = FraudDetector::checkCheckoutAllowed($_SESSION['CUSID'], $orderTotal);
			if (!$fraudCheck['allowed']) {
				FraudDetector::logPaymentAttempt($_SESSION['CUSID'], isset($_POST['ORDEREDNUM']) ? $_POST['ORDEREDNUM'] : null, isset($_POST['paymethod']) ? $_POST['paymethod'] : 'unknown', $orderTotal, 'blocked', $fraudCheck['message']);
				message($fraudCheck['message'], 'error');
				redirect(web_root . 'index.php?q=orderdetails');
			}

			// Simulate payment validation / failure for testing
			$paymethod = isset($_POST['paymethod']) ? $_POST['paymethod'] : 'unknown';
			$paymentFailed = false;
			$failureReason = '';

			if ($paymethod === 'Card Payment') {
				$cvv = isset($_POST['card_cvv']) ? trim($_POST['card_cvv']) : '';
				$cardNumber = isset($_POST['card_number']) ? trim(str_replace(' ', '', $_POST['card_number'])) : '';
				if ($cvv === '999' || substr($cardNumber, -4) === '4444') {
					$paymentFailed = true;
					$failureReason = 'Card declined: Insufficient funds or invalid CVV.';
				}
			} elseif ($paymethod === 'UPI Payment') {
				$utr = isset($_POST['utr_number']) ? trim($_POST['utr_number']) : '';
				if ($utr === '000000000000' || strtolower($utr) === 'fail' || strlen($utr) < 12) {
					$paymentFailed = true;
					$failureReason = 'UPI transaction verification failed: Invalid UTR.';
				}
			}

			if ($paymentFailed) {
				FraudDetector::logPaymentAttempt($_SESSION['CUSID'], isset($_POST['ORDEREDNUM']) ? $_POST['ORDEREDNUM'] : null, $paymethod, $orderTotal, 'failed', $failureReason);
				message($failureReason . ' Please try again.', 'error');
				redirect(web_root . 'index.php?q=orderdetails');
			}

			if (isset($_POST['FNAME'])) {
				$cust = New Customer();
				$cust->FNAME = $_POST['FNAME'];
				$cust->LNAME = $_POST['LNAME'];
				$cust->CITYADD = $_POST['CITYADD'];
				$cust->STREETADD = $_POST['STREETADD'];
				$cust->ZIPCODE = $_POST['ZIPCODE'];
				$cust->PROVINCE = $_POST['PROVINCE'];
				$cust->update($_SESSION['CUSID']);
				
				// Keep session name updated
				$_SESSION['CUSNAME'] = $_POST['FNAME'] . ' ' . $_POST['LNAME'];
			}
 
		//	$_SESSION['ORDEREDNUM'] = $_POST['ORDEREDNUM'];
			 
			
		 	// $autonumber = New Autonumber();
 			// $res = $autonumber->set_autonumber('ordernumber');


			$count_cart = (isset($_SESSION['gcCart']) && is_array($_SESSION['gcCart'])) ? count($_SESSION['gcCart']) : 0;
             for ($i=0; $i < $count_cart  ; $i++) {

			$order = New Order();
			$order->PROID		    = $_SESSION['gcCart'][$i]['productid'];
			$order->ORDEREDQTY		= $_SESSION['gcCart'][$i]['qty'];
			$order->ORDEREDPRICE	= $_SESSION['gcCart'][$i]['price'];
			$order->ORDEREDNUM		= $_POST['ORDEREDNUM'];
	     	$order->create();

		  	$product = New Product();
			$product->qtydeduct($_SESSION['gcCart'][$i]['productid'],$_SESSION['gcCart'][$i]['qty']);
		  }

		  // Create order summary ONCE (not in the loop)
		  $remarks = 'Your order is on process.';
		  if ($_POST['paymethod'] === 'UPI Payment' && isset($_POST['utr_number'])) {
			  $remarks = 'Paid via UPI. UTR: ' . trim($_POST['utr_number']);
		  } elseif ($_POST['paymethod'] === 'Card Payment' && isset($_POST['card_number'])) {
			  $maskedCard = '•••• •••• •••• ' . substr(str_replace(' ', '', $_POST['card_number']), -4);
			  $remarks = 'Paid via Card. Card: ' . $maskedCard;
		  }
		  $summary = New Summary();
		  $summary->ORDEREDDATE 	= date("Y-m-d h:i:s");
		  $summary->CUSTOMERID		= $_SESSION['CUSID'];
		  $summary->ORDEREDNUM  	= $_POST['ORDEREDNUM'];
		  $summary->DELFEE  		= $_POST['PLACE'];
		  $summary->PAYMENTMETHOD	= $_POST['paymethod'];
		  $summary->PAYMENT 		= $_POST['alltot'];
		  $summary->ORDEREDSTATS 	= 'Pending';
		  $summary->CLAIMEDDATE		= $_POST['CLAIMEDDATE'];
		  $summary->ORDEREDREMARKS 	= $remarks;
		  $summary->HVIEW 			= 0	;
		  $summary->create();

     


		$autonumber = New Autonumber();
		$autonumber->auto_update('ordernumber');

 
		unset($_SESSION['gcCart']);  
		unset($_SESSION['orderdetails']); 

		FraudDetector::logPaymentAttempt($_SESSION['CUSID'], $_POST['ORDEREDNUM'], $_POST['paymethod'], $orderTotal, 'success', 'Order placed');
		message("Order created successfully!", "success"); 		 
		redirect(web_root."index.php?q=profile");

		}
			 


	function processwishlist(){
		global $mydb;
		if(isset($_GET['wishid'])){

		  $query ="UPDATE `tblwishlist` SET `WISHSTATS`=1  WHERE `WISHLISTID`=" .$_GET['wishid'];
	      $mydb->setQuery($query);
	      $res = $mydb->executeQuery();
		 if (isset($res)){
		 		message("Product has been removed in your wishlist", "success"); 		 
			redirect(web_root."index.php?q=profile");
		 }

		

		}
		

		}
			 

	function addwishlist(){
		global $mydb;

		$proid = $_GET['proid'];
		$id =$_SESSION['CUSID'];

		$query="SELECT * FROM `tblwishlist` WHERE  CUSID=".$id." AND `PROID` =" .$proid ;
		$mydb->setQuery($query);
		$res = $mydb->executeQuery();
		$maxrow = $mydb->num_rows($res);

		if($maxrow>0){
				message("Product is already added to your wishlist", "error"); 		 
				redirect(web_root."index.php?q=profile"); 
		}else{
				$query ="INSERT INTO `tblwishlist` (`PROID`, `CUSID`, `WISHDATE`, `WISHSTATS`)  VALUES ('{$proid}','{$id}','".DATE('Y-m-d')."',0)";
				$mydb->setQuery($query);
				$mydb->executeQuery();
			 
	 	message("Product has been added to your wishlist", "success"); 		 
			redirect(web_root."index.php?q=profile"); 
		}
			 
			 
	 

		}
		function doupdateimage(){
 
			$errofile = $_FILES['photo']['error'];
			$type = $_FILES['photo']['type'];
			$temp = $_FILES['photo']['tmp_name'];
			$myfile =$_FILES['photo']['name'];
		 	$location="customer_image/".$myfile;


		if ( $errofile > 0) {
				message("No Image Selected!", "error");
				redirect(web_root. "index.php?q=profile");
		}else{
	 
				@$file=$_FILES['photo']['tmp_name'];
				@$image= addslashes(file_get_contents($_FILES['photo']['tmp_name']));
				@$image_name= addslashes($_FILES['photo']['name']); 
				@$image_size= getimagesize($_FILES['photo']['tmp_name']);

			if ($image_size==FALSE ) {
				message(web_root. "Uploaded file is not an image!", "error");
				redirect(web_root. "index.php?q=profile");
			}else{
					//uploading the file
					move_uploaded_file($temp,"customer_image/" . $myfile);
		 	
					 
						$customer = New Customer(); 
						$customer->CUSPHOTO 		= $location; 
						$customer->update($_SESSION['CUSID']); 

						redirect(web_root. "index.php?q=profile");
						 
							
					}
			}
			 
		}


		function doChangePassword(){
			if (isset($_POST['save'])) {
				# code...
				$customer = New Customer(); 
				$customer->CUSPASS			= password_hash($_POST['CUSPASS'], PASSWORD_BCRYPT);
				$customer->update($_SESSION['CUSID']);


			message("Password has been updated!", "success");
			redirect(web_root.'index.php?q=profile');
			}
		}

		function doCancelOrder(){
			global $mydb;
			$orderNum = isset($_GET['id']) ? (int)$_GET['id'] : (isset($_POST['id']) ? (int)$_POST['id'] : 0);
			$reason = isset($_POST['cancel_reason']) ? trim($_POST['cancel_reason']) : 'Cancelled by Customer';
			
			if ($orderNum > 0) {
				// Verify order belongs to the logged-in customer
				$mydb->setQuery("SELECT * FROM tblsummary WHERE ORDEREDNUM = {$orderNum} AND CUSTOMERID = " . (int)$_SESSION['CUSID']);
				$order = $mydb->loadSingleResult();
				
				if ($order) {
					// Retrieve customer membership tier
					$mydb->setQuery("SELECT membership_tier FROM tblcustomer WHERE CUSTOMERID = " . (int)$_SESSION['CUSID']);
					$custRow = $mydb->loadSingleResult();
					$tier = ($custRow && !empty($custRow->membership_tier)) ? $custRow->membership_tier : 'Silver';

					// Evaluate status eligibility based on tier
					$isEligible = false;
					if (strcasecmp($tier, 'VIP') === 0 || strcasecmp($tier, 'Platinum') === 0) {
						$isEligible = in_array($order->ORDEREDSTATS, ['Pending', 'Confirmed', 'Shipped']);
					} elseif (strcasecmp($tier, 'Gold') === 0) {
						$isEligible = in_array($order->ORDEREDSTATS, ['Pending', 'Confirmed']);
					} else { // Silver
						$isEligible = ($order->ORDEREDSTATS === 'Pending');
					}

					if ($isEligible) {
						// 1. Update order status to Cancelled
						$mydb->setQuery("UPDATE tblsummary SET ORDEREDSTATS = 'Cancelled', ORDEREDREMARKS = 'Cancelled: " . $mydb->escape_value($reason) . "' WHERE ORDEREDNUM = {$orderNum}");
						if ($mydb->executeQuery()) {
							// 2. Replenish product quantities back in tblproduct
							$mydb->setQuery("SELECT PROID, ORDEREDQTY FROM tblorder WHERE ORDEREDNUM = {$orderNum}");
							$items = $mydb->loadResultList();
							foreach ($items as $item) {
								$mydb->setQuery("UPDATE tblproduct SET PROQTY = PROQTY + {$item->ORDEREDQTY} WHERE PROID = {$item->PROID}");
								$mydb->executeQuery();
							}
							message("Order #HM-{$orderNum} has been cancelled successfully. Reason: " . htmlspecialchars($reason), "success");
						} else {
							message("Failed to cancel order.", "error");
						}
					} else {
						message("Order cannot be cancelled under your " . htmlspecialchars($tier) . " membership restrictions (current status: " . htmlspecialchars($order->ORDEREDSTATS) . ").", "error");
					}
				} else {
					message("Order not found.", "error");
				}
			}
			redirect(web_root . 'index.php?q=profile');
		}

		function doRequestReturn(){
			global $mydb;
			$orderNum = isset($_POST['order_number']) ? (int)$_POST['order_number'] : 0;
			$reasonCode = isset($_POST['return_reason_code']) ? trim($_POST['return_reason_code']) : 'other';
			$details = isset($_POST['reason_details']) ? trim($_POST['reason_details']) : '';
			
			// Format clean category summary
			$categoryLabel = ucfirst(str_replace('_', ' ', $reasonCode));
			$reasonSummary = "[" . $categoryLabel . "] " . $details;
			
			if ($orderNum > 0 && !empty($details)) {
				// Verify order belongs to the customer and is eligible (Confirmed or Delivered or Shipped)
				$mydb->setQuery("SELECT * FROM tblsummary WHERE ORDEREDNUM = {$orderNum} AND CUSTOMERID = " . (int)$_SESSION['CUSID']);
				$order = $mydb->loadSingleResult();
				
				if ($order && ($order->ORDEREDSTATS === 'Confirmed' || $order->ORDEREDSTATS === 'Delivered' || $order->ORDEREDSTATS === 'Shipped')) {
					// Check if a return has already been requested
					$mydb->setQuery("SELECT * FROM returns WHERE order_number = {$orderNum}");
					$exist = $mydb->loadSingleResult();
					
					if (!$exist) {
						// Retrieve customer membership tier
						$mydb->setQuery("SELECT membership_tier FROM tblcustomer WHERE CUSTOMERID = " . (int)$_SESSION['CUSID']);
						$custRow = $mydb->loadSingleResult();
						$tier = ($custRow && !empty($custRow->membership_tier)) ? $custRow->membership_tier : 'Silver';

						// Calculate days elapsed since the order date
						$orderDate = date_create($order->ORDEREDDATE);
						$now = date_create(date("Y-m-d H:i:s"));
						$diff = date_diff($orderDate, $now);
						$daysElapsed = $diff->days;

						// Define return windows
						$maxDays = 7; // Silver
						if (strcasecmp($tier, 'VIP') === 0 || strcasecmp($tier, 'Platinum') === 0) {
							$maxDays = 30;
						} elseif (strcasecmp($tier, 'Gold') === 0) {
							$maxDays = 15;
						}

						if ($daysElapsed > $maxDays) {
							message("This order is outside your " . htmlspecialchars($tier) . " membership return window of {$maxDays} days (ordered {$daysElapsed} days ago).", "error");
							redirect(web_root . 'index.php?q=profile');
							return;
						}

						$refundAmt = $order->PAYMENT;

						if (strcasecmp($tier, 'VIP') === 0 || strcasecmp($tier, 'Platinum') === 0) {
							// VIP Instant Auto-Refund Flow
							$mydb->setQuery("
								INSERT INTO returns (customer_id, order_number, return_status, reason_summary, refund_amount)
								VALUES (" . (int)$_SESSION['CUSID'] . ", {$orderNum}, 'Refunded', '" . $mydb->escape_value($reasonSummary) . "', {$refundAmt})
							");
							if ($mydb->executeQuery()) {
								$returnId = $mydb->insert_id();
								
								// 2. Fetch order items and insert into return_items
								$mydb->setQuery("SELECT PROID, ORDEREDQTY FROM tblorder WHERE ORDEREDNUM = {$orderNum}");
								$items = $mydb->loadResultList();
								foreach ($items as $item) {
									$mydb->setQuery("
										INSERT INTO return_items (return_id, product_id, quantity, return_reason_code)
										VALUES ({$returnId}, {$item->PROID}, {$item->ORDEREDQTY}, '" . $mydb->escape_value($reasonCode) . "')
									");
									$mydb->executeQuery();
								}
								
								// 3. Add record in refunds table as Success
								$transactionRef = 'REF-' . date('Ymd') . '-' . rand(100, 999);
								$mydb->setQuery("
									INSERT INTO `refunds` (`return_id`, `transaction_reference`, `refund_method`, `refund_status`, `processed_at`)
									VALUES ({$returnId}, '{$transactionRef}', 'Instant Auto-Refund', 'Success', CURRENT_TIMESTAMP)
								");
								$mydb->executeQuery();

								// 4. Restock returned items to active inventory
								foreach ($items as $item) {
									$mydb->setQuery("UPDATE `tblproduct` SET `PROQTY` = `PROQTY` + {$item->ORDEREDQTY} WHERE `PROID` = {$item->product_id}");
									$mydb->executeQuery();
								}
								
								// 5. Update summary status and remarks
								$mydb->setQuery("UPDATE tblsummary SET ORDEREDSTATS = 'Refunded', ORDEREDREMARKS = 'Instant Auto-Refund: " . $mydb->escape_value($reasonSummary) . "' WHERE ORDEREDNUM = {$orderNum}");
								$mydb->executeQuery();
								
								message("Instant Auto-Refund processed successfully due to your VIP membership! Order #HM-{$orderNum} has been fully refunded.", "success");
							} else {
								message("Failed to process VIP auto-refund return record.", "error");
							}
						} else {
							// Standard Flow (Silver / Gold)
							$mydb->setQuery("
								INSERT INTO returns (customer_id, order_number, return_status, reason_summary, refund_amount)
								VALUES (" . (int)$_SESSION['CUSID'] . ", {$orderNum}, 'Pending', '" . $mydb->escape_value($reasonSummary) . "', {$refundAmt})
							");
							if ($mydb->executeQuery()) {
								$returnId = $mydb->insert_id();
								
								$mydb->setQuery("SELECT PROID, ORDEREDQTY FROM tblorder WHERE ORDEREDNUM = {$orderNum}");
								$items = $mydb->loadResultList();
								foreach ($items as $item) {
									$mydb->setQuery("
										INSERT INTO return_items (return_id, product_id, quantity, return_reason_code)
										VALUES ({$returnId}, {$item->PROID}, {$item->ORDEREDQTY}, '" . $mydb->escape_value($reasonCode) . "')
									");
									$mydb->executeQuery();
								}
								
								$mydb->setQuery("UPDATE tblsummary SET ORDEREDSTATS = 'Return Pending', ORDEREDREMARKS = 'Return requested: " . $mydb->escape_value($reasonSummary) . "' WHERE ORDEREDNUM = {$orderNum}");
								$mydb->executeQuery();
								
								message("Return/Refund request (" . $categoryLabel . ") for Order #HM-{$orderNum} has been submitted successfully.", "success");
							} else {
								message("Failed to submit return request.", "error");
							}
						}
					} else {
						message("A return request has already been filed for this order.", "error");
					}
				} else {
					message("This order is not eligible for return (current status: " . htmlspecialchars($order->ORDEREDSTATS) . ").", "error");
				}
			} else {
				message("Please provide a valid description for the return request.", "error");
			}
			redirect(web_root . 'index.php?q=profile');
		}

		function doUpgradeMembership(){
			global $mydb;
			if (isset($_POST['membership_tier'])) {
				$tier = trim($_POST['membership_tier']);
				if (in_array($tier, ['Silver', 'Gold', 'VIP'])) {
					$mydb->setQuery("UPDATE tblcustomer SET membership_tier = '" . $mydb->escape_value($tier) . "' WHERE CUSTOMERID = " . (int)$_SESSION['CUSID']);
					if ($mydb->executeQuery()) {
						message("Your membership has been successfully updated to {$tier}!", "success");
					} else {
						message("Failed to update membership tier.", "error");
					}
				} else {
					message("Invalid membership tier selected.", "error");
				}
			}
			redirect(web_root . 'index.php?q=profile');
		}
 
?>
