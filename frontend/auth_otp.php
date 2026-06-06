<?php
require_once('../backend/include/initialize.php');

if (isset($_POST['otp_send'])) {
    $email = trim($_POST['email']);
    $purpose = isset($_POST['purpose']) ? $_POST['purpose'] : (isset($_GET['purpose']) ? $_GET['purpose'] : 'login');
    $res = OtpService::sendOtp($email, $purpose);
    if ($res['ok']) {
        message($res['message'], 'success');
    } else {
        message($res['message'], 'error');
    }
    redirect(web_root . 'index.php?q=login&otp=1&email=' . urlencode($email));
}

if (isset($_POST['otp_verify_login'])) {
    $email = trim($_POST['email']);
    $code = trim($_POST['otp_code']);
    $verify = OtpService::verifyOtp($email, $code, 'login');
    if (!$verify['ok']) {
        message($verify['message'], 'error');
        redirect(web_root . 'index.php?q=login&otp=1&email=' . urlencode($email));
    }
    if (OtpService::loginCustomerByEmail($email)) {
        message('Logged in successfully with OTP!', 'success');
        redirect(web_root . 'index.php?q=profile');
    }
    message('No account found. Please register first.', 'error');
    redirect(web_root . 'index.php?q=signup');
}

if (isset($_POST['otp_verify_signup'])) {
    $email = trim($_POST['email']);
    $code = trim($_POST['otp_code']);
    $verify = OtpService::verifyOtp($email, $code, 'signup');
    if (!$verify['ok']) {
        message($verify['message'], 'error');
        redirect(web_root . 'index.php?q=signup&otp_verified=0');
    }
    $_SESSION['signup_otp_verified'] = $email;
    message('Email verified! Complete your registration.', 'success');
    redirect(web_root . 'index.php?q=signup&otp_verified=1&email=' . urlencode($email));
}

redirect(web_root . 'index.php?q=login');
