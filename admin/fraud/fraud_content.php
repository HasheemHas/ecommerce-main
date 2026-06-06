<!-- Title Row -->
<style>
.fraud-stat-card {
    background: var(--card-bg, #fff);
    padding: 16px;
    border-radius: 10px;
    border: 1px solid var(--border-color, #e2e8f0);
    transition: background 0.25s, border-color 0.25s;
}
.fraud-table-wrap {
    background: var(--card-bg, #fff);
    border-radius: 12px;
    border: 1px solid var(--border-color, #e2e8f0);
    padding: 16px;
    transition: background 0.25s, border-color 0.25s;
}
</style>
<div class="page-title-row" style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:25px;">
    <div>
        <h1 style="font-weight:800; color:var(--primary); font-size:28px; margin:0 0 5px 0;">Fraud Detection & Monitoring</h1>
        <p style="color:var(--text-muted); margin:0; font-size:14px;">ML-driven real-time analysis of transaction risks, brute-force logs, and payment anomalies.</p>
    </div>
</div>

<div class="row" style="margin-bottom:20px;">
    <div class="col-md-4"><div class="fraud-stat-card"><strong style="font-size:24px;color:#ef4444;"><?php echo count($alerts); ?></strong><br><span style="color:var(--text-muted,#64748b);">Open Alerts</span></div></div>
    <div class="col-md-4"><div class="fraud-stat-card"><strong style="font-size:24px;color:var(--text-main,#1e293b);"><?php echo $failedLogins24h; ?></strong><br><span style="color:var(--text-muted,#64748b);">Failed Logins (24h)</span></div></div>
    <div class="col-md-4"><div class="fraud-stat-card"><strong style="font-size:24px;color:var(--text-main,#1e293b);"><?php echo $failedPayments24h; ?></strong><br><span style="color:var(--text-muted,#64748b);">Failed Payments (24h)</span></div></div>
</div>

<div class="fraud-table-wrap">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Severity</th>
                <th>Type</th>
                <th>Customer</th>
                <th>Description</th>
                <th>Time</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($alerts)) { ?>
            <tr><td colspan="7" class="text-center text-muted">No active fraud alerts. System is monitoring login &amp; payment patterns.</td></tr>
        <?php } else { foreach ($alerts as $a) {
            $sevClass = $a->SEVERITY === 'high' ? 'danger' : ($a->SEVERITY === 'medium' ? 'warning' : 'info');
            $cust = $a->CUSTOMERID ? ($a->FNAME . ' ' . $a->LNAME . ' (' . $a->CUSUNAME . ')') : '—';
        ?>
            <tr>
                <td><?php echo (int) $a->ALERT_ID; ?></td>
                <td><span class="label label-<?php echo $sevClass; ?>"><?php echo strtoupper($a->SEVERITY); ?></span></td>
                <td><?php echo htmlspecialchars($a->ALERT_TYPE); ?></td>
                <td><?php echo htmlspecialchars($cust); ?></td>
                <td><?php echo htmlspecialchars($a->DESCRIPTION); ?></td>
                <td><?php echo date('M d, Y H:i', strtotime($a->CREATED_AT)); ?></td>
                <td><a class="btn btn-sm btn-success" href="index.php?resolve=<?php echo (int) $a->ALERT_ID; ?>">Resolve</a></td>
            </tr>
        <?php } } ?>
        </tbody>
    </table>
</div>

<p class="text-muted" style="margin-top:16px;font-size:13px;">
    <i class="fa fa-shield"></i> ML fraud rules: brute-force login (5+ fails/15min), repeated failed payments, unusual order value, rapid multi-orders.
</p>
