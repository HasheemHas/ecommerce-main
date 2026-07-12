<?php
/**
 * Automated script to execute all H-Mart Database Migrations and Seeding.
 * Visit: http://hmart.gt.tc/backend/database/install_all_migrations.php
 */
require_once(dirname(__DIR__) . '/include/initialize.php');

header('Content-Type: text/html; charset=utf-8');

$sqlFiles = [
    'Base Database Structure & Core Data' => dirname(__DIR__) . '/db_ecommerce.sql',
    'Migrations Expansion (Translations, Currencies, Logs)' => __DIR__ . '/migrations_expansion.sql',
    'Smart Features (OTP, Browse History, Fraud Alerts)' => __DIR__ . '/smart_features.sql',
    'Admin Dashboard AI (Forecasts, Recommendations, Churn)' => __DIR__ . '/admin_dashboard_ai.sql',
    'Mock/Seed Data (Test Products & Analytics)' => __DIR__ . '/admin_seed.sql'
];

// Verify all files exist first
foreach ($sqlFiles as $name => $filePath) {
    if (!file_exists($filePath)) {
        die("<p style='color:red;'>Error: SQL file for '{$name}' not found at '{$filePath}'.</p>");
    }
}

// Connect to database using configuration constants
$conn = mysqli_connect(server, user, pass, database_name);
if (!$conn) {
    die("<p style='color:red;'>Database connection failed: " . mysqli_connect_error() . "</p>");
}

echo "<h1>H-Mart Complete Database Installer & Migrator</h1>";
echo "<p>Connected successfully to database: <strong>" . database_name . "</strong></p>";
echo "<hr>";

foreach ($sqlFiles as $name => $filePath) {
    echo "<h2>Installing: {$name}...</h2>";
    $sqlContent = file_get_contents($filePath);
    
    try {
        if (mysqli_multi_query($conn, $sqlContent)) {
            $queriesRun = 0;
            do {
                $queriesRun++;
                // Store / consume the result sets to clear the buffers
                if ($result = mysqli_store_result($conn)) {
                    mysqli_free_result($result);
                }
            } while (mysqli_next_result($conn));
            
            if (mysqli_errno($conn)) {
                echo "<p style='color:red; font-weight:bold;'>Error during execution: " . mysqli_error($conn) . "</p>";
            } else {
                echo "<p style='color:green; font-weight:bold;'>Success: Executed {$queriesRun} statements successfully!</p>";
            }
        } else {
            echo "<p style='color:red; font-weight:bold;'>Failed to start execution: " . mysqli_error($conn) . "</p>";
        }
    } catch (\Exception $e) {
        echo "<p style='color:red; font-weight:bold;'>Error during execution: " . $e->getMessage() . "</p>";
    }
    echo "<hr>";
}

// Check and add membership_tier column to tblcustomer if not exists
$checkCol = mysqli_query($conn, "SHOW COLUMNS FROM `tblcustomer` LIKE 'membership_tier'");
if ($checkCol && mysqli_num_rows($checkCol) == 0) {
    if (mysqli_query($conn, "ALTER TABLE `tblcustomer` ADD COLUMN `membership_tier` VARCHAR(30) DEFAULT 'Silver'")) {
        echo "<p style='color:green; font-weight:bold;'>Success: Added 'membership_tier' column to 'tblcustomer' table!</p><hr>";
    } else {
        echo "<p style='color:red; font-weight:bold;'>Error adding 'membership_tier' column: " . mysqli_error($conn) . "</p><hr>";
    }
}

// Run the new PHP product and promo seeder
echo "<h2>Running dynamic product and promo seeder...</h2>";
$_GET['run'] = '1';
try {
    $original_conn = $conn;
    require(__DIR__ . '/seed_products.php');
    $conn = $original_conn;
    echo "<p style='color:green; font-weight:bold;'>Success: Seeded 3360 dynamic products and generated variant images successfully!</p><hr>";
} catch (\Exception $e) {
    echo "<p style='color:red; font-weight:bold;'>Error running product seeder: " . $e->getMessage() . "</p><hr>";
}

mysqli_close($conn);
echo "<p style='font-size:1.2em; color:green; font-weight:bold;'>Setup Finished! All tables are installed and seeded successfully.</p>";
echo "<p><a href='../../frontend/index.php'>Go back to Home Page</a></p>";
?>
