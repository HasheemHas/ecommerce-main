<?php
/**
 * Installer script for Expanded H-Mart Admin Dashboard Tables and Seed Data.
 * Visit: http://localhost/ecommerce/database/install_admin_features.php
 */
require_once(dirname(__DIR__) . '/include/initialize.php');

header('Content-Type: text/html; charset=utf-8');

$schemaFile = __DIR__ . '/admin_dashboard_ai.sql';
$seedFile = __DIR__ . '/admin_seed.sql';

if (!file_exists($schemaFile) || !file_exists($seedFile)) {
    die('SQL schema or seed file not found.');
}

global $mydb;

echo '<h2>H-Mart Admin Dashboard Tables Installer</h2>';

// Run Schema Migrations
echo '<h3>Creating 20 New Tables...</h3><ul>';
$schemaSql = file_get_contents($schemaFile);
$statements = array_filter(array_map('trim', preg_split('/;\s*[\r\n]+/', $schemaSql)));

foreach ($statements as $stmt) {
    if ($stmt === '' || strpos($stmt, '--') === 0) {
        continue;
    }
    $mydb->setQuery($stmt);
    if ($mydb->executeQuery()) {
        echo '<li style="color:green;">Executed: ' . htmlspecialchars(substr($stmt, 0, 50)) . '...</li>';
    } else {
        echo '<li style="color:red;">Error: ' . htmlspecialchars($mydb->error_msg) . '</li>';
    }
}
echo '</ul>';

// Run Seeding
echo '<h3>Seeding Testing/Mock Records...</h3><ul>';
$seedSql = file_get_contents($seedFile);
$seedStatements = array_filter(array_map('trim', preg_split('/;\s*[\r\n]+/', $seedSql)));

foreach ($seedStatements as $stmt) {
    if ($stmt === '' || strpos($stmt, '--') === 0) {
        continue;
    }
    $mydb->setQuery($stmt);
    if ($mydb->executeQuery()) {
        echo '<li style="color:green;">Seeded: ' . htmlspecialchars(substr($stmt, 0, 50)) . '...</li>';
    } else {
        echo '<li style="color:red;">Error: ' . htmlspecialchars($mydb->error_msg) . '</li>';
    }
}
echo '</ul>';

echo '<p><strong>Done.</strong> Database setup completed successfully.</p>';
?>
