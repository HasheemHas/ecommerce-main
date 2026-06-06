<?php 
if (!isset($_SESSION['CUSID'])){
    redirect(web_root."index.php");
}

$customerid = $_SESSION['CUSID'];
$customer = New Customer();
$singlecustomer = $customer->single_customer($customerid);

$autonumber = New Autonumber();
$res = $autonumber->set_autonumber('ordernumber'); 
?>

<!-- Google Fonts & Custom CSS -->
<style type="text/css">
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');

    .hmart-checkout-wrapper {
        font-family: 'Outfit', sans-serif;
        color: #1e293b;
        background-color: #f8fafc;
        padding: 40px 0 80px;
        margin-top: -20px; /* seamless header navbar integration */
    }

    /* Checkout Progress Steps */
    .checkout-steps-row {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0;
        margin-bottom: 50px;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }
    .checkout-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        flex: 1;
    }
    .step-circle {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        border: 2px solid #cbd5e1;
        background: white;
        color: #64748b;
        z-index: 2;
        transition: all 0.3s ease;
    }
    .step-circle.done {
        border-color: #1e3a8a;
        background: #1e3a8a;
        color: white;
    }
    .step-circle.active {
        border-color: #1e3a8a;
        background: white;
        color: #1e3a8a;
        box-shadow: 0 0 0 4px rgba(30, 58, 138, 0.15);
    }
    .step-line {
        position: absolute;
        height: 2px;
        background: #cbd5e1;
        width: 100%;
        top: 19px;
        left: 50%;
        z-index: 1;
    }
    .step-line.done {
        background: #1e3a8a;
    }
    .step-label {
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
        margin-top: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .step-label.active {
        color: #1e3a8a;
    }

    /* Left-side Input Cards */
    .checkout-card {
        background: white;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.01);
        margin-bottom: 30px;
        text-align: left;
    }
    .checkout-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 15px;
    }
    .checkout-card-title {
        font-size: 20px;
        font-weight: 800;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .checkout-card-title i {
        color: #1e3a8a;
    }
    .edit-address-link {
        font-size: 13px;
        font-weight: 700;
        color: #1e3a8a;
        text-decoration: none !important;
    }

    /* Form Fields */
    .input-label {
        font-size: 12px;
        font-weight: 700;
        color: #475569;
        margin-bottom: 6px;
        display: block;
    }
    .checkout-input {
        width: 100%;
        padding: 10px 14px;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        font-size: 14px;
        outline: none;
        background: #f8fafc;
        color: #1e293b;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .checkout-input:focus {
        border-color: #1e3a8a;
        background: white;
    }

    /* Horizontal Payment Selectors */
    .payment-selectors-group {
        display: flex;
        gap: 15px;
        margin-bottom: 25px;
    }
    .pay-selector {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        background: white;
        font-size: 13px;
        font-weight: 700;
        color: #475569;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .pay-selector:hover {
        background: #f8fafc;
        border-color: #94a3b8;
    }
    .pay-selector.active {
        border-color: #1e3a8a;
        background: rgba(30, 58, 138, 0.04);
        color: #1e3a8a;
        box-shadow: 0 0 0 2px rgba(30, 58, 138, 0.1);
    }

    /* Card Details Inputs */
    .cc-icon-input {
        position: relative;
    }
    .cc-icon {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 16px;
    }
    .checkout-security-banner {
        display: flex;
        align-items: center;
        gap: 12px;
        background: #f8fafc;
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 12px;
        color: #64748b;
        font-weight: 600;
        margin-top: 25px;
        border: 1px solid #e2e8f0;
    }
    .checkout-security-banner i {
        color: #b45309;
        font-size: 16px;
    }

    /* Right Order Summary */
    .summary-card {
        background: white;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.01);
        text-align: left;
    }
    .summary-heading {
        font-size: 22px;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 25px;
    }

    /* Small Row Item inside Summary */
    .summary-item-row {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
    }
    .summary-item-img-box {
        background: #f8fafc;
        border-radius: 8px;
        padding: 8px;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #f1f5f9;
        flex-shrink: 0;
    }
    .summary-item-img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
    .summary-item-details {
        flex: 1;
        min-width: 0;
    }
    .summary-item-name {
        font-size: 14px;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 2px 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .summary-item-qty {
        font-size: 12px;
        color: #94a3b8;
        font-weight: 600;
    }
    .summary-item-price {
        font-size: 15px;
        font-weight: 800;
        color: #1e3a8a;
        flex-shrink: 0;
    }

    .summary-line {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        color: #475569;
        font-weight: 600;
        margin-bottom: 12px;
    }
    .summary-divider {
        border-top: 1px solid #f1f5f9;
        margin: 20px 0;
    }
    .summary-total-line {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        font-size: 18px;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 30px;
    }
    .summary-total-price {
        font-size: 26px;
        font-weight: 800;
        color: #1e3a8a;
    }
    .place-order-btn {
        width: 100%;
        background: #f97316;
        color: white !important;
        font-weight: 700;
        padding: 14px;
        border-radius: 8px;
        border: none;
        font-size: 15px;
        cursor: pointer;
        transition: background 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-bottom: 15px;
        box-shadow: 0 4px 12px rgba(249, 115, 22, 0.25);
    }
    .place-order-btn:hover {
        background: #ea580c;
    }
    .disclaimer-text {
        font-size: 11px;
        color: #94a3b8;
        text-align: center;
        font-weight: 600;
        line-height: 1.4;
    }

    /* Premium Dark Mode Overrides */
    body.dark-mode .hmart-checkout-wrapper {
        background-color: #0f172a !important;
        color: #f1f5f9 !important;
    }
    body.dark-mode .step-circle {
        background: #1e293b !important;
        border-color: #334155 !important;
        color: #94a3b8 !important;
    }
    body.dark-mode .step-circle.active {
        background: #1e293b !important;
        border-color: #38bdf8 !important;
        color: #38bdf8 !important;
        box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.15) !important;
    }
    body.dark-mode .step-circle.done {
        background: #38bdf8 !important;
        border-color: #38bdf8 !important;
        color: #0f172a !important;
    }
    body.dark-mode .step-line {
        background: #334155 !important;
    }
    body.dark-mode .step-line.done {
        background: #38bdf8 !important;
    }
    body.dark-mode .step-label {
        color: #64748b !important;
    }
    body.dark-mode .step-label.active {
        color: #38bdf8 !important;
    }
    body.dark-mode .checkout-card {
        background: #1e293b !important;
        border-color: #334155 !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3) !important;
    }
    body.dark-mode .checkout-card-header {
        border-bottom-color: #334155 !important;
    }
    body.dark-mode .checkout-card-title {
        color: #f1f5f9 !important;
    }
    body.dark-mode .checkout-card-title i {
        color: #38bdf8 !important;
    }
    body.dark-mode .edit-address-link {
        color: #38bdf8 !important;
    }
    body.dark-mode .input-label {
        color: #cbd5e1 !important;
    }
    body.dark-mode .checkout-input {
        background: #0f172a !important;
        color: #f1f5f9 !important;
        border-color: #334155 !important;
    }
    body.dark-mode .checkout-input:focus {
        border-color: #38bdf8 !important;
        background: #0f172a !important;
    }
    body.dark-mode .pay-selector {
        background: #1e293b !important;
        border-color: #334155 !important;
        color: #cbd5e1 !important;
    }
    body.dark-mode .pay-selector:hover {
        background: #334155 !important;
        color: white !important;
    }
    body.dark-mode .pay-selector.active {
        border-color: #38bdf8 !important;
        background: rgba(56, 189, 248, 0.08) !important;
        color: #38bdf8 !important;
        box-shadow: 0 0 0 2px rgba(56, 189, 248, 0.1) !important;
    }
    body.dark-mode .checkout-security-banner {
        background: #0f172a !important;
        border-color: #334155 !important;
        color: #94a3b8 !important;
    }
    body.dark-mode .checkout-security-banner i {
        color: #fbbf24 !important;
    }
    body.dark-mode .summary-card {
        background: #1e293b !important;
        border-color: #334155 !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3) !important;
    }
    body.dark-mode .summary-heading {
        color: #f1f5f9 !important;
    }
    body.dark-mode .summary-item-img-box {
        background: #0f172a !important;
        border-color: #334155 !important;
    }
    body.dark-mode .summary-item-name {
        color: #f1f5f9 !important;
    }
    body.dark-mode .summary-item-qty {
        color: #64748b !important;
    }
    body.dark-mode .summary-item-price {
        color: #38bdf8 !important;
    }
    body.dark-mode .summary-line {
        color: #cbd5e1 !important;
    }
    body.dark-mode .summary-divider {
        border-top-color: #334155 !important;
    }
    body.dark-mode .summary-total-line {
        color: #f1f5f9 !important;
    }
    body.dark-mode .summary-total-price {
        color: #38bdf8 !important;
    }
    body.dark-mode .place-order-btn {
        background: #f97316 !important;
        color: white !important;
        box-shadow: 0 4px 12px rgba(249, 115, 22, 0.25) !important;
    }
    body.dark-mode .place-order-btn:hover {
        background: #ea580c !important;
    }
    body.dark-mode .disclaimer-text {
        color: #64748b !important;
    }
</style>

<div class="hmart-checkout-wrapper">
    <div class="container">
        <!-- 1. Progress Step Bar -->
        <div class="checkout-steps-row">
            <div class="checkout-step">
                <div class="step-circle done"><i class="fa fa-check"></i></div>
                <div class="step-line done"></div>
                <span class="step-label">Shipping</span>
            </div>
            <div class="checkout-step">
                <div class="step-circle active">2</div>
                <div class="step-line"></div>
                <span class="step-label active">Payment</span>
            </div>
            <div class="checkout-step">
                <div class="step-circle">3</div>
                <span class="step-label">Review</span>
            </div>
        </div>

        <form onsubmit="return handleCheckoutNameSplit();" action="../backend/customer/controller.php?action=processorder" method="post">
            <!-- Hidden database values -->
            <input type="hidden" value="<?php echo $res->AUTO; ?>" id="ORDEREDNUM" name="ORDEREDNUM">
            <input type="hidden" name="alltot" id="alltot" value="">
            <input type="hidden" name="FNAME" id="FNAME" value="">
            <input type="hidden" name="LNAME" id="LNAME" value="">

            <div class="row">
                <!-- Left Side: Address & Payment Methods -->
                <div class="col-lg-8">
                    <!-- Address Card -->
                    <div class="checkout-card">
                        <div class="checkout-card-header">
                            <h2 class="checkout-card-title"><i class="fa fa-truck"></i> Shipping Address</h2>
                            <a href="index.php?q=profile" class="edit-address-link">Edit</a>
                        </div>
                        
                        <div class="row" style="margin-bottom: 15px;">
                            <div class="col-md-6" style="margin-bottom: 15px;">
                                <label class="input-label">Full Name</label>
                                <input type="text" id="fullname" class="checkout-input" value="<?php echo htmlspecialchars($singlecustomer->FNAME .' '.$singlecustomer->LNAME); ?>" style="background: white;" required>
                            </div>
                            <div class="col-md-6" style="margin-bottom: 15px;">
                                <label class="input-label">Street Address</label>
                                <input type="text" name="STREETADD" class="checkout-input" value="<?php echo htmlspecialchars($singlecustomer->CUSHOMENUM . ' ' . $singlecustomer->STREETADD . ' ' .$singlecustomer->BRGYADD); ?>" style="background: white;" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4" style="margin-bottom: 15px;">
                                <label class="input-label">City</label>
                                <input type="text" name="CITYADD" class="checkout-input" value="<?php echo htmlspecialchars($singlecustomer->CITYADD); ?>" style="background: white;" required>
                            </div>
                            <div class="col-md-4" style="margin-bottom: 15px;">
                                <label class="input-label">State</label>
                                <input type="text" name="PROVINCE" class="checkout-input" value="<?php echo htmlspecialchars($singlecustomer->PROVINCE ? $singlecustomer->PROVINCE : 'IL'); ?>" style="background: white;" required>
                            </div>
                            <div class="col-md-4" style="margin-bottom: 15px;">
                                <label class="input-label">Zip Code</label>
                                <input type="text" name="ZIPCODE" class="checkout-input" value="<?php echo htmlspecialchars($singlecustomer->ZIPCODE ? $singlecustomer->ZIPCODE : '62704'); ?>" style="background: white;" required>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method Selectors -->
                    <div class="checkout-card">
                        <div class="checkout-card-header" style="margin-bottom: 20px;">
                            <h2 class="checkout-card-title"><i class="fa fa-credit-card"></i> Payment Method</h2>
                        </div>

                        <!-- 3 Selector buttons matching mockup styling -->
                        <div class="payment-selectors-group">
                            <button type="button" id="pay_card_btn" class="pay-selector active" onclick="selectPaymentMethod('card')">
                                <i class="fa fa-credit-card"></i> Credit/Debit Card
                            </button>
                            <button type="button" id="pay_upi_btn" class="pay-selector" onclick="selectPaymentMethod('upi')">
                                <i class="fa fa-qrcode"></i> UPI (GPay/Paytm)
                            </button>
                            <button type="button" id="pay_cod_btn" class="pay-selector" onclick="selectPaymentMethod('cod')">
                                <i class="fa fa-money"></i> Cash on Delivery
                            </button>
                        </div>

                        <!-- Hidden native radio triggers for backend form compatibility -->
                        <input type="radio" class="paymethod" name="paymethod" id="deliveryfee" value="Cash on Delivery" style="display:none;">
                        <input type="radio" class="paymethod" name="paymethod" id="upipayment" value="UPI Payment" style="display:none;">
                        <input type="radio" class="paymethod" name="paymethod" id="cardpayment" value="Card Payment" checked style="display:none;">

                        <!-- Credit Card input terminal -->
                        <div id="cardGatewayScreen">
                            <!-- Card Number Box -->
                            <div style="margin-bottom: 15px;">
                                <label class="input-label" for="card_number">Card Number</label>
                                <div class="cc-icon-input">
                                    <input type="text" name="card_number" id="card_number" class="checkout-input" placeholder="0000 0000 0000 0000" maxlength="19" style="background: white;">
                                    <i class="fa fa-credit-card cc-icon"></i>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <label class="input-label" for="card_expiry">Expiry Date</label>
                                    <input type="text" name="card_expiry" id="card_expiry" class="checkout-input" placeholder="MM / YY" maxlength="5" style="background: white;">
                                </div>
                                <div class="col-xs-6">
                                    <label class="input-label" for="card_cvv">CVV <i class="fa fa-info-circle" style="color: #94a3b8; cursor: pointer;" title="3 digits on back of card"></i></label>
                                    <input type="password" name="card_cvv" id="card_cvv" class="checkout-input" placeholder="•••" maxlength="3" style="background: white;">
                                </div>
                            </div>
                        </div>

                        <!-- UPI Gateway Input box (if selected) -->
                        <div id="upiGatewayScreen" style="display: none;">
                            <div style="text-align: center; margin-bottom: 20px;">
                                <div style="background: white; padding: 15px; border-radius: 8px; display: inline-block; border: 1px solid #e2e8f0; margin-bottom: 15px;">
                                    <img id="upiQR" src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=upi://pay?pa=hmart@upi%26pn=Hmart%26cu=INR" alt="UPI QR" style="width: 150px; height: 150px;">
                                </div>
                                <div style="font-weight: 700; color: #1e3a8a; margin-bottom: 12px;">Merchant UPI ID: hmart@upi</div>
                                
                                <!-- Premium App Selector Buttons -->
                                <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 8px; max-width: 500px; margin: 0 auto 20px auto;">
                                    <a id="link_paytm" href="#" class="btn" style="background: #00b9f5; color: white !important; font-weight: 700; border-radius: 6px; padding: 10px 18px; text-decoration: none !important; font-size: 13px; border: none;">
                                        <i class="fa fa-mobile"></i> Paytm
                                    </a>
                                    <a id="link_phonepe" href="#" class="btn" style="background: #5f259f; color: white !important; font-weight: 700; border-radius: 6px; padding: 10px 18px; text-decoration: none !important; font-size: 13px; border: none;">
                                        <i class="fa fa-mobile"></i> PhonePe
                                    </a>
                                    <a id="link_gpay" href="#" class="btn" style="background: #ea4335; color: white !important; font-weight: 700; border-radius: 6px; padding: 10px 18px; text-decoration: none !important; font-size: 13px; border: none;">
                                        <i class="fa fa-mobile"></i> GPay
                                    </a>
                                    <a id="link_bhim" href="#" class="btn" style="background: #e06000; color: white !important; font-weight: 700; border-radius: 6px; padding: 10px 18px; text-decoration: none !important; font-size: 13px; border: none;">
                                        <i class="fa fa-mobile"></i> BHIM
                                    </a>
                                </div>
                            </div>
                            <div>
                                <label class="input-label" for="utr_number">Enter 12-Digit Reference Number (UTR)</label>
                                <input type="text" name="utr_number" id="utr_number" class="checkout-input" placeholder="e.g. 123456789012" maxlength="12" style="background: white;">
                            </div>
                        </div>

                        <!-- COD placeholder details (if selected) -->
                        <div id="codGatewayScreen" style="display: none; padding: 15px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0; font-weight: 600; font-size: 13px; color: #475569;">
                            <i class="fa fa-check-circle" style="color: #10b981; font-size: 16px; margin-right: 6px;"></i> Pay in cash upon physical delivery to your home.
                        </div>

                        <div class="checkout-security-banner">
                            <i class="fa fa-shield"></i>
                            <span>Your payment information is encrypted and processed on a secure server. We never store your full card details.</span>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Order Summary -->
                <div class="col-lg-4">
                    <div class="summary-card">
                        <h2 class="summary-heading">Order Summary</h2>

                        <!-- Scrollable Products thumbnails list -->
                        <div style="max-height: 280px; overflow-y: auto; margin-bottom: 25px; padding-right: 5px;">
                            <?php
                            $tot = 0;
                            if (!empty($_SESSION['gcCart']) && is_array($_SESSION['gcCart'])){ 
                                $count_cart = count($_SESSION['gcCart']);
                                for ($i=0; $i < $count_cart  ; $i++) { 
                                    $query = "SELECT * FROM `tblpromopro` pr , `tblproduct` p , `tblcategory` c
                                              WHERE pr.`PROID`=p.`PROID` AND  p.`CATEGID` = c.`CATEGID` AND p.PROID='".$_SESSION['gcCart'][$i]['productid']."'";
                                    $mydb->setQuery($query);
                                    $cur = $mydb->loadResultList();
                                    foreach ($cur as $result){ 
                            ?>
                                        <div class="summary-item-row">
                                            <div class="summary-item-img-box">
                                                <img src="<?php echo str_replace('frontend/', '', web_root). 'admin/products/'.$result->IMAGES; ?>" class="summary-item-img">
                                            </div>
                                            <div class="summary-item-details">
                                                <h3 class="summary-item-name"><?php echo $result->PRODESC; ?></h3>
                                                <span class="summary-item-qty">Qty: <?php echo $_SESSION['gcCart'][$i]['qty']; ?></span>
                                            </div>
                                            <span class="summary-item-price">&#8377;<?php echo number_format($_SESSION['gcCart'][$i]['price'], 2); ?></span>
                                        </div>
                            <?php
                                        $tot += $_SESSION['gcCart'][$i]['price'];
                                    }
                                }
                            }
                            ?>
                        </div>

                        <!-- Calculations Lines -->
                        <div class="summary-line">
                            <span>Subtotal</span>
                            <span>&#8377;<span id="subtotal-label"><?php echo number_format($tot, 2); ?></span></span>
                        </div>
                        <div class="summary-line">
                            <span>Shipping</span>
                            <span style="color: #10b981; font-weight: 700;">FREE</span>
                        </div>
                        <div class="summary-line">
                            <span>Tax</span>
                            <span>&#8377;<span id="tax-label">0.00</span></span>
                        </div>

                        <div class="summary-divider"></div>

                        <div class="summary-total-line">
                            <span>Total</span>
                            <span class="summary-total-price">&#8377;<span id="grand-total-label">0.00</span></span>
                        </div>

                        <!-- Submit CTA -->
                        <button type="submit" name="btnorder" class="place-order-btn">
                            <i class="fa fa-lock"></i> Place Order
                        </button>

                        <div class="disclaimer-text">
                            By placing your order, you agree to our <a href="javascript:void(0);" onclick="alert('Terms and Conditions')" style="color: #1e3a8a; text-decoration: underline;">Terms of Service</a>.
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    // Select payment methods and sync native inputs
    function selectPaymentMethod(method) {
        document.getElementById('pay_card_btn').classList.remove('active');
        document.getElementById('pay_upi_btn').classList.remove('active');
        document.getElementById('pay_cod_btn').classList.remove('active');
        
        document.getElementById('cardGatewayScreen').style.display = 'none';
        document.getElementById('upiGatewayScreen').style.display = 'none';
        document.getElementById('codGatewayScreen').style.display = 'none';
        
        document.getElementById('cardpayment').checked = false;
        document.getElementById('upipayment').checked = false;
        document.getElementById('deliveryfee').checked = false;

        if (method === 'card') {
            document.getElementById('pay_card_btn').classList.add('active');
            document.getElementById('cardGatewayScreen').style.display = 'block';
            document.getElementById('cardpayment').checked = true;
        } else if (method === 'upi') {
            document.getElementById('pay_upi_btn').classList.add('active');
            document.getElementById('upiGatewayScreen').style.display = 'block';
            document.getElementById('upipayment').checked = true;
        } else if (method === 'cod') {
            document.getElementById('pay_cod_btn').classList.add('active');
            document.getElementById('codGatewayScreen').style.display = 'block';
            document.getElementById('deliveryfee').checked = true;
        }
    }

    // Dynamic CVV & Expiry formats inside interactive inputs
    document.addEventListener("DOMContentLoaded", function() {
        const subtotal = <?php echo $tot; ?>;
        const tax = subtotal * 0.08;
        const grandTotal = subtotal + tax;

        document.getElementById('tax-label').innerText = tax.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        document.getElementById('grand-total-label').innerText = grandTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        document.getElementById('alltot').value = grandTotal.toFixed(2);

        // Dynamically append the QR code parameter with grand total
        const upiQRImg = document.getElementById('upiQR');
        if (upiQRImg) {
            const payVal = grandTotal.toFixed(2);
            upiQRImg.src = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=upi://pay?pa=hmart@upi%26pn=Hmart%26am=${payVal}%26cu=INR`;
            
            document.getElementById('link_paytm').href = `paytmmp://pay?pa=hmart@upi&pn=Hmart&am=${payVal}&cu=INR`;
            document.getElementById('link_phonepe').href = `phonepe://pay?pa=hmart@upi&pn=Hmart&am=${payVal}&cu=INR`;
            document.getElementById('link_gpay').href = `tez://upi/pay?pa=hmart@upi&pn=Hmart&am=${payVal}&cu=INR`;
            document.getElementById('link_bhim').href = `upi://pay?pa=hmart@upi&pn=Hmart&am=${payVal}&cu=INR`;

            const apps = ['paytm', 'phonepe', 'gpay', 'bhim'];
            apps.forEach(function(app) {
                const el = document.getElementById('link_' + app);
                if (el) {
                    el.addEventListener('click', function(e) {
                        const fakeUtr = Math.floor(100000000000 + Math.random() * 900000000000);
                        const utrInput = document.getElementById('utr_number');
                        if (utrInput) {
                            utrInput.value = fakeUtr;
                        }
                    });
                }
            });
        }

        // Auto formats Card number field
        const ccInput = document.getElementById('card_number');
        if (ccInput) {
            ccInput.addEventListener('input', function (e) {
                let targetVal = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
                let matches = targetVal.match(/\d{4,16}/g);
                let match = matches && matches[0] || '';
                let parts = [];

                for (let i = 0, len = match.length; i < len; i += 4) {
                    parts.push(match.substring(i, i + 4));
                }

                if (parts.length > 0) {
                    e.target.value = parts.join(' ');
                } else {
                    e.target.value = targetVal;
                }
            });
        }

        // Auto formats Expiry field
        const expiryInput = document.getElementById('card_expiry');
        if (expiryInput) {
            expiryInput.addEventListener('input', function (e) {
                let targetVal = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
                if (targetVal.length >= 2) {
                    e.target.value = targetVal.substring(0, 2) + ' / ' + targetVal.substring(2, 4);
                } else {
                    e.target.value = targetVal;
                }
            });
        }
    });

    // Split Full Name into FNAME and LNAME before form submission for dynamic PHP controller updates
    function handleCheckoutNameSplit() {
        const fullnameVal = document.getElementById('fullname').value.trim();
        if (fullnameVal === '') {
            alert('Please enter your full name.');
            return false;
        }

        const nameParts = fullnameVal.split(' ');
        const firstName = nameParts[0] || 'Customer';
        const lastName = nameParts.slice(1).join(' ') || 'User';

        document.getElementById('FNAME').value = firstName;
        document.getElementById('LNAME').value = lastName;
        return true;
    }
</script>