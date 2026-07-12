<?php 
    if (!isset($_SESSION['USERID'])){
        redirect(web_root."admin/login.php");
    } 
?>

<style>
/* ── Report Page Styles ─────────────────────────────────── */
.report-filter-card {
    background: var(--card-bg, #fff);
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 14px;
    padding: 24px 28px;
    margin-bottom: 28px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    transition: background 0.25s, border-color 0.25s;
}
.report-filter-card h5 {
    margin: 0 0 18px 0;
    font-size: 14px;
    font-weight: 700;
    color: var(--text-muted, #64748b);
    text-transform: uppercase;
    letter-spacing: 0.8px;
}
.filter-row {
    display: flex;
    align-items: flex-end;
    gap: 16px;
    flex-wrap: wrap;
}
.filter-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.filter-group label {
    font-size: 12px;
    font-weight: 600;
    color: var(--text-muted, #64748b);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.filter-group input[type="date"] {
    padding: 10px 14px;
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 8px;
    font-size: 14px;
    color: var(--text-main, #1e293b);
    background: var(--bg-color, #f8fafc);
    outline: none;
    font-family: 'Inter', sans-serif;
    min-width: 180px;
    transition: border-color 0.2s, background 0.2s;
}
.filter-group input[type="date"]:focus {
    border-color: #1e3a8a;
    background: var(--card-bg, #fff);
    box-shadow: 0 0 0 3px rgba(30,58,138,0.08);
}
.btn-search {
    padding: 10px 24px;
    background: #1e3a8a;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: background 0.2s;
    font-family: 'Inter', sans-serif;
}
.btn-search:hover { background: #1e40af; }

.btn-reset {
    padding: 10px 18px;
    background: var(--bg-color, #f1f5f9);
    color: var(--text-muted, #475569);
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    font-family: 'Inter', sans-serif;
    transition: background 0.2s;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 6px;
}
.btn-reset:hover { background: var(--hover-bg, #e2e8f0); color: var(--text-main, #1e293b); text-decoration: none; }

/* Stat Cards */
.stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 28px;
}
.stat-card {
    background: var(--card-bg, #fff);
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 14px;
    padding: 22px 24px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    transition: background 0.25s, border-color 0.25s;
}
.stat-card .stat-label {
    font-size: 11px;
    font-weight: 700;
    color: var(--text-muted, #94a3b8);
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin-bottom: 8px;
}
.stat-card .stat-value {
    font-size: 26px;
    font-weight: 800;
    color: var(--text-main, #0f172a);
    line-height: 1;
    margin-bottom: 4px;
}
.stat-card .stat-sub {
    font-size: 12px;
    color: var(--text-muted, #94a3b8);
}
.stat-card.revenue .stat-value { color: #1e3a8a; }
.stat-card.orders  .stat-value { color: #0369a1; }
.stat-card.qty     .stat-value { color: #0f766e; }
.stat-card.avg     .stat-value { color: #7c3aed; }

/* Table Card */
.report-table-card {
    background: var(--card-bg, #fff);
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 14px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    overflow: hidden;
    margin-bottom: 24px;
    transition: background 0.25s, border-color 0.25s;
}
.report-table-card .table-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px;
    border-bottom: 1px solid var(--border-color, #f1f5f9);
}
.report-table-card .table-header h4 {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
    color: var(--text-main, #0f172a);
}
.report-table-card .table-header .date-range-label {
    font-size: 13px;
    color: var(--text-muted, #64748b);
    background: var(--bg-color, #f1f5f9);
    padding: 5px 12px;
    border-radius: 20px;
}
.report-table {
    width: 100%;
    border-collapse: collapse;
}
.report-table thead th {
    background: var(--table-header-bg, #f8fafc);
    padding: 13px 18px;
    text-align: left;
    font-size: 11px;
    font-weight: 700;
    color: var(--text-muted, #64748b);
    text-transform: uppercase;
    letter-spacing: 0.6px;
    border-bottom: 1px solid var(--border-color, #e2e8f0);
}
.report-table tbody tr {
    border-bottom: 1px solid var(--border-color, #f1f5f9);
    transition: background 0.15s;
}
.report-table tbody tr:hover { background: var(--hover-bg, #f8fafc); }
.report-table tbody td {
    padding: 14px 18px;
    font-size: 14px;
    color: var(--text-main, #334155);
    vertical-align: middle;
}
.report-table tbody td:last-child { font-weight: 600; color: var(--text-main, #0f172a); }
.report-table tfoot td {
    padding: 14px 18px;
    font-weight: 700;
    font-size: 14px;
    background: var(--table-header-bg, #f8fafc);
    border-top: 2px solid var(--border-color, #e2e8f0);
    color: var(--text-main, #0f172a);
}
.report-table tfoot td.revenue-total { color: #1e3a8a; font-size: 16px; }

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #94a3b8;
}
.empty-state i { font-size: 48px; margin-bottom: 16px; display: block; }
.empty-state h4 { font-size: 18px; font-weight: 700; color: #475569; margin: 0 0 8px; }
.empty-state p { font-size: 14px; margin: 0; }

.product-pill {
    display: inline-block;
    background: #eff6ff;
    color: #1e3a8a;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}
.price-tag { font-family: monospace; font-size: 13px; }

/* Action Bar */
.report-actions {
    display: flex;
    gap: 12px;
    margin-top: 8px;
}
.btn-print {
    padding: 10px 22px;
    background: #0f172a;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    font-family: 'Inter', sans-serif;
    transition: background 0.2s;
}
.btn-print:hover { background: #1e293b; }
.btn-export {
    padding: 10px 22px;
    background: #f1f5f9;
    color: #334155;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    font-family: 'Inter', sans-serif;
    transition: background 0.2s;
}
.btn-export:hover { background: #e2e8f0; }

@media print {
    .report-filter-card, .report-actions, .admin-sidebar, .admin-header { display: none !important; }
    .admin-content-wrapper { padding: 0 !important; }
    .report-table-card { box-shadow: none; border: none; }
}
</style>

<?php
/* ── Backend Query ─────────────────────────────────────── */
$searched    = false;
$totRevenue  = 0;
$totCapital  = 0;
$totQty      = 0;
$totOrders   = 0;
$results     = [];
$date_from   = '';
$date_to     = '';

if (isset($_POST['submit'])) {
    $searched  = true;
    $date_from = trim($_POST['date_from'] ?₹ '');
    $date_to   = trim($_POST['date_to']   ?₹ '');

    if ($date_from && $date_to) {
        /* Join: product → promopro → order → summary → customer
           Group by product description so we get one row per product
           with summed quantity                                        */
        $query = "
            SELECT
                p.PRODESC,
                p.ORIGINALprice,
                p.PROPRICE,
                SUM(o.ORDEREDQTY)   AS QTY,
                MIN(s.ORDEREDDATE)  AS FIRST_ORDER,
                MAX(s.ORDEREDDATE)  AS LAST_ORDER
            FROM tblproduct   p
            JOIN tblpromopro  pr ON p.PROID       = pr.PROID
            JOIN tblorder     o  ON pr.PROID       = o.PROID
            JOIN tblsummary   s  ON o.ORDEREDNUM   = s.ORDEREDNUM
            WHERE DATE(s.ORDEREDDATE) >= '$date_from'
              AND DATE(s.ORDEREDDATE) <= '$date_to'
            GROUP BY p.PROID, p.PRODESC, p.ORIGINALprice, p.PROPRICE
            ORDER BY QTY DESC
        ";
        $mydb->setQuery($query);
        $results   = $mydb->loadResultList();
        $totOrders = count($results);

        foreach ($results as $r) {
            $totCapital += $r->ORIGINALPRICE ?₹ 0;
            $totRevenue += $r->PROPRICE * $r->QTY;
            $totQty     += $r->QTY;
        }
        $avgOrder = $totOrders > 0 ₹ round($totRevenue / $totOrders, 2) : 0;
    }
}
$avgOrder = isset($avgOrder) ₹ $avgOrder : 0;
?>

<!-- Title Row -->
<div class="page-title-row" style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:25px;">
    <div>
        <h1 style="font-weight:800; color:var(--primary); font-size:28px; margin:0 0 5px 0;">Sales Reports</h1>
        <p style="color:var(--text-muted); margin:0; font-size:14px;">Generate detailed sales, revenue, and product velocity reports.</p>
    </div>
</div>

<!-- Filter Card -->
<div class="report-filter-card">
    <h5><i class="fa fa-filter" style="margin-right:8px;"></i>Date Range Filter</h5>
    <form method="post" action="">
        <div class="filter-row">
            <div class="filter-group">
                <label>From Date</label>
                <input type="date" name="date_from" id="date_from"
                       value="<?php echo htmlspecialchars($date_from); ?>" required>
            </div>
            <div class="filter-group">
                <label>To Date</label>
                <input type="date" name="date_to" id="date_to"
                       value="<?php echo htmlspecialchars($date_to); ?>" required>
            </div>
            <button type="submit" name="submit" class="btn-search">
                <i class="fa fa-search"></i> Generate Report
            </button>
            <a href="index.php" class="btn-reset">
                <i class="fa fa-refresh"></i> Reset
            </a>
        </div>
    </form>
</div>

<?php if ($searched && $date_from && $date_to): ?>

<!-- Stats Row -->
<div class="stats-row">
    <div class="stat-card revenue">
        <div class="stat-label">Total Revenue</div>
        <div class="stat-value">₹<?php echo number_format($totRevenue, 2); ?></div>
        <div class="stat-sub">Selling price × qty</div>
    </div>
    <div class="stat-card orders">
        <div class="stat-label">Products Sold</div>
        <div class="stat-value"><?php echo $totOrders; ?></div>
        <div class="stat-sub">Unique product lines</div>
    </div>
    <div class="stat-card qty">
        <div class="stat-label">Total Units</div>
        <div class="stat-value"><?php echo number_format($totQty); ?></div>
        <div class="stat-sub">Total quantity ordered</div>
    </div>
    <div class="stat-card avg">
        <div class="stat-label">Avg Revenue/Product</div>
        <div class="stat-value">₹<?php echo number_format($avgOrder, 2); ?></div>
        <div class="stat-sub">Revenue ÷ product lines</div>
    </div>
</div>

<?php endif; ?>

<!-- Report Table -->
<div id="printout">
<div class="report-table-card">
    <div class="table-header">
        <h4><i class="fa fa-bar-chart" style="margin-right:8px; color:#1e3a8a;"></i>
            Sales Report — List of Ordered Products
        </h4>
        <?php if ($searched && $date_from && $date_to): ?>
        <span class="date-range-label">
            <i class="fa fa-calendar"></i>
            <?php echo date('M d, Y', strtotime($date_from)); ?>
            &nbsp;&rarr;&nbsp;
            <?php echo date('M d, Y', strtotime($date_to)); ?>
        </span>
        <?php endif; ?>
    </div>

    <table class="report-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Product Name</th>
                <th>Original Price</th>
                <th>Selling Price</th>
                <th>Units Sold</th>
                <th>Sub-Total Revenue</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!$searched): ?>
            <tr>
                <td colspan="6">
                    <div class="empty-state">
                        <i class="fa fa-calendar-o"></i>
                        <h4>Select a Date Range</h4>
                        <p>Choose a From and To date above, then click "Generate Report" to view your sales data.</p>
                    </div>
                </td>
            </tr>
        <?php elseif (empty($results)): ?>
            <tr>
                <td colspan="6">
                    <div class="empty-state">
                        <i class="fa fa-inbox"></i>
                        <h4>No Orders Found</h4>
                        <p>No completed orders were found between
                            <strong><?php echo date('M d, Y', strtotime($date_from)); ?></strong>
                            and
                            <strong><?php echo date('M d, Y', strtotime($date_to)); ?></strong>.
                        </p>
                    </div>
                </td>
            </tr>
        <?php else:
            $i = 1;
            foreach ($results as $r):
                $subtotal = $r->PROPRICE * $r->QTY;
        ?>
            <tr>
                <td style="color:#94a3b8; font-size:12px;"><?php echo $i++; ?></td>
                <td><span class="product-pill"><?php echo htmlspecialchars($r->PRODESC); ?></span></td>
                <td class="price-tag">₹<?php echo number_format($r->ORIGINALPRICE ?₹ 0, 2); ?></td>
                <td class="price-tag">₹<?php echo number_format($r->PROPRICE, 2); ?></td>
                <td><strong><?php echo number_format($r->QTY); ?></strong></td>
                <td>₹<?php echo number_format($subtotal, 2); ?></td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
        <?php if ($searched && !empty($results)): ?>
        <tfoot>
            <tr>
                <td colspan="2"><strong>TOTALS</strong></td>
                <td>—</td>
                <td>—</td>
                <td><strong><?php echo number_format($totQty); ?> units</strong></td>
                <td class="revenue-total">₹<?php echo number_format($totRevenue, 2); ?></td>
            </tr>
        </tfoot>
        <?php endif; ?>
    </table>
</div>
</div>

<!-- Action Buttons -->
<?php if ($searched && !empty($results)): ?>
<div class="report-actions">
    <button onclick="printReport()" class="btn-print">
        <i class="fa fa-print"></i> Print Report
    </button>
    <button onclick="exportCSV()" class="btn-export">
        <i class="fa fa-download"></i> Export CSV
    </button>
</div>
<?php endif; ?>

<script>
function printReport() {
    var content = document.getElementById('printout').innerHTML;
    var win = window.open('', '', 'width=900,height=700,toolbar=no,menubar=no,scrollbars=yes');
    win.document.open();
    win.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>H-Mart Sales Report</title>
            <style>
                body { font-family: Arial, sans-serif; font-size: 13px; color: #1e293b; padding: 30px; }
                .table-header { display: flex; justify-content: space-between; margin-bottom: 20px; }
                .table-header h4 { font-size: 18px; font-weight: 800; }
                .report-table { width: 100%; border-collapse: collapse; }
                .report-table th { background: #f1f5f9; padding: 10px; text-align: left; font-size: 11px; text-transform: uppercase; border: 1px solid #e2e8f0; }
                .report-table td { padding: 10px; border: 1px solid #e2e8f0; }
                .report-table tfoot td { background: #f8fafc; font-weight: bold; }
                .product-pill { background: none; color: inherit; padding: 0; }
                h2 { color: #0f172a; }
            </style>
        </head>
        <body>
            <h2>H-Mart &mdash; Sales Report</h2>
            ${content}
        </body>
        </html>
    `);
    win.document.close();
    win.print();
}

function exportCSV() {
    var rows = [['#', 'Product', 'Original Price', 'Selling Price', 'Units Sold', 'Sub-Total']];
    document.querySelectorAll('.report-table tbody tr').forEach(function(tr, i) {
        var cells = tr.querySelectorAll('td');
        if (cells.length < 6) return;
        rows.push([
            i + 1,
            cells[1].textContent.trim(),
            cells[2].textContent.trim(),
            cells[3].textContent.trim(),
            cells[4].textContent.trim(),
            cells[5].textContent.trim()
        ]);
    });
    var csv = rows.map(function(r) {
        return r.map(function(c) { return '"' + c + '"'; }).join(',');
    }).join('\n');
    var a = document.createElement('a');
    a.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv);
    a.download = 'hmart_sales_report.csv';
    a.click();
}
</script>