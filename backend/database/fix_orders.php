<?php
header('Content-Type: text/html; charset=utf-8');
require_once("../include/initialize.php");
global $mydb;

?>
<!DOCTYPE html>
<html>
<head>
    <title>Fix Orders</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f0f0f0; }
        .box { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; }
        h1 { color: #333; }
        .success { color: green; padding: 15px; background: #e8f5e9; border: 1px solid #4caf50; border-radius: 4px; margin: 10px 0; }
        .error { color: red; padding: 15px; background: #ffebee; border: 1px solid #f44336; border-radius: 4px; margin: 10px 0; }
        .info { color: #1565c0; padding: 15px; background: #e3f2fd; border: 1px solid #2196f3; border-radius: 4px; margin: 10px 0; }
        button { padding: 10px 20px; background: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; }
        button:hover { background: #45a049; }
    </style>
</head>
<body>
<div class="box">
    <h1>🔧 Fix Duplicate Order Numbers</h1>

<?php

echo "<h2>1. Current Orders with Duplicate Numbers:</h2>";
$mydb->setQuery("SELECT ORDEREDNUM, COUNT(*) as count FROM tblordersummary
                GROUP BY ORDEREDNUM HAVING count > 1
                ORDER BY count DESC");
$duplicates = $mydb->loadResultList();

if (count($duplicates) > 0) {
    echo "<div class='error'>Found " . count($duplicates) . " duplicate order numbers</div>";
    foreach ($duplicates as $dup) {
        echo "- Order #" . $dup->ORDEREDNUM . ": " . $dup->count . " entries<br>";
    }
} else {
    echo "<div class='success'>✓ No duplicate order numbers found</div>";
}

echo "<h2>2. Clean Up Duplicate Orders:</h2>";

if (isset($_POST['cleanup'])) {
    // Delete duplicate summary entries (keep only the first one)
    $mydb->setQuery("DELETE FROM tblordersummary
                    WHERE ORDERSUMMARYID NOT IN (
                        SELECT MIN(ORDERSUMMARYID) FROM (
                            SELECT MIN(ORDERSUMMARYID) as ORDERSUMMARYID
                            FROM tblordersummary
                            GROUP BY ORDEREDNUM
                        ) as temp
                    )");

    if ($mydb->executeQuery()) {
        $deleted = mysqli_affected_rows(mysqli_connect(server, user, pass, database_name));
        echo "<div class='success'>✓ Cleaned up duplicate orders (removed entries)</div>";
        echo "<div class='info'>Next time you place an order, use the updated controller.php file</div>";
        echo "<p><strong>IMPORTANT:</strong> Upload this file to your server: <code>/backend/customer/controller.php</code></p>";
    } else {
        echo "<div class='error'>✗ Cleanup failed: " . $mydb->error_msg . "</div>";
    }
} else {
    echo "<form method='POST'>";
    echo "<p>This will remove duplicate order entries from the database.</p>";
    echo "<button name='cleanup' type='submit'>Clean Up Duplicates</button>";
    echo "</form>";
}

echo "<h2>3. Verify Order Numbers After Fix:</h2>";
$mydb->setQuery("SELECT ORDEREDNUM, COUNT(*) as count FROM tblordersummary
                GROUP BY ORDEREDNUM ORDER BY ORDEREDNUM DESC LIMIT 10");
$orders = $mydb->loadResultList();

if (count($orders) > 0) {
    echo "<table style='width: 100%; border-collapse: collapse;'>";
    echo "<tr style='background: #f5f5f5;'><th style='border: 1px solid #ddd; padding: 10px;'>Order #</th><th style='border: 1px solid #ddd; padding: 10px;'>Count</th></tr>";
    foreach ($orders as $o) {
        $color = $o->count > 1 ? '#ffebee' : '#e8f5e9';
        echo "<tr style='background: {$color};'><td style='border: 1px solid #ddd; padding: 10px;'>" . $o->ORDEREDNUM . "</td><td style='border: 1px solid #ddd; padding: 10px;'>" . $o->count . "</td></tr>";
    }
    echo "</table>";
}

?>

<div class="info" style="margin-top: 20px;">
    <strong>⚠️ CRITICAL:</strong><br>
    You MUST upload the fixed <code>controller.php</code> file to prevent this error from happening again!<br><br>
    Location: <code>/backend/customer/controller.php</code>
</div>

</div>
</body>
</html>
