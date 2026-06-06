<?php
/**
 * Test SMTP / OTP email delivery.
 * Visit once: http://localhost/ecommerce/setup_email.php
 * Delete this file after configuration works.
 */
require_once('include/initialize.php');

$testEmail = isset($_POST['test_email']) ? trim($_POST['test_email']) : '';
$result = null;

if ($testEmail && isset($_POST['send_test'])) {
    $result = OtpService::sendOtp($testEmail, 'signup');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Email / OTP Setup — H-Mart</title>
    <style>
        body { font-family: system-ui, sans-serif; max-width: 560px; margin: 40px auto; padding: 0 20px; color: #1e293b; }
        .box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 20px; margin: 16px 0; }
        .ok { background: #dcfce7; border-color: #86efac; }
        .err { background: #fee2e2; border-color: #fca5a5; }
        input, button { font-size: 16px; padding: 10px; margin: 8px 0; width: 100%; box-sizing: border-box; }
        button { background: #1e3a8a; color: #fff; border: none; border-radius: 8px; cursor: pointer; }
        code { background: #e2e8f0; padding: 2px 6px; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>OTP email setup</h1>

    <div class="box <?php echo Mailer::isConfigured() ? 'ok' : 'err'; ?>">
        <strong>SMTP status:</strong>
        <?php echo Mailer::isConfigured() ? 'Configured' : 'Not configured'; ?>
    </div>

    <?php if (!Mailer::isConfigured()) { ?>
    <div class="box">
        <ol>
            <li>Copy <code>include/mail_config.example.php</code> to <code>include/mail_config.local.php</code></li>
            <li>Set your Gmail (or SMTP) username and <strong>App Password</strong></li>
            <li>Reload this page</li>
        </ol>
        <p><small>Gmail: Google Account → Security → 2-Step Verification → App passwords</small></p>
    </div>
    <?php } ?>

    <?php if ($result) { ?>
    <div class="box <?php echo $result['ok'] ? 'ok' : 'err'; ?>">
        <?php echo htmlspecialchars($result['message']); ?>
    </div>
    <?php } ?>

    <?php if (Mailer::isConfigured()) { ?>
    <form method="post">
        <label>Send test OTP to any email:</label>
        <input type="email" name="test_email" required placeholder="customer@email.com" value="<?php echo htmlspecialchars($testEmail); ?>">
        <button type="submit" name="send_test" value="1">Send test OTP</button>
    </form>
    <?php } ?>

    <p><a href="index.php?q=login">Go to login</a></p>
</body>
</html>
