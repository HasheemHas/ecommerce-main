<?php
/**
 * Coupon Manager Dashboard View.
 */
global $mydb;

// Fetch all coupons
$mydb->setQuery("SELECT * FROM coupons ORDER BY coupon_id DESC");
$coupons = $mydb->loadResultList();

// Aggregates
$activeCount = 0;
$totalUsage = 0;
$totalValue = 0.0;
$countVal = 0;

foreach ($coupons as $cp) {
    if ($cp->status === 'active') $activeCount++;
    $totalUsage += $cp->times_used;
    if ($cp->type !== 'BOGO') {
        $totalValue += $cp->value;
        $countVal++;
    }
}
$avgDiscount = $countVal > 0 ? round($totalValue / $countVal, 1) : 0;
?>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-gift" style="color:var(--primary-light);"></i> Coupon Manager <small>Marketing Suite</small></h1>
    </div>
</div>

<!-- KPI Summaries -->
<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default text-center" style="border-radius:12px; overflow:hidden;">
            <div class="panel-body" style="background:var(--card-bg); color:var(--text-main); padding:20px;">
                <div style="font-size:12px; text-transform:uppercase; color:var(--text-muted); font-weight:700; letter-spacing:0.5px;">Active Campaigns</div>
                <div style="font-size:32px; font-weight:800; color:var(--primary-light); margin:10px 0;"><?php echo $activeCount; ?></div>
                <div style="font-size:11px; color:var(--text-muted);">Active discount codes currently accepted</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default text-center" style="border-radius:12px; overflow:hidden;">
            <div class="panel-body" style="background:var(--card-bg); color:var(--text-main); padding:20px;">
                <div style="font-size:12px; text-transform:uppercase; color:var(--text-muted); font-weight:700; letter-spacing:0.5px;">Total Redemptions</div>
                <div style="font-size:32px; font-weight:800; color:#22c55e; margin:10px 0;"><?php echo $totalUsage; ?> times</div>
                <div style="font-size:11px; color:var(--text-muted);">Redeemed coupons applied on checkout</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default text-center" style="border-radius:12px; overflow:hidden;">
            <div class="panel-body" style="background:var(--card-bg); color:var(--text-main); padding:20px;">
                <div style="font-size:12px; text-transform:uppercase; color:var(--text-muted); font-weight:700; letter-spacing:0.5px;">Avg Discount Yield</div>
                <div style="font-size:32px; font-weight:800; color:#f59e0b; margin:10px 0;"><?php echo $avgDiscount; ?>% / ₹</div>
                <div style="font-size:11px; color:var(--text-muted);">Average discount percentage or fixed value</div>
            </div>
        </div>
    </div>
</div>

<div class="row" style="margin-bottom:20px;">
    <div class="col-md-12 text-right">
        <button class="btn btn-primary" data-toggle="modal" data-target="#couponModal" style="border-radius:6px; font-weight:600;">
            <i class="fa fa-plus"></i> Create New Coupon
        </button>
    </div>
</div>

<!-- Coupons List Table -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default" style="border-radius:12px; overflow:hidden;">
            <div class="panel-heading" style="font-weight:700; background:var(--card-header-bg); border-bottom:1px solid var(--border-color); color:var(--primary);">
                Promotional Campaign Registry
            </div>
            <div class="panel-body" style="background:var(--card-bg); padding:0;">
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="dash-table" style="margin:0; font-size:13.5px;">
                        <thead>
                            <tr style="background:var(--table-header-bg); border-bottom:1px solid var(--border-color);">
                                <th style="font-weight:600; padding:15px;">Coupon Code</th>
                                <th style="font-weight:600; padding:15px;">Type</th>
                                <th style="font-weight:600; padding:15px; text-align:right;">Value</th>
                                <th style="font-weight:600; padding:15px; text-align:center;">Validity Dates</th>
                                <th style="font-weight:600; padding:15px; text-align:center;">Usage Rate</th>
                                <th style="font-weight:600; padding:15px;">Target Customer Segment</th>
                                <th style="font-weight:600; padding:15px; text-align:center;">Status</th>
                                <th style="font-weight:600; padding:15px; text-align:right; width:180px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($coupons as $cp) { 
                                $statusClass = $cp->status === 'active' ? 'label-success' : 'label-default';
                                $valueDisplay = $cp->type === 'percent' ? $cp->value . '%' : ($cp->type === 'fixed' ? '₹' . number_format($cp->value, 2) : 'BOGO');
                                
                                $segmentClass = 'label-info';
                                if ($cp->target_segment === 'VIP') $segmentClass = 'label-warning';
                                elseif ($cp->target_segment === 'Churn_Risk') $segmentClass = 'label-danger';
                            ?>
                                <tr style="border-bottom:1px solid var(--border-color);">
                                    <td style="padding:15px; font-weight:800; letter-spacing:0.5px;"><?php echo htmlspecialchars($cp->coupon_code); ?></td>
                                    <td style="padding:15px; text-transform:capitalize;"><?php echo $cp->type; ?></td>
                                    <td style="padding:15px; text-align:right; font-weight:700; color:var(--primary-light);"><?php echo $valueDisplay; ?></td>
                                    <td style="padding:15px; text-align:center; color:var(--text-muted);">
                                        <?php echo date('Y-m-d', strtotime($cp->start_date)) . ' to ' . date('Y-m-d', strtotime($cp->expiry_date)); ?>
                                    </td>
                                    <td style="padding:15px; text-align:center; font-weight:600;">
                                        <?php echo $cp->times_used; ?> / <?php echo $cp->usage_limit; ?>
                                    </td>
                                    <td style="padding:15px;">
                                        <span class="label <?php echo $segmentClass; ?>" style="font-weight:600; padding:3px 6px; border-radius:4px;"><?php echo $cp->target_segment; ?></span>
                                    </td>
                                    <td style="padding:15px; text-align:center; vertical-align:middle;">
                                        <span class="label <?php echo $statusClass; ?>" style="font-size:10.5px; padding:3px 6px; border-radius:4px;"><?php echo ucfirst($cp->status); ?></span>
                                    </td>
                                    <td style="padding:15px; text-align:right; vertical-align:middle;">
                                        <div style="display:flex; justify-content:flex-end; gap:4px;">
                                            <a href="index.php?action=toggle&id=<?php echo $cp->coupon_id; ?>" class="btn btn-default btn-xs" style="font-weight:600; border-color:var(--border-color);">
                                                <?php echo $cp->status === 'active' ? 'Disable' : 'Enable'; ?>
                                            </a>
                                            <a href="index.php?action=delete&id=<?php echo $cp->coupon_id; ?>" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this coupon?')" style="font-weight:600;">Delete</a>
                                        </div>
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

<!-- Coupon Creation Modal -->
<div class="modal fade" id="couponModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius:12px; overflow:hidden;">
            <div class="modal-header" style="background:var(--card-header-bg); border-bottom:1px solid var(--border-color);">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="outline:none;">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" style="font-weight:700; color:var(--primary);">Create Promotional Coupon</h4>
            </div>
            <form action="index.php?action=add" method="POST">
                <div class="modal-body" style="background:var(--card-bg); color:var(--text-main); padding:20px;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="coupon_code" style="font-weight:600; font-size:13px; color:var(--text-muted);">Coupon Code:</label>
                                <input type="text" name="coupon_code" id="coupon_code" class="form-control" placeholder="e.g. SUMMER20" required style="border-radius:6px; text-transform:uppercase;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="type" style="font-weight:600; font-size:13px; color:var(--text-muted);">Discount Type:</label>
                                <select name="type" id="type" class="form-control" style="border-radius:6px;">
                                    <option value="percent">Percent Discount (%)</option>
                                    <option value="fixed">Fixed Price Discount (₹)</option>
                                    <option value="BOGO">Buy One Get One (BOGO)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="value" style="font-weight:600; font-size:13px; color:var(--text-muted);">Value:</label>
                                <input type="number" step="0.1" name="value" id="value" class="form-control" placeholder="Discount percentage / price value" required style="border-radius:6px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="target_segment" style="font-weight:600; font-size:13px; color:var(--text-muted);">Target Segment Restriction:</label>
                                <select name="target_segment" id="target_segment" class="form-control" style="border-radius:6px;">
                                    <option value="All">All Customers (All)</option>
                                    <option value="VIP">VIP Customers (VIP)</option>
                                    <option value="Churn_Risk">Churn Risk Recovery (Churn_Risk)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="usage_limit" style="font-weight:600; font-size:13px; color:var(--text-muted);">Usage Limit Count:</label>
                                <input type="number" name="usage_limit" id="usage_limit" class="form-control" value="100" required style="border-radius:6px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="min_spend" style="font-weight:600; font-size:13px; color:var(--text-muted);">Minimum Spend Limit (₹):</label>
                                <input type="number" step="0.01" name="min_spend" id="min_spend" class="form-control" value="0.00" style="border-radius:6px;">
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
                                <label for="expiry_date" style="font-weight:600; font-size:13px; color:var(--text-muted);">Expiry Date:</label>
                                <input type="date" name="expiry_date" id="expiry_date" class="form-control" value="<?php echo date('Y-m-d', strtotime('+30 days')); ?>" required style="border-radius:6px;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="background:var(--card-header-bg); border-top:1px solid var(--border-color);">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius:6px; font-weight:600; border-color:var(--border-color);">Close</button>
                    <button type="submit" class="btn btn-primary" style="border-radius:6px; font-weight:600;">Create Campaign</button>
                </div>
            </form>
        </div>
    </div>
</div>
