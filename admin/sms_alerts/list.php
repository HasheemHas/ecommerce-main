<?php
global $mydb;

// Fetch configurations
$mydb->setQuery("SELECT * FROM `sms_alerts_config`");
$configs_raw = $mydb->loadResultList();
$configs = [];
foreach ($configs_raw as $c) {
    $configs[$c->alert_type] = $c;
}

// Fetch SMS logs
$mydb->setQuery("SELECT * FROM `sms_logs` ORDER BY `sms_id` DESC LIMIT 200");
$logs = $mydb->loadResultList();

$alert_types = [
    'high_value_order' => ['label' => 'High-Value Order Alert', 'desc' => 'Notify when an order total exceeds $500'],
    'fraud' => ['label' => 'Fraud Alert Notification', 'desc' => 'Notify when a high-risk transaction score is flagged'],
    'critical_stock' => ['label' => 'Critical Low Stock alert', 'desc' => 'Notify when a high-demand item is completely sold out'],
    'back_in_stock' => ['label' => 'Back In Stock Notification', 'desc' => 'Send SMS updates to waiting customers automatically']
];
?>

<div class="row">
    <!-- SMS Configurations -->
    <div class="col-md-5">
        <div class="panel panel-default" style="border-radius: 12px; box-shadow: var(--shadow); border: 1px solid var(--border-color); background: var(--card-bg);">
            <div class="panel-heading" style="background: var(--card-header-bg); border-bottom: 1px solid var(--border-color); padding: 20px; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <h3 style="margin: 0; font-weight: 800; font-size: 18px; color: var(--primary-light);"><i class="fa fa-sliders"></i> SMS Alert Rules</h3>
                <p style="margin: 4px 0 0 0; font-size: 12px; color: var(--text-muted);">Enable and configure recipient phone numbers for system alerts.</p>
            </div>
            
            <div class="panel-body" style="padding: 20px;">
                <form action="index.php?view=update_config" method="post">
                    <?php foreach ($alert_types as $type => $info) { 
                        $enabled = isset($configs[$type]) ? $configs[$type]->enabled : 0;
                        $phone = isset($configs[$type]) ? $configs[$type]->recipient_phone : '';
                    ?>
                        <div style="border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 15px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                <label style="font-weight: 700; margin: 0; color: var(--primary);"><?php echo $info['label']; ?></label>
                                <span class="material-switch">
                                    <input type="checkbox" name="enabled_<?php echo $type; ?>" id="enabled_<?php echo $type; ?>" value="1" <?php echo $enabled ? 'checked' : ''; ?> style="width:auto;">
                                </span>
                            </div>
                            <p style="font-size: 12px; color: var(--text-muted); margin: 0 0 8px 0;"><?php echo $info['desc']; ?></p>
                            <input type="text" name="recipient_phone[<?php echo $type; ?>]" class="form-control" placeholder="+1234567890" value="<?php echo htmlspecialchars($phone); ?>" style="font-size: 13px; border-radius: 6px;">
                        </div>
                    <?php } ?>
                    
                    <button type="submit" name="save_config" class="btn btn-primary btn-block" style="background: var(--primary-light); border: none; font-weight: 700; font-size: 14px; padding: 10px; border-radius: 6px;">
                        <i class="fa fa-save"></i> Save Configurations
                    </button>
                </form>
            </div>
        </div>

        <!-- Simulated Send Terminal -->
        <div class="panel panel-default" style="border-radius: 12px; box-shadow: var(--shadow); border: 1px solid var(--border-color); background: var(--card-bg); margin-top: 20px;">
            <div class="panel-heading" style="background: var(--card-header-bg); border-bottom: 1px solid var(--border-color); padding: 20px; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <h3 style="margin: 0; font-weight: 800; font-size: 18px; color: var(--primary-light);"><i class="fa fa-paper-plane"></i> Quick SMS Broadcaster</h3>
                <p style="margin: 4px 0 0 0; font-size: 12px; color: var(--text-muted);">Manually trigger an outgoing message via the Twilio SMS gateway.</p>
            </div>
            
            <div class="panel-body" style="padding: 20px;">
                <form action="index.php?view=send_test" method="post">
                    <div class="form-group">
                        <label style="font-weight: 600; font-size: 13px;">Recipient Phone Number</label>
                        <input type="text" name="phone" class="form-control" placeholder="e.g. +639123456789" required style="font-size: 13px; border-radius: 6px;">
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 600; font-size: 13px;">Message Content</label>
                        <textarea name="message" class="form-control" rows="3" placeholder="Type SMS content here..." required style="font-size: 13px; border-radius: 6px; resize: none;"></textarea>
                    </div>
                    <button type="submit" name="send_sms" class="btn btn-default btn-block" style="border-radius: 6px; font-weight: 700; font-size: 13px; border: 1px solid var(--border-color);">
                        <i class="fa fa-send"></i> Dispatch Message
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- SMS Gateway Logs -->
    <div class="col-md-7">
        <div class="panel panel-default" style="border-radius: 12px; box-shadow: var(--shadow); border: 1px solid var(--border-color); background: var(--card-bg);">
            <div class="panel-heading" style="background: var(--card-header-bg); border-bottom: 1px solid var(--border-color); padding: 20px; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <h3 style="margin: 0; font-weight: 800; font-size: 18px; color: var(--primary-light);"><i class="fa fa-history"></i> Outbound SMS Delivery Logs</h3>
                <p style="margin: 4px 0 0 0; font-size: 12px; color: var(--text-muted);">Real-time status updates of notifications pushed to phone carriers.</p>
            </div>
            
            <div class="panel-body" style="padding: 20px;">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dash-table">
                        <thead>
                            <tr style="background: var(--table-header-bg);">
                                <th style="font-weight: 700; font-size: 12px;">ID</th>
                                <th style="font-weight: 700; font-size: 12px;">Recipient</th>
                                <th style="font-weight: 700; font-size: 12px;">Message</th>
                                <th style="font-weight: 700; font-size: 12px;">Status</th>
                                <th style="font-weight: 700; font-size: 12px;">Timestamp</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($logs as $row) { 
                                $statusBadge = ($row->status === 'Sent') ? 'label-success' : 'label-danger';
                            ?>
                                <tr>
                                    <td>#<?php echo $row->sms_id; ?></td>
                                    <td><strong><?php echo htmlspecialchars($row->phone_number); ?></strong></td>
                                    <td>
                                        <div style="font-size: 12px; max-width: 250px; white-space: normal; word-wrap: break-word;">
                                            <?php echo htmlspecialchars($row->message_body); ?>
                                            <?php if (!empty($row->error_message)) { ?>
                                                <div style="color: #ef4444; font-size: 10px; margin-top: 4px;">
                                                    <i class="fa fa-warning"></i> <?php echo htmlspecialchars($row->error_message); ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </td>
                                    <td><span class="label <?php echo $statusBadge; ?>" style="text-transform: uppercase; font-size: 9px; font-weight: 800;"><?php echo htmlspecialchars($row->status); ?></span></td>
                                    <td><small class="text-muted"><?php echo date_toText($row->sent_at); ?></small></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
