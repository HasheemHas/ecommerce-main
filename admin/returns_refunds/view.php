<?php
/**
 * Returns & Refunds Dashboard View.
 */
global $mydb;

// Fetch returns list
$mydb->setQuery("
    SELECT r.*, CONCAT(c.FNAME, ' ', c.LNAME) as customer_name, c.EMAILADD, rf.refund_status, rf.transaction_reference
    FROM `returns` r
    JOIN `tblcustomer` c ON r.customer_id = c.CUSTOMERID
    LEFT JOIN `refunds` rf ON r.return_id = rf.return_id
    ORDER BY r.request_date DESC
");
$returns = $mydb->loadResultList();

// Fetch top returned products
$mydb->setQuery("
    SELECT p.PRODESC, c.CATEGORIES, COUNT(*) as return_count 
    FROM `return_items` ri 
    JOIN `tblproduct` p ON ri.product_id = p.PROID 
    JOIN `tblcategory` c ON p.CATEGID = c.CATEGID 
    GROUP BY p.PROID 
    ORDER BY return_count DESC 
    LIMIT 5
");
$topReturned = $mydb->loadResultList();

// Calculate total checkouts
$mydb->setQuery("SELECT COUNT(*) as total_orders FROM `tblsummary` WHERE `ORDEREDSTATS` != 'Cancelled'");
$orderStats = $mydb->loadSingleResult();
$totalOrders = $orderStats ? (int)$orderStats->total_orders : 1;

// Aggregates
$totalReturnsCount = count($returns);
$returnRate = $totalOrders > 0 ? round(($totalReturnsCount / $totalOrders) * 100, 1) : 0;

$pendingReturns = 0;
$totalRefundedAmt = 0.0;
foreach ($returns as $r) {
    if ($r->return_status === 'Pending') $pendingReturns++;
    if ($r->return_status === 'Refunded') $totalRefundedAmt += $r->refund_amount;
}
?>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-reply" style="color:var(--primary-light);"></i> Returns & Refunds <small>Operations Suite</small></h1>
    </div>
</div>

<!-- KPI Cards -->
<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default text-center" style="border-radius:12px; overflow:hidden;">
            <div class="panel-body" style="background:var(--card-bg); color:var(--text-main); padding:20px;">
                <div style="font-size:12px; text-transform:uppercase; color:var(--text-muted); font-weight:700; letter-spacing:0.5px;">Return Rate</div>
                <div style="font-size:32px; font-weight:800; color:#ef4444; margin:10px 0;"><?php echo $returnRate; ?>%</div>
                <div style="font-size:11px; color:var(--text-muted);"><?php echo $totalReturnsCount; ?> returns out of <?php echo $totalOrders; ?> checkouts</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default text-center" style="border-radius:12px; overflow:hidden;">
            <div class="panel-body" style="background:var(--card-bg); color:var(--text-main); padding:20px;">
                <div style="font-size:12px; text-transform:uppercase; color:var(--text-muted); font-weight:700; letter-spacing:0.5px;">Pending Approvals</div>
                <div style="font-size:32px; font-weight:800; color:#f59e0b; margin:10px 0;"><?php echo $pendingReturns; ?></div>
                <div style="font-size:11px; color:var(--text-muted);">Return requests awaiting inspection</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default text-center" style="border-radius:12px; overflow:hidden;">
            <div class="panel-body" style="background:var(--card-bg); color:var(--text-main); padding:20px;">
                <div style="font-size:12px; text-transform:uppercase; color:var(--text-muted); font-weight:700; letter-spacing:0.5px;">Refunded Capital</div>
                <div style="font-size:32px; font-weight:800; color:#22c55e; margin:10px 0;">₹<?php echo number_format($totalRefundedAmt, 2); ?></div>
                <div style="font-size:11px; color:var(--text-muted);">Total capital returned to shoppers</div>
            </div>
        </div>
    </div>
</div>

<div class="row" style="margin-bottom:20px;">
    <div class="col-md-12 text-right">
        <a href="index.php?action=export" class="btn btn-default" style="border-radius:6px; border-color:var(--border-color); font-weight:600;">
            <i class="fa fa-download"></i> Export Returns Log (CSV)
        </a>
    </div>
</div>

<div class="row">
    <!-- Returns Registry -->
    <div class="col-md-8">
        <div class="panel panel-default" style="border-radius:12px; overflow:hidden;">
            <div class="panel-heading" style="font-weight:700; background:var(--card-header-bg); border-bottom:1px solid var(--border-color); color:var(--primary);">
                Return Requests Log
            </div>
            <div class="panel-body" style="background:var(--card-bg); padding:0;">
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="dash-table" style="margin:0; font-size:13.5px;">
                        <thead>
                            <tr style="background:var(--table-header-bg); border-bottom:1px solid var(--border-color);">
                                <th style="font-weight:600; padding:15px;">Order #</th>
                                <th style="font-weight:600; padding:15px;">Customer</th>
                                <th style="font-weight:600; padding:15px;">Reason Detail</th>
                                <th style="font-weight:600; padding:15px; text-align:right;">Refund Sum</th>
                                <th style="font-weight:600; padding:15px; text-align:center;">Status</th>
                                <th style="font-weight:600; padding:15px; text-align:right; width:150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($returns as $r) { 
                                $statusLbl = 'label-warning';
                                if ($r->return_status === 'Refunded') $statusLbl = 'label-success';
                                elseif ($r->return_status === 'Approved') $statusLbl = 'label-info';
                                elseif ($r->return_status === 'Rejected') $statusLbl = 'label-danger';
                            ?>
                                <tr style="border-bottom:1px solid var(--border-color);">
                                    <td style="padding:15px; font-weight:700;">#<?php echo $r->order_number; ?></td>
                                    <td style="padding:15px;">
                                        <div style="font-weight:600;"><?php echo htmlspecialchars($r->customer_name); ?></div>
                                        <div style="font-size:11px; color:var(--text-muted);"><?php echo $r->EMAILADD; ?></div>
                                    </td>
                                    <td style="padding:15px; font-size:12.5px;"><?php echo htmlspecialchars($r->reason_summary); ?></td>
                                    <td style="padding:15px; text-align:right; font-weight:700; color:var(--primary-light);">
                                        ₹<?php echo number_format($r->refund_amount, 2); ?>
                                    </td>
                                    <td style="padding:15px; text-align:center; vertical-align:middle;">
                                        <span class="label <?php echo $statusLbl; ?>" style="font-size:10.5px; padding:3px 6px; border-radius:4px;"><?php echo $r->return_status; ?></span>
                                    </td>
                                    <td style="padding:15px; text-align:right; vertical-align:middle;">
                                        <?php if ($r->return_status === 'Pending') { ?>
                                            <div style="display:flex; justify-content:flex-end; gap:4px;">
                                                <a href="index.php?action=approve&id=<?php echo $r->return_id; ?>" class="btn btn-info btn-xs" style="font-weight:600;">Approve</a>
                                                <a href="index.php?action=reject&id=<?php echo $r->return_id; ?>" class="btn btn-default btn-xs" style="font-weight:600; border-color:var(--border-color);">Reject</a>
                                            </div>
                                        <?php } elseif ($r->return_status === 'Approved' && $r->refund_status === 'Pending') { ?>
                                            <a href="index.php?action=refund&id=<?php echo $r->return_id; ?>" class="btn btn-success btn-xs" style="font-weight:600;">
                                                <i class="fa fa-check"></i> Complete Refund
                                            </a>
                                        <?php } else { ?>
                                            <span style="color:var(--text-muted); font-size:12px;">No Actions</span>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Top Returned Products List -->
    <div class="col-md-4">
        <div class="panel panel-default" style="border-radius:12px; overflow:hidden;">
            <div class="panel-heading" style="font-weight:700; background:var(--card-header-bg); border-bottom:1px solid var(--border-color); color:var(--primary);">
                Top Returned Items
            </div>
            <div class="panel-body" style="background:var(--card-bg); padding:0;">
                <div class="table-responsive">
                    <table class="table table-hover" style="margin:0; font-size:13px;">
                        <thead>
                            <tr style="background:var(--table-header-bg); border-bottom:1px solid var(--border-color);">
                                <th style="font-weight:600; padding:12px 15px;">Product</th>
                                <th style="font-weight:600; padding:12px 15px; text-align:right;">Incident Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($topReturned)) { ?>
                                <tr>
                                    <td colspan="2" class="text-center" style="color:var(--text-muted); padding:30px;">No returned items registered.</td>
                                </tr>
                            <?php } else { ?>
                                <?php foreach ($topReturned as $tr) { ?>
                                    <tr style="border-bottom:1px solid var(--border-color);">
                                        <td style="padding:12px 15px;">
                                            <div style="font-weight:700; color:var(--primary-light);"><?php echo htmlspecialchars($tr->PRODESC); ?></div>
                                            <div style="font-size:10.5px; color:var(--text-muted);"><?php echo htmlspecialchars($tr->CATEGORIES); ?></div>
                                        </td>
                                        <td style="padding:12px 15px; text-align:right; font-weight:700; color:#ef4444; vertical-align:middle;">
                                            <i class="fa fa-warning"></i> <?php echo $tr->return_count; ?> returns
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
