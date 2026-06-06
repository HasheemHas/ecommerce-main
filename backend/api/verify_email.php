<?php
require_once(dirname(__DIR__) . '/include/initialize.php');

$email = isset($_GET['email']) ? trim($_GET['email']) : '';
$token = isset($_GET['token']) ? trim($_GET['token']) : '';

if (empty($email) || empty($token)) {
    die("Invalid verification link.");
}

global $mydb;
$emailEsc = $mydb->escape_value($email);
$tokenEsc = $mydb->escape_value($token);

// Validate token in tbl_otp_codes
$mydb->setQuery("SELECT * FROM tbl_otp_codes WHERE EMAIL='{$emailEsc}' AND OTP_CODE='{$tokenEsc}' AND PURPOSE='signup' AND IS_USED=0 AND EXPIRES_AT > NOW() LIMIT 1");
$row = $mydb->loadSingleResult();

if (!$row) {
    die("The verification link is invalid or expired.");
}

// Mark token as used
$mydb->setQuery("UPDATE tbl_otp_codes SET IS_USED=1 WHERE OTP_ID=" . (int)$row->OTP_ID);
$mydb->executeQuery();

// Mark customer as verified (TERMS = 1)
$mydb->setQuery("UPDATE tblcustomer SET TERMS=1 WHERE LOWER(CUSUNAME)='{$emailEsc}' OR LOWER(EMAILADD)='{$emailEsc}'");
$mydb->executeQuery();

// Authenticate user and log them in
$mydb->setQuery("SELECT * FROM tblcustomer WHERE LOWER(CUSUNAME)='{$emailEsc}' OR LOWER(EMAILADD)='{$emailEsc}' LIMIT 1");
$user = $mydb->loadSingleResult();
if ($user) {
    $_SESSION['CUSID']   = $user->CUSTOMERID;
    $_SESSION['CUSNAME'] = $user->FNAME . ' ' . $user->LNAME;
    $_SESSION['CUSUNAME'] = $user->CUSUNAME; 
    $_SESSION['CUSUPASS'] = $user->CUSPASS;
}

echo "<script>
    alert('Email verified successfully! You are now logged in.');
    window.location.href = '../index.php?q=profile';
</script>";
