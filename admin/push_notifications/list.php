<?php
global $mydb;

// Fetch subscriptions count
$mydb->setQuery("SELECT COUNT(*) as sub_count FROM `push_subscriptions`");
$sub_res = $mydb->loadSingleResult();
$sub_count = $sub_res ? $sub_res->sub_count : 0;

// Seed a few push subscriptions for demonstration if 0
if ($sub_count == 0) {
    $mydb->setQuery("
        INSERT INTO `push_subscriptions` (`user_type`, `user_id`, `endpoint`, `p256dh`, `auth`)
        VALUES ('Customer', 18, 'https://fcm.googleapis.com/fcm/send/fake-endpoint-token-1234', 'p256-dh-auth-key-1', 'auth-secret-1')
    ");
    $mydb->executeQuery();
    $mydb->setQuery("SELECT COUNT(*) as sub_count FROM `push_subscriptions`");
    $sub_res = $mydb->loadSingleResult();
    $sub_count = $sub_res ? $sub_res->sub_count : 1;
}

// Fetch push logs
$mydb->setQuery("SELECT * FROM `push_notifications_log` ORDER BY log_id DESC LIMIT 30");
$push_logs = $mydb->loadResultList();
?>

<div class="row">
    <!-- Push Composer Form -->
    <div class="col-md-5">
        <div class="panel panel-default" style="border-radius: 12px; box-shadow: var(--shadow); border: 1px solid var(--border-color); background: var(--card-bg);">
            <div class="panel-heading" style="background: var(--card-header-bg); border-bottom: 1px solid var(--border-color); padding: 15px; border-top-left-radius: 12px; border-top-right-radius: 12px; display: flex; justify-content: space-between; align-items: center;">
                <h4 style="margin: 0; font-weight: 800; font-size: 15px; color: var(--primary-light);"><i class="fa fa-paper-plane"></i> Push Notification Composer</h4>
                <span class="label label-info" style="font-size: 11px; font-weight: 700; border-radius: 4px;"><?php echo $sub_count; ?> Active Subscribers</span>
            </div>
            <div class="panel-body" style="padding: 15px;">
                <form action="index.php" method="POST">
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 12px; color: var(--text-main);">Target Segment:</label>
                        <select name="target_segment" required class="form-control" style="border-radius: 6px; border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-main);">
                            <option value="All Customers">All Customers</option>
                            <option value="VIP Members">VIP Members Only</option>
                            <option value="Store Admins">Store Administrators</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 12px; color: var(--text-main);">Notification Title:</label>
                        <input type="text" name="title" required class="form-control" placeholder="e.g. Flash Sale Alert!" style="border-radius: 6px; border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-main);">
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 12px; color: var(--text-main);">Message Body:</label>
                        <textarea name="body" required class="form-control" rows="5" placeholder="e.g. Get 20% off site-wide for the next 2 hours only. Don't miss out!" style="border-radius: 6px; border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-main);"></textarea>
                    </div>
                    <button type="submit" name="btn_broadcast_push" class="btn btn-primary btn-block" style="background: var(--primary-light); border: none; border-radius: 6px; font-weight: 700; padding: 10px;"><i class="fa fa-bell"></i> Broadcast Browser Push</button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Broadcast Logs -->
    <div class="col-md-7">
        <div class="panel panel-default" style="border-radius: 12px; box-shadow: var(--shadow); border: 1px solid var(--border-color); background: var(--card-bg);">
            <div class="panel-heading" style="background: var(--card-header-bg); border-bottom: 1px solid var(--border-color); padding: 15px; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <h4 style="margin: 0; font-weight: 800; font-size: 15px; color: var(--primary-light);"><i class="fa fa-history"></i> Broadcast History Log</h4>
            </div>
            <div class="panel-body" style="padding: 15px;">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr style="background: var(--table-header-bg);">
                                <th style="font-weight: 700; font-size: 12px;">Notification Details</th>
                                <th style="font-weight: 700; font-size: 12px;">Target Segment</th>
                                <th style="font-weight: 700; font-size: 12px;">Sent At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($push_logs) > 0) { 
                                foreach ($push_logs as $log) {
                            ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($log->title); ?></strong><br>
                                        <small class="text-muted"><?php echo htmlspecialchars($log->body); ?></small>
                                    </td>
                                    <td><span class="label label-primary" style="font-size:10px; font-weight:700;"><?php echo htmlspecialchars($log->target_segment); ?></span></td>
                                    <td><?php echo date_toText($log->sent_at); ?></td>
                                </tr>
                            <?php } 
                            } else { ?>
                                <tr>
                                    <td colspan="3" style="text-align: center; color: var(--text-muted); padding: 30px 0;">No pushes broadcasted yet.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
