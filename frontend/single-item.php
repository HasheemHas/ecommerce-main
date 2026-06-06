<?php 
$PROID = $_GET['id']; 
$query = "SELECT * FROM `tblpromopro` pr , `tblproduct` p , `tblcategory` c
          WHERE pr.`PROID`=p.`PROID` AND p.`CATEGID` = c.`CATEGID` AND p.`PROID`=" . $PROID;
$mydb->setQuery($query);
$cur = $mydb->loadResultList();

foreach ($cur as $result) {
    RecommendationEngine::trackView((int) $result->PROID, (int) $result->CATEGID);
}
foreach ($cur as $result) { 
    $originalPrice = (float) $result->PROPRICE;
    $discountedPrice = (float) $result->PRODISPRICE;
    if ($originalPrice <= $discountedPrice) {
        $originalPrice = round($discountedPrice * 1.25); // 20% discount simulated
    }
    $discountPercent = round((($originalPrice - $discountedPrice) / $originalPrice) * 100);

    // Dynamic Specifications Table based on Category
    $specs = [];
    $categoryName = strtoupper(trim($result->CATEGORIES));
    if (strpos($categoryName, 'SHOE') !== false) {
        $specs = [
            'Brand' => 'SportFit Select',
            'Type' => 'Sports / Running Shoes',
            'Sole Material' => 'Phylon & Rubber',
            'Fastening' => 'Lace-Up',
            'Weight' => '240g (Single Shoe)',
            'Ideal For' => 'Men & Women',
            'Country of Origin' => 'India'
        ];
    } elseif (strpos($categoryName, 'BAG') !== false) {
        $specs = [
            'Brand' => 'HM Backpacks',
            'Type' => 'Waterproof Laptop Backpack',
            'Material' => 'Polyester',
            'Capacity' => '32 Litres',
            'No. of Compartments' => '3 Main, 2 Side Pockets',
            'Laptop Sleeve' => 'Yes (Up to 15.6 inch)',
            'Warranty' => '1 Year Domestic Warranty'
        ];
    } elseif (strpos($categoryName, 'CLOTH') !== false || strpos($categoryName, 'FASH') !== false || strpos($categoryName, 'MEN') !== false || strpos($categoryName, 'WOMEN') !== false) {
        $specs = [
            'Fabric' => '100% Organic combed cotton',
            'Fit' => 'Regular Comfort Fit',
            'Sleeve Type' => 'Short/Long Sleeve',
            'Pattern' => 'Solid / Printed Graphic',
            'Occasion' => 'Casual Wear',
            'Fabric Care' => 'Gentle Machine Wash, Do Not Bleach',
            'Style Code' => 'HM-' . $result->PROID
        ];
    } else {
        $specs = [
            'Brand' => 'H-Mart Assured',
            'Model Number' => 'HM-PRO-' . $result->PROID,
            'Material' => 'Premium Grade Materials',
            'Warranty' => '1 Year Manufacturer Warranty',
            'Return Policy' => '7 Days Replacement/Refund Policy',
            'Package Contents' => '1 Product Unit, User Manual, Warranty Card'
        ];
    }
?>
<style>
    .product-detail-container {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.01);
        margin-bottom: 40px;
    }
    .product-gallery {
        text-align: center;
        border: 1px solid #f1f5f9;
        border-radius: 12px;
        padding: 20px;
        background: #fafafa;
        position: relative;
        overflow: hidden;
        margin-bottom: 20px;
    }
    .product-gallery img {
        max-width: 100%;
        height: auto;
        max-height: 400px;
        object-fit: contain;
        transition: transform 0.3s ease;
    }
    .product-gallery img:hover {
        transform: scale(1.05);
    }
    .brand-assured-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
    }
    .assured-badge {
        background: #2874f0;
        color: white;
        font-size: 11px;
        font-weight: 800;
        padding: 3px 8px;
        border-radius: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .product-title {
        font-size: 26px;
        font-weight: 700;
        color: #0f172a;
        margin-top: 0;
        margin-bottom: 8px;
    }
    .ratings-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 15px;
        font-size: 14px;
    }
    .rating-badge {
        background: #388e3c;
        color: white;
        font-size: 12px;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        gap: 3px;
    }
    .rating-text {
        color: #878787;
        font-weight: 600;
    }
    .price-section {
        margin-bottom: 20px;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 15px;
    }
    .discount-label {
        color: #388e3c;
        font-weight: 700;
        font-size: 16px;
        margin-left: 10px;
    }
    .original-price {
        text-decoration: line-through;
        color: #878787;
        font-size: 16px;
        margin-left: 8px;
    }
    .selling-price {
        font-size: 28px;
        font-weight: 800;
        color: #000;
    }
    .offers-list {
        background: #fdfdfd;
        border: 1px solid #f1f5f9;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 20px;
    }
    .offers-list h5 {
        margin: 0 0 10px 0;
        font-weight: 700;
        color: #0f172a;
    }
    .offer-item {
        display: flex;
        gap: 8px;
        font-size: 13.5px;
        margin-bottom: 8px;
        color: #1e293b;
        align-items: flex-start;
    }
    .offer-item i {
        color: #388e3c;
        margin-top: 3px;
    }
    .specs-title {
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
        margin-top: 25px;
        margin-bottom: 12px;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 8px;
    }
    .specs-table {
        width: 100%;
        margin-bottom: 25px;
        border-collapse: collapse;
    }
    .specs-table td {
        padding: 10px 14px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 14px;
    }
    .specs-table .spec-name {
        color: #878787;
        width: 35%;
        font-weight: 600;
    }
    .specs-table .spec-value {
        color: #212121;
        font-weight: 500;
    }
    .action-buttons-group {
        display: flex;
        gap: 15px;
        margin-top: 20px;
    }
    .btn-cart-action {
        flex: 1;
        padding: 14px;
        font-size: 15px;
        font-weight: 700;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s;
    }
    .btn-buy-now {
        background: #ff9f00;
        color: white !important;
        box-shadow: 0 4px 10px rgba(255, 159, 0, 0.2);
    }
    .btn-buy-now:hover {
        background: #f39700;
    }
    .btn-add-cart {
        background: #fb641b;
        color: white !important;
        box-shadow: 0 4px 10px rgba(251, 100, 27, 0.2);
    }
    .btn-add-cart:hover {
        background: #e85a17;
    }
    
    /* Dark Mode compatibility */
    body.dark-mode .product-detail-container {
        background: #1e293b !important;
        border-color: #334155 !important;
    }
    body.dark-mode .product-gallery {
        background: #0f172a !important;
        border-color: #334155 !important;
    }
    body.dark-mode .product-title {
        color: #f1f5f9 !important;
    }
    body.dark-mode .selling-price {
        color: #fff !important;
    }
    body.dark-mode .offers-list {
        background: #1e293b !important;
        border-color: #334155 !important;
    }
    body.dark-mode .offers-list h5 {
        color: #f1f5f9 !important;
    }
    body.dark-mode .offer-item {
        color: #cbd5e1 !important;
    }
    body.dark-mode .specs-title {
        color: #f1f5f9 !important;
        border-bottom-color: #334155 !important;
    }
    body.dark-mode .specs-table td {
        border-bottom-color: #334155 !important;
    }
    body.dark-mode .specs-table .spec-value {
        color: #cbd5e1 !important;
    }
</style>

<div class="product-detail-container">
    <form method="POST" action="../backend/cart/controller.php?action=add">
        <input type="hidden" name="PROPRICE" value="<?php echo $result->PRODISPRICE; ?>">
        <input type="hidden" id="PROQTY" name="PROQTY" value="<?php echo $result->PROQTY; ?>">
        <input type="hidden" name="PROID" value="<?php echo $result->PROID; ?>">

        <div class="row">
            <!-- Left Side: Image Gallery + CTA Buttons -->
            <div class="col-md-6 col-sm-12">
                <div class="product-gallery">
                    <img src="<?php echo str_replace('frontend/', '', web_root) . 'admin/products/' . $result->IMAGES; ?>" alt="<?php echo htmlspecialchars($result->PRODESC); ?>" id="main-product-image">
                </div>
                
                <div class="action-buttons-group">
                    <button type="submit" name="btnorder" class="btn-cart-action btn-add-cart">
                        <i class="fa fa-shopping-cart"></i> ADD TO CART
                    </button>
                    <button type="submit" name="buynow" class="btn-cart-action btn-buy-now">
                        <i class="fa fa-bolt"></i> BUY NOW
                    </button>
                </div>
            </div>

            <!-- Right Side: Details & Specifications -->
            <div class="col-md-6 col-sm-12">
                <div class="brand-assured-row">
                    <span style="font-weight: 700; color: #878787; text-transform: uppercase; font-size: 13px;"><?php echo htmlspecialchars($result->CATEGORIES); ?></span>
                    <span class="assured-badge"><i class="fa fa-shield"></i> Assured</span>
                </div>
                
                <h1 class="product-title"><?php echo htmlspecialchars($result->PRODESC); ?></h1>
                
                <div class="ratings-row">
                    <span class="rating-badge">4.4 <i class="fa fa-star" style="font-size: 10px;"></i></span>
                    <span class="rating-text">14,320 Ratings &amp; 890 Reviews</span>
                </div>

                <div class="price-section">
                    <span class="selling-price">₹<?php echo number_format($discountedPrice, 2); ?></span>
                    <span class="original-price">₹<?php echo number_format($originalPrice, 2); ?></span>
                    <span class="discount-label"><?php echo $discountPercent; ?>% off</span>
                    <p style="color: #388e3c; font-size: 12px; font-weight: 700; margin: 4px 0 0 0;"><i class="fa fa-tag"></i> Special price ends soon</p>
                </div>

                <!-- Flipkart-style Bank Offers -->
                <div class="offers-list">
                    <h5>Available Offers</h5>
                    <div class="offer-item">
                        <i class="fa fa-tag"></i>
                        <span><strong>Bank Offer</strong> 5% Unlimited Cashback on H-Mart Axis Bank Credit Card. <a href="javascript:void(0);" style="color: #2874f0; text-decoration: none; font-weight: 600;">T&amp;C</a></span>
                    </div>
                    <div class="offer-item">
                        <i class="fa fa-tag"></i>
                        <span><strong>Special Price</strong> Get extra 10% off (price inclusive of cashback/coupon). <a href="javascript:void(0);" style="color: #2874f0; text-decoration: none; font-weight: 600;">T&amp;C</a></span>
                    </div>
                    <div class="offer-item">
                        <i class="fa fa-tag"></i>
                        <span><strong>Combo Offer</strong> Buy 2 items save 5%; Buy 3+ save 10% on selected styles. <a href="javascript:void(0);" style="color: #2874f0; text-decoration: none; font-weight: 600;">T&amp;C</a></span>
                    </div>
                </div>

                <!-- Delivery Options Pin Code checker -->
                <div style="margin-top:20px; border-top:1px solid #f1f5f9; padding-top:15px; padding-bottom: 15px;">
                    <h5 style="font-weight:700; margin:0 0 10px 0; color:#0f172a;">Delivery Options</h5>
                    <div style="display:flex; gap:10px; max-width:300px; margin-bottom:8px;">
                        <input type="text" id="delivery-pincode" class="form-control" placeholder="Enter Delivery Pincode" maxlength="6" style="padding:8px 12px; border-radius:6px; font-size:13px; background: white; border: 1px solid #cbd5e1;">
                        <button type="button" onclick="checkPincodeDelivery()" class="btn btn-primary" style="font-weight:700; font-size:13px; border-radius:6px; background:#0c3c78; border-color:#0c3c78;">Check</button>
                    </div>
                    <p id="pincode-status" style="font-size:13px; color:#388e3c; font-weight:600; margin:0;">
                        <i class="fa fa-truck"></i> Free Delivery available. Delivery by tomorrow, 11 AM.
                    </p>
                </div>

                <div class="specs-title">Product Specifications</div>
                <table class="specs-table">
                    <tbody>
                        <?php foreach ($specs as $name => $value) { ?>
                            <tr>
                                <td class="spec-name"><?php echo htmlspecialchars($name); ?></td>
                                <td class="spec-value"><?php echo htmlspecialchars($value); ?></td>
                            </tr>
                        <?php } ?>
                        <?php if (!empty($result->INGREDIENTS)) { ?>
                            <tr>
                                <td class="spec-name">Details/Ingredients</td>
                                <td class="spec-value"><?php echo htmlspecialchars($result->INGREDIENTS); ?></td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td class="spec-name">Stock Status</td>
                            <td class="spec-value" style="color: <?php echo ($result->PROQTY > 0) ? '#388e3c' : '#ef4444'; ?>; font-weight: 700;">
                                <?php echo ($result->PROQTY > 0) ? 'In Stock (' . $result->PROQTY . ' available)' : 'Out of Stock'; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</div>
<?php } ?>

<!-- Related Products Section -->
<?php 
$query = "SELECT * FROM `tblpromopro` pr , `tblproduct` p , `tblcategory` c
          WHERE pr.`PROID`=p.`PROID` AND p.`CATEGID` = c.`CATEGID` AND c.`CATEGORIES`='" . $result->CATEGORIES . "' AND p.`PROID` != " . $PROID . " LIMIT 4";
$mydb->setQuery($query);
$cur = $mydb->loadResultList(); 
if (!empty($cur)) {
?>
<div class="row" style="margin-top: 30px;">
    <div class="col-lg-12" style="border-bottom: 1px solid #e2e8f0; margin-bottom: 20px; padding-bottom: 10px;">
        <h3 style="font-weight: 800; color: #0f172a; margin: 0;">Related Products</h3>
    </div>
    <?php foreach ($cur as $result) { ?>
        <div class="col-md-3 col-sm-6 col-xs-12" style="margin-bottom: 20px;">
            <div style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 15px; text-align: center; height: 100%;">
                <a href="index.php?q=single-item&id=<?php echo $result->PROID; ?>" style="display: block; margin-bottom: 10px;">
                    <img src="<?php echo str_replace('frontend/', '', web_root) . 'admin/products/' . $result->IMAGES; ?>" alt="<?php echo htmlspecialchars($result->PRODESC); ?>" style="max-height: 120px; max-width: 100%; object-fit: contain;">
                </a>
                <a href="index.php?q=single-item&id=<?php echo $result->PROID; ?>" style="font-weight: 700; color: #0c3c78; text-decoration: none; font-size: 14px; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                    <?php echo htmlspecialchars($result->PRODESC); ?>
                </a>
                <div style="font-weight: 800; color: #000; margin-top: 5px;">₹<?php echo number_format($result->PRODISPRICE, 2); ?></div>
            </div>
        </div>
    <?php } ?>
</div>
<?php } ?>

<script type="text/javascript">
    function checkPincodeDelivery() {
        const pin = document.getElementById('delivery-pincode').value.trim();
        const status = document.getElementById('pincode-status');
        if (/^\d{6}$/.test(pin)) {
            status.style.color = '#388e3c';
            status.innerHTML = `<i class="fa fa-check-circle"></i> Delivery available to pincode ${pin}. Estimated delivery by tomorrow, 11 AM.`;
        } else {
            status.style.color = '#ef4444';
            status.innerHTML = `<i class="fa fa-exclamation-circle"></i> Invalid pincode. Please enter a valid 6-digit delivery pincode.`;
        }
    }
</script>