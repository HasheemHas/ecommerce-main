<?php
require_once('../../backend/include/initialize.php');
if (!isset($_SESSION['USERID'])) {
    redirect(web_root . 'admin/login.php');
}

if (isset($_GET['resolve']) && (int) $_GET['resolve'] > 0) {
    FraudDetector::resolveAlert((int) $_GET['resolve']);
    message('Fraud alert marked as resolved.', 'success');
    redirect(web_root . 'admin/fraud/index.php');
}

$title = 'Fraud Detection';
$alerts = FraudDetector::getUnresolvedAlerts(100);

global $mydb;
$mydb->setQuery("SELECT COUNT(*) AS c FROM tbl_login_attempts WHERE SUCCESS=0 AND ATTEMPTED_AT >= DATE_SUB(NOW(), INTERVAL 24 HOUR)");
$failedLogins24h = (int) $mydb->loadSingleResult()->c;
$mydb->setQuery("SELECT COUNT(*) AS c FROM tbl_payment_attempts WHERE STATUS='failed' AND ATTEMPTED_AT >= DATE_SUB(NOW(), INTERVAL 24 HOUR)");
$failedPayments24h = (int) $mydb->loadSingleResult()->c;

$content = __DIR__ . '/fraud_content.php';
require_once('../theme/templates.php');
