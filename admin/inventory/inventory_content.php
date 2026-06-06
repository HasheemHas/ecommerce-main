<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<style>
.inv-stat-card {
    background: var(--card-bg, #fff);
    padding: 16px;
    border-radius: 10px;
    border: 1px solid var(--border-color, #e2e8f0);
    transition: background 0.25s, border-color 0.25s;
}
.inv-stat-card strong { display: block; }
.inv-chart-box {
    background: var(--card-bg, #fff);
    border-radius: 12px;
    padding: 20px;
    border: 1px solid var(--border-color, #e2e8f0);
    margin-bottom: 24px;
    transition: background 0.25s, border-color 0.25s;
}
.inv-chart-box h4 { margin-top: 0; font-weight: 700; color: var(--text-main, #1e293b); }
.inv-table-wrap {
    background: var(--card-bg, #fff);
    border-radius: 10px;
    border: 1px solid var(--border-color, #e2e8f0);
    transition: background 0.25s, border-color 0.25s;
}
.inv-table-wrap-scroll {
    background: var(--card-bg, #fff);
    border-radius: 10px;
    border: 1px solid var(--border-color, #e2e8f0);
    max-height: 320px;
    overflow-y: auto;
    transition: background 0.25s, border-color 0.25s;
}
</style>
<?php
$fast = [];
$slow = [];
$labels = [];
$stockQty = [];
$soldQty = [];
$i = 0;
foreach ($movement as $m) {
    if ($i >= 10) break;
    $labels[] = mb_substr($m->PRODESC, 0, 15);
    $stockQty[] = (int) $m->PROQTY;
    $soldQty[] = (int) $m->units_sold;
    if ((int) $m->units_sold >= ML_FAST_MOVING_MIN_QTY) $fast[] = $m;
    if ((int) $m->units_sold === 0 && (int) $m->PROQTY > 0) $slow[] = $m;
    $i++;
}
?>
<!-- Title Row -->
<div class="page-title-row" style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:25px;">
    <div>
        <h1 style="font-weight:800; color:var(--primary); font-size:28px; margin:0 0 5px 0;">Inventory AI Analytics</h1>
        <p style="color:var(--text-muted); margin:0; font-size:14px;">AI-powered inventory forecasting, stock movements, and velocity analysis.</p>
    </div>
</div>

<div class="row" style="margin-bottom:20px;">
    <div class="col-md-4"><div class="inv-stat-card"><strong style="font-size:24px;color:#ef4444;"><?php echo count($lowStock); ?></strong>Low Stock (≤<?php echo ML_LOW_STOCK_THRESHOLD; ?>)</div></div>
    <div class="col-md-4"><div class="inv-stat-card"><strong style="font-size:24px;color:#22c55e;"><?php echo count($fast); ?></strong>Fast Moving</div></div>
    <div class="col-md-4"><div class="inv-stat-card"><strong style="font-size:24px;color:#f59e0b;"><?php echo count($slow); ?></strong>Slow Moving</div></div>
</div>

<div class="inv-chart-box">
    <h4>Stock vs Sales (Top 10 Products)</h4>
    <canvas id="invChart" height="120"></canvas>
</div>

<div class="row">
    <div class="col-md-6">
        <h4>Low Stock Alerts</h4>
        <div class="inv-table-wrap">
            <table class="table table-condensed">
                <thead><tr><th>Product</th><th>Category</th><th>Qty</th></tr></thead>
                <tbody>
                <?php if (empty($lowStock)) { ?><tr><td colspan="3" class="text-muted">All stock levels OK</td></tr>
                <?php } else { foreach ($lowStock as $p) { ?>
                    <tr class="danger"><td><?php echo htmlspecialchars($p->PRODESC); ?></td><td><?php echo $p->CATEGORIES; ?></td><td><strong><?php echo (int) $p->PROQTY; ?></strong></td></tr>
                <?php } } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-6">
        <h4>Recent Inventory Alerts</h4>
        <div class="inv-table-wrap-scroll">
            <table class="table table-condensed">
                <thead><tr><th>Type</th><th>Message</th></tr></thead>
                <tbody>
                <?php foreach ($alerts as $a) { ?>
                    <tr><td><span class="label label-default"><?php echo $a->ALERT_TYPE; ?></span></td><td style="font-size:12px;"><?php echo htmlspecialchars($a->MESSAGE); ?></td></tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
new Chart(document.getElementById('invChart'), {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [
            { label: 'In Stock', data: <?php echo json_encode($stockQty); ?>, backgroundColor: '#1e3a8a' },
            { label: 'Sold (<?php echo ML_FAST_MOVING_DAYS; ?>d)', data: <?php echo json_encode($soldQty); ?>, backgroundColor: '#22c55e' }
        ]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});
</script>
