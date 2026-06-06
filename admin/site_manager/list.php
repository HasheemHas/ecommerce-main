<?php
global $mydb;

// Auto-seed default sites if table is empty
$mydb->setQuery("SELECT count(*) as total FROM `sites`");
$count_res = $mydb->loadSingleResult();
if ($count_res->total == 0) {
    $seeds = [
        ['H-Mart Philippines', 'PH', 'PHP', 'en', 0.12, 'Asia/Manila', 'Active'],
        ['H-Mart United States', 'US', 'USD', 'en', 0.08, 'America/New_York', 'Active'],
        ['H-Mart Spain', 'ES', 'EUR', 'es', 0.21, 'Europe/Madrid', 'Active'],
        ['H-Mart Saudi Arabia', 'SA', 'SAR', 'ar', 0.15, 'Asia/Riyadh', 'Active']
    ];
    foreach ($seeds as $s) {
        $mydb->setQuery("INSERT INTO `sites` (`site_name`, `country_code`, `currency_code`, `language_code`, `tax_rate`, `timezone`, `status`) 
                         VALUES ('{$s[0]}', '{$s[1]}', '{$s[2]}', '{$s[3]}', {$s[4]}, '{$s[5]}', '{$s[6]}')");
        $mydb->executeQuery();
    }
}

// Fetch all sites
$mydb->setQuery("SELECT * FROM `sites` ORDER BY `site_id` ASC");
$sites = $mydb->loadResultList();
?>

<div class="row">
    <!-- Add Site/Regional Config -->
    <div class="col-md-4">
        <div class="panel panel-default" style="border-radius: 12px; box-shadow: var(--shadow); border: 1px solid var(--border-color); background: var(--card-bg);">
            <div class="panel-heading" style="background: var(--card-header-bg); border-bottom: 1px solid var(--border-color); padding: 20px; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <h3 style="margin: 0; font-weight: 800; font-size: 18px; color: var(--primary-light);"><i class="fa fa-plus-circle"></i> Add Regional Site</h3>
                <p style="margin: 4px 0 0 0; font-size: 12px; color: var(--text-muted);">Establish localized configurations for a country/sub-domain site.</p>
            </div>
            
            <div class="panel-body" style="padding: 20px;">
                <form action="index.php?view=add" method="post">
                    <div class="form-group">
                        <label style="font-weight: 600; font-size: 13px;">Site name</label>
                        <input type="text" name="site_name" class="form-control" placeholder="e.g. H-Mart Germany" required style="border-radius: 6px; font-size: 13px;">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label style="font-weight: 600; font-size: 13px;">Country (2-Letter)</label>
                                <input type="text" name="country_code" class="form-control" placeholder="DE" maxlength="2" required style="border-radius: 6px; font-size: 13px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label style="font-weight: 600; font-size: 13px;">Currency (3-Letter)</label>
                                <input type="text" name="currency_code" class="form-control" placeholder="EUR" maxlength="3" required style="border-radius: 6px; font-size: 13px;">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label style="font-weight: 600; font-size: 13px;">Language Code</label>
                                <input type="text" name="language_code" class="form-control" placeholder="de" maxlength="5" required style="border-radius: 6px; font-size: 13px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label style="font-weight: 600; font-size: 13px;">Tax Rate (e.g. 0.19)</label>
                                <input type="number" name="tax_rate" step="0.0001" class="form-control" placeholder="0.19" required style="border-radius: 6px; font-size: 13px;">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label style="font-weight: 600; font-size: 13px;">Local Timezone</label>
                        <select name="timezone" class="form-control" required style="border-radius: 6px; font-size: 13px;">
                            <option value="Asia/Manila">Asia/Manila (GMT+8)</option>
                            <option value="Asia/Riyadh">Asia/Riyadh (GMT+3)</option>
                            <option value="America/New_York">America/New_York (EST/EDT)</option>
                            <option value="Europe/Madrid">Europe/Madrid (CET/CEST)</option>
                            <option value="Europe/London">Europe/London (GMT/BST)</option>
                            <option value="Asia/Kolkata">Asia/Kolkata (GMT+5:30)</option>
                        </select>
                    </div>
                    
                    <button type="submit" name="save" class="btn btn-primary btn-block" style="background: var(--primary-light); border: none; font-weight: 700; font-size: 14px; padding: 10px; border-radius: 6px;">
                        <i class="fa fa-globe"></i> Activate Regional Site
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Active Sites List -->
    <div class="col-md-8">
        <div class="panel panel-default" style="border-radius: 12px; box-shadow: var(--shadow); border: 1px solid var(--border-color); background: var(--card-bg);">
            <div class="panel-heading" style="background: var(--card-header-bg); border-bottom: 1px solid var(--border-color); padding: 20px; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <h3 style="margin: 0; font-weight: 800; font-size: 18px; color: var(--primary-light);"><i class="fa fa-server"></i> Regional Subdomain Directory</h3>
                <p style="margin: 4px 0 0 0; font-size: 12px; color: var(--text-muted);">Manage localized storefront settings, VAT/tax parameters, and currency mappings.</p>
            </div>
            
            <div class="panel-body" style="padding: 20px;">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dash-table">
                        <thead>
                            <tr style="background: var(--table-header-bg);">
                                <th style="font-weight: 700; font-size: 12px;">Site Name</th>
                                <th style="font-weight: 700; font-size: 12px;">Country</th>
                                <th style="font-weight: 700; font-size: 12px;">Currency</th>
                                <th style="font-weight: 700; font-size: 12px;">Lang</th>
                                <th style="font-weight: 700; font-size: 12px;">Tax Rate</th>
                                <th style="font-weight: 700; font-size: 12px;">Timezone</th>
                                <th style="font-weight: 700; font-size: 12px;">Status</th>
                                <th style="font-weight: 700; font-size: 12px; text-align: center; width: 180px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sites as $row) { 
                                $statusBadge = ($row->status === 'Active') ? 'label-success' : 'label-danger';
                            ?>
                                <tr>
                                    <td>
                                        <div id="display-name-<?php echo $row->site_id; ?>" style="font-weight: 700; font-size: 13px;">
                                            <?php echo htmlspecialchars($row->site_name); ?>
                                        </div>
                                    </td>
                                    <td><span class="label label-default" style="font-size: 10px; font-weight: 800;"><?php echo htmlspecialchars($row->country_code); ?></span></td>
                                    <td><strong><?php echo htmlspecialchars($row->currency_code); ?></strong></td>
                                    <td><code><?php echo htmlspecialchars($row->language_code); ?></code></td>
                                    <td>
                                        <div id="display-tax-<?php echo $row->site_id; ?>">
                                            <?php echo ($row->tax_rate * 100); ?>%
                                        </div>
                                    </td>
                                    <td>
                                        <div id="display-tz-<?php echo $row->site_id; ?>" style="font-size: 12px;">
                                            <?php echo htmlspecialchars($row->timezone); ?>
                                        </div>
                                        
                                        <!-- Edit inline form -->
                                        <form id="edit-form-<?php echo $row->site_id; ?>" action="index.php?view=edit" method="post" style="display: none; margin-top: 10px;">
                                            <input type="hidden" name="site_id" value="<?php echo $row->site_id; ?>">
                                            <div class="form-group" style="margin-bottom: 5px;">
                                                <input type="text" name="site_name" class="form-control input-sm" value="<?php echo htmlspecialchars($row->site_name); ?>" required placeholder="Site Name">
                                            </div>
                                            <div class="form-group" style="margin-bottom: 5px;">
                                                <input type="number" name="tax_rate" step="0.0001" class="form-control input-sm" value="<?php echo $row->tax_rate; ?>" required placeholder="Tax (e.g. 0.12)">
                                            </div>
                                            <div class="form-group" style="margin-bottom: 5px;">
                                                <input type="text" name="timezone" class="form-control input-sm" value="<?php echo htmlspecialchars($row->timezone); ?>" required placeholder="Timezone">
                                            </div>
                                            <div class="form-group" style="margin-bottom: 8px;">
                                                <select name="status" class="form-control input-sm" style="border-radius: 4px;">
                                                    <option value="Active" <?php echo $row->status === 'Active' ? 'selected' : ''; ?>>Active</option>
                                                    <option value="Inactive" <?php echo $row->status === 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
                                                </select>
                                            </div>
                                            <div style="display: flex; gap: 5px;">
                                                <button type="submit" name="save_edit" class="btn btn-xs btn-success"><i class="fa fa-save"></i> Save</button>
                                                <button type="button" onclick="toggleEdit(<?php echo $row->site_id; ?>, false)" class="btn btn-xs btn-default">Cancel</button>
                                            </div>
                                        </form>
                                    </td>
                                    <td>
                                        <span class="label <?php echo $statusBadge; ?>" style="text-transform: uppercase; font-size: 9px; font-weight: 800;"><?php echo htmlspecialchars($row->status); ?></span>
                                    </td>
                                    <td style="text-align: center;">
                                        <button id="edit-btn-<?php echo $row->site_id; ?>" onclick="toggleEdit(<?php echo $row->site_id; ?>, true)" class="btn btn-xs btn-warning" style="border-radius: 4px; font-weight: 700;"><i class="fa fa-edit"></i> Edit</button>
                                        <a href="index.php?view=delete&id=<?php echo $row->site_id; ?>" class="btn btn-xs btn-danger" style="border-radius: 4px; font-weight: 700;" onclick="return confirm('Remove this regional store config?');"><i class="fa fa-trash"></i> Delete</a>
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

<script>
function toggleEdit(id, show) {
    var displayName = document.getElementById('display-name-' + id);
    var displayTax = document.getElementById('display-tax-' + id);
    var displayTz = document.getElementById('display-tz-' + id);
    var editBtn = document.getElementById('edit-btn-' + id);
    var form = document.getElementById('edit-form-' + id);
    
    if (show) {
        displayName.style.display = 'none';
        displayTax.style.display = 'none';
        displayTz.style.display = 'none';
        editBtn.style.display = 'none';
        form.style.display = 'block';
    } else {
        displayName.style.display = 'block';
        displayTax.style.display = 'block';
        displayTz.style.display = 'block';
        editBtn.style.display = 'inline-block';
        form.style.display = 'none';
    }
}
</script>
