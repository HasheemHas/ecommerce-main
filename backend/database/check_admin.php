<?php
header('Content-Type: text/html; charset=utf-8');
require_once("../include/initialize.php");
global $mydb;

?>
<!DOCTYPE html>
<html>
<head>
    <title>Check Admin</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f0f0f0; }
        .box { max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 5px; }
        .success { color: green; padding: 10px; background: #e8f5e9; margin: 10px 0; border-radius: 3px; }
        .error { color: red; padding: 10px; background: #ffebee; margin: 10px 0; border-radius: 3px; }
        .info { color: #1565c0; padding: 10px; background: #e3f2fd; margin: 10px 0; border-radius: 3px; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 10px; text-align: left; }
        th { background: #f5f5f5; }
    </style>
</head>
<body>
<div class="box">
    <h1>🔍 Admin Account Diagnostic</h1>

<?php

echo "<h2>1. Check if admin account exists:</h2>";
$mydb->setQuery("SELECT * FROM tbluseraccount WHERE U_USERNAME = 'admin@hmart.com'");
$admin = $mydb->loadSingleResult();

if ($admin) {
    echo "<div class='success'>✓ Admin account EXISTS in database</div>";
    echo "<table>";
    echo "<tr><th>Field</th><th>Value</th></tr>";
    echo "<tr><td>USERID</td><td>" . $admin->USERID . "</td></tr>";
    echo "<tr><td>U_NAME</td><td>" . $admin->U_NAME . "</td></tr>";
    echo "<tr><td>U_USERNAME</td><td>" . $admin->U_USERNAME . "</td></tr>";
    echo "<tr><td>U_ROLE</td><td>" . $admin->U_ROLE . "</td></tr>";
    echo "</table>";
    echo "<div class='info'>Try logging in again with: admin@hmart.com / admin</div>";
} else {
    echo "<div class='error'>✗ Admin account NOT found in database</div>";
    echo "<p>You need to run the setup script first.</p>";
}

echo "<h2>2. Create admin account now:</h2>";
echo "<form method='POST'>";
echo "<button name='create_admin' style='padding: 10px 20px; background: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;'>Create Admin Account</button>";
echo "</form>";

if (isset($_POST['create_admin'])) {
    $admin_email = 'admin@hmart.com';
    $admin_pass = sha1('admin');

    // Delete if exists
    $mydb->setQuery("DELETE FROM tbluseraccount WHERE U_USERNAME = '{$admin_email}'");
    $mydb->executeQuery();

    // Create new
    $insert_sql = "INSERT INTO tbluseraccount (USERID, U_NAME, U_USERNAME, U_PASS, U_ROLE, USERIMAGE)
                  VALUES (128, 'Admin', '{$admin_email}', '{$admin_pass}', 'Administrator', '')";
    $mydb->setQuery($insert_sql);

    if ($mydb->executeQuery()) {
        echo "<div class='success'>✅ Admin account created successfully!</div>";
        echo "<p>Email: <strong>admin@hmart.com</strong></p>";
        echo "<p>Password: <strong>admin</strong></p>";
        echo "<p><a href='" . web_root . "admin/login.php'>Go to Login</a></p>";
    } else {
        echo "<div class='error'>✗ Failed to create account: " . $mydb->error_msg . "</div>";
    }
}

echo "<h2>3. Database Info:</h2>";
$mydb->setQuery("SELECT COUNT(*) as count FROM tbluseraccount");
$result = $mydb->loadSingleResult();
echo "<div class='info'>Total users in database: " . $result->count . "</div>";

echo "<h2>4. All Users:</h2>";
$mydb->setQuery("SELECT USERID, U_NAME, U_USERNAME, U_ROLE FROM tbluseraccount");
$users = $mydb->loadResultList();
if (count($users) > 0) {
    echo "<table>";
    echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th></tr>";
    foreach ($users as $u) {
        echo "<tr><td>" . $u->USERID . "</td><td>" . $u->U_NAME . "</td><td>" . $u->U_USERNAME . "</td><td>" . $u->U_ROLE . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "<div class='error'>No users found in database</div>";
}

?>

</div>
</body>
</html>
