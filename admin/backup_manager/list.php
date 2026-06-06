<?php
global $mydb;

// Fetch backup logs
$mydb->setQuery("SELECT * FROM `backup_logs` ORDER BY `backup_id` DESC");
$backups = $mydb->loadResultList();

// Format bytes helper
function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}
?>

<div class="row">
    <!-- Info & Creation Trigger -->
    <div class="col-md-4">
        <div class="panel panel-default" style="border-radius: 12px; box-shadow: var(--shadow); border: 1px solid var(--border-color); background: var(--card-bg);">
            <div class="panel-heading" style="background: var(--card-header-bg); border-bottom: 1px solid var(--border-color); padding: 20px; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <h3 style="margin: 0; font-weight: 800; font-size: 18px; color: var(--primary-light);"><i class="fa fa-info-circle"></i> Backup Strategy</h3>
                <p style="margin: 4px 0 0 0; font-size: 12px; color: var(--text-muted);">Ensure system state persistence before executing migrations or system upgrades.</p>
            </div>
            
            <div class="panel-body" style="padding: 20px;">
                <p style="font-size: 13px; line-height: 1.6; color: var(--text-main); margin-bottom: 20px;">
                    Backups generate a complete SQL script declaring table definitions and inserting data rows. 
                    Restoring from a backup will overwrite existing database schema. Please exercise caution.
                </p>
                
                <a href="index.php?view=create" class="btn btn-primary btn-block" style="background: var(--primary-light); border: none; font-weight: 700; font-size: 14px; padding: 12px; border-radius: 6px;">
                    <i class="fa fa-database"></i> Generate Database Dump
                </a>
            </div>
        </div>
        
        <div class="panel panel-default" style="border-radius: 12px; box-shadow: var(--shadow); border: 1px solid var(--border-color); background: var(--card-bg); margin-top: 20px;">
            <div class="panel-body" style="padding: 20px; display: flex; align-items: center; gap: 15px;">
                <div style="font-size: 32px; color: var(--primary-light);"><i class="fa fa-shield"></i></div>
                <div>
                    <h5 style="margin: 0; font-weight: 800; font-size: 14px;">Local Encryption</h5>
                    <p style="margin: 4px 0 0 0; font-size: 11px; color: var(--text-muted);">Backups are safely stored locally using server security controls.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Backup Log List -->
    <div class="col-md-8">
        <div class="panel panel-default" style="border-radius: 12px; box-shadow: var(--shadow); border: 1px solid var(--border-color); background: var(--card-bg);">
            <div class="panel-heading" style="background: var(--card-header-bg); border-bottom: 1px solid var(--border-color); padding: 20px; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <h3 style="margin: 0; font-weight: 800; font-size: 18px; color: var(--primary-light);"><i class="fa fa-history"></i> SQL Snapshot Registry</h3>
                <p style="margin: 4px 0 0 0; font-size: 12px; color: var(--text-muted);">View, download, and roll back MySQL configurations and tables.</p>
            </div>
            
            <div class="panel-body" style="padding: 20px;">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dash-table">
                        <thead>
                            <tr style="background: var(--table-header-bg);">
                                <th style="font-weight: 700; font-size: 12px;">Backup ID</th>
                                <th style="font-weight: 700; font-size: 12px;">File name</th>
                                <th style="font-weight: 700; font-size: 12px;">File Size</th>
                                <th style="font-weight: 700; font-size: 12px;">Storage</th>
                                <th style="font-weight: 700; font-size: 12px;">Status</th>
                                <th style="font-weight: 700; font-size: 12px;">Created At</th>
                                <th style="font-weight: 700; font-size: 12px; text-align: center; width: 220px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($backups as $row) { 
                                $statusBadge = ($row->status === 'Success') ? 'label-success' : 'label-danger';
                            ?>
                                <tr>
                                    <td>#<?php echo $row->backup_id; ?></td>
                                    <td>
                                        <code style="font-size: 12px;"><?php echo htmlspecialchars($row->file_name ? $row->file_name : 'N/A'); ?></code>
                                        <?php if (!empty($row->error_details)) { ?>
                                            <div style="color: #ef4444; font-size: 10px; margin-top: 4px;">
                                                <i class="fa fa-warning"></i> <?php echo htmlspecialchars($row->error_details); ?>
                                            </div>
                                        <?php } ?>
                                    </td>
                                    <td><?php echo formatBytes($row->file_size_bytes); ?></td>
                                    <td><span class="label label-default" style="font-size: 10px; font-weight: 700;"><?php echo htmlspecialchars($row->storage_location); ?></span></td>
                                    <td><span class="label <?php echo $statusBadge; ?>" style="text-transform: uppercase; font-size: 9px; font-weight: 800;"><?php echo htmlspecialchars($row->status); ?></span></td>
                                    <td><small class="text-muted"><?php echo date_toText($row->created_at); ?></small></td>
                                    <td style="text-align: center;">
                                        <?php if ($row->status === 'Success') { ?>
                                            <a href="index.php?view=download&file=<?php echo urlencode($row->file_name); ?>" class="btn btn-xs btn-primary" style="border-radius: 4px; font-weight: 700;"><i class="fa fa-download"></i> Get</a>
                                            <a href="index.php?view=restore&file=<?php echo urlencode($row->file_name); ?>" class="btn btn-xs btn-warning" style="border-radius: 4px; font-weight: 700;" onclick="return confirm('WARNING: Restoring will overwrite the current database. Proceed?');"><i class="fa fa-refresh"></i> Restore</a>
                                        <?php } ?>
                                        <a href="index.php?view=delete&id=<?php echo $row->backup_id; ?>" class="btn btn-xs btn-danger" style="border-radius: 4px; font-weight: 700;" onclick="return confirm('Delete backup file from disk permanently?');"><i class="fa fa-trash"></i> Delete</a>
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
