<?php
global $mydb;

// Auto-seed default currencies if empty
$mydb->setQuery("SELECT count(*) as total FROM `currencies`");
$count_res = $mydb->loadSingleResult();
if ($count_res->total == 0) {
    // Seed default Philippine Peso (PHP) as Base, and USD/EUR/INR
    $seeds = [
        ['PHP', '₱', 1.0, 1, 'Active'],
        ['USD', '$', 0.018, 0, 'Active'],
        ['EUR', '€', 0.016, 0, 'Active'],
        ['INR', '₹', 1.50, 0, 'Active']
    ];
    foreach ($seeds as $s) {
        $mydb->setQuery("INSERT INTO `currencies` (`currency_code`, `currency_symbol`, `exchange_rate`, `is_base`, `status`) 
                         VALUES ('{$s[0]}', '{$s[1]}', {$s[2]}, {$s[3]}, '{$s[4]}')");
        $mydb->executeQuery();
        
        // Also insert into exchange_rates history
        $mydb->setQuery("INSERT INTO `exchange_rates` (`currency_code`, `rate`) VALUES ('{$s[0]}', {$s[2]})");
        $mydb->executeQuery();
    }
}

// Fetch all currencies
$mydb->setQuery("SELECT * FROM `currencies` ORDER BY `is_base` DESC, `currency_code` ASC");
$currencies = $mydb->loadResultList();

// Fetch rate updates history
$mydb->setQuery("SELECT * FROM `exchange_rates` ORDER BY `last_updated` DESC LIMIT 10");
$rate_history = $mydb->loadResultList();
?>

<div class="row">
    <!-- Add Currency & Info Panel -->
    <div class="col-md-4">
        <div class="panel panel-default" style="border-radius: 12px; box-shadow: var(--shadow); border: 1px solid var(--border-color); background: var(--card-bg);">
            <div class="panel-heading" style="background: var(--card-header-bg); border-bottom: 1px solid var(--border-color); padding: 20px; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <h3 style="margin: 0; font-weight: 800; font-size: 18px; color: var(--primary-light);"><i class="fa fa-money"></i> Add New Currency</h3>
                <p style="margin: 4px 0 0 0; font-size: 12px; color: var(--text-muted);">Add secondary currencies for customer storefront conversions.</p>
            </div>
            
            <div class="panel-body" style="padding: 20px;">
                <form action="index.php?view=add" method="post">
                    <div class="form-group">
                        <label style="font-weight: 600; font-size: 13px;">ISO Currency Code</label>
                        <input type="text" name="currency_code" class="form-control" placeholder="e.g. EUR" maxlength="3" required style="border-radius: 6px; font-size: 13px;">
                    </div>
                    
                    <div class="form-group">
                        <label style="font-weight: 600; font-size: 13px;">Currency Symbol</label>
                        <input type="text" name="currency_symbol" class="form-control" placeholder="e.g. €" required style="border-radius: 6px; font-size: 13px;">
                    </div>
                    
                    <div class="form-group">
                        <label style="font-weight: 600; font-size: 13px;">Exchange Rate (Relative to Base)</label>
                        <input type="number" name="exchange_rate" step="0.000001" class="form-control" placeholder="e.g. 0.016" required style="border-radius: 6px; font-size: 13px;">
                        <small class="text-muted" style="font-size: 11px;">Multiplier to convert Base prices to this currency.</small>
                    </div>
                    
                    <button type="submit" name="save" class="btn btn-primary btn-block" style="background: var(--primary-light); border: none; font-weight: 700; font-size: 14px; padding: 10px; border-radius: 6px;">
                        <i class="fa fa-check"></i> Register Currency
                    </button>
                </form>
            </div>
        </div>

        <!-- Exchange Rate History -->
        <div class="panel panel-default" style="border-radius: 12px; box-shadow: var(--shadow); border: 1px solid var(--border-color); background: var(--card-bg); margin-top: 20px;">
            <div class="panel-heading" style="background: var(--card-header-bg); border-bottom: 1px solid var(--border-color); padding: 20px; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <h3 style="margin: 0; font-weight: 800; font-size: 16px; color: var(--primary-light);"><i class="fa fa-line-chart"></i> Recent Rate Fluctuations</h3>
                <p style="margin: 4px 0 0 0; font-size: 11px; color: var(--text-muted);">Historical log of recent rate adjustments.</p>
            </div>
            
            <div class="panel-body" style="padding: 15px;">
                <ul class="list-group" style="margin-bottom: 0; border: none;">
                    <?php foreach ($rate_history as $hist) { ?>
                        <li class="list-group-item" style="background: transparent; border: none; border-bottom: 1px solid var(--border-color); padding: 10px 0; display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <span class="label label-info" style="font-size: 9px; font-weight: 800;"><?php echo htmlspecialchars($hist->currency_code); ?></span>
                                <span style="font-size: 13px; font-weight: 600; margin-left: 8px;">Rate: <?php echo number_format($hist->rate, 4); ?></span>
                            </div>
                            <small class="text-muted" style="font-size: 11px;"><?php echo date_toText($hist->last_updated); ?></small>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>

    <!-- Currency Directory -->
    <div class="col-md-8">
        <div class="panel panel-default" style="border-radius: 12px; box-shadow: var(--shadow); border: 1px solid var(--border-color); background: var(--card-bg);">
            <div class="panel-heading" style="background: var(--card-header-bg); border-bottom: 1px solid var(--border-color); padding: 20px; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <h3 style="margin: 0; font-weight: 800; font-size: 18px; color: var(--primary-light);"><i class="fa fa-globe"></i> Active Currencies</h3>
                <p style="margin: 4px 0 0 0; font-size: 12px; color: var(--text-muted);">Configure conversion coefficients and establish system base currency.</p>
            </div>
            
            <div class="panel-body" style="padding: 20px;">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr style="background: var(--table-header-bg);">
                                <th style="font-weight: 700; font-size: 12px;">Code</th>
                                <th style="font-weight: 700; font-size: 12px;">Symbol</th>
                                <th style="font-weight: 700; font-size: 12px;">Exchange Rate (1 Base =)</th>
                                <th style="font-weight: 700; font-size: 12px; text-align: center;">Base Currency</th>
                                <th style="font-weight: 700; font-size: 12px;">Status</th>
                                <th style="font-weight: 700; font-size: 12px; text-align: center; width: 200px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($currencies as $row) { ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($row->currency_code); ?></strong></td>
                                    <td><span style="font-size: 16px; font-weight: 700;"><?php echo htmlspecialchars($row->currency_symbol); ?></span></td>
                                    <td>
                                        <form id="edit-form-<?php echo $row->currency_id; ?>" action="index.php?view=edit" method="post" style="margin: 0; display: flex; gap: 8px;">
                                            <input type="hidden" name="currency_id" value="<?php echo $row->currency_id; ?>">
                                            <input type="number" name="exchange_rate" step="0.000001" class="form-control input-sm" value="<?php echo $row->exchange_rate; ?>" <?php echo $row->is_base ? 'readonly' : ''; ?> required style="width: 120px; border-radius: 6px;">
                                            
                                            <input type="hidden" name="status" value="<?php echo htmlspecialchars($row->status); ?>">
                                            
                                            <?php if (!$row->is_base) { ?>
                                                <button type="submit" name="save_edit" class="btn btn-xs btn-success" title="Update Exchange Rate" style="border-radius: 4px; padding: 4px 8px;"><i class="fa fa-save"></i> Save</button>
                                            <?php } ?>
                                        </form>
                                    </td>
                                    <td style="text-align: center;">
                                        <?php if ($row->is_base) { ?>
                                            <span class="label label-primary" style="font-size: 10px; font-weight: 800; text-transform: uppercase; padding: 4px 8px; border-radius: 10px;">Primary Base</span>
                                        <?php } else { ?>
                                            <a href="index.php?view=set_base&id=<?php echo $row->currency_id; ?>" class="btn btn-xs btn-default" style="border-radius: 4px; font-weight: 700;" onclick="return confirm('Setting this as base resets its rate to 1.0. Make sure other rates are relative to this.');">Make Base</a>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <span class="label <?php echo $row->status === 'Active' ? 'label-success' : 'label-danger'; ?>" style="font-weight: 800; font-size: 10px; text-transform: uppercase;">
                                            <?php echo htmlspecialchars($row->status); ?>
                                        </span>
                                    </td>
                                    <td style="text-align: center;">
                                        <?php if (!$row->is_base) { ?>
                                            <a href="index.php?view=delete&id=<?php echo $row->currency_id; ?>" class="btn btn-xs btn-danger" style="border-radius: 4px; font-weight: 700;" onclick="return confirm('Are you sure you want to remove this currency?');"><i class="fa fa-trash"></i> Delete</a>
                                        <?php } else { ?>
                                            <span class="text-muted" style="font-size: 11px;">Protected Base</span>
                                        <?php } ?>
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
