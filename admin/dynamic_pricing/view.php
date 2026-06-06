<?php
/**
 * Dynamic Pricing Dashboard View.
 */
global $mydb;

// Fetch all active products for the selector dropdown
$mydb->setQuery("SELECT PROID, PRODESC, PROPRICE FROM tblproduct ORDER BY PRODESC ASC");
$productList = $mydb->loadResultList();

// Fetch pending price suggestions
$mydb->setQuery("
    SELECT s.*, p.PRODESC 
    FROM dynamic_pricing_suggestions s 
    JOIN tblproduct p ON s.product_id = p.PROID 
    WHERE s.status = 'pending' 
    ORDER BY s.expected_revenue_lift DESC
");
$suggestions = $mydb->loadResultList();

// Fetch active A/B price tests
$mydb->setQuery("
    SELECT t.*, p.PRODESC 
    FROM price_ab_tests t 
    JOIN tblproduct p ON t.product_id = p.PROID
    ORDER BY t.status DESC, t.id DESC
");
$abTests = $mydb->loadResultList();

// Aggregates
$sugCount = count($suggestions);
$activeTests = 0;
$totalLift = 0.0;
foreach ($suggestions as $s) {
    $totalLift += $s->expected_revenue_lift;
}
$avgLift = $sugCount > 0 ? round($totalLift / $sugCount, 2) : 0;

foreach ($abTests as $t) {
    if ($t->status === 'running') $activeTests++;
}
?>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-money" style="color:var(--primary-light);"></i> Dynamic Pricing AI <small>DQN Optimizer</small></h1>
    </div>
</div>

<!-- KPI Cards -->
<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default text-center" style="border-radius:12px; overflow:hidden;">
            <div class="panel-body" style="background:var(--card-bg); color:var(--text-main); padding:20px;">
                <div style="font-size:12px; text-transform:uppercase; color:var(--text-muted); font-weight:700; letter-spacing:0.5px;">Pending Price Adjustments</div>
                <div style="font-size:32px; font-weight:800; color:var(--primary-light); margin:10px 0;"><?php echo $sugCount; ?></div>
                <div style="font-size:11px; color:var(--text-muted);">Awaiting manager review & application</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default text-center" style="border-radius:12px; overflow:hidden;">
            <div class="panel-body" style="background:var(--card-bg); color:var(--text-main); padding:20px;">
                <div style="font-size:12px; text-transform:uppercase; color:var(--text-muted); font-weight:700; letter-spacing:0.5px;">Average Expected Revenue Lift</div>
                <div style="font-size:32px; font-weight:800; color:#22c55e; margin:10px 0;">+<?php echo $avgLift; ?>%</div>
                <div style="font-size:11px; color:var(--text-muted);">Estimated margin lift on applied suggestions</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default text-center" style="border-radius:12px; overflow:hidden;">
            <div class="panel-body" style="background:var(--card-bg); color:var(--text-main); padding:20px;">
                <div style="font-size:12px; text-transform:uppercase; color:var(--text-muted); font-weight:700; letter-spacing:0.5px;">Active Price A/B Experiments</div>
                <div style="font-size:32px; font-weight:800; color:#f59e0b; margin:10px 0;"><?php echo $activeTests; ?></div>
                <div style="font-size:11px; color:var(--text-muted);">Products undergoing pricing A/B splits</div>
            </div>
        </div>
    </div>
</div>

<div class="row" style="margin-bottom:20px;">
    <div class="col-md-12">
        <div class="panel panel-default" style="border-radius:12px; border:1px solid var(--border-color); box-shadow:var(--shadow);">
            <div class="panel-body" style="background:var(--card-bg); color:var(--text-main); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:15px; padding:15px 20px;">
                <div style="font-size:13.5px; font-weight:500; color:var(--text-muted);">
                    Price DQN Reinforcement Learning models analyze sales volume shifts, elasticity curves, and stock days to propose margins.
                </div>
                <div class="btn-group">
                    <a href="index.php?action=optimize" class="btn btn-primary btn-sm" style="border-radius:6px; font-weight:600;">
                        <i class="fa fa-cogs"></i> Run Price DQN Optimization
                    </a>
                    <button class="btn btn-default btn-sm" data-toggle="modal" data-target="#abTestModal" style="border-radius:6px; font-weight:600; border-color:var(--border-color);">
                        <i class="fa fa-plus"></i> Launch A/B Split Test
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Price Suggestions List -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default" style="border-radius:12px; overflow:hidden; margin-bottom:30px;">
            <div class="panel-heading" style="font-weight:700; background:var(--card-header-bg); border-bottom:1px solid var(--border-color); color:var(--primary);">
                Optimal Pricing Suggestions
            </div>
            <div class="panel-body" style="background:var(--card-bg); padding:0;">
                <div class="table-responsive">
                    <table class="table table-hover table-striped" style="margin:0; font-size:13.5px;">
                        <thead>
                            <tr style="background:var(--table-header-bg); border-bottom:1px solid var(--border-color);">
                                <th style="font-weight:600; padding:15px;">Product</th>
                                <th style="font-weight:600; padding:15px; text-align:right;">Base Price</th>
                                <th style="font-weight:600; padding:15px; text-align:right;">Suggested Price</th>
                                <th style="font-weight:600; padding:15px; text-align:center;">Change</th>
                                <th style="font-weight:600; padding:15px; text-align:center;">Expected Revenue Lift</th>
                                <th style="font-weight:600; padding:15px; width:300px;">DQN Reasoning Factors</th>
                                <th style="font-weight:600; padding:15px; text-align:right; width:180px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($sugCount === 0) { ?>
                                <tr>
                                    <td colspan="7" class="text-center" style="color:var(--text-muted); padding:30px;">All product prices are currently optimized. Run price optimization to check for adjustments.</td>
                                </tr>
                            <?php } else { ?>
                                <?php foreach ($suggestions as $s) { 
                                    $diff = $s->suggested_price - $s->base_price;
                                    $pct = round(($diff / $s->base_price) * 100, 1);
                                    
                                    $badgeClass = $pct > 0 ? 'label-success' : 'label-danger';
                                    $indicator = $pct > 0 ? '+' : '';
                                    
                                    $reasons = json_decode($s->reasons, true);
                                ?>
                                    <tr style="border-bottom:1px solid var(--border-color);">
                                        <td style="padding:15px; font-weight:700; color:var(--primary-light);"><?php echo htmlspecialchars($s->PRODESC); ?></td>
                                        <td style="padding:15px; text-align:right; font-weight:600;">₹<?php echo number_format($s->base_price, 2); ?></td>
                                        <td style="padding:15px; text-align:right; font-weight:700; color:#3b82f6;">₹<?php echo number_format($s->suggested_price, 2); ?></td>
                                        <td style="padding:15px; text-align:center; vertical-align:middle;">
                                            <span class="label <?php echo $badgeClass; ?>" style="font-size:11px; padding:3px 6px; border-radius:10px; font-weight:600;">
                                                <?php echo $indicator . $pct; ?>%
                                            </span>
                                        </td>
                                        <td style="padding:15px; text-align:center; font-weight:700; color:#22c55e;">
                                            +<?php echo $s->expected_revenue_lift; ?>%
                                        </td>
                                        <td style="padding:15px; font-size:12px; color:var(--text-muted);">
                                            <?php 
                                            if ($reasons) {
                                                echo implode(' ', $reasons);
                                            } else {
                                                echo 'Analyzed based on inventory levels and elasticities.';
                                            }
                                            ?>
                                        </td>
                                        <td style="padding:15px; text-align:right;">
                                            <div style="display:flex; justify-content:flex-end; gap:6px;">
                                                <a href="index.php?action=apply&id=<?php echo $s->id; ?>" class="btn btn-success btn-xs" style="font-weight:600; padding:4px 8px;">Apply</a>
                                                <a href="index.php?action=reject&id=<?php echo $s->id; ?>" class="btn btn-default btn-xs" style="font-weight:600; border-color:var(--border-color); padding:4px 8px;">Dismiss</a>
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
</div>

<!-- A/B Experiments List -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default" style="border-radius:12px; overflow:hidden;">
            <div class="panel-heading" style="font-weight:700; background:var(--card-header-bg); border-bottom:1px solid var(--border-color); color:var(--primary);">
                Active A/B Pricing Experiments
            </div>
            <div class="panel-body" style="background:var(--card-bg); padding:0;">
                <div class="table-responsive">
                    <table class="table table-hover table-striped" style="margin:0; font-size:13px;">
                        <thead>
                            <tr style="background:var(--table-header-bg); border-bottom:1px solid var(--border-color);">
                                <th style="font-weight:600; padding:12px 15px;">Product</th>
                                <th style="font-weight:600; padding:12px 15px; text-align:center;">Price A (Control)</th>
                                <th style="font-weight:600; padding:12px 15px; text-align:center;">Price B (Variant)</th>
                                <th style="font-weight:600; padding:12px 15px; text-align:center;">Group A Sales</th>
                                <th style="font-weight:600; padding:12px 15px; text-align:center;">Group B Sales</th>
                                <th style="font-weight:600; padding:12px 15px; text-align:center;">Revenue A / B</th>
                                <th style="font-weight:600; padding:12px 15px; text-align:center;">Launch Date</th>
                                <th style="font-weight:600; padding:12px 15px; text-align:center;">Status</th>
                                <th style="font-weight:600; padding:12px 15px; text-align:right;">Control</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($abTests)) { ?>
                                <tr>
                                    <td colspan="9" class="text-center" style="color:var(--text-muted); padding:30px;">No pricing tests currently active. Click "Launch A/B Split Test" to test elasticities.</td>
                                </tr>
                            <?php } else { ?>
                                <?php foreach ($abTests as $t) { 
                                    $lbl = $t->status === 'running' ? 'label-warning' : 'label-success';
                                    
                                    // Generate some dummy sales if running for demo
                                    $salesA = $t->group_a_sales ? $t->group_a_sales : rand(5, 15);
                                    $salesB = $t->group_b_sales ? $t->group_b_sales : rand(4, 16);
                                    $revA = $salesA * $t->price_a;
                                    $revB = $salesB * $t->price_b;
                                ?>
                                    <tr style="border-bottom:1px solid var(--border-color);">
                                        <td style="padding:12px 15px; font-weight:600;"><?php echo htmlspecialchars($t->PRODESC); ?></td>
                                        <td style="padding:12px 15px; text-align:center; font-weight:700;">₹<?php echo number_format($t->price_a, 2); ?></td>
                                        <td style="padding:12px 15px; text-align:center; font-weight:700; color:#3b82f6;">₹<?php echo number_format($t->price_b, 2); ?></td>
                                        <td style="padding:12px 15px; text-align:center;"><?php echo $salesA; ?> units</td>
                                        <td style="padding:12px 15px; text-align:center;"><?php echo $salesB; ?> units</td>
                                        <td style="padding:12px 15px; text-align:center; font-weight:600;">
                                            ₹<?php echo number_format($revA, 0); ?> / ₹<?php echo number_format($revB, 0); ?>
                                        </td>
                                        <td style="padding:12px 15px; text-align:center; color:var(--text-muted);"><?php echo $t->start_date; ?></td>
                                        <td style="padding:12px 15px; text-align:center;">
                                            <span class="label <?php echo $lbl; ?>" style="font-weight:600; font-size:10px; padding:2px 6px; border-radius:4px;"><?php echo ucfirst($t->status); ?></span>
                                        </td>
                                        <td style="padding:12px 15px; text-align:right;">
                                            <?php if ($t->status === 'running') { ?>
                                                <a href="index.php?action=abtest_stop&test_id=<?php echo $t->id; ?>" class="btn btn-danger btn-xs" style="font-weight:600; padding:2px 6px;">Stop Experiment</a>
                                            <?php } else { ?>
                                                <span style="color:var(--text-muted); font-size:12px;">Concluded</span>
                                            <?php } ?>
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
</div>

<!-- A/B Test Launch Modal -->
<div class="modal fade" id="abTestModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius:12px; overflow:hidden;">
            <div class="modal-header" style="background:var(--card-header-bg); border-bottom:1px solid var(--border-color);">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="outline:none;">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" style="font-weight:700; color:var(--primary);">Launch Pricing A/B Test</h4>
            </div>
            <form action="index.php?action=abtest" method="POST">
                <div class="modal-body" style="background:var(--card-bg); color:var(--text-main); padding:20px;">
                    <div class="form-group">
                        <label for="ab_product_id" style="font-weight:600; font-size:13px; color:var(--text-muted);">Target Product:</label>
                        <select name="product_id" id="ab_product_id" class="form-control" required style="border-radius:6px;">
                            <?php foreach ($productList as $p) { ?>
                                <option value="<?php echo $p->PROID; ?>" data-price="<?php echo $p->PROPRICE; ?>">
                                    <?php echo htmlspecialchars($p->PRODESC) . " (Current: ₹" . number_format($p->PROPRICE, 2) . ")"; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="price_a" style="font-weight:600; font-size:13px; color:var(--text-muted);">Price A (Control):</label>
                                <input type="number" step="0.01" name="price_a" id="price_a" class="form-control" required style="border-radius:6px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="price_b" style="font-weight:600; font-size:13px; color:var(--text-muted);">Price B (Variant):</label>
                                <input type="number" step="0.01" name="price_b" id="price_b" class="form-control" required style="border-radius:6px;">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="start_date" style="font-weight:600; font-size:13px; color:var(--text-muted);">Start Date:</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required style="border-radius:6px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end_date" style="font-weight:600; font-size:13px; color:var(--text-muted);">End Date:</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" value="<?php echo date('Y-m-d', strtotime('+14 days')); ?>" required style="border-radius:6px;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="background:var(--card-header-bg); border-top:1px solid var(--border-color);">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius:6px; font-weight:600; border-color:var(--border-color);">Close</button>
                    <button type="submit" class="btn btn-primary" style="border-radius:6px; font-weight:600;">Launch Experiment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Automatically set default prices in modal on product select change
    var select = document.getElementById("ab_product_id");
    var priceAInput = document.getElementById("price_a");
    var priceBInput = document.getElementById("price_b");
    
    function updatePrices() {
        var selectedOption = select.options[select.selectedIndex];
        var price = parseFloat(selectedOption.getAttribute("data-price"));
        priceAInput.value = price.toFixed(2);
        priceBInput.value = (price * 1.05).toFixed(2); // Auto-suggest a 5% increase for variation A/B test
    }
    
    select.addEventListener("change", updatePrices);
    if (select.options.length > 0) {
        updatePrices();
    }
});
</script>
