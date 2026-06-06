<?php
require_once(LIB_PATH . DS . 'ml_config.php');

class FraudDetector
{
    public static function clientIp()
    {
        return OtpService::clientIp();
    }

    public static function checkLoginAllowed($username)
    {
        global $mydb;
        $ip = $mydb->escape_value(self::clientIp());
        $mydb->setQuery("SELECT COUNT(*) AS cnt FROM tbl_login_attempts
            WHERE IP_ADDRESS='{$ip}' AND SUCCESS=0 AND ATTEMPTED_AT >= DATE_SUB(NOW(), INTERVAL 15 MINUTE)");
        $row = $mydb->loadSingleResult();
        if ($row && (int) $row->cnt >= ML_MAX_FAILED_LOGINS) {
            return ['allowed' => false, 'message' => 'Login temporarily blocked: too many failed attempts from this IP. Try again in 15 minutes.'];
        }
        return ['allowed' => true];
    }

    public static function logLoginAttempt($username, $success)
    {
        global $mydb;
        $ip = $mydb->escape_value(self::clientIp());
        $user = $mydb->escape_value($username);
        $ok = $success ? 1 : 0;
        $mydb->setQuery("INSERT INTO tbl_login_attempts (USERNAME, IP_ADDRESS, SUCCESS) VALUES ('{$user}','{$ip}',{$ok})");
        @$mydb->executeQuery();

        if (!$success) {
            self::checkLoginFraud($username, $ip);
        }
    }

    public static function logPaymentAttempt($customerId, $orderNum, $method, $amount, $status, $reason = '')
    {
        global $mydb;
        $cid = $customerId ? (int) $customerId : 'NULL';
        $ord = $orderNum ? (int) $orderNum : 'NULL';
        $methodEsc = $mydb->escape_value($method);
        $amount = (float) $amount;
        $statusEsc = $mydb->escape_value($status);
        $reasonEsc = $mydb->escape_value($reason);
        $ip = $mydb->escape_value(self::clientIp());
        $mydb->setQuery("INSERT INTO tbl_payment_attempts (CUSTOMERID, ORDEREDNUM, PAYMENT_METHOD, AMOUNT, STATUS, FAILURE_REASON, IP_ADDRESS)
            VALUES ({$cid}, {$ord}, '{$methodEsc}', {$amount}, '{$statusEsc}', '{$reasonEsc}', '{$ip}')");
        @$mydb->executeQuery();

        if ($status === 'failed' || $status === 'blocked') {
            self::checkPaymentFraud($customerId);
        }
    }

    public static function checkCheckoutAllowed($customerId, $orderTotal)
    {
        global $mydb;
        $customerId = (int) $customerId;
        $orderTotal = (float) $orderTotal;

        // 1. Failed payments in last hour
        $mydb->setQuery("SELECT COUNT(*) AS cnt FROM tbl_payment_attempts
            WHERE CUSTOMERID={$customerId} AND STATUS='failed'
            AND ATTEMPTED_AT >= DATE_SUB(NOW(), INTERVAL 1 HOUR)");
        $failed = $mydb->loadSingleResult();
        $failedCount = $failed ? (int)$failed->cnt : 0;

        // 2. Fetch past payment values (amounts)
        $past_payments = [];
        $mydb->setQuery("SELECT PAYMENT FROM tblsummary WHERE CUSTOMERID={$customerId} AND ORDEREDSTATS='Confirmed'");
        $payList = $mydb->loadResultList();
        if ($payList) {
            foreach ($payList as $row) {
                $past_payments[] = (float)$row->PAYMENT;
            }
        }

        // 3. Rapid orders (3+ in 10 minutes)
        $mydb->setQuery("SELECT COUNT(*) AS cnt FROM tblsummary WHERE CUSTOMERID={$customerId}
            AND ORDEREDDATE >= DATE_SUB(NOW(), INTERVAL 10 MINUTE)");
        $rapid = $mydb->loadSingleResult();
        $rapidCount = $rapid ? (int)$rapid->cnt : 0;

        // Call Python ML Service
        $payload = [
            'customer_id' => $customerId,
            'order_total' => $orderTotal,
            'past_order_payments' => $past_payments,
            'failed_payments_last_hour' => $failedCount,
            'orders_last_10_minutes' => $rapidCount
        ];

        $res = MLBridge::runPython('detect_fraud', $payload);

        if ($res && isset($res['ok']) && $res['ok'] && isset($res['fraud_check'])) {
            $check = $res['fraud_check'];
            if (!$check['allowed']) {
                self::createAlert($customerId, $check['alert'], 'high', $check['reason']);
                return ['allowed' => false, 'message' => $check['reason']];
            }
            if ($check['risk'] === 'medium') {
                self::createAlert($customerId, $check['alert'], 'medium', $check['reason']);
            }
        }

        return ['allowed' => true];
    }

    private static function checkLoginFraud($username, $ip)
    {
        global $mydb;
        $mydb->setQuery("SELECT COUNT(*) AS cnt FROM tbl_login_attempts
            WHERE IP_ADDRESS='{$ip}' AND SUCCESS=0 AND ATTEMPTED_AT >= DATE_SUB(NOW(), INTERVAL 15 MINUTE)");
        $row = $mydb->loadSingleResult();
        if ($row && (int) $row->cnt >= ML_MAX_FAILED_LOGINS) {
            self::createAlert(null, 'brute_force_login', 'high',
                "Abnormal login attempts from IP {$ip} for user {$username}.", ['ip' => $ip, 'username' => $username]);
        }
    }

    private static function checkPaymentFraud($customerId)
    {
        global $mydb;
        $customerId = (int) $customerId;
        if ($customerId <= 0) {
            return;
        }
        $mydb->setQuery("SELECT COUNT(*) AS cnt FROM tbl_payment_attempts
            WHERE CUSTOMERID={$customerId} AND STATUS='failed' AND ATTEMPTED_AT >= DATE_SUB(NOW(), INTERVAL 1 HOUR)");
        $row = $mydb->loadSingleResult();
        if ($row && (int) $row->cnt >= ML_MAX_FAILED_PAYMENTS) {
            self::createAlert($customerId, 'failed_payments', 'high', 'Repeated failed payment attempts detected.');
        }
    }

    public static function createAlert($customerId, $type, $severity, $description, $meta = [])
    {
        global $mydb;
        $cid = $customerId ? (int) $customerId : 'NULL';
        $typeEsc = $mydb->escape_value($type);
        $sevEsc = $mydb->escape_value($severity);
        $descEsc = $mydb->escape_value($description);
        $metaJson = $mydb->escape_value(json_encode($meta));
        $mydb->setQuery("INSERT INTO tbl_fraud_alerts (CUSTOMERID, ALERT_TYPE, SEVERITY, DESCRIPTION, META_JSON)
            VALUES ({$cid}, '{$typeEsc}', '{$sevEsc}', '{$descEsc}', '{$metaJson}')");
        @$mydb->executeQuery();
    }

    public static function getUnresolvedAlerts($limit = 50)
    {
        global $mydb;
        $mydb->setQuery("SELECT a.*, c.FNAME, c.LNAME, c.CUSUNAME FROM tbl_fraud_alerts a
            LEFT JOIN tblcustomer c ON c.CUSTOMERID = a.CUSTOMERID
            WHERE a.IS_RESOLVED = 0 ORDER BY a.CREATED_AT DESC LIMIT " . (int) $limit);
        return $mydb->loadResultList();
    }

    public static function resolveAlert($alertId)
    {
        global $mydb;
        $mydb->setQuery('UPDATE tbl_fraud_alerts SET IS_RESOLVED=1 WHERE ALERT_ID=' . (int) $alertId);
        return $mydb->executeQuery();
    }
}
