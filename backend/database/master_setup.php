<?php
/**
 * Master Setup Script - Creates Admin Account & Seeds Products
 * Visit: https://hmart.gt.tc/backend/database/master_setup.php
 */

require_once(__DIR__ . '/../include/config.php');
require_once(__DIR__ . '/../include/database.php');

global $mydb;

// Set execution time to unlimited for product seeding
ini_set('max_execution_time', 0);
set_time_limit(0);

?>
<!DOCTYPE html>
<html>
<head>
    <title>H-Mart Database Setup</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 3px solid #0066cc; padding-bottom: 10px; }
        .status { margin: 20px 0; padding: 15px; border-radius: 4px; }
        .success { background: #e8f5e9; border-left: 4px solid #4caf50; color: #2e7d32; }
        .error { background: #ffebee; border-left: 4px solid #f44336; color: #c62828; }
        .info { background: #e3f2fd; border-left: 4px solid #2196f3; color: #1565c0; }
        .progress { margin: 10px 0; }
        .step { padding: 10px; margin: 5px 0; background: #f9f9f9; border-left: 3px solid #2196f3; }
        .checkmark { color: #4caf50; font-weight: bold; }
        .cross { color: #f44336; font-weight: bold; }
        footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; text-align: center; color: #666; font-size: 12px; }
    </style>
</head>
<body>
<div class='container'>
    <h1>🚀 H-Mart Database Setup Master</h1>
    <p>This will setup your admin account and seed all products.</p>

<?php

try {
    // ===== STEP 1: Create Admin Account =====
    echo "<div class='status info'><strong>Step 1: Creating Admin Account...</strong></div>";

    $admin_email = 'admin@hmart.com';
    $admin_pass = sha1('admin');

    // Delete existing admin if present
    $mydb->setQuery("DELETE FROM tbluseraccount WHERE U_USERNAME = '{$admin_email}'");
    $mydb->executeQuery();

    // Insert admin account
    $query = "INSERT INTO tbluseraccount (USERID, U_NAME, U_USERNAME, U_PASS, U_ROLE, USERIMAGE)
              VALUES (128, 'Admin', '{$admin_email}', '{$admin_pass}', 'Administrator', '')";

    $mydb->setQuery($query);
    if ($mydb->executeQuery()) {
        echo "<div class='step'><span class='checkmark'>✓</span> Admin account created successfully!</div>";
        echo "<div class='step'>Email: <strong>admin@hmart.com</strong></div>";
        echo "<div class='step'>Password: <strong>admin</strong></div>";
    } else {
        echo "<div class='status error'>✗ Admin account creation failed: " . $mydb->error_msg . "</div>";
    }

    // ===== STEP 2: Verify Categories Exist =====
    echo "<div class='status info'><strong>Step 2: Verifying Categories...</strong></div>";

    $categories = [
        "SHOES", "BAGS", "CLOTHING", "INTERIORS", "HOUSEHOLDS", "FASHION",
        "KIDS", "WOMENS", "MENS", "SPORTSWEAR", "MOBILE", "ELECTRONICS",
        "LAPTOPS", "AUDIO", "CAMERAS", "GROCERY"
    ];

    foreach ($categories as $cat_name) {
        $cat_name_escaped = mysqli_real_escape_string(mysqli_connect(server, user, pass, database_name), $cat_name);
        $mydb->setQuery("SELECT CATEGID FROM tblcategory WHERE CATEGORIES = '{$cat_name_escaped}'");
        $cur = $mydb->executeQuery();

        if ($mydb->num_rows($cur) == 0) {
            $mydb->setQuery("INSERT INTO tblcategory (CATEGORIES, USERID) VALUES ('{$cat_name_escaped}', 0)");
            $mydb->executeQuery();
            echo "<div class='step'><span class='checkmark'>✓</span> Category '{$cat_name}' created</div>";
        } else {
            echo "<div class='step'><span class='checkmark'>✓</span> Category '{$cat_name}' exists</div>";
        }
    }

    // ===== STEP 3: Prepare for Product Seeding =====
    echo "<div class='status info'><strong>Step 3: Preparing Product Database...</strong></div>";

    // Get next product ID
    $mydb->setQuery("SELECT MAX(PROID) as max_id FROM tblproduct");
    $cur = $mydb->executeQuery();
    $row = $mydb->loadSingleResult();
    $start_proid = max(300001, $row->max_id + 1);

    // Clean up old migrated products
    $mydb->setQuery("SELECT COUNT(*) as count FROM tblproduct WHERE PROID >= 300001");
    $cur = $mydb->executeQuery();
    $row = $mydb->loadSingleResult();
    $existing_count = $row->count;

    if ($existing_count > 0) {
        echo "<div class='step'><span class='checkmark'>✓</span> Removing {$existing_count} previously seeded products...</div>";
        $mydb->setQuery("DELETE FROM tblpromopro WHERE PROID >= 300001");
        $mydb->executeQuery();
        $mydb->setQuery("DELETE FROM tblproduct WHERE PROID >= 300001");
        $mydb->executeQuery();
    }

    echo "<div class='status success'>
        <span class='checkmark'>✓</span> <strong>Setup Completed!</strong><br>
        <strong>Admin Account:</strong> admin@hmart.com / admin<br>
        <strong>Categories:</strong> All 16 categories are ready<br><br>
        <strong>Next Steps:</strong><br>
        1. <a href='seed_products.php?run=1' target='_blank'><strong>Click here to Seed Products</strong></a> (This will take a few minutes)<br>
        2. After products are seeded, visit <a href='" . rtrim($web_root, '/') . "/admin/login.php' target='_blank'><strong>Admin Login</strong></a><br>
        3. Then you can delete this setup file for security
    </div>";

} catch (Exception $e) {
    echo "<div class='status error'>
        <span class='cross'>✗</span> <strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "
    </div>";
}

?>

    <footer>
        <p>💡 Tip: After login, visit <strong>admin/login.php</strong> and delete both <strong>master_setup.php</strong> and <strong>setup_admin.php</strong> for security</p>
    </footer>
</div>
</body>
</html>
