<?php
header('Content-Type: text/html; charset=utf-8');
require_once("../include/initialize.php");
global $mydb;

?>
<!DOCTYPE html>
<html>
<head>
    <title>Setup</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f0f0f0; }
        .box { max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 5px; }
        .success { color: green; padding: 10px; background: #e8f5e9; margin: 10px 0; border-radius: 3px; }
        .error { color: red; padding: 10px; background: #ffebee; margin: 10px 0; border-radius: 3px; }
        h1 { color: #333; }
    </style>
</head>
<body>
<div class="box">
    <h1>H-Mart Setup</h1>

<?php

// Step 1: Create Admin Account
echo "<h2>Step 1: Admin Account</h2>";
$admin_email = 'admin@hmart.com';
$admin_pass = sha1('admin');

$mydb->setQuery("DELETE FROM tbluseraccount WHERE U_USERNAME = '{$admin_email}'");
$mydb->executeQuery();

$insert_sql = "INSERT INTO tbluseraccount (USERID, U_NAME, U_USERNAME, U_PASS, U_ROLE, USERIMAGE)
              VALUES (128, 'Admin', '{$admin_email}', '{$admin_pass}', 'Administrator', '')";
$mydb->setQuery($insert_sql);

if ($mydb->executeQuery()) {
    echo "<div class='success'>✓ Admin account created: admin@hmart.com / admin</div>";
} else {
    echo "<div class='error'>✗ Admin creation failed: " . $mydb->error_msg . "</div>";
}

// Step 2: Create Categories
echo "<h2>Step 2: Categories</h2>";
$categories = array("SHOES", "BAGS", "CLOTHING", "INTERIORS", "HOUSEHOLDS", "FASHION", "KIDS", "WOMENS", "MENS", "SPORTSWEAR", "MOBILE", "ELECTRONICS", "LAPTOPS", "AUDIO", "CAMERAS", "GROCERY");

$cat_count = 0;
foreach ($categories as $cat) {
    $mydb->setQuery("SELECT CATEGID FROM tblcategory WHERE CATEGORIES = '{$cat}' LIMIT 1");
    $res = $mydb->executeQuery();

    if ($mydb->num_rows($res) == 0) {
        $mydb->setQuery("INSERT INTO tblcategory (CATEGORIES, USERID) VALUES ('{$cat}', 0)");
        $mydb->executeQuery();
        $cat_count++;
    }
}

echo "<div class='success'>✓ {$cat_count} new categories created (16 total)</div>";

echo "<h2>✅ Setup Complete!</h2>";
echo "<p><strong>Next Steps:</strong></p>";
echo "<ol>";
echo "<li>Visit: <a href='seed_products.php?run=1' target='_blank'>seed_products.php?run=1</a> (wait 5-10 min)</li>";
echo "<li>Then visit: <a href='generate_placeholder_images.php?run=1' target='_blank'>generate_placeholder_images.php?run=1</a> (wait 1-2 min)</li>";
echo "<li>Login at: <a href='" . web_root . "admin/login.php' target='_blank'>Admin Login</a></li>";
echo "</ol>";

?>
</div>
</body>
</html>
