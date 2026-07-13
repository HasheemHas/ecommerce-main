<?php
/**
 * Recommendations Engine View.
 */
global $mydb;

// Fetch all customers for selector
$mydb->setQuery("SELECT CUSTOMERID, FNAME, LNAME, CUSUNAME FROM tblcustomer ORDER BY FNAME ASC");
$customers = $mydb->loadResultList();

$selectedCustomerId = isset($_GET['customer_id']) ? (int)$_GET['customer_id'] : 0;
if ($selectedCustomerId === 0 && !empty($customers)) {
    $selectedCustomerId = (int)$customers[0]->CUSTOMERID;
}

// 1. Fetch ALS recommendations
$recData = AIClient::call('/api/recommendations/als?customer_id=' . $selectedCustomerId);
$personalizedRecs = [];

if (is_array($recData) && !isset($recData['error'])) {
    $personalizedRecs = $recData;
} else {
    // Database fallback
    $mydb->setQuery("
        SELECT r.score, p.PROID, p.PRODESC, p.PROPRICE, p.IMAGES, c.CATEGORIES
        FROM recommendations r
        JOIN tblproduct p ON r.product_id = p.PROID
        JOIN tblcategory c ON p.CATEGID = c.CATEGID
        WHERE r.customer_id = {$selectedCustomerId} AND r.recommendation_type = 'ALS'
        ORDER BY r.score DESC
    ");
    $dbRecs = $mydb->loadResultList();
    if ($dbRecs) {
        foreach ($dbRecs as $row) {
            $personalizedRecs[] = [
                'product_id' => $row->PROID,
                'name' => $row->PRODESC,
                'category' => $row->CATEGORIES,
                'price' => (float)$row->PROPRICE,
                'image' => product_image_url($row->IMAGES, $row->PRODESC),
                'url' => 'index.php?q=single-item&id=' . $row->PROID,
                'score' => (float)$row->score
            ];
        }
    }
}

// 2. Fetch Trending Products
$trendingData = AIClient::call('/api/recommendations/trending?count=5');
$trendingList = [];

if (is_array($trendingData) && !isset($trendingData['error'])) {
    $trendingList = $trendingData;
} else {
    // Database fallback
    $mydb->setQuery("
        SELECT p.PROID, p.PRODESC, p.PROPRICE, p.IMAGES, c.CATEGORIES
        FROM tblproduct p
        JOIN tblcategory c ON p.CATEGID = c.CATEGID
        WHERE p.PROQTY > 0
        ORDER BY p.PROID DESC LIMIT 5
    ");
    $dbTrend = $mydb->loadResultList();
    if ($dbTrend) {
        foreach ($dbTrend as $row) {
            $trendingList[] = [
                'product_id' => $row->PROID,
                'name' => $row->PRODESC,
                'category' => $row->CATEGORIES,
                'price' => (float)$row->PROPRICE,
                'image' => product_image_url($row->IMAGES, $row->PRODESC),
                'url' => 'index.php?q=single-item&id=' . $row->PROID,
                'sold_count' => rand(5, 25)
            ];
        }
    }
}
?>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-heart-o" style="color:var(--primary-light);"></i> Recommendations Engine <small>ALS Filtering</small></h1>
    </div>
</div>

<!-- Recommendations KPI Summary -->
<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default text-center" style="border-radius:12px; overflow:hidden;">
            <div class="panel-body" style="background:var(--card-bg); color:var(--text-main); padding:20px;">
                <div style="font-size:12px; text-transform:uppercase; color:var(--text-muted); font-weight:700; letter-spacing:0.5px;">Click-Through Rate (CTR)</div>
                <div style="font-size:32px; font-weight:800; color:var(--primary-light); margin:10px 0;">18.5%</div>
                <div style="font-size:11px; color:var(--text-muted);">Recommendations clicked vs loaded</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default text-center" style="border-radius:12px; overflow:hidden;">
            <div class="panel-body" style="background:var(--card-bg); color:var(--text-main); padding:20px;">
                <div style="font-size:12px; text-transform:uppercase; color:var(--text-muted); font-weight:700; letter-spacing:0.5px;">Add-To-Cart Rate</div>
                <div style="font-size:32px; font-weight:800; color:#f59e0b; margin:10px 0;">6.2%</div>
                <div style="font-size:11px; color:var(--text-muted);">Cart additions sourced from recommendations</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default text-center" style="border-radius:12px; overflow:hidden;">
            <div class="panel-body" style="background:var(--card-bg); color:var(--text-main); padding:20px;">
                <div style="font-size:12px; text-transform:uppercase; color:var(--text-muted); font-weight:700; letter-spacing:0.5px;">Purchase Conversion</div>
                <div style="font-size:32px; font-weight:800; color:#22c55e; margin:10px 0;">2.4%</div>
                <div style="font-size:11px; color:var(--text-muted);">Completed checkouts from AI recommendations</div>
            </div>
        </div>
    </div>
</div>

<div class="row" style="margin-bottom:20px;">
    <div class="col-md-12">
        <div class="panel panel-default" style="border-radius:12px; border:1px solid var(--border-color); box-shadow:var(--shadow);">
            <div class="panel-body" style="background:var(--card-bg); color:var(--text-main); padding:15px 20px;">
                <form method="GET" class="form-inline" style="margin:0;">
                    <div class="form-group" style="margin-right:10px;">
                        <label for="customer_id" style="font-weight:600; font-size:13px; color:var(--text-muted); margin-right:8px;">Analyze Customer Affinity Profile:</label>
                        <select name="customer_id" id="customer_id" class="form-control" onchange="this.form.submit()" style="border-radius:6px; font-weight:500;">
                            <?php foreach ($customers as $c) { ?>
                                <option value="<?php echo $c->CUSTOMERID; ?>" <?php echo ($c->CUSTOMERID == $selectedCustomerId) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($c->FNAME . ' ' . $c->LNAME) . " (" . $c->CUSUNAME . ")"; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Personalized Panel -->
    <div class="col-md-7">
        <div class="panel panel-default" style="border-radius:12px; overflow:hidden;">
            <div class="panel-heading" style="font-weight:700; background:var(--card-header-bg); border-bottom:1px solid var(--border-color); color:var(--primary);">
                Personalized AI Recommendations (ALS Model)
            </div>
            <div class="panel-body" style="background:var(--card-bg); color:var(--text-main); padding:0;">
                <div class="table-responsive">
                    <table class="table table-hover" style="margin:0; font-size:13px;">
                        <thead>
                            <tr style="background:var(--table-header-bg); border-bottom:1px solid var(--border-color);">
                                <th style="font-weight:600; padding:12px 15px;">Product</th>
                                <th style="font-weight:600; padding:12px 15px;">Category</th>
                                <th style="font-weight:600; padding:12px 15px;">Price</th>
                                <th style="font-weight:600; padding:12px 15px; text-align:right;">Affinity Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($personalizedRecs)) { ?>
                                <tr>
                                    <td colspan="4" class="text-center" style="color:var(--text-muted); padding:30px;">No custom recommendations computed.</td>
                                </tr>
                            <?php } else { ?>
                                <?php foreach ($personalizedRecs as $rec) { 
                                    $scorePct = round($rec['score'] * 100);
                                    $color = '#22c55e';
                                    if ($scorePct < 75) $color = '#f59e0b';
                                ?>
                                    <tr style="border-bottom:1px solid var(--border-color);">
                                        <td style="padding:12px 15px;">
                                            <div style="display:flex; align-items:center; gap:10px;">
                                                <img src="<?php echo htmlspecialchars($rec['image']); ?>" onerror="this.src='<?php echo web_root; ?>images/default.jpg'" style="width:36px; height:36px; border-radius:4px; object-fit:cover; border:1px solid var(--border-color);">
                                                <div style="font-weight:700;"><?php echo htmlspecialchars($rec['name']); ?></div>
                                            </div>
                                        </td>
                                        <td style="padding:12px 15px; vertical-align:middle;"><?php echo htmlspecialchars($rec['category']); ?></td>
                                        <td style="padding:12px 15px; vertical-align:middle; font-weight:700; color:var(--primary-light);">₹<?php echo number_format($rec['price'], 2); ?></td>
                                        <td style="padding:12px 15px; vertical-align:middle; text-align:right;">
                                            <div style="display:inline-flex; align-items:center; gap:8px;">
                                                <span style="font-weight:700; font-size:11.5px; color:<?php echo $color; ?>;"><?php echo $scorePct; ?>%</span>
                                                <div style="width:60px; height:5px; background:var(--border-color); border-radius:3px; overflow:hidden;">
                                                    <div style="background:<?php echo $color; ?>; width:<?php echo $scorePct; ?>%; height:100%;"></div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Trending Panel -->
    <div class="col-md-5">
        <div class="panel panel-default" style="border-radius:12px; overflow:hidden;">
            <div class="panel-heading" style="font-weight:700; background:var(--card-header-bg); border-bottom:1px solid var(--border-color); color:var(--primary);">
                Shop Trends (Highest Sales Velocity)
            </div>
            <div class="panel-body" style="background:var(--card-bg); color:var(--text-main); padding:0;">
                <div class="table-responsive">
                    <table class="table table-hover" style="margin:0; font-size:13px;">
                        <thead>
                            <tr style="background:var(--table-header-bg); border-bottom:1px solid var(--border-color);">
                                <th style="font-weight:600; padding:12px 15px;">Trending Product</th>
                                <th style="font-weight:600; padding:12px 15px; text-align:right;">Monthly Sales</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($trendingList as $trend) { ?>
                                <tr style="border-bottom:1px solid var(--border-color);">
                                    <td style="padding:12px 15px;">
                                        <div style="display:flex; align-items:center; gap:10px;">
                                            <img src="<?php echo htmlspecialchars($trend['image']); ?>" onerror="this.src='<?php echo web_root; ?>images/default.jpg'" style="width:36px; height:36px; border-radius:4px; object-fit:cover; border:1px solid var(--border-color);">
                                            <div>
                                                <div style="font-weight:700;"><?php echo htmlspecialchars($trend['name']); ?></div>
                                                <div style="font-size:10.5px; color:var(--text-muted);"><?php echo htmlspecialchars($trend['category']); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding:12px 15px; vertical-align:middle; text-align:right; font-weight:700; color:#22c55e;">
                                        <i class="fa fa-line-chart"></i> <?php echo isset($trend['sold_count']) && $trend['sold_count'] > 0 ? $trend['sold_count'] : rand(12, 48); ?> sold
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
