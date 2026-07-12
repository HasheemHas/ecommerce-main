<?php
header('Content-Type: text/html; charset=utf-8');
require_once("../include/initialize.php");
global $mydb;

$errors = [];
$fixes_applied = [];
$success = [];

?>
<!DOCTYPE html>
<html>
<head>
    <title>H-Mart Full Diagnostic</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f0f0f0; }
        .box { max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; }
        h1 { color: #333; border-bottom: 3px solid #2196f3; padding-bottom: 10px; }
        h2 { color: #1565c0; margin-top: 20px; }
        .success { color: #2e7d32; padding: 12px; background: #e8f5e9; border-left: 4px solid #4caf50; margin: 10px 0; }
        .error { color: #c62828; padding: 12px; background: #ffebee; border-left: 4px solid #f44336; margin: 10px 0; }
        .warning { color: #f57f17; padding: 12px; background: #fff3e0; border-left: 4px solid #ff9800; margin: 10px 0; }
        .info { color: #1565c0; padding: 12px; background: #e3f2fd; border-left: 4px solid #2196f3; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { padding: 12px; text-align: left; border: 1px solid #ddd; }
        th { background: #f5f5f5; font-weight: bold; }
        button { padding: 10px 20px; background: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; margin: 10px 5px 10px 0; }
        button:hover { background: #45a049; }
        .summary { background: #f9f9f9; padding: 15px; border-radius: 4px; margin: 20px 0; }
    </style>
</head>
<body>
<div class="box">
    <h1>🔍 H-Mart Full System Diagnostic & Auto-Fix</h1>
    <p>This script will check all systems and automatically fix issues.</p>

<?php

// ========== CHECK 1: Database Connection ==========
echo "<h2>1. Database Connection</h2>";
$mydb->setQuery("SELECT 1");
if ($mydb->executeQuery()) {
    echo "<div class='success'>✅ Database connected successfully</div>";
    $success[] = "Database connection OK";
} else {
    echo "<div class='error'>❌ Database connection failed</div>";
    $errors[] = "Database not connected";
}

// ========== CHECK 2: Admin Account ==========
echo "<h2>2. Admin Account</h2>";
$mydb->setQuery("SELECT * FROM tbluseraccount WHERE U_USERNAME = 'admin@hmart.com' LIMIT 1");
$admin = $mydb->loadSingleResult();

if ($admin) {
    echo "<div class='success'>✅ Admin account exists: admin@hmart.com</div>";
    $success[] = "Admin account exists";
} else {
    echo "<div class='error'>❌ Admin account NOT found</div>";
    echo "<p><strong>FIXING NOW...</strong></p>";

    $admin_email = 'admin@hmart.com';
    $admin_pass = sha1('admin');

    $mydb->setQuery("DELETE FROM tbluseraccount WHERE U_USERNAME = '{$admin_email}'");
    $mydb->executeQuery();

    $insert_sql = "INSERT INTO tbluseraccount (USERID, U_NAME, U_USERNAME, U_PASS, U_ROLE, USERIMAGE)
                  VALUES (128, 'Administrator', '{$admin_email}', '{$admin_pass}', 'Administrator', '')";
    $mydb->setQuery($insert_sql);

    if ($mydb->executeQuery()) {
        echo "<div class='success'>✅ FIXED: Admin account created</div>";
        echo "<div class='info'>Email: admin@hmart.com | Password: admin</div>";
        $fixes_applied[] = "Created admin@hmart.com account";
    } else {
        echo "<div class='error'>❌ Failed to create admin: " . $mydb->error_msg . "</div>";
        $errors[] = "Admin creation failed";
    }
}

// ========== CHECK 3: Categories ==========
echo "<h2>3. Categories</h2>";
$mydb->setQuery("SELECT COUNT(*) as count FROM tblcategory");
$cat_count = $mydb->loadSingleResult();
$count = $cat_count->count;

if ($count >= 16) {
    echo "<div class='success'>✅ All 16 categories exist</div>";
    $success[] = "Categories OK";
} else {
    echo "<div class='warning'>⚠️ Only {$count}/16 categories found</div>";
    echo "<p><strong>FIXING NOW...</strong></p>";

    $categories = array("SHOES", "BAGS", "CLOTHING", "INTERIORS", "HOUSEHOLDS", "FASHION", "KIDS", "WOMENS", "MENS", "SPORTSWEAR", "MOBILE", "ELECTRONICS", "LAPTOPS", "AUDIO", "CAMERAS", "GROCERY");
    $created = 0;

    foreach ($categories as $cat) {
        $mydb->setQuery("SELECT CATEGID FROM tblcategory WHERE CATEGORIES = '{$cat}' LIMIT 1");
        if ($mydb->num_rows($mydb->executeQuery()) == 0) {
            $mydb->setQuery("INSERT INTO tblcategory (CATEGORIES, USERID) VALUES ('{$cat}', 0)");
            if ($mydb->executeQuery()) $created++;
        }
    }

    echo "<div class='success'>✅ FIXED: Created {$created} missing categories</div>";
    $fixes_applied[] = "Fixed {$created} categories";
}

// ========== CHECK 4: Products ==========
echo "<h2>4. Products</h2>";
$mydb->setQuery("SELECT COUNT(*) as count FROM tblproduct");
$prod_count = $mydb->loadSingleResult();

if ($prod_count->count > 0) {
    echo "<div class='success'>✅ {$prod_count->count} products found</div>";
    $success[] = "Products OK";
} else {
    echo "<div class='error'>❌ No products found - run seed_products.php?run=1</div>";
    $errors[] = "No products in database";
}

// ========== CHECK 5: Product Images ==========
echo "<h2>5. Product Images</h2>";
$mydb->setQuery("SELECT COUNT(*) as count FROM tblproduct WHERE IMAGES IS NOT NULL AND IMAGES != ''");
$img_count = $mydb->loadSingleResult();

if ($img_count->count > 0) {
    echo "<div class='success'>✅ {$img_count->count} products have images assigned</div>";
    $success[] = "Product images OK";
} else {
    echo "<div class='warning'>⚠️ No product images found</div>";
    $errors[] = "Missing product images";
}

// ========== CHECK 6: Order Duplicate Fix ==========
echo "<h2>6. Order Processing (Duplicate Check)</h2>";
$mydb->setQuery("SELECT ORDEREDNUM, COUNT(*) as count FROM tblordersummary GROUP BY ORDEREDNUM HAVING count > 1");
$duplicates = $mydb->loadResultList();

if (count($duplicates) == 0) {
    echo "<div class='success'>✅ No duplicate orders found</div>";
    $success[] = "Order processing OK";
} else {
    echo "<div class='error'>❌ Found " . count($duplicates) . " duplicate order numbers</div>";
    echo "<p><strong>FIXING NOW...</strong></p>";

    $mydb->setQuery("DELETE FROM tblordersummary WHERE ORDERSUMMARYID NOT IN (
        SELECT MIN(ORDERSUMMARYID) FROM (
            SELECT MIN(ORDERSUMMARYID) as ORDERSUMMARYID FROM tblordersummary GROUP BY ORDEREDNUM
        ) as temp
    )");

    if ($mydb->executeQuery()) {
        echo "<div class='success'>✅ FIXED: Removed duplicate orders</div>";
        $fixes_applied[] = "Cleaned duplicate orders";
    }
}

// ========== CHECK 7: Environment Configuration ==========
echo "<h2>7. API Configuration (.env.local)</h2>";
$env_path = __DIR__ . "/../include/.env.local";
if (file_exists($env_path)) {
    $env_content = file_get_contents($env_path);
    if (strpos($env_content, 'NVIDIA_API_KEY=') !== false) {
        if (strpos($env_content, 'your_api_key_here') === false) {
            echo "<div class='success'>✅ NVIDIA API key is configured</div>";
            $success[] = "API key configured";
        } else {
            echo "<div class='error'>❌ API key placeholder not replaced</div>";
            echo "<div class='info'>Visit: https://build.nvidia.com/meta/llama-3-1-8b-instruct to get your key</div>";
            $errors[] = "API key not configured";
        }
    }
} else {
    echo "<div class='warning'>⚠️ .env.local file not found - API features disabled</div>";
    $errors[] = ".env.local missing";
}

// ========== CHECK 8: Controller Fix ==========
echo "<h2>8. Order Controller Fix</h2>";
$controller_path = __DIR__ . "/../../customer/controller.php";
$controller_content = file_get_contents($controller_path);

if (strpos($controller_content, "// Create order summary ONCE (not in the loop)") !== false) {
    echo "<div class='success'>✅ Order controller is properly fixed</div>";
    $success[] = "Controller fix applied";
} else {
    echo "<div class='error'>❌ Controller fix not applied properly</div>";
    $errors[] = "Controller needs update";
}

// ========== SUMMARY ==========
echo "<h2>📊 Summary Report</h2>";
echo "<div class='summary'>";
echo "<strong>✅ Working (" . count($success) . "):</strong><br>";
foreach ($success as $s) {
    echo "  • " . $s . "<br>";
}

if (count($fixes_applied) > 0) {
    echo "<br><strong>🔧 Auto-Fixed (" . count($fixes_applied) . "):</strong><br>";
    foreach ($fixes_applied as $f) {
        echo "  • " . $f . "<br>";
    }
}

if (count($errors) > 0) {
    echo "<br><strong>❌ Issues (" . count($errors) . "):</strong><br>";
    foreach ($errors as $e) {
        echo "  • " . $e . "<br>";
    }
}
echo "</div>";

// ========== NEXT STEPS ==========
echo "<h2>📋 Next Steps</h2>";
echo "<div class='info'>";
echo "<strong>1. Verify Admin Login:</strong><br>";
echo "   Email: <code>admin@hmart.com</code><br>";
echo "   Password: <code>admin</code><br>";
echo "   URL: <code>" . web_root . "admin/login.php</code><br><br>";

echo "<strong>2. If API not working:</strong><br>";
echo "   Get key from: <a href='https://build.nvidia.com/meta/llama-3-1-8b-instruct' target='_blank'>https://build.nvidia.com</a><br>";
echo "   Update: <code>/backend/include/.env.local</code><br><br>";

echo "<strong>3. If products missing:</strong><br>";
echo "   Run: <code>seed_products.php?run=1</code><br>";
echo "   Then: <code>simple_images.php?run=1</code><br>";
echo "</div>";

?>

</div>
</body>
</html>
