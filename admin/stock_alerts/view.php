<?php
/**
 * Proactive Stock Alerts View.
 */
global $mydb;

// Fetch low stock alerts
$mydb->setQuery("
    SELECT lsa.*, p.PRODESC as product_name, p.PROQTY as tbl_qty, p.PROPRICE
    FROM low_stock_alerts lsa
    LEFT JOIN tblproduct p ON lsa.product_id = p.PROID
    ORDER BY lsa.status ASC, lsa.alert_id DESC
");
$alerts = $mydb->loadResultList();

// Fetch all products for threshold configuration dropdown
$mydb->setQuery("SELECT PROID, PRODESC, PROQTY FROM tblproduct ORDER BY PRODESC ASC");
$all_products = $mydb->loadResultList();

// Aggregates
$activeAlertsCount = 0;
$resolvedAlertsCount = 0;
$criticalItemsCount = 0;

foreach ($alerts as $a) {
    if ($a->status === 'Active') {
        $activeAlertsCount++;
        if ($a->tbl_qty <= ($a->threshold / 2)) {
            $criticalItemsCount++;
        }
    } else {
        $resolvedAlertsCount++;
    }
}
?>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            <i class="fa fa-exclamation-triangle" style="color:#ef4444;"></i> Proactive Stock Alerts
            <small>Inventory Health & Procurement Automation</small>
        </h1>
    </div>
</div>

<!-- KPI Summary Cards -->
<div class="row">
    <div class="col-md-3">
        <div class="panel panel-default text-center" style="border-radius:12px; overflow:hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
            <div class="panel-body" style="background:var(--card-bg); color:var(--text-main); padding:20px;">
                <div style="font-size:12px; text-transform:uppercase; color:var(--text-muted); font-weight:700; letter-spacing:0.5px;">Active Alerts</div>
                <div style="font-size:32px; font-weight:800; color:#ef4444; margin:10px 0;"><?php echo $activeAlertsCount; ?></div>
                <div style="font-size:11px; color:var(--text-muted);">Products currently below threshold</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default text-center" style="border-radius:12px; overflow:hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
            <div class="panel-body" style="background:var(--card-bg); color:var(--text-main); padding:20px;">
                <div style="font-size:12px; text-transform:uppercase; color:var(--text-muted); font-weight:700; letter-spacing:0.5px;">Critical Deficit</div>
                <div style="font-size:32px; font-weight:800; color:#b91c1c; margin:10px 0;"><?php echo $criticalItemsCount; ?></div>
                <div style="font-size:11px; color:var(--text-muted);">Stock level <= 50% of threshold</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default text-center" style="border-radius:12px; overflow:hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
            <div class="panel-body" style="background:var(--card-bg); color:var(--text-main); padding:20px;">
                <div style="font-size:12px; text-transform:uppercase; color:var(--text-muted); font-weight:700; letter-spacing:0.5px;">Resolved / Replenished</div>
                <div style="font-size:32px; font-weight:800; color:#22c55e; margin:10px 0;"><?php echo $resolvedAlertsCount; ?></div>
                <div style="font-size:11px; color:var(--text-muted);">Replenished stock alert cases</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default text-center" style="border-radius:12px; overflow:hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
            <div class="panel-body" style="background:var(--card-bg); color:var(--text-main); padding:20px;">
                <div style="font-size:12px; text-transform:uppercase; color:var(--text-muted); font-weight:700; letter-spacing:0.5px;">Total Catalog Items</div>
                <div style="font-size:32px; font-weight:800; color:var(--primary-light); margin:10px 0;"><?php echo count($all_products); ?></div>
                <div style="font-size:11px; color:var(--text-muted);">Monitored items in active catalog</div>
            </div>
        </div>
    </div>
</div>

<!-- Header Controls -->
<div class="row" style="margin-bottom:20px;">
    <div class="col-md-12 text-right">
        <div style="display:flex; justify-content:flex-end; gap:8px;">
            <a href="index.php?action=check_stock" class="btn btn-default" style="border-radius:6px; font-weight:600; border-color:var(--border-color);">
                <i class="fa fa-refresh"></i> Run Inventory Scan
            </a>
            <button class="btn btn-primary" data-toggle="modal" data-target="#thresholdModal" style="border-radius:6px; font-weight:600;">
                <i class="fa fa-cog"></i> Configure Threshold
            </button>
        </div>
    </div>
</div>

<!-- Low Stock Alerts Table -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default" style="border-radius:12px; overflow:hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
            <div class="panel-heading" style="font-weight:700; background:var(--card-header-bg); border-bottom:1px solid var(--border-color); color:var(--primary);">
                Alert Registry & Procurement Controls
            </div>
            <div class="panel-body" style="background:var(--card-bg); padding:0;">
                <div class="table-responsive">
                    <table class="table table-hover table-striped" style="margin:0; font-size:13.5px;">
                        <thead>
                            <tr style="background:var(--table-header-bg); border-bottom:1px solid var(--border-color);">
                                <th style="font-weight:600; padding:15px;">Product ID</th>
                                <th style="font-weight:600; padding:15px;">Product Description</th>
                                <th style="font-weight:600; padding:15px; text-align:center;">Current Stock</th>
                                <th style="font-weight:600; padding:15px; text-align:center;">Alert Threshold</th>
                                <th style="font-weight:600; padding:15px; text-align:center;">Alert Status</th>
                                <th style="font-weight:600; padding:15px; text-align:center;">Last Notified</th>
                                <th style="font-weight:600; padding:15px; text-align:center;">Resolved At</th>
                                <th style="font-weight:600; padding:15px; text-align:right; width:220px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($alerts) == 0): ?>
                                <tr>
                                    <td colspan="8" class="text-center" style="padding: 20px; color: var(--text-muted);">No stock alerts registered yet. Run an Inventory Scan to register items.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($alerts as $a): 
                                    $statusClass = $a->status === 'Active' ? 'label-danger' : 'label-success';
                                    $isDeficit = ($a->tbl_qty <= $a->threshold);
                                    $stockColor = $isDeficit ? '#ef4444' : '#22c55e';
                                    
                                    // Highlight critical stock levels
                                    if ($a->status === 'Active' && $a->tbl_qty <= ($a->threshold / 2)) {
                                        $stockColor = '#b91c1c';
                                        $stockWeight = '800';
                                    } else {
                                        $stockWeight = '600';
                                    }
                                ?>
                                    <tr style="border-bottom:1px solid var(--border-color);">
                                        <td style="padding:15px; font-weight:800; color:var(--text-muted);">#<?php echo $a->product_id; ?></td>
                                        <td style="padding:15px; font-weight:700;"><?php echo htmlspecialchars($a->product_name); ?></td>
                                        <td style="padding:15px; text-align:center; font-weight:<?php echo $stockWeight; ?>; color:<?php echo $stockColor; ?>;">
                                            <?php echo $a->tbl_qty; ?> units
                                        </td>
                                        <td style="padding:15px; text-align:center; font-weight:600; color:var(--text-main);"><?php echo $a->threshold; ?> units</td>
                                        <td style="padding:15px; text-align:center; vertical-align:middle;">
                                            <span class="label <?php echo $statusClass; ?>" style="font-size:10.5px; padding:3px 6px; border-radius:4px;"><?php echo $a->status; ?></span>
                                        </td>
                                        <td style="padding:15px; text-align:center; color:var(--text-muted);">
                                            <?php echo $a->notified_at ? date('Y-m-d H:i', strtotime($a->notified_at)) : '<span class="text-warning">Pending</span>'; ?>
                                        </td>
                                        <td style="padding:15px; text-align:center; color:var(--text-muted);">
                                            <?php echo $a->resolved_at ? date('Y-m-d H:i', strtotime($a->resolved_at)) : '<span class="text-danger">Outstanding</span>'; ?>
                                        </td>
                                        <td style="padding:15px; text-align:right; vertical-align:middle;">
                                            <div style="display:flex; justify-content:flex-end; gap:4px;">
                                                <?php if ($a->status === 'Active'): ?>
                                                    <a href="index.php?action=notify&alert_id=<?php echo $a->alert_id; ?>" class="btn btn-default btn-xs" style="font-weight:600; border-radius:4px;" title="Dispatches mock alert communication">
                                                        <i class="fa fa-envelope-o"></i> Notify
                                                    </a>
                                                    <a href="index.php?action=autoprocure&alert_id=<?php echo $a->alert_id; ?>" class="btn btn-primary btn-xs" style="font-weight:600; border-radius:4px;" onclick="return confirm('Initiate auto-procurement: This will create a Purchase Order, register a payout, and restock catalog quantity. Proceed?')">
                                                        <i class="fa fa-shopping-cart"></i> Auto-Procure
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-success" style="font-weight:600; font-size:12px;"><i class="fa fa-check-circle"></i> Replenished</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Threshold Configuration Modal -->
<div class="modal fade" id="thresholdModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius:12px; overflow:hidden;">
            <div class="modal-header" style="background:var(--card-header-bg); border-bottom:1px solid var(--border-color);">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="outline:none;">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" style="font-weight:700; color:var(--primary);">Configure Product Alert Threshold</h4>
            </div>
            <form action="index.php?action=set_threshold" method="POST">
                <div class="modal-body" style="background:var(--card-bg); color:var(--text-main); padding:20px;">
                    <div class="form-group">
                        <label for="product_id" style="font-weight:600; font-size:13px; color:var(--text-muted);">Select Catalog Product:</label>
                        <select name="product_id" id="product_id" class="form-control" required style="border-radius:6px;">
                            <option value="">-- Choose Product --</option>
                            <?php foreach ($all_products as $p): ?>
                                <option value="<?php echo $p->PROID; ?>"><?php echo htmlspecialchars($p->PRODESC); ?> (Stock: <?php echo $p->PROQTY; ?> units)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="threshold" style="font-weight:600; font-size:13px; color:var(--text-muted);">Low Stock Alert Threshold Value:</label>
                        <input type="number" name="threshold" id="threshold" class="form-control" placeholder="10" required style="border-radius:6px;">
                        <small class="text-muted">An alert triggers automatically when catalog inventory drops to or below this quantity.</small>
                    </div>
                </div>
                <div class="modal-footer" style="background:var(--card-header-bg); border-top:1px solid var(--border-color);">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius:6px; font-weight:600; border-color:var(--border-color);">Close</button>
                    <button type="submit" class="btn btn-primary" style="border-radius:6px; font-weight:600;">Save Settings</button>
                </div>
            </form>
        </div>
    </div>
</div>
