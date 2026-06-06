<?php
require_once(dirname(__DIR__) . '/include/initialize.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'message' => 'Invalid request.']);
    exit;
}

$email = isset($_POST['email']) ? $_POST['email'] : '';
$code = isset($_POST['otp']) ? $_POST['otp'] : '';
$purpose = isset($_POST['purpose']) ? $_POST['purpose'] : 'login';
if (!in_array($purpose, ['login', 'signup', 'reset'], true)) {
    $purpose = 'login';
}

$result = OtpService::verifyOtp($email, $code, $purpose);
if (!$result['ok']) {
    echo json_encode($result);
    exit;
}

if ($purpose === 'login') {
    if (OtpService::loginCustomerByEmail($email)) {
        $go = !empty($_SESSION['login_redirect']) ? $_SESSION['login_redirect'] : 'index.php?q=profile';
        unset($_SESSION['login_redirect']);
        $result['redirect'] = web_root . $go;
    } else {
        $result['ok'] = false;
        $result['message'] = 'No account found. Please sign up first.';
    }
} elseif ($purpose === 'signup') {
    $_SESSION['signup_otp_verified'] = trim(strtolower($email));
    $result['signup_verified'] = true;
}

echo json_encode($result);
