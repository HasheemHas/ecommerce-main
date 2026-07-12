<?php
check_message(); 

// Fetch Stats for KPI Cards
$mydb->setQuery("SELECT COUNT(*) as total FROM `tblproduct`");
$total_products = $mydb->loadSingleResult()->total;

$mydb->setQuery("SELECT COUNT(*) as total FROM `tblproduct` WHERE PROQTY < 10 AND PROQTY > 0");
$low_stock = $mydb->loadSingleResult()->total;

$mydb->setQuery("SELECT COUNT(*) as total FROM `tblproduct` WHERE PROQTY <= 0");
$out_of_stock = $mydb->loadSingleResult()->total;

$mydb->setQuery("SELECT COUNT(*) as total FROM `tblcategory`");
$total_categories = $mydb->loadSingleResult()->total;
?>

<style>
/* Custom Styles for Modern Products Page */
.page-title-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 25px;
}
.page-title-row h1 {
    font-weight: 800;
    color: var(--primary);
    font-size: 28px;
    margin: 0 0 5px 0;
}
.page-title-row p {
    color: var(--text-muted);
    margin: 0;
    font-size: 14px;
}
.header-actions-btn {
    display: flex;
    gap: 12px;
}
.btn-export {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    color: var(--text-main);
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
}
.btn-export:hover {
    background: var(--bg-color);
}
.btn-new-prod {
    background: var(--primary-light);
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
}
.btn-new-prod:hover {
    background: var(--primary);
    color: white;
}

/* KPI Cards */
.kpi-row {
    display: flex;
    gap: 20px;
    margin-bottom: 25px;
}
.kpi-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 20px;
    flex: 1;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.kpi-info h4 {
    margin: 0 0 5px 0;
    font-size: 12px;
    text-transform: uppercase;
    color: var(--text-muted);
    font-weight: 600;
    letter-spacing: 0.5px;
}
.kpi-info h2 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
    color: var(--primary);
}
.kpi-icon {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

/* Table Card */
.table-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 30px;
}
.table-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}
.table-tabs {
    display: flex;
    background: var(--bg-color);
    padding: 4px;
    border-radius: 8px;
}
.table-tabs span {
    padding: 6px 16px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    color: var(--text-muted);
}
.table-tabs span.active {
    background: var(--card-bg);
    color: var(--text-main);
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.modern-table {
    width: 100%;
    border-collapse: collapse;
}
.modern-table th {
    text-transform: uppercase;
    font-size: 11px;
    font-weight: 600;
    color: var(--text-muted);
    padding: 15px 10px;
    border-bottom: 1px solid var(--border-color);
    text-align: left;
}
.modern-table td {
    padding: 15px 10px;
    border-bottom: 1px solid var(--border-color);
    vertical-align: middle;
}
.modern-table tbody tr:hover {
    background-color: #f8fafc;
}
.prod-img {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    object-fit: cover;
    border: 1px solid var(--border-color);
}
.prod-name {
    font-weight: 700;
    color: var(--primary-light);
    font-size: 14px;
    display: block;
    text-decoration: none;
}
.prod-sku {
    font-size: 11px;
    color: var(--text-muted);
}
.cat-badge {
    background: #e2e8f0;
    color: var(--text-main);
    padding: 4px 10px;
    border-radius: 15px;
    font-size: 11px;
    font-weight: 600;
}
.desc-text {
    font-size: 12px;
    color: var(--text-muted);
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;  
    overflow: hidden;
}
</style>

<!-- Title Row -->
<div class="page-title-row">
    <div>
        <h1>Products Management</h1>
        <p>Manage inventory, pricing, and product details across the platform.</p>
    </div>
    <div class="header-actions-btn">
        <a href="#" class="btn-export"><i class="fa fa-download"></i> Export CSV</a>
        <a href="index.php?view=add" class="btn-new-prod"><i class="fa fa-plus"></i> New Product</a>
    </div>
</div>

<!-- KPI Cards -->
<div class="kpi-row">
    <div class="kpi-card">
        <div class="kpi-info">
            <h4>Total Products</h4>
            <h2><?php echo number_format($total_products); ?></h2>
        </div>
        <div class="kpi-icon" style="background: #e0e7ff; color: #4338ca;">
            <i class="fa fa-archive"></i>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-info">
            <h4>Low Stock</h4>
            <h2 style="color: #d97706;"><?php echo number_format($low_stock); ?></h2>
        </div>
        <div class="kpi-icon" style="background: #fef3c7; color: #d97706;">
            <i class="fa fa-exclamation-triangle"></i>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-info">
            <h4>Out of Stock</h4>
            <h2 style="color: #dc2626;"><?php echo number_format($out_of_stock); ?></h2>
        </div>
        <div class="kpi-icon" style="background: #fee2e2; color: #dc2626;">
            <i class="fa fa-exclamation-circle"></i>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-info">
            <h4>Categories</h4>
            <h2><?php echo number_format($total_categories); ?></h2>
        </div>
        <div class="kpi-icon" style="background: #f1f5f9; color: #64748b;">
            <i class="fa fa-th-large"></i>
        </div>
    </div>
</div>

<!-- Table Area -->
<form action="controller.php?action=delete" Method="POST">
    <div class="table-card">
        <div class="table-toolbar">
            <div class="table-tabs">
                <?php
                $status = isset($_GET['status']) ₹ $_GET['status'] : 'Active';
                ?>
                <a href="index.php?status=Active" style="text-decoration:none;"><span class="<?php echo ($status == 'Active') ₹ 'active' : ''; ?>">Active</span></a>
                <a href="index.php?status=Draft" style="text-decoration:none;"><span class="<?php echo ($status == 'Draft') ₹ 'active' : ''; ?>">Draft</span></a>
                <a href="index.php?status=Archived" style="text-decoration:none;"><span class="<?php echo ($status == 'Archived') ₹ 'active' : ''; ?>">Archived</span></a>
            </div>
            <div>
                <button type="submit" class="btn btn-danger btn-sm" name="delete" style="border-radius: 8px;"><i class="fa fa-trash"></i> Delete Selected</button>
            </div>
        </div>

        <div class="table-responsive">
            <table id="dash-table" class="modern-table">
                <thead>
                    <tr>
                        <th width="1%"><input type="checkbox" id="chkall" onclick="return checkall('selector[]');"></th>
                        <th width="60">Image</th>
                        <th width="200">Product Name</th>
                        <th width="120">Category</th>
                        <th width="100">Status</th>
                        <th width="300">Description</th>
                        <th width="100">Price</th>
                        <th width="80">Disc. %</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $status = isset($_GET['status']) ₹ $_GET['status'] : 'Active';
                    $db_status = 'Available';
                    if ($status == 'Draft') {
                        $db_status = 'NotAvailable';
                    } elseif ($status == 'Archived') {
                        $db_status = 'Archived';
                    }

                    $query = "SELECT * FROM `tblpromopro` pr, `tblproduct` p, `tblcategory` c 
                              WHERE pr.`PROID`=p.`PROID` AND p.`CATEGID` = c.`CATEGID` 
                                AND p.PROSTATS = '{$db_status}' 
                              ORDER BY p.PROID DESC";
                    $mydb->setQuery($query);
                    $cur = $mydb->loadResultList();

                    foreach ($cur as $result) { 
                        $img = web_root.'admin/products/'.$result->IMAGES;
                    ?>
                    <tr>
                        <td><input type="checkbox" name="selector[]" id="selector[]" value="<?php echo $result->PROID; ?>"/></td>
                        <td>
                            <a class="PROID" href="" data-target="#productmodal" data-toggle="modal" data-id="<?php echo $result->PROID; ?>">
                                <img class="prod-img" src="<?php echo $img; ?>" alt="img" onerror="this.src='<?php echo web_root; ?>images/default.jpg'">
                            </a>
                        </td>
                        <td>
                            <a class="prod-name" href="index.php?view=edit&id=<?php echo $result->PROID; ?>"><?php echo $result->PRODESC; ?></a>
                            <span class="prod-sku">SKU: HM-<?php echo substr(md5($result->PROID), 0, 6); ?></span>
                        </td>
                        <td>
                            <span class="cat-badge"><?php echo $result->CATEGORIES; ?></span>
                        </td>
                        <td>
                            <?php 
                            if ($result->PROSTATS == 'Available') {
                                echo '<span class="cat-badge" style="background:#e8f5e9; color:#2e7d32; font-weight:700;">Active</span>';
                            } elseif ($result->PROSTATS == 'NotAvailable') {
                                echo '<span class="cat-badge" style="background:#fff8e1; color:#f57f17; font-weight:700;">Draft</span>';
                            } elseif ($result->PROSTATS == 'Archived') {
                                echo '<span class="cat-badge" style="background:#ffebee; color:#c62828; font-weight:700;">Archived</span>';
                            } else {
                                echo '<span class="cat-badge" style="background:#f5f5f5; color:#616161; font-weight:700;">' . htmlspecialchars($result->PROSTATS) . '</span>';
                            }
                            ?>
                        </td>
                        <td>
                            <span class="desc-text"><?php echo strip_tags($result->PRODESC); ?></span>
                        </td>
                        <td>
                            <strong style="color: var(--text-main); font-size: 14px;">₹ <?php echo number_format($result->PROPRICE, 2); ?></strong>
                        </td>
                        <td>
                            <?php if($result->PRODISCOUNT > 0): ?>
                                <span style="color: #dc2626; font-weight: 600; font-size: 13px;"><?php echo number_format($result->PRODISCOUNT, 0); ?>%</span>
                            <?php else: ?>
                                <span style="color: var(--text-muted);">&mdash;</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</form>

<!-- Modal for Image Upload -->
<div class="modal fade" id="productmodal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 12px; overflow: hidden; border: none;">
            <div class="modal-header" style="background: var(--card-bg, white); border-bottom: 1px solid var(--border-color);">
                <button class="close" data-dismiss="modal" type="button">×</button>
                <h4 class="modal-title" style="font-weight: 700; color: var(--primary);">Update Product Image</h4>
            </div>
            <form action="controller.php?action=photos" enctype="multipart/form-data" method="post">
                <div class="modal-body" style="background: var(--bg-color);">
                    <div class="form-group">
                        <input class="proid" type="hidden" name="proid" id="proid" value="">
                        <input name="MAX_FILE_SIZE" type="hidden" value="1000000"> 
                        <label style="font-size: 12px; color: var(--text-muted); text-transform: uppercase;">Select New Image</label>
                        <input id="photo" name="photo" type="file" class="form-control" style="border-radius: 8px;">
                    </div>
                </div>
                <div class="modal-footer" style="background: var(--card-bg, white); border-top: 1px solid var(--border-color);">
                    <button class="btn btn-default" data-dismiss="modal" type="button" style="border-radius: 8px;">Cancel</button> 
                    <button class="btn btn-primary" name="savephoto" type="submit" style="border-radius: 8px; background: var(--primary-light);">Upload Photo</button>
                </div>
            </form>
        </div>
    </div>
</div>