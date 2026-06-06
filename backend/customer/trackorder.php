<?php  
 
     if (!isset($_SESSION['CUSID'])){
      redirect("index.php");
     }


     // if($_SESSION['fixnmixConfiremd']>0){
     //   $query = "update `tblpayment` SET `HVIEW` = true WHERE `CUSTOMERID`='".$_SESSION['CUSID']."' AND STATS in ('Confirmed','Cancelled')  AND HVIEW=0";
     //    mysql_query($query);
     // }
    

    $customer = New Customer();
    $singlecustomer = $customer->single_customer($_SESSION['CUSID']);
  ?>

<style type="text/css">
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');
    
    .track-order-wrapper {
        font-family: 'Outfit', sans-serif;
        color: #1e293b;
    }
    
    /* Stepper Container */
    .stepper-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        margin: 40px 0;
        padding: 0 20px;
    }
    
    /* Progress Line Background */
    .stepper-progress-line {
        position: absolute;
        top: 32px;
        left: 60px;
        right: 60px;
        height: 4px;
        background-color: #cbd5e1;
        z-index: 1;
    }
    
    /* Active Progress line */
    .stepper-progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #3b82f6, #10b981);
        transition: width 0.4s ease;
    }
    
    /* Individual Step */
    .stepper-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 2;
        flex: 1;
    }
    
    /* Step Circle */
    .step-circle {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background-color: white;
        border: 4px solid #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #64748b;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    
    /* State Modifiers */
    .stepper-step.active .step-circle {
        border-color: #3b82f6;
        color: #3b82f6;
        box-shadow: 0 0 0 5px rgba(59, 130, 246, 0.15);
    }
    
    .stepper-step.completed .step-circle {
        border-color: #10b981;
        background-color: #10b981;
        color: white;
        box-shadow: 0 0 0 5px rgba(16, 185, 129, 0.15);
    }
    
    .step-label {
        margin-top: 15px;
        font-size: 15px;
        font-weight: 700;
        color: #64748b;
        transition: color 0.3s ease;
    }
    
    .stepper-step.active .step-label {
        color: #1e293b;
    }
    
    .stepper-step.completed .step-label {
        color: #10b981;
    }
    
    .step-time {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 4px;
    }

    /* Cancelled style */
    .stepper-step.cancelled .step-circle {
        border-color: #ef4444;
        background-color: #ef4444;
        color: white;
        box-shadow: 0 0 0 5px rgba(239, 68, 68, 0.15);
    }
    .stepper-step.cancelled .step-label {
        color: #ef4444;
    }

    .btn-pup {
        background-color: #64748b !important;
        color: white !important;
        font-weight: 700 !important;
        border: none !important;
        border-radius: 8px !important;
        padding: 6px 15px !important;
        transition: background-color 0.2s ease !important;
    }
    .btn-pup:hover {
        background-color: #475569 !important;
    }

    /* Dark Mode Integration */
    body.dark-mode .step-circle {
        background-color: #1e293b;
        border-color: #475569;
    }
    body.dark-mode .stepper-step.active .step-label {
        color: #cbd5e1;
    }
    body.dark-mode .stepper-progress-line {
        background-color: #334155;
    }
    .track-details-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        margin-top: 20px;
    }
    body.dark-mode .track-details-card {
        background: #1e293b;
        border-color: #334155;
        color: #cbd5e1;
    }
    .delivery-updates-box {
        background-color: #f8fafc;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #e2e8f0;
        margin-top: 30px;
    }
    body.dark-mode .delivery-updates-box {
        background-color: #0f172a;
        border-color: #334155;
    }
    .updates-title {
        font-size: 14px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
    }
    body.dark-mode .updates-title {
        color: #f1f5f9;
    }
</style>
    

      <div class="col-sm-3">
 
          <div class="panel">            
            <div class="panel-body">
            <a href="" data-target="#myModal" data-toggle="modal" >
            <?php 
$photo = $singlecustomer->CUSPHOTO;
$actual_file = '../backend/customer/' . $photo;
if (empty($photo) || $photo == 'NONE' || $photo == 'none' || !file_exists($actual_file) || is_dir($actual_file)) {
    $gender = strtolower($singlecustomer->GENDER);
    $img_src = ($gender === "female") ? "https://api.dicebear.com/9.x/avataaars/svg?seed=Mia" : "https://api.dicebear.com/9.x/avataaars/svg?seed=Felix";
} else {
    $img_src = $actual_file;
}
?>
<img title="profile image" class="img-hover" style="width:100%; max-width:200px; height:250px; border-radius:12px; object-fit:cover; border:3px solid #cbd5e1; display:block; margin: 0 auto; box-shadow: 0 4px 15px rgba(0,0,0,0.1);" src="<?php echo $img_src; ?>" onerror="this.src='https://ui-avatars.com/api/?name=User&background=random';">
            </a>
             </div>
          <ul class="list-group">
          
         
            <!-- <li class="list-group-item text-muted">Profile</li> -->
             <li class="list-group-item text-right"><span class="pull-left"><strong>Real name</strong></span> <?php echo $singlecustomer->FNAME .' '.$singlecustomer->LNAME; ?> </li>
            <li class="list-group-item text-right"><span class="pull-left"><strong>Joined</strong></span><?php echo date_format(date_create($singlecustomer->DATEJOIN),'M. d, y');?></li>
            <!-- <li class="list-group-item text-right"><span class="pull-left"><strong>Last seen</strong></span> Yesterday</li> -->
           
            
          </ul> 
                
        </div>
    </div>
         
        <!--/col-3-->
<div class="col-sm-9"> 

<div class="panel track-order-wrapper">  
<div class="panel-header" style="border-bottom: 1px solid #e2e8f0; padding-bottom: 15px; margin-bottom: 20px;">
  <h2 style="font-weight: 800; font-size: 26px; color: inherit; margin: 0;">
    Tracking Order 
    <small><a href="<?php echo web_root; ?>index.php?q=profile" class="btn btn-sm btn-pup" style="margin-left: 15px;"><i class="fa fa-chevron-left"></i> Back</a></small>
  </h2>
</div>          
<div class="panel-body">
    <?php 
        $orderedNum = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($orderedNum <= 0) {
            // Default to the customer's latest order if no specific ID is given
            $mydb->setQuery("SELECT ORDEREDNUM FROM `tblsummary` WHERE `CUSTOMERID`=" . (int)$_SESSION['CUSID'] . " ORDER BY `ORDEREDNUM` DESC LIMIT 1");
            $latest = $mydb->loadSingleResult();
            if ($latest) {
                $orderedNum = (int)$latest->ORDEREDNUM;
            }
        }

        $cur = null;
        if ($orderedNum > 0) {
            $query = "SELECT * FROM `tblsummary` WHERE ORDEREDNUM=" . $orderedNum . " AND `CUSTOMERID`=" . (int)$_SESSION['CUSID'];
            $mydb->setQuery($query);
            $cur = $mydb->loadSingleResult();
        }

        if ($cur) {
            $status = $cur->ORDEREDSTATS;
            $fill_width = "0%";
            $step1_class = "";
            $step2_class = "";
            $step3_class = "";

            if ($status == 'Pending') {
                $fill_width = "0%";
                $step1_class = "active";
            } elseif ($status == 'Confirmed') {
                $fill_width = "50%";
                $step1_class = "completed";
                $step2_class = "active";
            } elseif ($status == 'Delivered') {
                $fill_width = "100%";
                $step1_class = "completed";
                $step2_class = "completed";
                $step3_class = "completed";
            } elseif ($status == 'Cancelled') {
                $fill_width = "100%";
                $step1_class = "cancelled";
                $step2_class = "cancelled";
                $step3_class = "cancelled";
            }
    ?>

    <div class="track-details-card">
        <h4 style="font-weight: 700; margin-bottom: 5px; color: inherit;">Order #<?php echo $orderedNum; ?> Status Details</h4>
        <p style="font-size: 13.5px; color: #64748b; margin-bottom: 30px;">
            Payment Method: <strong><?php echo $cur->PAYMENTMETHOD; ?></strong> &bull; Ordered Date: <strong><?php echo date_format(date_create($cur->ORDEREDDATE),"M. d, Y h:i A"); ?></strong>
        </p>

        <!-- Stepper Progress -->
        <div class="stepper-container">
            <div class="stepper-progress-line">
                <div class="stepper-progress-fill" style="width: <?php echo $fill_width; ?>;"></div>
            </div>

            <!-- Step 1: Processing -->
            <div class="stepper-step <?php echo $step1_class; ?>">
                <div class="step-circle">
                    <i class="fa fa-cog fa-spin"></i>
                </div>
                <div class="step-label">Processing</div>
                <div class="step-time"><?php echo ($status == 'Pending' || $status == 'Confirmed' || $status == 'Delivered') ? 'Order Received' : 'Cancelled'; ?></div>
            </div>

            <!-- Step 2: Shipping -->
            <div class="stepper-step <?php echo $step2_class; ?>">
                <div class="step-circle">
                    <i class="fa fa-plane"></i>
                </div>
                <div class="step-label">Shipping / Confirmed</div>
                <div class="step-time"><?php echo ($status == 'Confirmed' || $status == 'Delivered') ? 'In Transit' : 'Awaiting Confirmation'; ?></div>
            </div>

            <!-- Step 3: Delivered -->
            <div class="stepper-step <?php echo $step3_class; ?>">
                <div class="step-circle">
                    <i class="fa fa-truck"></i>
                </div>
                <div class="step-label">Delivered</div>
                <div class="step-time"><?php echo ($status == 'Delivered') ? 'Arrived & Signed' : 'Out for Delivery'; ?></div>
            </div>
        </div>

        <div class="delivery-updates-box">
            <div class="updates-title">Latest Delivery Updates:</div>
            <div style="font-size: 13.5px; color: inherit; line-height: 1.6; opacity: 0.9;">
                <?php if ($status == 'Pending') { ?>
                    Your order has been received by our store and is currently being packaged with fresh ingredients. Thank you for shopping with H-Mart!
                <?php } elseif ($status == 'Confirmed') { ?>
                    Your order has been confirmed by our staff and is loaded for delivery. Our carrier is in transit to your specified shipping address.
                <?php } elseif ($status == 'Delivered') { ?>
                    Your package has arrived at its destination and was successfully delivered. We hope you enjoy your fresh premium H-Mart products!
                <?php } elseif ($status == 'Cancelled') { ?>
                    Your order was cancelled due to incomplete address details or communication lag. Please contact our support team at support@hmart.com.
                <?php } ?>
            </div>
        </div>
    </div>

    <?php } else { ?>
        <div style="text-align: center; padding: 50px 20px; color: #64748b; background: white; border-radius: 16px; border: 1px solid #e2e8f0; margin-top: 20px;" class="track-details-card">
            <i class="fa fa-shopping-basket" style="font-size: 48px; color: #cbd5e1; margin-bottom: 15px;"></i>
            <h4 style="font-weight: 700; color: inherit; margin-bottom: 8px;">No Orders Found</h4>
            <p style="font-size: 14px; margin-bottom: 20px;">You haven't placed any orders yet. Start shopping to track your orders!</p>
            <a href="index.php?q=product" class="btn btn-primary" style="background-color: #1e3a8a; border: none; font-weight: 700; border-radius: 8px; padding: 8px 20px; color: white;">Browse Catalog</a>
        </div>
    <?php } ?>
</div>
</div> 
</div>




     <!-- Modal photo -->
          <div class="modal fade" id="myModal" tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button class="close" data-dismiss="modal" type=
                  "button">×</button>

                  <h4 class="modal-title" id="myModalLabel">Choose Image.</h4>
                </div>

                <form action="../backend/customer/controller.php?action=photos" enctype="multipart/form-data" method=
                "post">
                  <div class="modal-body">
                    <div class="form-group">
                      <div class="rows">
                        <div class="col-md-12">
                          <div class="rows">
                            <div class="col-md-8">
                              <input name="MAX_FILE_SIZE" type=
                              "hidden" value="1000000"> <input id=
                              "photo" name="photo" type=
                              "file">
                            </div>

                            <div class="col-md-4"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="modal-footer">
                    <button class="btn btn-default" data-dismiss="modal" type=
                    "button">Close</button> <button class="btn btn-primary"
                    name="savephoto" type="submit">Upload Photo</button>
                  </div>
                </form>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->
<!--   
  <script>
$(document).delegate('*[data-toggle="lightbox"]', 'click', function(event) {
    event.preventDefault();
    $(this).ekkoLightbox();
}); 

  </script> -->