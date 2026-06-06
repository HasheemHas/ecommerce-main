<section class="hmart-premium-cart">
    <style>
        /* Premium CSS here */
        :root {
            --cart-bg: #f8fafc;
            --card-bg: #ffffff;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --accent: #2563eb;
            --accent-hover: #1d4ed8;
            --accent-glow: rgba(37, 99, 235, 0.25);
            --danger: #ef4444;
            --success: #10b981;
        }

        body.dark-mode .hmart-premium-cart {
            --cart-bg: #0f172a;
            --card-bg: #1e293b;
            --text-dark: #f8fafc;
            --text-muted: #94a3b8;
            --border: #334155;
            --accent: #3b82f6;
            --accent-hover: #60a5fa;
            --accent-glow: rgba(59, 130, 246, 0.25);
        }

        .hmart-premium-cart {
            font-family: 'Inter', sans-serif;
            background-color: var(--cart-bg);
            min-height: 80vh;
            padding: 60px 0 100px;
            color: var(--text-dark);
            transition: background-color 0.3s ease;
        }

        .cart-title-wrapper {
            margin-bottom: 40px;
        }

        .cart-title-wrapper h1 {
            font-size: 42px;
            font-weight: 800;
            letter-spacing: -1px;
            margin: 0 0 10px 0;
            color: var(--text-dark);
        }

        .cart-title-wrapper p {
            font-size: 16px;
            color: var(--text-muted);
            margin: 0;
        }

        .premium-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 35px;
            box-shadow: 0 10px 40px -10px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        body.dark-mode .premium-card {
            box-shadow: 0 10px 40px -10px rgba(0,0,0,0.4);
        }

        .cart-item {
            display: grid;
            grid-template-columns: 120px 1fr auto auto;
            gap: 25px;
            align-items: center;
            padding: 30px 0;
            border-bottom: 1px solid var(--border);
        }
        .cart-item:first-child { padding-top: 0; }
        .cart-item:last-child { padding-bottom: 0; border-bottom: none; }

        .item-image {
            width: 120px;
            height: 120px;
            background: var(--cart-bg);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
            overflow: hidden;
        }
        .item-image img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            transition: transform 0.3s ease;
        }
        .cart-item:hover .item-image img {
            transform: scale(1.05);
        }

        .item-info {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .item-category {
            font-size: 12px;
            font-weight: 700;
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .item-name {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
            line-height: 1.3;
        }
        .item-sku {
            font-size: 13px;
            color: var(--text-muted);
        }

        .qty-controls {
            display: flex;
            align-items: center;
            gap: 15px;
            background: var(--cart-bg);
            padding: 8px 12px;
            border-radius: 100px;
            border: 1px solid var(--border);
        }
        .qty-btn {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--card-bg);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 600;
            color: var(--text-dark);
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .qty-btn:hover {
            border-color: var(--accent);
            color: var(--accent);
            transform: translateY(-1px);
        }
        .qty-input {
            width: 30px;
            text-align: center;
            background: transparent;
            border: none;
            font-size: 16px;
            font-weight: 700;
            color: var(--text-dark);
            outline: none;
            -moz-appearance: textfield;
        }
        .qty-input::-webkit-outer-spin-button,
        .qty-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .item-price-col {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 12px;
            min-width: 120px;
        }
        .item-price {
            font-size: 22px;
            font-weight: 800;
            color: var(--text-dark);
        }
        .remove-link {
            font-size: 13px;
            font-weight: 600;
            color: var(--danger);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 8px;
            background: rgba(239, 68, 68, 0.1);
            transition: all 0.2s ease;
        }
        .remove-link:hover {
            background: var(--danger);
            color: #fff;
        }

        /* Summary Sticky Widget */
        .summary-widget {
            position: sticky;
            top: 100px;
        }
        
        .promo-box {
            position: relative;
            margin-bottom: 30px;
        }
        .promo-box label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 12px;
        }
        .promo-input-wrapper {
            position: relative;
            display: flex;
        }
        .promo-input {
            width: 100%;
            padding: 16px 120px 16px 20px;
            border-radius: 16px;
            border: 2px solid var(--border);
            background: var(--cart-bg);
            font-size: 15px;
            font-weight: 500;
            color: var(--text-dark);
            outline: none;
            transition: border-color 0.3s ease;
        }
        .promo-input:focus {
            border-color: var(--accent);
        }
        .promo-btn {
            position: absolute;
            right: 6px;
            top: 6px;
            bottom: 6px;
            background: var(--text-dark);
            color: var(--card-bg);
            border: none;
            border-radius: 10px;
            padding: 0 24px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .promo-btn:hover {
            transform: scale(0.97);
            background: var(--accent);
        }

        .summary-title {
            font-size: 24px;
            font-weight: 800;
            color: var(--text-dark);
            margin: 0 0 25px 0;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
            font-size: 15px;
            color: var(--text-muted);
            font-weight: 500;
        }
        .summary-row.total {
            margin-top: 25px;
            padding-top: 25px;
            border-top: 2px dashed var(--border);
            font-size: 20px;
            color: var(--text-dark);
            font-weight: 800;
        }
        .summary-row.total .total-val {
            font-size: 32px;
            color: var(--accent);
        }

        .checkout-action {
            width: 100%;
            padding: 18px;
            border-radius: 16px;
            background: var(--accent);
            color: #fff !important;
            border: none;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-top: 30px;
            box-shadow: 0 8px 25px var(--accent-glow);
            transition: all 0.3s ease;
            text-decoration: none;
        }
        .checkout-action:hover {
            transform: translateY(-2px);
            background: var(--accent-hover);
            box-shadow: 0 12px 30px var(--accent-glow);
        }

        .empty-cart-state {
            text-align: center;
            padding: 80px 20px;
        }
        .empty-cart-state i {
            font-size: 72px;
            color: var(--border);
            margin-bottom: 25px;
        }
        .empty-cart-state h2 {
            font-size: 28px;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 12px;
        }
        .empty-cart-state p {
            font-size: 16px;
            color: var(--text-muted);
            margin-bottom: 35px;
        }
        .empty-btn {
            display: inline-flex;
            padding: 16px 32px;
            border-radius: 12px;
            background: var(--text-dark);
            color: var(--card-bg) !important;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .empty-btn:hover {
            background: var(--accent);
            transform: translateY(-2px);
        }
    </style>

    <div class="container">
        <?php check_message(); ?>

        <?php
        $countCart = (!empty($_SESSION['gcCart']) && is_array($_SESSION['gcCart'])) ? count($_SESSION['gcCart']) : 0;
        ?>

        <div class="cart-title-wrapper">
            <h1>Your Shopping Cart</h1>
            <p>You have <?php echo $countCart; ?> items in your basket.</p>
        </div>

        <div class="row" id="table">
            <div class="col-lg-8">
                <div class="premium-card">
                    <?php
                    if ($countCart > 0) {
                        for ($i = 0; $i < $countCart; $i++) {
                            $query = "SELECT * FROM `tblpromopro` pr , `tblproduct` p , `tblcategory` c
                                      WHERE pr.`PROID`=p.`PROID` AND  p.`CATEGID` = c.`CATEGID` AND p.`PROID` = '".@$_SESSION['gcCart'][$i]['productid']."'";
                            $mydb->setQuery($query);
                            $cur = $mydb->loadResultList();
                            
                            foreach ($cur as $result) {
                    ?>
                                <div class="cart-item">
                                    <div class="item-image">
                                        <img src="<?php echo str_replace('frontend/', '', web_root). 'admin/products/'.$result->IMAGES; ?>" alt="<?php echo $result->PRODESC; ?>">
                                    </div>

                                    <div class="item-info">
                                        <span class="item-category"><?php echo $result->CATEGORIES; ?></span>
                                        <h3 class="item-name"><?php echo $result->PRODESC; ?></h3>
                                        <span class="item-sku">SKU: H-PRM-<?php echo $result->PROID; ?></span>
                                    </div>

                                    <input type="hidden" id="PROPRICE<?php echo $result->PROID; ?>" value="<?php echo $result->PRODISPRICE; ?>">
                                    <input type="hidden" id="ORIGQTY<?php echo $result->PROID; ?>" value="<?php echo $result->PROQTY; ?>">
                                    <input type="hidden" id="TOT<?php echo $result->PROID; ?>" value="<?php echo $_SESSION['gcCart'][$i]['price']; ?>">

                                    <div class="qty-controls">
                                        <button type="button" class="qty-btn" onclick="decrementCartQty(<?php echo $result->PROID; ?>)">-</button>
                                        <input type="number" id="QTY<?php echo $result->PROID; ?>" data-id="<?php echo $result->PROID; ?>" class="QTY qty-input" value="<?php echo $_SESSION['gcCart'][$i]['qty']; ?>" min="1" readonly>
                                        <button type="button" class="qty-btn" onclick="incrementCartQty(<?php echo $result->PROID; ?>)">+</button>
                                    </div>

                                    <div class="item-price-col">
                                        <span class="item-price">&#8377;<output style="display:inline;" id="Osubtot<?php echo $result->PROID; ?>"><?php echo number_format($_SESSION['gcCart'][$i]['price'], 2); ?></output></span>
                                        <a href="../backend/cart/controller.php?action=delete&id=<?php echo $result->PROID; ?>" class="remove-link">
                                            <i class="fa fa-trash-o"></i> Remove
                                        </a>
                                    </div>
                                </div>
                    <?php
                            }
                        }
                    } else {
                    ?>
                        <div class="empty-cart-state">
                            <i class="fa fa-shopping-bag"></i>
                            <h2>Your cart is empty</h2>
                            <p>Discover our fresh produce and premium items today.</p>
                            <a href="index.php?q=product" class="empty-btn">Shop Collection</a>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="summary-widget">
                    <div class="premium-card" style="margin-bottom:20px;">
                        <div class="promo-box">
                            <label>Gift Card / Promo Code</label>
                            <div class="promo-input-wrapper">
                                <input type="text" id="promo-code-input" class="promo-input" placeholder="Enter code">
                                <button type="button" class="promo-btn" onclick="applyPromoCode()">Apply</button>
                            </div>
                            <div id="promo-message" style="margin-top:10px; font-size:12px; font-weight:700;"></div>
                        </div>

                        <h2 class="summary-title">Order Summary</h2>
                        
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span style="color:var(--text-dark); font-weight:700;">&#8377;<span id="sum">0.00</span></span>
                        </div>
                        
                        <div class="summary-row">
                            <span>Shipping</span>
                            <span style="color:var(--success); font-weight:700;">FREE</span>
                        </div>

                        <div class="summary-row" id="discount-row" style="display:none;">
                            <span>Discount</span>
                            <span style="color:var(--danger); font-weight:700;">-&#8377;<span id="summary-discount">0.00</span></span>
                        </div>

                        <div class="summary-row">
                            <span>Tax (Estimated)</span>
                            <span style="color:var(--text-dark); font-weight:700;">&#8377;<span id="summary-tax">0.00</span></span>
                        </div>

                        <div class="summary-row total">
                            <span>Total</span>
                            <span class="total-val">&#8377;<span id="summary-final-total">0.00</span></span>
                        </div>

                        <a href="index.php?q=orderdetails" class="checkout-action">
                            Proceed to Checkout <i class="fa fa-arrow-right" style="margin-left:4px;"></i>
                        </a>

                        <div style="text-align:center; margin-top:20px;">
                            <a href="index.php?q=product" style="color:var(--text-muted); font-size:13px; font-weight:600; text-decoration:none;">
                                <i class="fa fa-angle-left"></i> Continue Shopping
                            </a>
                        </div>
                    </div>
                    
                    <div style="display:flex; align-items:center; justify-content:center; gap:15px; color:var(--text-muted); font-size:12px; font-weight:600;">
                        <span><i class="fa fa-lock" style="color:var(--success);"></i> Secure Checkout</span>
                        <span><i class="fa fa-truck" style="color:var(--accent);"></i> Free Delivery</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
window.incrementCartQty = function(proid) {
    var input = document.getElementById('QTY' + proid);
    if(input) {
        input.value = parseInt(input.value) + 1;
        input.dispatchEvent(new Event('change', { 'bubbles': true }));
    }
};

window.decrementCartQty = function(proid) {
    var input = document.getElementById('QTY' + proid);
    if(input) {
        var val = parseInt(input.value);
        if(val > 1) {
            input.value = val - 1;
            input.dispatchEvent(new Event('change', { 'bubbles': true }));
        }
    }
};

function updateSummaryTotals() {
    let subtotalText = document.getElementById('sum').innerText || document.getElementById('sum').innerHTML;
    let subtotal = parseFloat(subtotalText.replace(/,/g, '')) || 0;
    let discountText = document.getElementById('summary-discount') ? document.getElementById('summary-discount').innerText : "0";
    let discount = parseFloat(discountText.replace(/,/g, '')) || 0;
    
    let finalTotal = subtotal - discount;
    if(finalTotal < 0) finalTotal = 0;

    let finalTotalEl = document.getElementById('summary-final-total');
    if (finalTotalEl) {
        finalTotalEl.innerText = finalTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }
}

const sumObserver = new MutationObserver(function(mutations) {
    updateSummaryTotals();
});

window.addEventListener('DOMContentLoaded', () => {
    setTimeout(updateSummaryTotals, 100);
    const sumEl = document.getElementById('sum');
    if(sumEl) {
        sumObserver.observe(sumEl, { childList: true, subtree: true, characterData: true });
    }
});
</script>
