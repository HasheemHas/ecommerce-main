<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.css">
<style>
.kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 28px; }
.kpi-card { background: var(--card-bg, #fff); border-radius: 12px; padding: 20px; border: 1px solid var(--border-color, #e2e8f0); transition: background 0.25s, border-color 0.25s; }
.kpi-card h3 { margin: 0; font-size: 28px; font-weight: 800; color: var(--primary-light, #1e3a8a); }
.kpi-card p { margin: 6px 0 0; font-size: 13px; color: var(--text-muted, #64748b); font-weight: 600; }
.chart-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px; }
.chart-box { background: var(--card-bg, #fff); border-radius: 12px; padding: 20px; border: 1px solid var(--border-color, #e2e8f0); transition: background 0.25s, border-color 0.25s; }
.chart-box h4 { margin: 0 0 16px; font-size: 16px; font-weight: 700; color: var(--text-main, #1e293b); }
</style>

<div class="kpi-grid">
    <div class="kpi-card"><h3><?php echo number_format($kpi->total_customers); ?></h3><p>Total Customers</p></div>
    <div class="kpi-card"><h3>₹<?php echo number_format($kpi->monthly_revenue, 2); ?></h3><p>Revenue (This Month)</p></div>
    <div class="kpi-card"><h3><?php echo $kpi->pending_orders; ?></h3><p>Pending Orders</p></div>
    <div class="kpi-card"><h3><?php echo $kpi->low_stock_count; ?></h3><p>Low Stock Items</p></div>
    <div class="kpi-card"><h3 style="color:#ef4444;"><?php echo $kpi->fraud_alerts; ?></h3><p>Open Fraud Alerts</p></div>
</div>

<div class="chart-grid">
    <div class="chart-box"><h4>Sales Performance (6 Months)</h4><canvas id="salesChart" height="220"></canvas></div>
    <div class="chart-box"><h4>Order Status</h4><canvas id="statusChart" height="220"></canvas></div>
    <div class="chart-box"><h4>Payment Methods</h4><canvas id="paymentChart" height="220"></canvas></div>
    <div class="chart-box"><h4>Top Products by Units Sold</h4><canvas id="topChart" height="220"></canvas></div>
    <div class="chart-box" style="grid-column: 1 / -1;"><h4>Customer Activity (Last 7 Days)</h4><canvas id="activityChart" height="100"></canvas></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
const chartOpts = { responsive: true, maintainAspectRatio: true, plugins: { legend: { position: 'bottom' } } };
new Chart(document.getElementById('salesChart'), {
    type: 'line',
    data: { labels: <?php echo json_encode($salesLabels); ?>, datasets: [{ label: 'Sales (₹)', data: <?php echo json_encode($salesData); ?>, borderColor: '#1e3a8a', backgroundColor: 'rgba(30,58,138,0.1)', fill: true, tension: 0.3 }] },
    options: chartOpts
});
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: { labels: <?php echo json_encode($statusLabels); ?>, datasets: [{ data: <?php echo json_encode($statusData); ?>, backgroundColor: ['#1e3a8a','#22c55e','#f59e0b','#ef4444','#94a3b8'] }] },
    options: chartOpts
});
new Chart(document.getElementById('paymentChart'), {
    type: 'bar',
    data: { labels: <?php echo json_encode($payLabels); ?>, datasets: [{ label: 'Orders', data: <?php echo json_encode($payData); ?>, backgroundColor: '#2563eb' }] },
    options: chartOpts
});
new Chart(document.getElementById('topChart'), {
    type: 'bar',
    data: { labels: <?php echo json_encode($topLabels); ?>, datasets: [{ label: 'Units', data: <?php echo json_encode($topData); ?>, backgroundColor: '#b45309' }] },
    options: { ...chartOpts, indexAxis: 'y' }
});
new Chart(document.getElementById('activityChart'), {
    type: 'line',
    data: { labels: <?php echo json_encode($activityLabels); ?>, datasets: [{ label: 'Active Customers', data: <?php echo json_encode($activityData); ?>, borderColor: '#22c55e', tension: 0.3 }] },
    options: chartOpts
});
</script>
