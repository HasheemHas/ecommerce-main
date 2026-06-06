<?php
global $mydb;

// Auto-seed basic translations if table is completely empty
$mydb->setQuery("SELECT count(*) as total FROM `translations_cache`");
$count_res = $mydb->loadSingleResult();
if ($count_res->total == 0) {
    // Seed sample English, Spanish, and Arabic terms
    $seeds = [
        ['en', 'store_welcome', 'Welcome to H-Mart'],
        ['es', 'store_welcome', 'Bienvenido a H-Mart'],
        ['ar', 'store_welcome', 'مرحبا بكم في اتش مارت'],
        
        ['en', 'add_to_cart', 'Add to Cart'],
        ['es', 'add_to_cart', 'Añadir al carrito'],
        ['ar', 'add_to_cart', 'أضف إلى السلة'],
        
        ['en', 'checkout', 'Checkout'],
        ['es', 'checkout', 'Pagar'],
        ['ar', 'checkout', 'الدفع والتسجيل'],
        
        ['en', 'coupon_applied', 'Coupon discount applied!'],
        ['es', 'coupon_applied', '¡Descuento de cupón aplicado!'],
        ['ar', 'coupon_applied', 'تم تطبيق خصم الكوبون!']
    ];
    foreach ($seeds as $s) {
        $mydb->setQuery("INSERT INTO `translations_cache` (`lang_code`, `text_key`, `translated_text`) VALUES ('{$s[0]}', '{$s[1]}', '{$s[2]}')");
        $mydb->executeQuery();
    }
}

// Fetch language codes present
$mydb->setQuery("SELECT DISTINCT `lang_code` FROM `translations_cache` ORDER BY `lang_code` ASC");
$languages = $mydb->loadResultList();

$selected_lang = isset($_GET['lang']) ? $_GET['lang'] : '';

// Query translation strings
$sql = "SELECT * FROM `translations_cache`";
if (!empty($selected_lang)) {
    $sql .= " WHERE `lang_code` = '" . $mydb->escape_value($selected_lang) . "'";
}
$sql .= " ORDER BY `text_key` ASC, `lang_code` ASC";
$mydb->setQuery($sql);
$translations = $mydb->loadResultList();
?>

<div class="row">
    <!-- Add Translation Form -->
    <div class="col-md-4">
        <div class="panel panel-default" style="border-radius: 12px; box-shadow: var(--shadow); border: 1px solid var(--border-color); background: var(--card-bg);">
            <div class="panel-heading" style="background: var(--card-header-bg); border-bottom: 1px solid var(--border-color); padding: 20px; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <h3 style="margin: 0; font-weight: 800; font-size: 18px; color: var(--primary-light);"><i class="fa fa-plus-circle"></i> Add Translation</h3>
                <p style="margin: 4px 0 0 0; font-size: 12px; color: var(--text-muted);">Add new dictionary labels for multi-lingual storefront rendering.</p>
            </div>
            
            <div class="panel-body" style="padding: 20px;">
                <form action="index.php?view=add" method="post">
                    <div class="form-group">
                        <label style="font-weight: 600; font-size: 13px;">Language Code</label>
                        <select name="lang_code" class="form-control" required style="border-radius: 6px;">
                            <option value="en">English (en)</option>
                            <option value="es">Spanish (es)</option>
                            <option value="ar">Arabic (ar - RTL)</option>
                            <option value="fr">French (fr)</option>
                            <option value="de">German (de)</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label style="font-weight: 600; font-size: 13px;">Dictionary Key</label>
                        <input type="text" name="text_key" class="form-control" placeholder="e.g. btn_submit_order" required style="border-radius: 6px; font-size: 13px;">
                        <small class="text-muted" style="font-size: 11px;">Keys must be unique, lowercase, and separated by underscores.</small>
                    </div>
                    
                    <div class="form-group">
                        <label style="font-weight: 600; font-size: 13px;">Translated Content</label>
                        <textarea name="translated_text" class="form-control" rows="4" placeholder="Enter translated text..." required style="border-radius: 6px; font-size: 13px; resize: none;"></textarea>
                    </div>
                    
                    <button type="submit" name="save" class="btn btn-primary btn-block" style="background: var(--primary-light); border: none; font-weight: 700; font-size: 14px; padding: 10px; border-radius: 6px;">
                        <i class="fa fa-check"></i> Register String
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Translation Manager List -->
    <div class="col-md-8">
        <div class="panel panel-default" style="border-radius: 12px; box-shadow: var(--shadow); border: 1px solid var(--border-color); background: var(--card-bg);">
            <div class="panel-heading" style="background: var(--card-header-bg); border-bottom: 1px solid var(--border-color); padding: 20px; border-top-left-radius: 12px; border-top-right-radius: 12px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h3 style="margin: 0; font-weight: 800; font-size: 18px; color: var(--primary-light);"><i class="fa fa-language"></i> Localization Dictionary Cache</h3>
                    <p style="margin: 4px 0 0 0; font-size: 12px; color: var(--text-muted);">Manage storefront translation strings stored in cached key-value store.</p>
                </div>
                
                <div style="display: flex; gap: 10px; align-items: center;">
                    <label style="font-size: 12px; font-weight: 600; margin: 0; color: var(--text-muted);">Filter Lang:</label>
                    <select onchange="location.href='index.php?lang=' + this.value" class="form-control input-sm" style="width: auto; display: inline-block; border-radius: 6px;">
                        <option value="">All Languages</option>
                        <?php foreach ($languages as $lang) { ?>
                            <option value="<?php echo htmlspecialchars($lang->lang_code); ?>" <?php echo $selected_lang === $lang->lang_code ? 'selected' : ''; ?>>
                                <?php echo strtoupper(htmlspecialchars($lang->lang_code)); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            
            <div class="panel-body" style="padding: 20px;">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dash-table">
                        <thead>
                            <tr style="background: var(--table-header-bg);">
                                <th style="font-weight: 700; font-size: 12px;">Lang</th>
                                <th style="font-weight: 700; font-size: 12px;">String Key</th>
                                <th style="font-weight: 700; font-size: 12px;">Translation text</th>
                                <th style="font-weight: 700; font-size: 12px; text-align: center; width: 150px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($translations as $row) { ?>
                                <tr>
                                    <td><span class="label label-info" style="font-weight: 800; font-size: 10px; text-transform: uppercase;"><?php echo htmlspecialchars($row->lang_code); ?></span></td>
                                    <td><code><?php echo htmlspecialchars($row->text_key); ?></code></td>
                                    <td>
                                        <div id="text-display-<?php echo $row->translation_id; ?>" style="font-size: 13px;">
                                            <?php echo htmlspecialchars($row->translated_text); ?>
                                        </div>
                                        <form id="edit-form-<?php echo $row->translation_id; ?>" action="index.php?view=edit" method="post" style="display: none;">
                                            <input type="hidden" name="translation_id" value="<?php echo $row->translation_id; ?>">
                                            <div class="input-group">
                                                <input type="text" name="translated_text" class="form-control input-sm" value="<?php echo htmlspecialchars($row->translated_text); ?>" required>
                                                <span class="input-group-btn">
                                                    <button type="submit" name="save_edit" class="btn btn-sm btn-success"><i class="fa fa-save"></i></button>
                                                    <button type="button" onclick="toggleEdit(<?php echo $row->translation_id; ?>, false)" class="btn btn-sm btn-default"><i class="fa fa-times"></i></button>
                                                </span>
                                            </div>
                                        </form>
                                    </td>
                                    <td style="text-align: center;">
                                        <button onclick="toggleEdit(<?php echo $row->translation_id; ?>, true)" class="btn btn-xs btn-warning" style="border-radius: 4px; font-weight: 700;"><i class="fa fa-edit"></i> Edit</button>
                                        <a href="index.php?view=delete&id=<?php echo $row->translation_id; ?>" class="btn btn-xs btn-danger" style="border-radius: 4px; font-weight: 700;" onclick="return confirm('Are you sure you want to delete this translation key?');"><i class="fa fa-trash"></i> Delete</a>
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
    var display = document.getElementById('text-display-' + id);
    var form = document.getElementById('edit-form-' + id);
    if (show) {
        display.style.display = 'none';
        form.style.display = 'block';
    } else {
        display.style.display = 'block';
        form.style.display = 'none';
    }
}
</script>
