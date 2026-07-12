	
<?php
require_once ("../include/initialize.php");
  if (!isset($_SESSION['CUSID'])){
      redirect("index.php");
     }



	
// if (isset($_POST['id'])){

// if ($_POST['actions']=='confirm') {
// 							# code...
// 	$status	= 'Confirmed';	
// 	// $remarks ='Your order has been confirmed. The ordered products will be yours anytime.';
	 
// }elseif ($_POST['actions']=='cancel'){
// 	// $order = New Order();
// 	$status	= 'Cancelled';
// 	// $remarks ='Your order has been cancelled due to lack of communication and incomplete information.';
// }

// $summary = New Summary();
// $summary->ORDEREDSTATS     = $status;
// $summary->update($_POST['id']);


// }

if(isset($_POST['close'])){
	unset($_SESSION['ordernumber']);
	redirect(web_root.'index.php'); 
}

if (isset($_POST['ordernumber'])){
	$_SESSION['ordernumber'] = $_POST['ordernumber'];
}

// unsetting notify msg
$summary = New Summary();
$summary->HVIEW = 1;
$summary->update($_SESSION['ordernumber']);  

// end


$query = "SELECT * FROM `tblsummary` s ,`tblcustomer` c 
		WHERE   s.`CUSTOMERID`=c.`CUSTOMERID` and ORDEREDNUM='".$_SESSION['ordernumber']."'";
		$mydb->setQuery($query);
		$cur = $mydb->loadSingleResult();

// Retrieve customer membership tier
$mydb->setQuery("SELECT membership_tier FROM tblcustomer WHERE CUSTOMERID = " . (int)$_SESSION['CUSID']);
$custRow = $mydb->loadSingleResult();
$tier = ($custRow && !empty($custRow->membership_tier)) ₹ $custRow->membership_tier : 'Silver';

// Evaluate cancellation eligibility based on tier
$canCancel = false;
if (strcasecmp($tier, 'VIP') === 0 || strcasecmp($tier, 'Platinum') === 0) {
	$canCancel = in_array($cur->ORDEREDSTATS, ['Pending', 'Confirmed', 'Shipped']);
} elseif (strcasecmp($tier, 'Gold') === 0) {
	$canCancel = in_array($cur->ORDEREDSTATS, ['Pending', 'Confirmed']);
} else { // Silver
	$canCancel = ($cur->ORDEREDSTATS === 'Pending');
}

// Evaluate return window and eligibility
$eligibleStatusForReturn = in_array($cur->ORDEREDSTATS, ['Confirmed', 'Delivered', 'Shipped']);
$orderDate = date_create($cur->ORDEREDDATE);
$now = date_create(date("Y-m-d H:i:s"));
$diff = date_diff($orderDate, $now);
$daysElapsed = $diff->days;

$maxDays = 7;
if (strcasecmp($tier, 'VIP') === 0 || strcasecmp($tier, 'Platinum') === 0) {
	$maxDays = 30;
} elseif (strcasecmp($tier, 'Gold') === 0) {
	$maxDays = 15;
}
$isWithinReturnWindow = ($daysElapsed <= $maxDays);

// Output HTML debug comment
echo "<!-- DEBUG VALUES: tier=" . htmlspecialchars($tier) . ", status=" . htmlspecialchars($cur->ORDEREDSTATS) . ", canCancel=" . ($canCancel ₹ "true" : "false") . ", eligibleStatusForReturn=" . ($eligibleStatusForReturn ₹ "true" : "false") . ", daysElapsed=" . $daysElapsed . ", maxDays=" . $maxDays . ", isWithinReturnWindow=" . ($isWithinReturnWindow ₹ "true" : "false") . " -->";

// $query = "SELECT * FROM tblusers
// 				WHERE   `USERID`='".$_SESSION['cus_id']."'";
// 		$mydb->setQuery($query);
// 		$row = $mydb->loadSingleResult();
?>
 

<style type="text/css">
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');
    
    .modal-dialog {
        font-family: 'Outfit', sans-serif !important;
    }
    .modal-content {
        border-radius: 20px !important;
        overflow: hidden !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08) !important;
    }
    .modal-header {
        background: #f8fafc !important;
        border-bottom: 1px solid #e2e8f0 !important;
        padding: 20px 25px !important;
        position: relative;
    }
    .modal-body {
        padding: 30px 25px !important;
    }
    .modal-footer {
        background: #f8fafc !important;
        border-top: 1px solid #e2e8f0 !important;
        padding: 15px 25px !important;
    }
    .table thead th {
        background-color: #f1f5f9 !important;
        color: #1e293b !important;
        font-weight: 700 !important;
        font-size: 13px !important;
        border-bottom: 2px solid #cbd5e1 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
    }
    .table tbody td {
        font-size: 14px !important;
        color: #334155 !important;
        vertical-align: middle !important;
        border-bottom: 1px solid #e2e8f0 !important;
    }
    .btn_fixnmix {
        background-color: #3b82f6 !important;
        color: white !important;
        font-weight: 700 !important;
        border: none !important;
        border-radius: 8px !important;
        padding: 8px 20px !important;
        transition: background-color 0.2s ease !important;
    }
    .btn_fixnmix:hover {
        background-color: #2563eb !important;
    }
    .btn-pup {
        background-color: #64748b !important;
        color: white !important;
        font-weight: 700 !important;
        border: none !important;
        border-radius: 8px !important;
        padding: 8px 20px !important;
        transition: background-color 0.2s ease !important;
    }
    .btn-pup:hover {
        background-color: #475569 !important;
    }
    
    /* Dark Mode Overrides */
    body.dark-mode .modal-content {
        background-color: #1e293b !important;
        border-color: #334155 !important;
        color: #f1f5f9 !important;
    }
    body.dark-mode .modal-header {
        background: #0f172a !important;
        border-bottom-color: #334155 !important;
    }
    body.dark-mode .modal-footer {
        background: #0f172a !important;
        border-top-color: #334155 !important;
    }
    body.dark-mode .table thead th {
        background-color: #0f172a !important;
        color: #f1f5f9 !important;
        border-bottom-color: #334155 !important;
    }
    body.dark-mode .table tbody td {
        color: #cbd5e1 !important;
        border-bottom-color: #334155 !important;
    }
    body.dark-mode #printout h2 {
        color: #f1f5f9 !important;
    }
    body.dark-mode #printout p {
        color: #94a3b8 !important;
    }
    body.dark-mode #printout h4, body.dark-mode #printout h5 {
        color: #f1f5f9 !important;
    }
    body.dark-mode #printout p strong, body.dark-mode #printout h4 strong {
        color: #ffffff !important;
    }
    body.dark-mode #printout hr {
        border-top-color: #334155 !important;
    }
    body.dark-mode .col-md-6.pull-right p strong {
        color: #38bdf8 !important;
    }
</style>

<div class="modal-dialog" style="width:65%">
  <div class="modal-content">
	<div class="modal-header">
		<button class="close" id="btnclose" data-dismiss="modal" type="button" style="font-size: 28px; font-weight: 400; opacity: 0.6; position: absolute; right: 25px; top: 20px;">&times;</button>
		 <span id="printout">
 
        <div style="text-align: center; margin: 15px 0 25px 0;">
            <h2 style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 32px; color: #1e293b; margin: 0; letter-spacing: -0.5px;">
                <span style="color: #3b82f6;">H</span>-Mart
            </h2>
            <p style="font-family: 'Outfit', sans-serif; font-size: 11px; color: #64748b; margin: 5px 0 0 0; text-transform: uppercase; letter-spacing: 1px; font-weight: 700;">
                Official Invoice & Order Summary
            </p>
        </div>
		
 	 <div class="modal-body"> 
<?php 
	 $query = "SELECT * FROM `tblsummary` s ,`tblcustomer` c 
				WHERE   s.`CUSTOMERID`=c.`CUSTOMERID` and ORDEREDNUM=".$_SESSION['ordernumber'];
		$mydb->setQuery($query);
		$cur = $mydb->loadSingleResult();

		if($cur->ORDEREDSTATS=='Confirmed'){

		
		if ($cur->PAYMENTMETHOD=="Cash on Pickup") {
			 
		
?>
 	 <h4>Your order has been confirmed and ready for Pick Up</h4><br/>
		<h5>DEAR Ma'am/Sir ,</h5>
		<h5>As you have ordered cash on pick up, please have the exact amount of cash to pay to our staff and bring this billing details.</h5>
		 <hr/>
		 <h4><strong>Pick up Information</strong></h4>
		 <div class="row">
		 	<!-- <div class="col-md-6">
		 		<p> ORDER NUMBER : <?php echo $_SESSION['ordernumber']; ?></p>
		 		<?php 
		 			$query="SELECT sum(ORDEREDQTY) as 'countitem' FROM `tblorder` WHERE `ORDEREDNUM`='".$_SESSION['ordernumber']."'";
		 			$mydb->setQuery($query);
					$res = $mydb->loadResultList();
					?>
		 		<p>Items to be pickup : <?php
		 		foreach ( $res as $row) echo $row->countitem; ?></p> 
		 	</div> -->
		 	<div class="col-md-6">
		 	<p>Name : <?php echo $cur->FNAME . ' '.  $cur->LNAME ;?></p>
		 	<p>Address : <?php echo $cur->CUSHOMENUM . ' ' . $cur->STREETADD . ' ' .$cur->BRGYADD . ' ' . $cur->CITYADD . ' ' .$cur->PROVINCE . ' ' .$cur->COUNTRY; ?></p>
		 		<!-- <p>Contact Number : <?php echo $cur->CONTACTNUMBER;?></p> -->
		 	</div>
		 </div>
<?php 
}elseif ($cur->PAYMENTMETHOD=="Cash on Delivery"){
		 
?>
 	 <h4>Your order has been confirmed and delivered</h4><br/>
 		<h5>DEAR Ma'am/Sir ,</h5>
		<h5>Your order is on its way! As you have ordered via Cash on Delivery, please have the exact amount of cash for our deliverer.	</h5>
		 <hr/>
		 <h4><strong>Delivery Information</strong></h4>
		 <div class="row">
		 	<div class="col-md-6">
		 		<p> ORDER NUMBER : <?php echo $_SESSION['ordernumber']; ?></p>

		 			<?php 
		 			$query="SELECT sum(ORDEREDQTY) as 'countitem' FROM `tblorder` WHERE `ORDEREDNUM`='".$_SESSION['ordernumber']."'";
		 			$mydb->setQuery($query);
					$res = $mydb->loadResultList();
					?>
		 		<p>Items to be delivered : <?php
		 		foreach ( $res as $row) echo $row->countitem; ?></p> 

		 	</div>
		 	<div class="col-md-6">
		 	<p>Name : <?php echo $cur->FNAME . ' '.  $cur->LNAME ;?></p>
		 	<!-- <p>Address : <?php echo $cur->ADDRESS;?></p> -->
		 		<!-- <p>Contact Number : <?php echo $cur->CONTACTNUMBER;?></p> -->
		 	</div>
		 </div>
<?php 
}
}elseif($cur->ORDEREDSTATS=='Cancelled'){

	 echo "Your order has been cancelled due to lack of communication and incomplete information.";

}else{
	echo "<h5>Your order is on process. Please check your profile for notification of confirmation.</h5>";
} 
?>
<hr/>
 <h4><strong>Order Information</strong></h4>
		<table id="table" class="table">
			<thead>
				<tr>
					<!-- <th>PRODUCT</th>₹ -->
					<th>PRODUCT</th>
					<!-- <th>DATE ORDER</th>  -->
					<th>PRICE</th>
					<th>QUANTITY</th>
					<th>TOTAL PRICE</th>
					<th></th> 
				</tr>
				</thead>
				<tbody>
 
				<?php
				 $subtot=0;
				  $query = "SELECT * 
							FROM  `tblproduct` p, tblcategory ct,  `tblcustomer` c,  `tblorder` o,  `tblsummary` s
							WHERE p.`CATEGID` = ct.`CATEGID` 
							AND p.`PROID` = o.`PROID` 
							AND o.`ORDEREDNUM` = s.`ORDEREDNUM` 
							AND s.`CUSTOMERID` = c.`CUSTOMERID` 
							AND o.`ORDEREDNUM`=".$_SESSION['ordernumber'];
				  		$mydb->setQuery($query);
				  		$cur = $mydb->loadResultList(); 
						foreach ($cur as $result) {
						echo '<tr>';  
				  		// echo '<td ><img src="'.web_root.'admin/modules/product/'. $result->IMAGES.'" width="60px" height="60px" title="'.$result->PRODUCTNAME.'"/></td>';
				  		// echo '<td>' . $result->PRODUCTNAME.'</td>';
				  		// echo '<td>'. $result->FIRSTNAME.' '. $result->LASTNAME.'</td>';
				  		echo '<td>'. $result->PRODESC.'</td>';
				  		// echo '<td>'.date_format(date_create($result->ORDEREDDATE),"M/d/Y h:i:s").'</td>';
				  		echo '<td> ₹ '. number_format($result->PROPRICE,2).' </td>';
				  		echo '<td align="center" >'. $result->ORDEREDQTY.'</td>';
				  		?>
				  		 <td> ₹ <output><?php echo  number_format($result->ORDEREDPRICE,2); ?></output></td> 
				  		<?php
				  		
				  		// echo '<td id="status" >'. $result->STATS.'</td>';
				  		// echo '<td><a  href="#"  data-id="'.$result->ORDERID.'"  class="cancel btn btn-danger btn-xs">Cancel</a>
				  		// 		<a href="#"  data-id="'.$result->ORDERID.'"   class="confirm btn btn-primary btn-xs">Confirm</a></td>';
				  		
				  		echo '</tr>';

				  		$subtot +=$result->ORDEREDPRICE;
				 
				}
				?> 
			</tbody>
		<tfoot >
		<?php 
				 $query = "SELECT * FROM `tblsummary` s ,`tblcustomer` c 
				WHERE   s.`CUSTOMERID`=c.`CUSTOMERID` and ORDEREDNUM=".$_SESSION['ordernumber'];
		$mydb->setQuery($query);
		$cur = $mydb->loadSingleResult();

		if ($cur->PAYMENTMETHOD=="Cash on Delivery") {
			# code...
			$price = $cur->DELFEE;
		}else{
			$price = 0.00;
		}


		// $tot =   $cur->PAYMENT  + $price;
		?>

	 </tfoot>
       </table> <hr/>
 		<div class="row">
		  	<div class="col-md-6 pull-left">
		  	 <div>Ordered Date : <?php echo date_format(date_create($cur->ORDEREDDATE),"M/d/Y h:i:s"); ?></div> 
		  		<div>Payment Method : <?php echo $cur->PAYMENTMETHOD; ?></div>

		  	</div>
		  	<div class="col-md-6 pull-right">
		  		<p align="right" style="font-size: 14px; margin-bottom: 6px;">Total Price : <strong>₹ <?php echo number_format($subtot,2);?></strong></p>
		  		<p align="right" style="font-size: 14px; margin-bottom: 6px;">Delivery Fee : <strong>₹ <?php echo number_format($price,2); ?></strong></p>
		  		<p align="right" style="font-size: 16px; color: #1e3a8a; font-weight: 800; margin-top: 10px; border-top: 1px dashed #cbd5e1; padding-top: 8px;">Overall Price : ₹ <?php echo number_format($cur->PAYMENT,2); ?></p>
		  	</div>
		  </div>
		 
			  <?php
		  if($cur->ORDEREDSTATS=="Confirmed"){
		  ?>
		   <hr/> 
		  <div class="row">
		 		 <p>Please print this as a proof of purchased</p><br/>
		  	  <p>We hope you enjoy your purchased products. Have a day!</p>
		  	  <p>Sincerely.</p>
		  	  <h4>Hmart Store</h4>
		  </div>
		  <?php }?>
		  
		  <!-- Request Return Form Box -->
		  <?php if ($eligibleStatusForReturn && $isWithinReturnWindow) { ?>
			  <div id="returnFormBox" style="display: none; background: #fffbeb; border: 1px solid #fef3c7; border-radius: 12px; padding: 15px; margin-top: 20px; text-align: left;">
				  <form action="../backend/customer/controller.php?action=requestreturn" method="POST">
					  <input type="hidden" name="order_number" value="<?php echo $_SESSION['ordernumber']; ?>">
					  
					  <div class="form-group" style="margin-bottom: 12px;">
						  <label style="font-weight: 700; font-size: 13.5px; color: #b45309; display: block; margin-bottom: 6px;">Select Reason for Return / Refund:</label>
						  <select name="return_reason_code" required class="form-control" style="border-radius: 8px; font-family: inherit;">
							  <option value="damaged">Damaged / Broken Product</option>
							  <option value="outdated">Outdated / Expired Product</option>
							  <option value="wrong_item">Incorrect Item / Size Sent</option>
							  <option value="not_as_described">Item Not as Described</option>
							  <option value="other">Other Reason</option>
						  </select>
					  </div>

					  <div class="form-group" style="margin-bottom: 15px;">
						  <label style="font-weight: 700; font-size: 13.5px; color: #b45309; display: block; margin-bottom: 6px;">Additional Details (Describe issue):</label>
						  <textarea name="reason_details" required class="form-control" rows="2" placeholder="e.g. The item received had a broken seal / reached expiry date..." style="border-radius: 8px; font-family: inherit;"></textarea>
					  </div>
					  
					  <div style="display: flex; gap: 8px;">
						  <button type="submit" class="action-btn-solid" style="padding: 6px 15px; background: #d97706 !important; border:none; border-radius: 6px; color:white; font-weight:700;">Submit Request</button>
						  <button type="button" class="action-btn-outline" style="padding: 6px 15px; border: 1px solid #cbd5e1; border-radius:6px; background:white; font-weight:700;" onclick="toggleReturnForm()">Cancel</button>
					  </div>
				  </form>
			  </div>
		  <?php } ?>

		  <!-- Request Cancellation Form Box -->
		  <?php if ($canCancel) { ?>
			  <div id="cancelFormBox" style="display: none; background: #fef2f2; border: 1px solid #fee2e2; border-radius: 12px; padding: 15px; margin-top: 20px; text-align: left;">
				  <form action="../backend/customer/controller.php?action=cancelorder" method="POST">
					  <input type="hidden" name="id" value="<?php echo $_SESSION['ordernumber']; ?>">
					  <div class="form-group" style="margin-bottom: 12px;">
						  <label style="font-weight: 700; font-size: 13.5px; color: #991b1b; display: block; margin-bottom: 6px;">Reason for Cancellation:</label>
						  <textarea name="cancel_reason" required class="form-control" rows="2" placeholder="e.g. Ordered by mistake, found a better price, changed my mind..." style="border-radius: 8px; font-family: inherit;"></textarea>
					  </div>
					  <div style="display: flex; gap: 8px;">
						  <button type="submit" class="action-btn-solid" style="padding: 6px 15px; background: #dc2626 !important; border:none; border-radius: 6px; color:white; font-weight:700;">Confirm Cancellation</button>
						  <button type="button" class="action-btn-outline" style="padding: 6px 15px; border: 1px solid #cbd5e1; border-radius:6px; background:white; font-weight:700;" onclick="toggleCancelForm()">Cancel</button>
					  </div>
				  </form>
			  </div>
		  <?php } ?>
   </div> 
</span>

		<div class="modal-footer" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 15px 25px;">
			<div class="pull-left" style="display: flex; gap: 8px;">
				<?php if ($canCancel) { ?>
					<button type="button" class="btn btn-danger" style="border-radius:8px; font-weight:700; padding:8px 20px; font-family:'Outfit',sans-serif;" onclick="toggleCancelForm()">
						<i class="fa fa-times"></i> Cancel Order
					</button>
				<?php } ?>
				<?php if ($eligibleStatusForReturn) { 
					if ($isWithinReturnWindow) { ?>
						<button type="button" class="btn btn-warning" style="border-radius:8px; font-weight:700; padding:8px 20px; font-family:'Outfit',sans-serif; background-color:#d97706; border:none; color:white;" onclick="toggleReturnForm()">
							<i class="fa fa-reply"></i> Request Return
						</button>
					<?php } else { ?>
						<button type="button" class="btn btn-warning disabled" style="border-radius:8px; font-weight:700; padding:8px 20px; font-family:'Outfit',sans-serif; background-color:#94a3b8; border:none; color:white; cursor:not-allowed;" title="Return window expired (<?php echo $maxDays; ?> days limit)" disabled>
							<i class="fa fa-reply"></i> Return Expired (<?php echo $maxDays; ?>d limit)
						</button>
					<?php }
				} ?>
			</div>
			
			<div id="divButtons" name="divButtons" style="display: flex; gap: 8px; margin-left: auto; align-items:center;">
				<?php if ($cur->ORDEREDSTATS != 'Pending' && $cur->ORDEREDSTATS != 'Cancelled' && $cur->ORDEREDSTATS != 'Return Pending') { ?> 
					<button onclick="tablePrint();" class="btn btn_fixnmix"><span class="glyphicon glyphicon-print"></span> Print</button>     
				<?php } ?>
				<button class="btn btn-pup" id="btnclose" data-dismiss="modal" type="button">Close</button> 
			</div> 
		</div>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
 </div>
 
  <script>
function tablePrint(){ 
    var display_setting="toolbar=no,location=no,directories=no,menubar=no,";  
    display_setting+="scrollbars=no,width=500, height=500, left=100, top=25";  
    var content_innerhtml = document.getElementById("printout").innerHTML;  
    var document_print=window.open("","",display_setting);  
    document_print.document.open();  
    document_print.document.write('<body style="font-family:verdana; font-size:12px;" onLoad="self.print();self.close();" >');  
    document_print.document.write(content_innerhtml);  
    document_print.document.write('</body></html>');  
    document_print.print();  
    document_print.document.close(); 
    return false; 
} 

function toggleReturnForm() {
    var box = document.getElementById('returnFormBox');
    if (box) {
        if (box.style.display === 'none') {
            box.style.display = 'block';
        } else {
            box.style.display = 'none';
        }
    }
}

function toggleCancelForm() {
    var box = document.getElementById('cancelFormBox');
    if (box) {
        if (box.style.display === 'none') {
            box.style.display = 'block';
        } else {
            box.style.display = 'none';
        }
    }
}
</script>