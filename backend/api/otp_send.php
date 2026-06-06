<?php
require_once(dirname(__DIR__) . '/include/initialize.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'message' => 'Invalid request.']);
    exit;
}

$email = isset($_POST['email']) ? $_POST['email'] : '';
$purpose = isset($_POST['purpose']) ? $_POST['purpose'] : 'login';
if (!in_array($purpose, ['login', 'signup', 'reset'], true)) {
    $purpose = 'login';
}

echo json_encode(OtpService::sendOtp($email, $purpose));
