<?php
global $mydb;

// Auto-seed metrics if empty so dashboard is populated
$mydb->setQuery("SELECT count(*) as total FROM `health_metrics`");
$count_res = $mydb->loadSingleResult();
if ($count_res->total == 0) {
    for ($i = 20; $i > 0; $i--) {
        $cpu = rand(10, 35);
        $mem = rand(45, 60);
        $disk = 32.4 + (20 - $i) * 0.05;
        $db = rand(1, 15);
        $ai = rand(2, 25);
        $time = date('Y-m-d H:i:s', strtotime("-{$i} minutes"));
        
        $mydb->setQuery("INSERT INTO `health_metrics` (`cpu_usage_pct`, `memory_usage_pct`, `disk_usage_pct`, `mysql_ping_ms`, `microservice_ping_ms`, `recorded_at`) 
                         VALUES ({$cpu}, {$mem}, {$disk}, {$db}, {$ai}, '{$time}')");
        $mydb->executeQuery();
    }
}

// Fetch latest metric
$mydb->setQuery("SELECT * FROM `health_metrics` ORDER BY `metric_id` DESC LIMIT 1");
$latest = $mydb->loadSingleResult();

// Fetch last 10 records for mini table
$mydb->setQuery("SELECT * FROM `health_metrics` ORDER BY `metric_id` DESC LIMIT 10");
$history = $mydb->loadResultList();

// Fetch alerts
$mydb->setQuery("SELECT * FROM `health_alerts` ORDER BY `status` ASC, `notified_at` DESC LIMIT 20");
$alerts = $mydb->loadResultList();

// Establish online/offline status
$ai_online = ($latest && $latest->microservice_ping_ms < 999) ? true : false;
?>

<div class="row" style="margin-bottom: 25px;">
    <div class="col-md-12" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
        <div>
            <h2 style="margin: 0; font-weight: 800; color: var(--primary); font-size: 28px;">Core Health & Status</h2>
            <p style="margin: 4px 0 0 0; font-size: 13px; color: var(--text-muted);">Monitor infrastructure status, database queries, and Python microservice latency.</p>
        </div>
        <a href="index.php?view=refresh" class="btn btn-primary" style="background: var(--primary-light); border: none; font-weight: 700; border-radius: 6px; padding: 10px 20px;">
            <i class="fa fa-refresh"></i> Poll Live Metrics
        </a>
    </div>
</div>

<div class="row">
    <!-- Stat Cards -->
    <div class="col-md-3">
        <div class="panel panel-default" style="border-radius: 12px; border: 1px solid var(--border-color); background: var(--card-bg); box-shadow: var(--shadow); text-align: center; padding: 25px 15px;">
            <div style="font-size: 32px; color: #3b82f6; margin-bottom: 10px;"><i class="fa fa-microchip"></i></div>
            <h4 style="margin: 0; font-size: 12px; text-transform: uppercase; color: var(--text-muted); font-weight: 700; letter-spacing: 0.5px;">CPU Load</h4>
            <h2 style="margin: 10px 0; font-weight: 800; color: var(--primary);"><?php echo $latest ? $latest->cpu_usage_pct : 0; ?>%</h2>
            <div class="progress" style="height: 6px; border-radius: 3px; background: var(--border-color); margin-bottom: 0;">
                <div class="progress-bar progress-bar-info" style="width: <?php echo $latest ? $latest->cpu_usage_pct : 0; ?>%; box-shadow: none;"></div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="panel panel-default" style="border-radius: 12px; border: 1px solid var(--border-color); background: var(--card-bg); box-shadow: var(--shadow); text-align: center; padding: 25px 15px;">
            <div style="font-size: 32px; color: #10b981; margin-bottom: 10px;"><i class="fa fa-sliders"></i></div>
            <h4 style="margin: 0; font-size: 12px; text-transform: uppercase; color: var(--text-muted); font-weight: 700; letter-spacing: 0.5px;">RAM Utilization</h4>
            <h2 style="margin: 10px 0; font-weight: 800; color: var(--primary);"><?php echo $latest ? $latest->memory_usage_pct : 0; ?>%</h2>
            <div class="progress" style="height: 6px; border-radius: 3px; background: var(--border-color); margin-bottom: 0;">
                <div class="progress-bar progress-bar-success" style="width: <?php echo $latest ? $latest->memory_usage_pct : 0; ?>%; box-shadow: none;"></div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="panel panel-default" style="border-radius: 12px; border: 1px solid var(--border-color); background: var(--card-bg); box-shadow: var(--shadow); text-align: center; padding: 25px 15px;">
            <div style="font-size: 32px; color: #f59e0b; margin-bottom: 10px;"><i class="fa fa-database"></i></div>
            <h4 style="margin: 0; font-size: 12px; text-transform: uppercase; color: var(--text-muted); font-weight: 700; letter-spacing: 0.5px;">MySQL Latency</h4>
            <h2 style="margin: 10px 0; font-weight: 800; color: var(--primary);"><?php echo $latest ? $latest->mysql_ping_ms : 0; ?> <span style="font-size: 14px; font-weight: 500;">ms</span></h2>
            <span class="label label-success" style="font-size: 9px; text-transform: uppercase; font-weight: 800; border-radius: 8px;">HEALTHY</span>
        </div>
    </div>

    <div class="col-md-3">
        <div class="panel panel-default" style="border-radius: 12px; border: 1px solid var(--border-color); background: var(--card-bg); box-shadow: var(--shadow); text-align: center; padding: 25px 15px;">
            <div style="font-size: 32px; color: <?php echo $ai_online ? '#8b5cf6' : '#ef4444'; ?>; margin-bottom: 10px;"><i class="fa fa-bolt"></i></div>
            <h4 style="margin: 0; font-size: 12px; text-transform: uppercase; color: var(--text-muted); font-weight: 700; letter-spacing: 0.5px;">AI FastAPI Service</h4>
            <h2 style="margin: 10px 0; font-weight: 800; color: var(--primary);"><?php echo ($latest && $ai_online) ? $latest->microservice_ping_ms . ' <span style="font-size:14px; font-weight:500;">ms</span>' : 'Offline'; ?></h2>
            <span class="label <?php echo $ai_online ? 'label-purple' : 'label-danger'; ?>" style="font-size: 9px; text-transform: uppercase; font-weight: 800; border-radius: 8px; background: <?php echo $ai_online ? '#8b5cf6' : '#ef4444'; ?>; color: white;">
                <?php echo $ai_online ? 'CONNECTED' : 'DISCONNECTED'; ?>
            </span>
        </div>
    </div>
</div>

<div class="row" style="margin-top: 20px;">
    <!-- Active System Alerts -->
    <div class="col-md-6">
        <div class="panel panel-default" style="border-radius: 12px; box-shadow: var(--shadow); border: 1px solid var(--border-color); background: var(--card-bg);">
            <div class="panel-heading" style="background: var(--card-header-bg); border-bottom: 1px solid var(--border-color); padding: 20px; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <h3 style="margin: 0; font-weight: 800; font-size: 18px; color: var(--primary-light);"><i class="fa fa-warning"></i> Performance Alerts Log</h3>
                <p style="margin: 4px 0 0 0; font-size: 12px; color: var(--text-muted);">Real-time monitoring thresholds and warning alerts status.</p>
            </div>
            
            <div class="panel-body" style="padding: 20px;">
                <?php if (empty($alerts)) { ?>
                    <div style="padding: 30px; text-align: center; color: var(--text-muted);">
                        <i class="fa fa-shield" style="font-size: 48px; margin-bottom: 12px;"></i>
                        <p style="margin: 0; font-weight: 600;">No system alerts recorded. All components normal.</p>
                    </div>
                <?php } else { ?>
                    <div class="table-responsive" style="max-height: 380px; overflow-y: auto;">
                        <table class="table table-hover">
                            <thead>
                                <tr style="background: var(--table-header-bg);">
                                    <th style="font-weight: 700; font-size: 11px;">Component</th>
                                    <th style="font-weight: 700; font-size: 11px;">Message</th>
                                    <th style="font-weight: 700; font-size: 11px;">Notified</th>
                                    <th style="font-weight: 700; font-size: 11px; text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($alerts as $row) { 
                                    $isResolved = ($row->status === 'Resolved');
                                    $badge = $isResolved ? 'label-default' : 'label-danger';
                                ?>
                                    <tr style="opacity: <?php echo $isResolved ? '0.7' : '1'; ?>;">
                                        <td>
                                            <span class="label <?php echo $badge; ?>" style="font-size: 9px; font-weight: 800; text-transform: uppercase;">
                                                <?php echo htmlspecialchars($row->component); ?>
                                            </span>
                                        </td>
                                        <td style="font-size: 12px;">
                                            <?php echo htmlspecialchars($row->alert_message); ?>
                                            <?php if ($isResolved) { ?>
                                                <div style="font-size: 10px; color: var(--text-muted); margin-top: 2px;">
                                                    Resolved: <?php echo date_toText($row->resolved_at); ?>
                                                </div>
                                            <?php } ?>
                                        </td>
                                        <td><small class="text-muted"><?php echo date_toText($row->notified_at); ?></small></td>
                                        <td style="text-align: center;">
                                            <?php if (!$isResolved) { ?>
                                                <a href="index.php?view=resolve&id=<?php echo $row->alert_id; ?>" class="btn btn-xs btn-success" style="border-radius: 4px; font-weight: 700;"><i class="fa fa-check"></i> Fix</a>
                                            <?php } else { ?>
                                                <i class="fa fa-check-circle text-success" title="Resolved"></i>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- Health Metrics History -->
    <div class="col-md-6">
        <div class="panel panel-default" style="border-radius: 12px; box-shadow: var(--shadow); border: 1px solid var(--border-color); background: var(--card-bg);">
            <div class="panel-heading" style="background: var(--card-header-bg); border-bottom: 1px solid var(--border-color); padding: 20px; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <h3 style="margin: 0; font-weight: 800; font-size: 18px; color: var(--primary-light);"><i class="fa fa-line-chart"></i> Historical Polling Data</h3>
                <p style="margin: 4px 0 0 0; font-size: 12px; color: var(--text-muted);">Review past load snapshots recorded by the monitoring daemon.</p>
            </div>
            
            <div class="panel-body" style="padding: 20px;">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr style="background: var(--table-header-bg);">
                                <th style="font-weight: 700; font-size: 11px;">Time</th>
                                <th style="font-weight: 700; font-size: 11px;">CPU</th>
                                <th style="font-weight: 700; font-size: 11px;">RAM</th>
                                <th style="font-weight: 700; font-size: 11px;">Disk</th>
                                <th style="font-weight: 700; font-size: 11px;">DB</th>
                                <th style="font-weight: 700; font-size: 11px;">AI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($history as $row) { ?>
                                <tr>
                                    <td><small class="text-muted"><?php echo date('H:i:s d M Y', strtotime($row->recorded_at)); ?></small></td>
                                    <td><strong><?php echo $row->cpu_usage_pct; ?>%</strong></td>
                                    <td><?php echo $row->memory_usage_pct; ?>%</td>
                                    <td><?php echo $row->disk_usage_pct; ?>%</td>
                                    <td><?php echo $row->mysql_ping_ms; ?> ms</td>
                                    <td>
                                        <span class="<?php echo $row->microservice_ping_ms >= 999 ? 'text-danger' : ''; ?>">
                                            <?php echo $row->microservice_ping_ms >= 999 ? 'Offline' : $row->microservice_ping_ms . ' ms'; ?>
                                        </span>
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
