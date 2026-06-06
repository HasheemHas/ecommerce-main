<?php
/**
 * Migration runner using mysqli_multi_query.
 */
require_once(dirname(__DIR__) . '/include/initialize.php');

$schemaFile = __DIR__ . '/admin_dashboard_ai.sql';
$seedFile = __DIR__ . '/admin_seed.sql';

if (!file_exists($schemaFile) || !file_exists($seedFile)) {
    die('SQL schema or seed file not found.');
}

// Get raw connection details from config.php constants
$conn = mysqli_connect(server, user, pass, database_name);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

echo "<h2>Executing Database Migrations (Schema)...</h2>";
$schemaSql = file_get_contents($schemaFile);

if (mysqli_multi_query($conn, $schemaSql)) {
    do {
        // Store first result set
        if ($result = mysqli_store_result($conn)) {
            mysqli_free_result($result);
        }
    } while (mysqli_next_result($conn));
    
    if (mysqli_errno($conn)) {
        echo "<p style='color:red;'>Error during schema execution: " . mysqli_error($conn) . "</p>";
    } else {
        echo "<p style='color:green;'>Schema migrations executed successfully!</p>";
    }
} else {
    echo "<p style='color:red;'>Failed to start schema execution: " . mysqli_error($conn) . "</p>";
}

echo "<h2>Executing Database Seeding...</h2>";
$seedSql = file_get_contents($seedFile);

if (mysqli_multi_query($conn, $seedSql)) {
    do {
        if ($result = mysqli_store_result($conn)) {
            mysqli_free_result($result);
        }
    } while (mysqli_next_result($conn));
    
    if (mysqli_errno($conn)) {
        echo "<p style='color:red;'>Error during seeding execution: " . mysqli_error($conn) . "</p>";
    } else {
        echo "<p style='color:green;'>Database seeding executed successfully!</p>";
    }
} else {
    echo "<p style='color:red;'>Failed to start seeding execution: " . mysqli_error($conn) . "</p>";
}

mysqli_close($conn);
echo "<p><strong>Finished migration.</strong></p>";
?>
