<?php
// Setup Admin Account Script
// Visit this file via URL to create the admin account

require_once(__DIR__ . '/../include/config.php');
require_once(__DIR__ . '/../include/database.php');

global $mydb;

echo "<!DOCTYPE html>
<html>
<head>
    <title>Admin Setup</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        .success { color: green; padding: 10px; background: #e8f5e9; border: 1px solid green; border-radius: 4px; }
        .error { color: red; padding: 10px; background: #ffebee; border: 1px solid red; border-radius: 4px; }
        h1 { color: #333; }
    </style>
</head>
<body>
<div class='container'>
<h1>Admin Account Setup</h1>";

try {
    // Delete existing admin account if it exists
    $mydb->setQuery("DELETE FROM tbluseraccount WHERE U_USERNAME = 'admin@hmart.com'");
    $mydb->executeQuery();

    // Insert new admin account with SHA1 hashed password
    $admin_pass = sha1('admin');
    $query = "INSERT INTO tbluseraccount (USERID, U_NAME, U_USERNAME, U_PASS, U_ROLE, USERIMAGE)
              VALUES (128, 'Admin', 'admin@hmart.com', '{$admin_pass}', 'Administrator', '')";

    $mydb->setQuery($query);
    if ($mydb->executeQuery()) {
        echo "<div class='success'>✓ Admin account created successfully!<br>";
        echo "Email: admin@hmart.com<br>";
        echo "Password: admin<br>";
        echo "You can now <a href='" . rtrim($web_root, '/') . "/admin/login.php'>login here</a></div>";
    } else {
        echo "<div class='error'>✗ Error: " . $mydb->error_msg . "</div>";
    }
} catch (Exception $e) {
    echo "<div class='error'>✗ Exception: " . $e->getMessage() . "</div>";
}

echo "</div></body></html>";
?>
