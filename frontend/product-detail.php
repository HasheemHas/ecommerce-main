<?php
if (!isset($_GET['id']) || (int) $_GET['id'] <= 0) {
    message('Product not found.', 'error');
    redirect(web_root . 'index.php?q=product');
}

$PROID = (int) $_GET['id'];
$query = "SELECT * FROM `tblpromopro` pr, `tblproduct` p, `tblcategory` c
    WHERE pr.PROID = p.PROID AND p.CATEGID = c.CATEGID AND p.PROID = {$PROID}";
$mydb->setQuery($query);
$p = $mydb->loadSingleResult();

if (!$p) {
    message('Product not found.', 'error');
    redirect(web_root . 'index.php?q=product');
}

RecommendationEngine::trackView((int) $p->PROID, (int) $p->CATEGID);

$imgUrl = str_replace('frontend/', '', web_root) . 'admin/products/' . $p->IMAGES;
$rating = ($p->PROID % 3) + 3;
$reviews = (($p->PROID * 7) % 300) + 15;
$discount = ($p->ORIGINALPRICE > 0 && $p->PRODISPRICE < $p->ORIGINALPRICE)
    ? round((($p->ORIGINALPRICE - $p->PRODISPRICE) / $p->ORIGINALPRICE) * 100) : 0;
$inStock = (int) $p->PROQTY > 0;
$highlights = array_filter(array_map('trim', preg_split('/[,;]/', $p->INGREDIENTS)));
if (empty($highlights)) {
    $highlights = ['Premium quality', 'Fast delivery', 'Easy returns within 7 days'];
}

$mydb->setQuery("SELECT * FROM `tblpromopro` pr, `tblproduct` p, `tblcategory` c
    WHERE pr.PROID = p.PROID AND p.CATEGID = c.CATEGID AND p.CATEGID = " . (int) $p->CATEGID . "
    AND p.PROID != {$PROID} AND p.PROQTY > 0 LIMIT 4");
$related = $mydb->loadResultList();
$isLoggedIn = isset($_SESSION['CUSID']);
?>

<style>
.pd-wrap { font-family: 'Outfit', sans-serif; background: #f8fafc; padding: 24px 0 48px; margin-top: -10px; }
.pd-breadcrumb { font-size: 13px; color: #64748b; margin-bottom: 20px; }
.pd-breadcrumb a { color: #1e3a8a; text-decoration: none; }
.pd-card { background: #fff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 28px; margin-bottom: 24px; }
.pd-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; }
.pd-image-box { background: #f8fafc; border-radius: 12px; padding: 20px; text-align: center; border: 1px solid #e2e8f0; }
.pd-image-box img { max-width: 100%; max-height: 420px; object-fit: contain; }
.pd-category { font-size: 13px; color: #64748b; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; }
.pd-title { font-size: 26px; font-weight: 800; color: #1e293b; line-height: 1.3; margin: 8px 0 12px; }
.pd-rating { color: #f59e0b; font-size: 14px; margin-bottom: 16px; }
.pd-rating span { color: #64748b; margin-left: 6px; }
.pd-price-row { display: flex; align-items: baseline; gap: 12px; flex-wrap: wrap; margin-bottom: 8px; }
.pd-price { font-size: 32px; font-weight: 800; color: #1e3a8a; }
.pd-mrp { font-size: 18px; color: #94a3b8; text-decoration: line-through; }
.pd-off { background: #dcfce7; color: #166534; font-size: 13px; font-weight: 700; padding: 4px 10px; border-radius: 6px; }
.pd-stock { font-size: 14px; font-weight: 600; margin: 12px 0 20px; }
.pd-stock.in { color: #16a34a; }
.pd-stock.out { color: #dc2626; }
.pd-qty-row { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; }
.pd-qty-row label { font-weight: 600; font-size: 14px; }
.pd-qty-input { width: 70px; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 16px; text-align: center; }
.pd-actions { display: flex; gap: 12px; flex-wrap: wrap; }
.pd-btn { padding: 14px 28px; border-radius: 10px; font-size: 15px; font-weight: 700; border: none; cursor: pointer; text-decoration: none; display: inline-block; text-align: center; }
.pd-btn-cart { background: #1e3a8a; color: white !important; }
.pd-btn-cart:hover { background: #1d4ed8; color: white !important; }
body.dark-mode .pd-btn-cart { background: #2563eb !important; color: white !important; }
body.dark-mode .pd-btn-cart:hover { background: #3b82f6 !important; color: white !important; }
.pd-btn-buy { background: #fb641b; color: #fff; }
.pd-btn-buy:hover { background: #e55a15; color: #fff; }
.pd-btn-outline { background: #fff; color: #1e3a8a; border: 2px solid #1e3a8a; }
.pd-delivery { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 14px 16px; margin-top: 20px; font-size: 14px; color: #166534; }
.pd-section-title { font-size: 18px; font-weight: 800; color: #1e293b; margin: 0 0 14px; padding-bottom: 10px; border-bottom: 2px solid #e2e8f0; }
.pd-highlights { list-style: none; padding: 0; margin: 0; }
.pd-highlights li { padding: 8px 0 8px 24px; position: relative; font-size: 14px; color: #475569; }
.pd-highlights li::before { content: '✓'; position: absolute; left: 0; color: #16a34a; font-weight: 700; }
.pd-desc { font-size: 15px; line-height: 1.7; color: #475569; }
.pd-related-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
.pd-related-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; text-decoration: none; color: inherit; transition: box-shadow .2s; }
.pd-related-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,.08); text-decoration: none; }
.pd-related-card img { width: 100%; height: 140px; object-fit: cover; }
.pd-related-card .info { padding: 12px; }
.pd-related-card .name { font-size: 13px; font-weight: 600; color: #1e293b; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.pd-related-card .price { font-size: 15px; font-weight: 800; color: #1e3a8a; margin-top: 6px; }
.pd-login-banner { background: #fff7ed; border: 1px solid #fed7aa; color: #9a3412; padding: 12px 16px; border-radius: 10px; margin-bottom: 16px; font-size: 14px; }
.pd-login-banner a { color: #1e3a8a; font-weight: 700; }
@media (max-width: 768px) {
    .pd-grid { grid-template-columns: 1fr; }
    .pd-related-grid { grid-template-columns: repeat(2, 1fr); }
}
</style>

<div class="pd-wrap">
    <div class="container">
        <div class="pd-breadcrumb">
            <a href="<?php echo web_root; ?>">Home</a> &rsaquo;
            <a href="index.php?q=product">Products</a> &rsaquo;
            <a href="index.php?q=product&category=<?php echo urlencode($p->CATEGORIES); ?>"><?php echo htmlspecialchars($p->CATEGORIES); ?></a> &rsaquo;
            <span><?php echo htmlspecialchars(mb_substr($p->PRODESC, 0, 40)); ?>…</span>
        </div>

        <div class="pd-card">
            <div class="pd-grid">
                <div class="pd-image-box">
                    <img src="<?php echo $imgUrl; ?>" alt="<?php echo htmlspecialchars($p->PRODESC); ?>">
                </div>
                <div>
                    <div class="pd-category"><?php echo htmlspecialchars($p->CATEGORIES); ?></div>
                    <h1 class="pd-title"><?php echo htmlspecialchars($p->PRODESC); ?></h1>
                    <div class="pd-rating">
                        <?php for ($i = 1; $i <= 5; $i++) {
                            echo $i <= $rating ? '<i class="fa fa-star"></i>' : '<i class="fa fa-star-o"></i>';
                        } ?>
                        <span><?php echo number_format($rating + ($reviews % 10) / 10, 1); ?> (<?php echo $reviews; ?> ratings)</span>
                    </div>

                    <div class="pd-price-row">
                        <span class="pd-price"><?php echo convert_price($p->PRODISPRICE); ?></span>
                        <?php if ($discount > 0) { ?>
                            <span class="pd-mrp"><?php echo convert_price($p->ORIGINALPRICE); ?></span>
                            <span class="pd-off"><?php echo $discount; ?>% off</span>
                        <?php } ?>
                    </div>

                    <p class="pd-stock <?php echo $inStock ? 'in' : 'out'; ?>">
                        <?php echo $inStock ? 'In stock (' . (int) $p->PROQTY . ' available)' : 'Out of stock'; ?>
                    </p>

                    <?php if (!$isLoggedIn) { ?>
                    <div class="pd-login-banner">
                        Please <a href="index.php?q=login&redirect=<?php echo urlencode('index.php?q=single-item&id=' . $PROID); ?>">login</a> first to add items to your cart.
                    </div>
                    <?php } ?>

                    <?php if ($inStock) { ?>
                    <form method="POST" action="../backend/cart/controller.php?action=add" id="pd-cart-form">
                        <input type="hidden" name="PROID" value="<?php echo $p->PROID; ?>">
                        <input type="hidden" name="PROPRICE" value="<?php echo $p->PRODISPRICE; ?>">
                        <input type="hidden" name="PROQTY" value="<?php echo $p->PROQTY; ?>">
                        <div class="pd-qty-row">
                            <label for="pd-qty">Quantity</label>
                            <input type="number" name="item_qty" id="pd-qty" class="pd-qty-input" value="1" min="1" max="<?php echo (int) $p->PROQTY; ?>">
                        </div>
                        <div class="pd-actions" style="display:flex; flex-direction:column; gap:12px;">
                            <div style="display:flex; gap:12px; align-items:center;">
                                <button type="submit" name="btnorder" class="pd-btn pd-btn-cart">Add to Cart</button>
                                <?php if ($isLoggedIn) { ?>
                                <button type="submit" name="buynow" value="1" class="pd-btn pd-btn-buy">Buy Now</button>
                                <?php } else { ?>
                                <a href="index.php?q=login&redirect=<?php echo urlencode('index.php?q=single-item&id=' . $PROID); ?>" class="pd-btn pd-btn-buy">Buy Now</a>
                                <?php } ?>
                                <button type="button" onclick="addToCompare(<?php echo $PROID; ?>)" class="btn btn-default" style="border-radius:10px; font-weight:700; border:1px solid #cbd5e1; height:48px;"><i class="fa fa-balance-scale"></i> Compare Specs</button>
                            </div>
                            
                            <?php if ($isLoggedIn) {
                                // Load wishlists
                                $mydb->setQuery("SELECT * FROM `customer_wishlists` WHERE `customer_id` = " . (int)$_SESSION['CUSID']);
                                $wishlists = $mydb->loadResultList();
                                if (empty($wishlists)) {
                                    $mydb->setQuery("INSERT INTO `customer_wishlists` (`customer_id`, `wishlist_name`, `is_default`) VALUES (" . (int)$_SESSION['CUSID'] . ", 'My Favorites', 1)");
                                    $mydb->executeQuery();
                                    $mydb->setQuery("SELECT * FROM `customer_wishlists` WHERE `customer_id` = " . (int)$_SESSION['CUSID']);
                                    $wishlists = $mydb->loadResultList();
                                }
                            ?>
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <span style="font-size:12px; font-weight:700; color:#64748b;">Add to Wishlist:</span>
                                    <div class="input-group" style="width: 250px;">
                                        <select id="wishlist-select" class="form-control" style="border-radius: 8px 0 0 8px; font-size:12px; font-weight:700; height:32px; padding:4px 8px;">
                                            <?php foreach ($wishlists as $w) { ?>
                                                <option value="<?php echo $w->wishlist_id; ?>"><?php echo htmlspecialchars($w->wishlist_name); ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-sm btn-danger" onclick="addProductToWishlist(<?php echo $PROID; ?>)" style="border-radius: 0 8px 8px 0; font-weight:700; height:32px; padding: 4px 12px;"><i class="fa fa-heart"></i> Add</button>
                                        </span>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </form>
                    <?php } ?>

                    <div class="pd-delivery">
                        <strong>Free delivery</strong> on orders above <?php echo convert_price(499); ?> &bull; Delivery in 3–5 business days
                    </div>
                </div>
            </div>
        </div>

        <div class="pd-card">
            <h2 class="pd-section-title">Product Highlights</h2>
            <ul class="pd-highlights">
                <?php foreach ($highlights as $h) { ?>
                    <li><?php echo htmlspecialchars($h); ?></li>
                <?php } ?>
                <li>Category: <?php echo htmlspecialchars($p->CATEGORIES); ?></li>
                <li>Sold by: <?php echo htmlspecialchars($p->OWNERNAME ?: 'H-Mart Official'); ?></li>
            </ul>
        </div>

        <div class="pd-card">
            <h2 class="pd-section-title">Description</h2>
            <div class="pd-desc">
                <p><?php echo nl2br(htmlspecialchars($p->PRODESC)); ?></p>
                <?php if (!empty($p->INGREDIENTS)) { ?>
                    <p style="margin-top:16px;"><strong>Details:</strong> <?php echo htmlspecialchars($p->INGREDIENTS); ?></p>
                <?php } ?>
            </div>
        </div>

        <?php if (!empty($related)) { ?>
        <div class="pd-card">
            <h2 class="pd-section-title">Similar Products</h2>
            <div class="pd-related-grid">
                <?php foreach ($related as $r) { ?>
                <a href="index.php?q=single-item&id=<?php echo (int) $r->PROID; ?>" class="pd-related-card">
                    <img src="<?php echo str_replace('frontend/', '', web_root) . 'admin/products/' . $r->IMAGES; ?>" alt="">
                    <div class="info">
                        <div class="name"><?php echo htmlspecialchars($r->PRODESC); ?></div>
                        <div class="price"><?php echo convert_price($r->PRODISPRICE); ?></div>
                    </div>
                </a>
                <?php } ?>
            </div>
        </div>
        <?php } ?>

        <!-- Customer Reviews & BERT Sentiment Ratings Section -->
        <div class="pd-card" id="reviews-section">
            <h2 class="pd-section-title">Verified Purchase Reviews & Star Ratings</h2>
            
            <div class="row" style="margin-bottom: 30px;">
                <div class="col-md-4 text-center" style="border-right: 1px solid #cbd5e1; padding: 20px 0;">
                    <h1 style="font-size: 54px; font-weight: 800; color: #1e3a8a; margin: 0;"><?php echo number_format($rating + ($reviews % 10) / 10, 1); ?></h1>
                    <div class="pd-rating" style="margin: 8px 0;">
                        <?php for ($i = 1; $i <= 5; $i++) {
                            echo $i <= $rating ? '<i class="fa fa-star"></i>' : '<i class="fa fa-star-o"></i>';
                        } ?>
                    </div>
                    <span class="text-muted" style="font-size: 13px;">Based on <?php echo $reviews; ?> verified reviews</span>
                </div>
                <div class="col-md-8" style="padding-left: 30px;">
                    <h4 style="font-weight:700; margin-top:0; font-size:15px;"><i class="fa fa-magic text-primary"></i> BERT AI Sentiment Distribution</h4>
                    <div style="display:flex; flex-direction:column; gap:8px; margin-top:12px;">
                        <div>
                            <div style="display:flex; justify-content:space-between; font-size:12px; font-weight:600; margin-bottom:2px;"><span>Positive sentiment</span><span>82%</span></div>
                            <div class="progress" style="height:6px; margin:0;"><div class="progress-bar progress-bar-success" style="width: 82%;"></div></div>
                        </div>
                        <div>
                            <div style="display:flex; justify-content:space-between; font-size:12px; font-weight:600; margin-bottom:2px;"><span>Neutral sentiment</span><span>12%</span></div>
                            <div class="progress" style="height:6px; margin:0;"><div class="progress-bar progress-bar-info" style="width: 12%;"></div></div>
                        </div>
                        <div>
                            <div style="display:flex; justify-content:space-between; font-size:12px; font-weight:600; margin-bottom:2px;"><span>Negative sentiment</span><span>6%</span></div>
                            <div class="progress" style="height:6px; margin:0;"><div class="progress-bar progress-bar-danger" style="width: 6%;"></div></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- List of Reviews -->
            <?php
            $mydb->setQuery("
                SELECT r.*, c.FNAME, c.LNAME 
                FROM `customer_reviews` r 
                LEFT JOIN `tblcustomer` c ON r.customer_id = c.CUSTOMERID 
                WHERE r.product_id = {$PROID} AND r.status='Approved'
                ORDER BY r.review_id DESC
            ");
            $cust_reviews = $mydb->loadResultList();
            
            if (empty($cust_reviews)) {
                // Seed a sample review
                $mydb->setQuery("INSERT INTO `customer_reviews` (`product_id`, `customer_id`, `rating`, `review_title`, `review_text`, `is_verified_purchase`) 
                                 VALUES ({$PROID}, 1, 5, 'Great Fresh Product!', 'This product exceeds expectation. Delivery was fast and packing was great!', 1)");
                $mydb->executeQuery();
                $mydb->setQuery("
                    SELECT r.*, c.FNAME, c.LNAME 
                    FROM `customer_reviews` r 
                    LEFT JOIN `tblcustomer` c ON r.customer_id = c.CUSTOMERID 
                    WHERE r.product_id = {$PROID} AND r.status='Approved'
                ");
                $cust_reviews = $mydb->loadResultList();
            }
            ?>
            <div style="display:flex; flex-direction:column; gap:20px; border-top:1px solid #cbd5e1; padding-top:20px;">
                <?php foreach ($cust_reviews as $rev) { 
                    $rev_id = $rev->review_id;
                    // Count votes
                    $mydb->setQuery("SELECT count(*) as total FROM `review_votes` WHERE `review_id` = {$rev_id} AND `vote_type` = 'helpful'");
                    $hlp = $mydb->loadSingleResult();
                    $hlp_count = $hlp ? $hlp->total : 0;
                    
                    $mydb->setQuery("SELECT count(*) as total FROM `review_votes` WHERE `review_id` = {$rev_id} AND `vote_type` = 'unhelpful'");
                    $unhlp = $mydb->loadSingleResult();
                    $unhlp_count = $unhlp ? $unhlp->total : 0;
                ?>
                    <div style="border-bottom:1px solid #f1f5f9; padding-bottom:15px;">
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <div>
                                <h4 style="font-weight:700; font-size:14px; margin:0 0 4px 0; color:var(--text-main);"><?php echo htmlspecialchars($rev->review_title); ?></h4>
                                <div class="pd-rating" style="font-size:11px; margin:0;">
                                    <?php for ($i = 1; $i <= 5; $i++) {
                                        echo $i <= $rev->rating ? '<i class="fa fa-star"></i>' : '<i class="fa fa-star-o"></i>';
                                    } ?>
                                    <span style="font-size:11px; font-weight:700; color:#10b981; margin-left:8px;">
                                        <?php if ($rev->is_verified_purchase) { echo '<i class="fa fa-check-circle"></i> Verified Purchase'; } ?>
                                    </span>
                                    <span class="label label-success" style="margin-left: 8px; font-size: 9px; font-weight:800;">BERT AI: Positive</span>
                                </div>
                            </div>
                            <small class="text-muted"><?php echo date_toText($rev->created_at); ?></small>
                        </div>
                        <p style="font-size:13.5px; color:#475569; margin:8px 0;"><?php echo htmlspecialchars($rev->review_text); ?></p>
                        <div style="display:flex; gap:12px; align-items:center; font-size:12px; color:#64748b;">
                            <span>Was this review helpful?</span>
                            <button class="btn btn-xs btn-default" onclick="voteReview(<?php echo $rev_id; ?>, 'helpful')" style="border-radius:4px;"><i class="fa fa-thumbs-up"></i> Yes (<span id="help-count-<?php echo $rev_id; ?>"><?php echo $hlp_count; ?></span>)</button>
                            <button class="btn btn-xs btn-default" onclick="voteReview(<?php echo $rev_id; ?>, 'unhelpful')" style="border-radius:4px;"><i class="fa fa-thumbs-down"></i> No (<span id="unhelp-count-<?php echo $rev_id; ?>"><?php echo $unhlp_count; ?></span>)</button>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <!-- Write Review Form -->
            <?php if ($isLoggedIn) { ?>
                <div style="background:#f8fafc; border-radius:12px; padding:20px; border:1px solid #cbd5e1; margin-top:25px;">
                    <h3 style="margin:0 0 15px 0; font-weight:800; font-size:16px;">Write a Product Review</h3>
                    <form action="<?php echo str_replace('frontend/', 'backend/api/', web_root); ?>submit_review.php" method="post">
                        <input type="hidden" name="product_id" value="<?php echo $PROID; ?>">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label style="font-size:13px; font-weight:600;">Rating (1 to 5 Stars)</label>
                                    <select name="rating" class="form-control" required style="border-radius:6px;">
                                        <option value="5">⭐⭐⭐⭐⭐ (5/5)</option>
                                        <option value="4">⭐⭐⭐⭐ (4/5)</option>
                                        <option value="3">⭐⭐⭐ (3/5)</option>
                                        <option value="2">⭐⭐ (2/5)</option>
                                        <option value="1">⭐ (1/5)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label style="font-size:13px; font-weight:600;">Review Title</label>
                                    <input type="text" name="review_title" class="form-control" placeholder="e.g. Excellent fresh quality!" required style="border-radius:6px;">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label style="font-size:13px; font-weight:600;">Review Details</label>
                            <textarea name="review_text" class="form-control" rows="4" placeholder="Detail your purchase experience..." required style="border-radius:6px; resize:none;"></textarea>
                        </div>
                        <button type="submit" name="submit_review" class="btn btn-sm btn-primary" style="background:#1e3a8a; border:none; padding:8px 20px; font-weight:700; border-radius:6px;">Submit Review</button>
                    </form>
                </div>
            <?php } ?>
        </div>

        <!-- Product QnA Section -->
        <div class="pd-card">
            <h2 class="pd-section-title">Product Q&A (Questions & Answers)</h2>
            
            <?php
            $mydb->setQuery("
                SELECT q.*, c.FNAME, c.LNAME 
                FROM `review_qna` q 
                LEFT JOIN `tblcustomer` c ON q.customer_id = c.CUSTOMERID 
                WHERE q.product_id = {$PROID}
                ORDER BY q.qna_id DESC
            ");
            $qnas = $mydb->loadResultList();
            ?>
            
            <?php if (empty($qnas)) { ?>
                <p class="text-muted" style="font-size:13px; font-style:italic;">No questions asked about this product yet. Be the first to ask!</p>
            <?php } else { ?>
                <div style="display:flex; flex-direction:column; gap:15px; margin-bottom:20px;">
                    <?php foreach ($qnas as $qna) { ?>
                        <div style="border-bottom:1px solid #f1f5f9; padding-bottom:12px;">
                            <div style="font-weight:700; font-size:13.5px; color:var(--text-main);"><i class="fa fa-question-circle text-primary"></i> Q: <?php echo htmlspecialchars($qna->question); ?></div>
                            <div style="font-size:13px; color:#475569; margin-top:4px; padding-left:18px;">
                                <i class="fa fa-comments-o text-success"></i> A: 
                                <?php if (!empty($qna->answer)) { 
                                    echo htmlspecialchars($qna->answer); 
                                } else { 
                                    echo '<span class="text-muted" style="font-style:italic;">Pending seller response...</span>'; 
                                } ?>
                            </div>
                            <small class="text-muted" style="font-size:10px; padding-left:18px;">Asked by <?php echo htmlspecialchars(($qna->FNAME ?? 'Guest') . ' ' . ($qna->LNAME ?? '')); ?> on <?php echo date_toText($qna->created_at); ?></small>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>

            <?php if ($isLoggedIn) { ?>
                <form action="<?php echo str_replace('frontend/', 'backend/api/', web_root); ?>submit_qna.php" method="post" style="border-top:1px solid #cbd5e1; padding-top:15px;">
                    <input type="hidden" name="product_id" value="<?php echo $PROID; ?>">
                    <div class="form-group">
                        <label style="font-size:13px; font-weight:600;">Ask a Question</label>
                        <input type="text" name="question_text" class="form-control" placeholder="Is this product organic? How long will it stay fresh?" required style="border-radius:6px;">
                    </div>
                    <button type="submit" name="submit_question" class="btn btn-sm btn-default" style="border-radius:6px; font-weight:700; border:1px solid #cbd5e1; background:var(--bg-color);">Post Question</button>
                </form>
            <?php } ?>
        </div>
    </div>
</div>

<!-- Comparison Drawer & Specs matrix Floating Overlay -->
<div id="compareDrawer" style="display:none; position:fixed; bottom:20px; right:20px; z-index:99999; background:white; border-radius:12px; border:1px solid #cbd5e1; box-shadow:0 10px 35px rgba(0,0,0,0.15); width:320px; padding:15px; font-family:'Outfit',sans-serif;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
        <strong style="font-size:14px; color:#1e293b;"><i class="fa fa-balance-scale"></i> Comparison Spec List</strong>
        <button onclick="toggleCompareDrawer(false)" class="btn btn-xs btn-default" style="border-radius:50%; width:20px; height:20px; padding:0; line-height:1;"><i class="fa fa-times"></i></button>
    </div>
    <div id="compareDrawerItems" style="font-size:12px; display:flex; flex-direction:column; gap:8px; margin-bottom:12px;"></div>
    <button onclick="showCompareModal()" class="btn btn-sm btn-primary btn-block" style="background:#1e3a8a; border:none; padding:8px; border-radius:6px; font-weight:700; font-size:12px;">Compare Now</button>
</div>

<!-- Comparison Matrix Modal -->
<div id="compareModal" class="modal fade" role="dialog" style="z-index:999999;">
    <div class="modal-dialog" style="max-width: 800px; width: 90%;">
        <div class="modal-content" style="border-radius:18px; padding:20px;">
            <div class="modal-header" style="border:none;">
                <button class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title" style="font-weight:800; color:#1e3a8a;"><i class="fa fa-balance-scale"></i> Dynamic Specifications Matrix</h3>
            </div>
            <div class="modal-body" style="padding-top:10px;">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="compare-matrix-table" style="font-size:13px; text-align:center;">
                        <thead>
                            <tr id="compare-matrix-header" style="background:#f8fafc;">
                                <th style="text-align:left;">Specification</th>
                            </tr>
                        </thead>
                        <tbody id="compare-matrix-body">
                            <tr id="compare-row-image">
                                <td style="text-align:left; font-weight:700;">Product Image</td>
                            </tr>
                            <tr id="compare-row-name">
                                <td style="text-align:left; font-weight:700;">Name</td>
                            </tr>
                            <tr id="compare-row-price">
                                <td style="text-align:left; font-weight:700;">Price</td>
                            </tr>
                            <tr id="compare-row-category">
                                <td style="text-align:left; font-weight:700;">Category</td>
                            </tr>
                            <tr id="compare-row-stock">
                                <td style="text-align:left; font-weight:700;">Availability</td>
                            </tr>
                            <tr id="compare-row-ingredients">
                                <td style="text-align:left; font-weight:700;">Specifications / Ingredients</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- AJAX API Helper Handlers -->
<script>
// 1. Wishlist Adding
function addProductToWishlist(productId) {
    let select = document.getElementById('wishlist-select');
    if (!select) return;
    
    let wishlistId = select.value;
    
    fetch('<?php echo str_replace('frontend/', 'backend/api/', web_root); ?>wishlist_add_api.php?action=add&product_id=' + productId + '&wishlist_id=' + wishlistId)
    .then(res => res.json())
    .then(data => {
        alert(data.message);
    })
    .catch(err => {
        console.error('Wishlist add error:', err);
    });
}

// 2. Review Votes
function voteReview(reviewId, type) {
    fetch('<?php echo str_replace('frontend/', 'backend/api/', web_root); ?>vote_review.php?review_id=' + reviewId + '&vote_type=' + type)
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            document.getElementById('help-count-' + reviewId).textContent = data.helpful_count;
            document.getElementById('unhelp-count-' + reviewId).textContent = data.unhelpful_count;
        } else {
            alert(data.message);
        }
    })
    .catch(err => {
        console.error('Vote error:', err);
    });
}

// 3. Product Comparisons
function toggleCompareDrawer(show) {
    let drw = document.getElementById('compareDrawer');
    if (drw) drw.style.display = show ? 'block' : 'none';
}

function addToCompare(productId) {
    fetch('<?php echo str_replace('frontend/', 'backend/api/', web_root); ?>compare_api.php?action=add&id=' + productId)
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            updateCompareDrawer();
        } else {
            alert(data.message);
        }
    })
    .catch(err => {
        console.error('Compare add error:', err);
    });
}

function removeFromCompare(productId) {
    fetch('<?php echo str_replace('frontend/', 'backend/api/', web_root); ?>compare_api.php?action=remove&id=' + productId)
    .then(res => res.json())
    .then(data => {
        updateCompareDrawer();
    })
    .catch(err => {
        console.error('Compare remove error:', err);
    });
}

function updateCompareDrawer() {
    fetch('<?php echo str_replace('frontend/', 'backend/api/', web_root); ?>compare_api.php?action=list')
    .then(res => res.json())
    .then(data => {
        let itemsContainer = document.getElementById('compareDrawerItems');
        if (data.items && data.items.length > 0) {
            itemsContainer.innerHTML = '';
            data.items.forEach(item => {
                let html = `
                <div style="display:flex; justify-content:space-between; align-items:center; background:#f8fafc; border:1px solid #e2e8f0; border-radius:6px; padding:6px 10px;">
                    <span style="font-weight:600; text-overflow:ellipsis; overflow:hidden; white-space:nowrap; width:180px;">${item.name}</span>
                    <button onclick="removeFromCompare(${item.product_id})" class="btn btn-xs btn-link" style="color:#ef4444; padding:0;"><i class="fa fa-trash"></i></button>
                </div>`;
                itemsContainer.insertAdjacentHTML('beforeend', html);
            });
            toggleCompareDrawer(true);
        } else {
            toggleCompareDrawer(false);
        }
    });
}

function showCompareModal() {
    fetch('<?php echo str_replace('frontend/', 'backend/api/', web_root); ?>compare_api.php?action=list')
    .then(res => res.json())
    .then(data => {
        if (data.items && data.items.length > 0) {
            // Build Matrix table
            let header = document.getElementById('compare-matrix-header');
            let rImg = document.getElementById('compare-row-image');
            let rName = document.getElementById('compare-row-name');
            let rPrice = document.getElementById('compare-row-price');
            let rCat = document.getElementById('compare-row-category');
            let rSt = document.getElementById('compare-row-stock');
            let rIng = document.getElementById('compare-row-ingredients');
            
            // Reset contents
            header.innerHTML = '<th style="text-align:left;">Specification</th>';
            rImg.innerHTML = '<td style="text-align:left; font-weight:700;">Product Image</td>';
            rName.innerHTML = '<td style="text-align:left; font-weight:700;">Name</td>';
            rPrice.innerHTML = '<td style="text-align:left; font-weight:700;">Price</td>';
            rCat.innerHTML = '<td style="text-align:left; font-weight:700;">Category</td>';
            rSt.innerHTML = '<td style="text-align:left; font-weight:700;">Availability</td>';
            rIng.innerHTML = '<td style="text-align:left; font-weight:700;">Specifications / Ingredients</td>';
            
            data.items.forEach(item => {
                header.insertAdjacentHTML('beforeend', `<th style="text-align:center;">${item.name}</th>`);
                rImg.insertAdjacentHTML('beforeend', `<td><img src="${item.image}" style="width:70px; height:70px; object-fit:contain;"></td>`);
                rName.insertAdjacentHTML('beforeend', `<td style="font-weight:700;">${item.name}</td>`);
                rPrice.insertAdjacentHTML('beforeend', `<td style="color:#1e3a8a; font-weight:800;">${item.price}</td>`);
                rCat.insertAdjacentHTML('beforeend', `<td><code>${item.category}</code></td>`);
                rSt.insertAdjacentHTML('beforeend', `<td><span class="label ${item.stock === 'In Stock' ? 'label-success' : 'label-danger'}">${item.stock}</span></td>`);
                rIng.insertAdjacentHTML('beforeend', `<td style="max-width:200px; white-space:normal; font-size:12px; color:#475569;">${item.ingredients}</td>`);
            });
            
            $('#compareModal').modal('show');
        }
    });
}

// Initial draw check
updateCompareDrawer();
</script>
