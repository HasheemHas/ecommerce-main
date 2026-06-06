<?php
/**
 * Vendor Management View.
 */
global $mydb;

// Fetch all vendors
$mydb->setQuery("SELECT * FROM vendors ORDER BY vendor_id DESC");
$vendors = $mydb->loadResultList();

// Fetch all purchase orders
$mydb->setQuery("
    SELECT po.*, v.vendor_name 
    FROM purchase_orders po 
    LEFT JOIN vendors v ON po.vendor_id = v.vendor_id 
    ORDER BY po.po_id DESC
");
$purchase_orders = $mydb->loadResultList();

// Fetch all payouts
$mydb->setQuery("
    SELECT vp.*, v.vendor_name, po.po_number 
    FROM vendor_payouts vp 
    LEFT JOIN vendors v ON vp.vendor_id = v.vendor_id 
    LEFT JOIN purchase_orders po ON vp.po_id = po.po_id 
    ORDER BY vp.payout_id DESC
");
$payouts = $mydb->loadResultList();

// Aggregates
$activeVendors = 0;
foreach ($vendors as $v) {
    if ($v->status === 'Active') $activeVendors++;
}

$pendingPayoutsVal = 0.0;
foreach ($payouts as $p) {
    if ($p->status === 'Unpaid') {
        $pendingPayoutsVal += $p->amount;
    }
}

$totalPO = count($purchase_orders);
?>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            <i class="fa fa-briefcase" style="color:var(--primary-light);"></i> Vendor Management
            <small>Supplier Directory & Procurement</small>
        </h1>
    </div>
</div>

<!-- KPI Summaries -->
<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default text-center" style="border-radius:12px; overflow:hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
            <div class="panel-body" style="background:var(--card-bg); color:var(--text-main); padding:20px;">
                <div style="font-size:12px; text-transform:uppercase; color:var(--text-muted); font-weight:700; letter-spacing:0.5px;">Active Suppliers</div>
                <div style="font-size:32px; font-weight:800; color:var(--primary-light); margin:10px 0;"><?php echo $activeVendors; ?></div>
                <div style="font-size:11px; color:var(--text-muted);">Registered and operational vendors</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default text-center" style="border-radius:12px; overflow:hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
            <div class="panel-body" style="background:var(--card-bg); color:var(--text-main); padding:20px;">
                <div style="font-size:12px; text-transform:uppercase; color:var(--text-muted); font-weight:700; letter-spacing:0.5px;">Pending Payouts</div>
                <div style="font-size:32px; font-weight:800; color:#ef4444; margin:10px 0;">₹<?php echo number_format($pendingPayoutsVal, 2); ?></div>
                <div style="font-size:11px; color:var(--text-muted);">Amount owed on outstanding purchase orders</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default text-center" style="border-radius:12px; overflow:hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
            <div class="panel-body" style="background:var(--card-bg); color:var(--text-main); padding:20px;">
                <div style="font-size:12px; text-transform:uppercase; color:var(--text-muted); font-weight:700; letter-spacing:0.5px;">POs Issued</div>
                <div style="font-size:32px; font-weight:800; color:#22c55e; margin:10px 0;"><?php echo $totalPO; ?> Orders</div>
                <div style="font-size:11px; color:var(--text-muted);">Total procurement orders processed</div>
            </div>
        </div>
    </div>
</div>

<!-- Navigation Tabs -->
<div class="row" style="margin-bottom:20px;">
    <div class="col-md-8">
        <ul class="nav nav-pills" id="vendorTabs" style="display: flex; gap: 8px;">
            <li class="active"><a href="#directory" data-toggle="tab" style="border-radius:6px; font-weight:600; padding:10px 20px;">Vendor Directory</a></li>
            <li><a href="#po" data-toggle="tab" style="border-radius:6px; font-weight:600; padding:10px 20px;">Purchase Orders</a></li>
            <li><a href="#payouts" data-toggle="tab" style="border-radius:6px; font-weight:600; padding:10px 20px;">Payout Registry</a></li>
        </ul>
    </div>
    <div class="col-md-4 text-right">
        <div style="display:flex; justify-content:flex-end; gap:8px;">
            <button class="btn btn-default" data-toggle="modal" data-target="#vendorModal" style="border-radius:6px; font-weight:600; border-color:var(--border-color);">
                <i class="fa fa-user-plus"></i> Add Vendor
            </button>
            <button class="btn btn-primary" data-toggle="modal" data-target="#poModal" style="border-radius:6px; font-weight:600;">
                <i class="fa fa-file-text-o"></i> Issue PO
            </button>
        </div>
    </div>
</div>

<div class="tab-content" style="margin-top:10px;">
    
    <!-- 1. VENDOR DIRECTORY TAB -->
    <div class="tab-pane active" id="directory">
        <div class="panel panel-default" style="border-radius:12px; overflow:hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
            <div class="panel-heading" style="font-weight:700; background:var(--card-header-bg); border-bottom:1px solid var(--border-color); color:var(--primary);">
                Supplier Directory
            </div>
            <div class="panel-body" style="background:var(--card-bg); padding:0;">
                <div class="table-responsive">
                    <table class="table table-hover table-striped" style="margin:0; font-size:13.5px;">
                        <thead>
                            <tr style="background:var(--table-header-bg); border-bottom:1px solid var(--border-color);">
                                <th style="font-weight:600; padding:15px;">Vendor Name</th>
                                <th style="font-weight:600; padding:15px;">Email</th>
                                <th style="font-weight:600; padding:15px;">Phone</th>
                                <th style="font-weight:600; padding:15px;">Address</th>
                                <th style="font-weight:600; padding:15px; text-align:center;">Rating</th>
                                <th style="font-weight:600; padding:15px; text-align:center;">Status</th>
                                <th style="font-weight:600; padding:15px; text-align:center;">Registered At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($vendors) == 0): ?>
                                <tr>
                                    <td colspan="7" class="text-center" style="padding: 20px; color: var(--text-muted);">No vendors registered yet.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($vendors as $v): 
                                    $statusClass = $v->status === 'Active' ? 'label-success' : 'label-default';
                                ?>
                                    <tr style="border-bottom:1px solid var(--border-color);">
                                        <td style="padding:15px; font-weight:700;"><?php echo htmlspecialchars($v->vendor_name); ?></td>
                                        <td style="padding:15px;"><?php echo htmlspecialchars($v->email); ?></td>
                                        <td style="padding:15px;"><?php echo htmlspecialchars($v->phone); ?></td>
                                        <td style="padding:15px; color:var(--text-muted);"><?php echo htmlspecialchars($v->address); ?></td>
                                        <td style="padding:15px; text-align:center; font-weight:700; color:#f59e0b;">
                                            <i class="fa fa-star"></i> <?php echo number_format($v->rating, 1); ?>
                                        </td>
                                        <td style="padding:15px; text-align:center; vertical-align:middle;">
                                            <span class="label <?php echo $statusClass; ?>" style="font-size:10.5px; padding:3px 6px; border-radius:4px;"><?php echo $v->status; ?></span>
                                        </td>
                                        <td style="padding:15px; text-align:center; color:var(--text-muted);">
                                            <?php echo date('Y-m-d H:i', strtotime($v->created_at)); ?>
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

    <!-- 2. PURCHASE ORDERS TAB -->
    <div class="tab-pane" id="po">
        <div class="panel panel-default" style="border-radius:12px; overflow:hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
            <div class="panel-heading" style="font-weight:700; background:var(--card-header-bg); border-bottom:1px solid var(--border-color); color:var(--primary);">
                Procurement Registry
            </div>
            <div class="panel-body" style="background:var(--card-bg); padding:0;">
                <div class="table-responsive">
                    <table class="table table-hover table-striped" style="margin:0; font-size:13.5px;">
                        <thead>
                            <tr style="background:var(--table-header-bg); border-bottom:1px solid var(--border-color);">
                                <th style="font-weight:600; padding:15px;">PO Number</th>
                                <th style="font-weight:600; padding:15px;">Vendor / Supplier</th>
                                <th style="font-weight:600; padding:15px; text-align:right;">Total Amount</th>
                                <th style="font-weight:600; padding:15px; text-align:center;">Expected Delivery</th>
                                <th style="font-weight:600; padding:15px; text-align:center;">Status</th>
                                <th style="font-weight:600; padding:15px; text-align:center;">Issued At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($purchase_orders) == 0): ?>
                                <tr>
                                    <td colspan="6" class="text-center" style="padding: 20px; color: var(--text-muted);">No Purchase Orders issued yet.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($purchase_orders as $po): 
                                    $statusClass = 'label-info';
                                    if ($po->status === 'Draft') $statusClass = 'label-default';
                                    elseif ($po->status === 'Received') $statusClass = 'label-success';
                                    elseif ($po->status === 'Sent') $statusClass = 'label-warning';
                                ?>
                                    <tr style="border-bottom:1px solid var(--border-color);">
                                        <td style="padding:15px; font-weight:800; letter-spacing:0.5px; color:var(--primary-light);"><?php echo htmlspecialchars($po->po_number); ?></td>
                                        <td style="padding:15px; font-weight:600;"><?php echo htmlspecialchars($po->vendor_name); ?></td>
                                        <td style="padding:15px; text-align:right; font-weight:700;">₹<?php echo number_format($po->total_amount, 2); ?></td>
                                        <td style="padding:15px; text-align:center; color:var(--text-muted);"><?php echo date('Y-m-d', strtotime($po->expected_delivery)); ?></td>
                                        <td style="padding:15px; text-align:center; vertical-align:middle;">
                                            <span class="label <?php echo $statusClass; ?>" style="font-size:10.5px; padding:3px 6px; border-radius:4px;"><?php echo $po->status; ?></span>
                                        </td>
                                        <td style="padding:15px; text-align:center; color:var(--text-muted);"><?php echo date('Y-m-d H:i', strtotime($po->created_at)); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. PAYOUT REGISTRY TAB -->
    <div class="tab-pane" id="payouts">
        <div class="panel panel-default" style="border-radius:12px; overflow:hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
            <div class="panel-heading" style="font-weight:700; background:var(--card-header-bg); border-bottom:1px solid var(--border-color); color:var(--primary);">
                Supplier Invoice & Payout Ledger
            </div>
            <div class="panel-body" style="background:var(--card-bg); padding:0;">
                <div class="table-responsive">
                    <table class="table table-hover table-striped" style="margin:0; font-size:13.5px;">
                        <thead>
                            <tr style="background:var(--table-header-bg); border-bottom:1px solid var(--border-color);">
                                <th style="font-weight:600; padding:15px;">PO Link</th>
                                <th style="font-weight:600; padding:15px;">Vendor / Supplier</th>
                                <th style="font-weight:600; padding:15px; text-align:right;">Payout Amount</th>
                                <th style="font-weight:600; padding:15px; text-align:center;">Payment Status</th>
                                <th style="font-weight:600; padding:15px; text-align:center;">Processed At</th>
                                <th style="font-weight:600; padding:15px; text-align:right; width:150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($payouts) == 0): ?>
                                <tr>
                                    <td colspan="6" class="text-center" style="padding: 20px; color: var(--text-muted);">No vendor payouts logged yet.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($payouts as $p): 
                                    $statusClass = $p->status === 'Paid' ? 'label-success' : 'label-danger';
                                ?>
                                    <tr style="border-bottom:1px solid var(--border-color);">
                                        <td style="padding:15px; font-weight:800;"><?php echo htmlspecialchars($p->po_number); ?></td>
                                        <td style="padding:15px; font-weight:600;"><?php echo htmlspecialchars($p->vendor_name); ?></td>
                                        <td style="padding:15px; text-align:right; font-weight:700; color:var(--primary-light);">₹<?php echo number_format($p->amount, 2); ?></td>
                                        <td style="padding:15px; text-align:center; vertical-align:middle;">
                                            <span class="label <?php echo $statusClass; ?>" style="font-size:10.5px; padding:3px 6px; border-radius:4px;"><?php echo $p->status; ?></span>
                                        </td>
                                        <td style="padding:15px; text-align:center; color:var(--text-muted);">
                                            <?php echo $p->processed_at ? date('Y-m-d H:i', strtotime($p->processed_at)) : 'N/A'; ?>
                                        </td>
                                        <td style="padding:15px; text-align:right; vertical-align:middle;">
                                            <?php if ($p->status === 'Unpaid'): ?>
                                                <a href="index.php?action=payout&payout_id=<?php echo $p->payout_id; ?>" class="btn btn-success btn-xs" style="font-weight:600; border-radius:4px;" onclick="return confirm('Confirm payout of ₹<?php echo number_format($p->amount, 2); ?> to <?php echo htmlspecialchars($p->vendor_name); ?>?')">
                                                    <i class="fa fa-check"></i> Mark Paid
                                                </a>
                                            <?php else: ?>
                                                <span class="text-success" style="font-weight:600;"><i class="fa fa-check-circle"></i> Settled</span>
                                            <?php endif; ?>
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

<!-- Vendor Creation Modal -->
<div class="modal fade" id="vendorModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius:12px; overflow:hidden;">
            <div class="modal-header" style="background:var(--card-header-bg); border-bottom:1px solid var(--border-color);">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="outline:none;">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" style="font-weight:700; color:var(--primary);">Add New Vendor</h4>
            </div>
            <form action="index.php?action=add" method="POST">
                <div class="modal-body" style="background:var(--card-bg); color:var(--text-main); padding:20px;">
                    <div class="form-group">
                        <label for="vendor_name" style="font-weight:600; font-size:13px; color:var(--text-muted);">Vendor Name:</label>
                        <input type="text" name="vendor_name" id="vendor_name" class="form-control" placeholder="e.g. Acme Wholesale Supplies" required style="border-radius:6px;">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" style="font-weight:600; font-size:13px; color:var(--text-muted);">Email Address:</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="sales@vendor.com" required style="border-radius:6px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone" style="font-weight:600; font-size:13px; color:var(--text-muted);">Phone Number:</label>
                                <input type="text" name="phone" id="phone" class="form-control" placeholder="+91-9876543210" required style="border-radius:6px;">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="address" style="font-weight:600; font-size:13px; color:var(--text-muted);">Office/Warehouse Address:</label>
                        <textarea name="address" id="address" class="form-control" rows="3" placeholder="Enter complete supplier address..." required style="border-radius:6px; resize:vertical;"></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="background:var(--card-header-bg); border-top:1px solid var(--border-color);">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius:6px; font-weight:600; border-color:var(--border-color);">Close</button>
                    <button type="submit" class="btn btn-primary" style="border-radius:6px; font-weight:600;">Register Supplier</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Purchase Order Modal -->
<div class="modal fade" id="poModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius:12px; overflow:hidden;">
            <div class="modal-header" style="background:var(--card-header-bg); border-bottom:1px solid var(--border-color);">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="outline:none;">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" style="font-weight:700; color:var(--primary);">Issue Purchase Order</h4>
            </div>
            <form action="index.php?action=po_add" method="POST">
                <div class="modal-body" style="background:var(--card-bg); color:var(--text-main); padding:20px;">
                    <div class="form-group">
                        <label for="vendor_id" style="font-weight:600; font-size:13px; color:var(--text-muted);">Select Supplier:</label>
                        <select name="vendor_id" id="vendor_id" class="form-control" required style="border-radius:6px;">
                            <option value="">-- Choose Vendor --</option>
                            <?php foreach ($vendors as $v): ?>
                                <?php if ($v->status === 'Active'): ?>
                                    <option value="<?php echo $v->vendor_id; ?>"><?php echo htmlspecialchars($v->vendor_name); ?> (Rating: <?php echo number_format($v->rating, 1); ?>)</option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="total_amount" style="font-weight:600; font-size:13px; color:var(--text-muted);">Total Order Amount (₹):</label>
                                <input type="number" step="0.01" name="total_amount" id="total_amount" class="form-control" placeholder="0.00" required style="border-radius:6px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="expected_delivery" style="font-weight:600; font-size:13px; color:var(--text-muted);">Expected Delivery Date:</label>
                                <input type="date" name="expected_delivery" id="expected_delivery" class="form-control" value="<?php echo date('Y-m-d', strtotime('+7 days')); ?>" required style="border-radius:6px;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="background:var(--card-header-bg); border-top:1px solid var(--border-color);">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius:6px; font-weight:600; border-color:var(--border-color);">Close</button>
                    <button type="submit" class="btn btn-primary" style="border-radius:6px; font-weight:600;">Issue Order</button>
                </div>
            </form>
        </div>
    </div>
</div>
