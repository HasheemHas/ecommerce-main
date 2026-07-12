<?php check_message(); ?>

<style>
/* ── Settings Page Styles ─────────────────────────── */
.settings-tabs {
    display: flex;
    gap: 8px;
    margin-bottom: 24px;
    border-bottom: 2px solid #e2e8f0;
    padding-bottom: 0;
}
.settings-tab-btn {
    padding: 10px 22px;
    border: none;
    background: none;
    font-family: 'Inter', sans-serif;
    font-size: 14px;
    font-weight: 600;
    color: #64748b;
    cursor: pointer;
    border-bottom: 2px solid transparent;
    margin-bottom: -2px;
    transition: all 0.2s;
}
.settings-tab-btn.active { color: #1e3a8a; border-bottom-color: #1e3a8a; }
.settings-tab-btn:hover:not(.active) { color: #334155; }

.settings-section { display: none; }
.settings-section.active { display: block; }

/* Search bar */
.settings-search-bar {
    display: flex;
    align-items: center;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 10px 16px;
    gap: 10px;
    margin-bottom: 20px;
    max-width: 420px;
}
.settings-search-bar i { color: #94a3b8; font-size: 15px; }
.settings-search-bar input {
    border: none;
    background: transparent;
    font-family: 'Inter', sans-serif;
    font-size: 14px;
    color: #1e293b;
    outline: none;
    width: 100%;
}
.settings-search-bar input::placeholder { color: #94a3b8; }

/* Product table */
.settings-table-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.settings-table {
    width: 100%;
    border-collapse: collapse;
}
.settings-table thead th {
    background: #f8fafc;
    padding: 12px 18px;
    text-align: left;
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    border-bottom: 1px solid #e2e8f0;
}
.settings-table tbody tr {
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.15s;
}
.settings-table tbody tr:hover { background: #f8fafc; }
.settings-table tbody td {
    padding: 14px 18px;
    font-size: 13px;
    color: #334155;
    vertical-align: middle;
}
.product-name-cell {
    font-weight: 600;
    color: #0f172a;
    max-width: 300px;
}
.category-pill {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    background: #f0fdf4;
    color: #15803d;
}
.price-cell { font-weight: 700; color: #0f172a; font-family: monospace; }

/* Action Buttons */
.action-group { display: flex; gap: 6px; align-items: center; }
.btn-avail {
    padding: 5px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-family: 'Inter', sans-serif;
    transition: all 0.2s;
}
.btn-avail.available {
    background: #dcfce7;
    color: #166534;
}
.btn-avail.available:hover { background: #166534; color: #fff; }
.btn-avail.notavail {
    background: #fee2e2;
    color: #991b1b;
}
.btn-avail.notavail:hover { background: #991b1b; color: #fff; }
.btn-discount {
    padding: 5px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    background: #1e3a8a;
    color: #fff;
    border: none;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-family: 'Inter', sans-serif;
    transition: background 0.2s;
}
.btn-discount:hover { background: #1e40af; color: #fff; text-decoration: none; }

.no-results {
    text-align: center;
    padding: 50px 20px;
    color: #94a3b8;
}
.no-results i { font-size: 36px; margin-bottom: 12px; display: block; }

/* Delivery section placeholder */
.delivery-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    padding: 24px;
}

.btn-delete-location {
    padding: 5px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    background: #fee2e2;
    color: #dc2626;
    border: none;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-family: 'Inter', sans-serif;
    transition: all 0.2s;
}
.btn-delete-location:hover {
    background: #dc2626;
    color: #fff;
    text-decoration: none;
}

/* DataTables Premium Styling */
.dataTables_wrapper {
    padding: 0;
}
.dataTables_length {
    margin-bottom: 20px;
    float: left;
    color: #64748b;
    font-size: 13px;
    font-family: 'Inter', sans-serif;
}
.dataTables_length select {
    padding: 6px 10px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    outline: none;
    color: #334155;
    background: #f8fafc;
    font-weight: 600;
    margin: 0 4px;
}
.dataTables_filter {
    margin-bottom: 20px;
    float: right;
    color: #64748b;
    font-size: 13px;
    font-family: 'Inter', sans-serif;
}
.dataTables_filter input {
    padding: 8px 14px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    outline: none;
    color: #1e293b;
    background: #f8fafc;
    margin-left: 8px;
    transition: all 0.2s;
    font-weight: 500;
}
.dataTables_filter input:focus {
    border-color: #1e3a8a;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.08);
}
.dataTables_info {
    float: left;
    padding-top: 15px;
    color: #64748b;
    font-size: 13px;
    font-family: 'Inter', sans-serif;
}
.dataTables_paginate {
    float: right;
    padding-top: 12px;
    font-family: 'Inter', sans-serif;
}
.dataTables_paginate .paginate_button {
    padding: 6px 12px;
    margin-left: 4px;
    border: 1px solid #e2e8f0 !important;
    border-radius: 8px !important;
    background: white !important;
    color: #475569 !important;
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.2s;
}
.dataTables_paginate .paginate_button:hover {
    background: #f1f5f9 !important;
    color: #0f172a !important;
    border-color: #cbd5e1 !important;
}
.dataTables_paginate .paginate_button.current {
    background: #1e3a8a !important;
    color: white !important;
    border-color: #1e3a8a !important;
}
.dataTables_paginate .paginate_button.disabled {
    color: #94a3b8 !important;
    cursor: not-allowed;
    background: #f8fafc !important;
    border-color: #e2e8f0 !important;
}
</style>
<!-- Title Row -->
<div class="page-title-row" style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:25px;">
    <div>
        <h1 style="font-weight:800; color:var(--primary); font-size:28px; margin:0 0 5px 0;">Settings</h1>
        <p style="color:var(--text-muted); margin:0; font-size:14px;">Manage store settings, products availability, discounts, and delivery fees.</p>
    </div>
</div>

<!-- Tab Nav -->
<div class="settings-tabs">
    <button class="settings-tab-btn active" onclick="switchTab('products', this)">
        <i class="fa fa-archive" style="margin-right:6px;"></i> Products
    </button>
    <button class="settings-tab-btn" onclick="switchTab('delivery', this)">
        <i class="fa fa-truck" style="margin-right:6px;"></i> Delivery Fees
    </button>
</div>

<!-- ── PRODUCTS TAB ───────────────────────────────── -->
<div id="tab-products" class="settings-section active">

    <!-- Search -->
    <div class="settings-search-bar">
        <i class="fa fa-search"></i>
        <input type="text" id="productSearch" placeholder="Search products by name or category..." oninput="filterProducts()">
    </div>

    <div class="settings-table-card">
        <table class="settings-table" id="productsTable">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Discount</th>
                    <th>Final Price</th>
                    <th style="text-align:center;">Action</th>
                </tr>
            </thead>
            <tbody id="productsBody">
            <?php
                $query = "SELECT p.*, c.CATEGORIES, pr.PRODISCOUNT, pr.PRODISPRICE, p.PROSTATS, pr.PROID as PROID_PR
                          FROM tblproduct p
                          JOIN tblcategory c ON p.CATEGID = c.CATEGID
                          JOIN tblpromopro pr ON p.PROID = pr.PROID
                          ORDER BY c.CATEGORIES, p.PRODESC";
                $mydb->setQuery($query);
                $cur = $mydb->loadResultList();

                if (empty($cur)) {
                    echo '<tr><td colspan="6"><div class="no-results">
                            <i class="fa fa-inbox"></i>
                            <p>No products found.</p>
                          </div></td></tr>';
                }

                foreach ($cur as $r) {
                    $isAvail  = ($r->PROSTATS == 'Available');
                    $btnClass = $isAvail ₹ 'available' : 'notavail';
                    $btnLabel = $isAvail ₹ 'Available' : 'Unavailable';
                    $nextStat = $isAvail ₹ 'NotAvailable' : 'Available';
                    $discPct  = $r->PRODISCOUNT > 0 ₹ $r->PRODISCOUNT . '%' : '—';

                    echo '<tr class="product-row" 
                               data-name="' . strtolower(htmlspecialchars($r->PRODESC)) . '" 
                               data-cat="'  . strtolower(htmlspecialchars($r->CATEGORIES)) . '">';

                    echo '<td class="product-name-cell">' . htmlspecialchars($r->PRODESC) . '</td>';
                    echo '<td><span class="category-pill">' . htmlspecialchars($r->CATEGORIES) . '</span></td>';
                    echo '<td class="price-cell">?' . number_format($r->PROPRICE, 2) . '</td>';
                    echo '<td style="color:#64748b;">' . $discPct . '</td>';
                    echo '<td class="price-cell" style="color:#1e3a8a;">?' . number_format($r->PRODISPRICE, 2) . '</td>';

                    echo '<td>
                        <div class="action-group">
                            <a href="' . web_root . 'admin/settings/controller.php?action=editStatus&id=' . $r->PROID . '&stats=' . $nextStat . '"
                               class="btn-avail ' . $btnClass . '" title="Toggle availability">
                                <i class="fa fa-' . ($isAvail ₹ 'check' : 'times') . '"></i> ' . $btnLabel . '
                            </a>
                            <a href="index.php?view=discount&id=' . $r->PROID . '" class="btn-discount" title="Set Discount">
                                <i class="fa fa-tag"></i> Discount
                            </a>
                        </div>
                    </td>';

                    echo '</tr>';
                }
            ?>
            </tbody>
        </table>

        <div id="noResultsMsg" style="display:none;" class="no-results">
            <i class="fa fa-search"></i>
            <p>No products match your search.</p>
        </div>
    </div>
</div>

<!-- ── DELIVERY TAB ───────────────────────────────── -->
<div id="tab-delivery" class="settings-section">
    <div class="delivery-card">
        <?php include "listlocation.php"; ?>
    </div>
</div>

<script>
function switchTab(tab, btn) {
    document.querySelectorAll('.settings-section').forEach(s => s.classList.remove('active'));
    document.querySelectorAll('.settings-tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');
    btn.classList.add('active');
}

function filterProducts() {
    const q = document.getElementById('productSearch').value.toLowerCase().trim();
    const rows = document.querySelectorAll('#productsBody .product-row');
    let visible = 0;

    rows.forEach(row => {
        const name = row.dataset.name || '';
        const cat  = row.dataset.cat  || '';
        const show = !q || name.includes(q) || cat.includes(q);
        row.style.display = show ₹ '' : 'none';
        if (show) visible++;
    });

    document.getElementById('noResultsMsg').style.display = (visible === 0 && q) ₹ 'block' : 'none';
}
</script>