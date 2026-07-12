<?php
header('Content-Type: text/html; charset=utf-8');
require_once("../include/initialize.php");
global $mydb;

?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Custom Admin</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f0f0f0; }
        .box { max-width: 500px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 2px solid #4CAF50; padding-bottom: 10px; }
        .form-group { margin: 15px 0; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
        input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; font-size: 14px; }
        button { width: 100%; padding: 12px; background: #4CAF50; color: white; border: none; border-radius: 4px; font-size: 16px; font-weight: bold; cursor: pointer; margin-top: 10px; }
        button:hover { background: #45a049; }
        .success { color: #2e7d32; padding: 15px; background: #e8f5e9; border: 1px solid #4caf50; border-radius: 4px; margin: 10px 0; }
        .error { color: #c62828; padding: 15px; background: #ffebee; border: 1px solid #f44336; border-radius: 4px; margin: 10px 0; }
        .info { color: #1565c0; padding: 15px; background: #e3f2fd; border: 1px solid #2196f3; border-radius: 4px; margin: 10px 0; }
        .credentials { background: #f9f9f9; padding: 15px; border-left: 4px solid #2196f3; margin: 10px 0; }
    </style>
</head>
<body>
<div class="box">
    <h1>👤 Create Custom Admin Account</h1>
    <p>Fill in the form below to create your custom admin account:</p>

<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $name = trim($_POST['name'] ?? 'Administrator');

    // Validation
    $errors = [];
    if (empty($email)) $errors[] = "Email is required";
    if (empty($password)) $errors[] = "Password is required";
    if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format";

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<div class='error'>✗ " . htmlspecialchars($error) . "</div>";
        }
    } else {
        // Create admin account
        $hashed_pass = sha1($password);

        // Delete existing admin with same email
        $mydb->setQuery("DELETE FROM tbluseraccount WHERE U_USERNAME = '" . $mydb->escape_value($email) . "'");
        $mydb->executeQuery();

        // Insert new admin
        $insert_sql = "INSERT INTO tbluseraccount (USERID, U_NAME, U_USERNAME, U_PASS, U_ROLE, USERIMAGE)
                      VALUES (128, '" . $mydb->escape_value($name) . "', '" . $mydb->escape_value($email) . "', '{$hashed_pass}', 'Administrator', '')";
        $mydb->setQuery($insert_sql);

        if ($mydb->executeQuery()) {
            echo "<div class='success'><strong>✅ Admin Account Created Successfully!</strong></div>";
            echo "<div class='credentials'>";
            echo "<strong>Your Admin Credentials:</strong><br>";
            echo "📧 Email: <code>" . htmlspecialchars($email) . "</code><br>";
            echo "🔑 Password: <code>" . htmlspecialchars($password) . "</code><br>";
            echo "</div>";
            echo "<p style='color: #666; margin-top: 15px;'><strong>Next Steps:</strong></p>";
            echo "<ol>";
            echo "<li>Go to: <a href='" . web_root . "admin/login.php' target='_blank' style='color: #2196f3;'>Admin Login Page</a></li>";
            echo "<li>Enter your email: <strong>" . htmlspecialchars($email) . "</strong></li>";
            echo "<li>Enter your password: <strong>" . htmlspecialchars($password) . "</strong></li>";
            echo "<li>Click 'Access Dashboard'</li>";
            echo "</ol>";
            echo "<p style='color: #d32f2f;'><strong>⚠️ Important:</strong> Save these credentials in a secure location!</p>";
        } else {
            echo "<div class='error'>✗ Failed to create account: " . $mydb->error_msg . "</div>";
        }
    }
} else {
    // Show form
    ?>
    <form method="POST">
        <div class="form-group">
            <label for="name">Admin Name:</label>
            <input type="text" id="name" name="name" placeholder="e.g., John Admin" value="Administrator">
        </div>

        <div class="form-group">
            <label for="email">Admin Email:</label>
            <input type="email" id="email" name="email" placeholder="e.g., admin@mysite.com" required>
        </div>

        <div class="form-group">
            <label for="password">Admin Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter a strong password" required>
        </div>

        <button type="submit">Create Admin Account</button>
    </form>

    <div class="info" style="margin-top: 20px;">
        <strong>💡 Tip:</strong> Use a strong password with letters, numbers, and special characters for security.
    </div>
    <?php
}

?>

</div>
</body>
</html>
