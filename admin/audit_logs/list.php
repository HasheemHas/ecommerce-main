<?php
global $mydb;
$mydb->setQuery("
    SELECT a.*, u.U_NAME 
    FROM `audit_logs` a
    LEFT JOIN `tbluseraccount` u ON a.admin_id = u.USERID
    ORDER BY a.log_id DESC
");
$logs = $mydb->loadResultList();
?>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default" style="border-radius: 12px; box-shadow: var(--shadow); border: 1px solid var(--border-color); background: var(--card-bg);">
            <div class="panel-heading" style="background: var(--card-header-bg); border-bottom: 1px solid var(--border-color); padding: 20px; border-top-left-radius: 12px; border-top-right-radius: 12px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
                <div>
                    <h3 style="margin: 0; font-weight: 800; font-size: 18px; color: var(--primary-light);"><i class="fa fa-history"></i> Audit Trail & Security Logs</h3>
                    <p style="margin: 4px 0 0 0; font-size: 12px; color: var(--text-muted);">Track all administrative data mutations, exports, and security logins.</p>
                </div>
                <a href="index.php?view=export" class="btn btn-primary" style="background: var(--primary-light); border: none; border-radius: 6px; font-weight: 700; font-size: 13px;"><i class="fa fa-download"></i> Export to CSV</a>
            </div>
            
            <div class="panel-body" style="padding: 20px;">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dash-table">
                        <thead>
                            <tr style="background: var(--table-header-bg);">
                                <th style="font-weight: 700; font-size: 12px;">Log ID</th>
                                <th style="font-weight: 700; font-size: 12px;">Operator</th>
                                <th style="font-weight: 700; font-size: 12px;">Action</th>
                                <th style="font-weight: 700; font-size: 12px;">Target Module</th>
                                <th style="font-weight: 700; font-size: 12px;">IP Address</th>
                                <th style="font-weight: 700; font-size: 12px;">Time</th>
                                <th style="font-weight: 700; font-size: 12px; text-align: center;">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($logs as $row) { 
                                $badgeClass = "label-info";
                                if ($row->action === "delete") {
                                    $badgeClass = "label-danger";
                                } elseif ($row->action === "create" || $row->action === "add") {
                                    $badgeClass = "label-success";
                                } elseif ($row->action === "update" || $row->action === "edit") {
                                    $badgeClass = "label-warning";
                                }
                            ?>
                                <tr>
                                    <td>#<?php echo $row->log_id; ?></td>
                                    <td><strong><?php echo htmlspecialchars($row->U_NAME ?? 'System'); ?></strong> <small class="text-muted">(ID: <?php echo $row->admin_id; ?>)</small></td>
                                    <td><span class="label <?php echo $badgeClass; ?>" style="text-transform: uppercase; font-size: 10px; font-weight: 700;"><?php echo htmlspecialchars($row->action); ?></span></td>
                                    <td><code><?php echo htmlspecialchars($row->target_table); ?></code></td>
                                    <td><?php echo htmlspecialchars($row->ip_address); ?></td>
                                    <td><?php echo date_toText($row->timestamp); ?></td>
                                    <td style="text-align: center;">
                                        <button class="btn btn-xs btn-default" data-toggle="collapse" data-target="#details-<?php echo $row->log_id; ?>" style="border-radius: 4px; font-weight: 700;"><i class="fa fa-eye"></i> Inspect</button>
                                    </td>
                                </tr>
                                <tr id="details-<?php echo $row->log_id; ?>" class="collapse">
                                    <td colspan="7" style="background: var(--bg-color); padding: 15px;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h5 style="margin-top: 0; font-weight: 700; color: var(--text-muted); font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">Previous Values (Before Change)</h5>
                                                <pre style="background: var(--card-bg); color: var(--text-main); border: 1px solid var(--border-color); border-radius: 6px; font-size: 12px; padding: 10px; max-height: 150px; overflow-y: auto;"><?php echo $row->old_values ? htmlspecialchars(json_encode(json_decode($row->old_values), JSON_PRETTY_PRINT)) : 'No historical data'; ?></pre>
                                            </div>
                                            <div class="col-md-6">
                                                <h5 style="margin-top: 0; font-weight: 700; color: var(--text-muted); font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">New Values (After Change)</h5>
                                                <pre style="background: var(--card-bg); color: var(--text-main); border: 1px solid var(--border-color); border-radius: 6px; font-size: 12px; padding: 10px; max-height: 150px; overflow-y: auto;"><?php echo $row->new_values ? htmlspecialchars(json_encode(json_decode($row->new_values), JSON_PRETTY_PRINT)) : 'No mutation data'; ?></pre>
                                            </div>
                                        </div>
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
