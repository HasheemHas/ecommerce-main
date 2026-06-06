<?php
/**
 * One-time installer for Smart E-Commerce ML tables.
 * Visit: http://localhost/ecommerce/install_smart_features.php
 * Delete this file after successful installation.
 */
require_once('include/initialize.php');

header('Content-Type: text/html; charset=utf-8');
$sqlFile = __DIR__ . '/database/smart_features.sql';
if (!file_exists($sqlFile)) {
    die('SQL file not found.');
}

$mydb = new Database();
$sql = file_get_contents($sqlFile);
$statements = array_filter(array_map('trim', preg_split('/;\s*[\r\n]+/', $sql)));

echo '<h2>Smart Features Installer</h2><ul>';
foreach ($statements as $stmt) {
    if ($stmt === '' || strpos($stmt, '--') === 0) {
        continue;
    }
    $mydb->setQuery($stmt);
    if ($mydb->executeQuery()) {
        echo '<li style="color:green;">OK: ' . htmlspecialchars(substr($stmt, 0, 60)) . '…</li>';
    } else {
        echo '<li style="color:red;">Error: ' . htmlspecialchars($mydb->error_msg) . '</li>';
    }
}
echo '</ul><p><strong>Done.</strong> You can now use OTP login, recommendations, fraud detection, and admin analytics.</p>';
echo '<p>Delete <code>install_smart_features.php</code> for security.</p>';
