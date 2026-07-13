<?php
require_once(LIB_PATH . DS . 'ml_config.php');
require_once(LIB_PATH . DS . 'mailer.php');

class OtpService
{
    public static function clientIp()
    {
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        }
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
    }

    public static function generateCode($length = null)
    {
        $length = $length ?: ML_OTP_LENGTH;
        $min = (int) pow(10, $length - 1);
        $max = (int) pow(10, $length) - 1;
        return (string) random_int($min, $max);
    }

    public static function sendOtp($email, $purpose = 'login')
    {
        global $mydb;
        $email = trim(strtolower($email));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['ok' => false, 'message' => 'Invalid email address.'];
        }

        if ($purpose === 'login' && !self::customerExists($email)) {
            return ['ok' => false, 'message' => 'No account found with this email. Please sign up first.'];
        }

        $mydb->setQuery("SELECT COUNT(*) AS cnt FROM tbl_otp_codes WHERE EMAIL='" . $mydb->escape_value($email) . "' AND CREATED_AT >= DATE_SUB(NOW(), INTERVAL 1 HOUR)");
        $countRow = $mydb->loadSingleResult();
        if ($countRow && (int) $countRow->cnt >= ML_OTP_MAX_PER_HOUR) {
            return ['ok' => false, 'message' => 'Too many OTP requests. Try again in an hour.'];
        }

        $resendSeconds = (int)ML_OTP_RESEND_SECONDS;
        $mydb->setQuery("SELECT OTP_ID FROM tbl_otp_codes WHERE EMAIL='" . $mydb->escape_value($email) . "' AND PURPOSE='" . $mydb->escape_value($purpose) . "' AND CREATED_AT >= DATE_SUB(NOW(), INTERVAL {$resendSeconds} SECOND) LIMIT 1");
        if ($mydb->loadSingleResult()) {
            return ['ok' => false, 'message' => 'Please wait ' . ML_OTP_RESEND_SECONDS . ' seconds before requesting a new code.'];
        }

        $code = self::generateCode();
        $expiryMinutes = (int)ML_OTP_EXPIRY_MINUTES;
        $emailEsc = $mydb->escape_value($email);
        $codeEsc = $mydb->escape_value($code);
        $purposeEsc = $mydb->escape_value($purpose);

        $mydb->setQuery("INSERT INTO tbl_otp_codes (EMAIL, OTP_CODE, PURPOSE, EXPIRES_AT) VALUES ('{$emailEsc}','{$codeEsc}','{$purposeEsc}', DATE_ADD(NOW(), INTERVAL {$expiryMinutes} MINUTE))");
        $mydb->executeQuery();

        $sent = self::sendOtpEmail($email, $code, $purpose);
        if (!$sent['ok']) {
            if (defined('ML_DEMO_OTP_IN_RESPONSE') && ML_DEMO_OTP_IN_RESPONSE) {
                $_SESSION['otp_pending_email'] = $email;
                $_SESSION['otp_pending_purpose'] = $purpose;
                return [
                    'ok' => true,
                    'message' => 'Verification code (DEMO): ' . $code . ' (Email not sent because SMTP is not configured).',
                    'expires_in' => ML_OTP_EXPIRY_MINUTES * 60,
                    'email' => $email,
                    'otp' => $code,
                ];
            }
            return $sent;
        }

        $_SESSION['otp_pending_email'] = $email;
        $_SESSION['otp_pending_purpose'] = $purpose;

        $response = [
            'ok' => true,
            'message' => 'Verification code sent to ' . $email . '. Check your inbox.',
            'expires_in' => ML_OTP_EXPIRY_MINUTES * 60,
            'email' => $email,
        ];
        if (defined('ML_DEMO_OTP_IN_RESPONSE') && ML_DEMO_OTP_IN_RESPONSE) {
            $response['otp'] = $code;
            $response['message'] .= ' (DEMO OTP: ' . $code . ')';
        }
        return $response;
    }

    private static function sendOtpEmail($email, $code, $purpose)
    {
        $purposeLabel = $purpose === 'signup' ? 'sign up' : ($purpose === 'reset' ? 'reset your password' : 'sign in');
        $minutes = ML_OTP_EXPIRY_MINUTES;

        $html = EmailTemplates::otpVerification($code, $purposeLabel, $minutes);
        $text = EmailTemplates::otpPlainText($code, $purposeLabel, $minutes);

        return Mailer::send($email, 'H-Mart — Your verification code', $html, $text);
    }

    public static function verifyOtp($email, $code, $purpose = 'login')
    {
        global $mydb;
        $email = trim(strtolower($email));
        $code = trim($code);
        if (!preg_match('/^\d{' . ML_OTP_LENGTH . '}$/', $code)) {
            return ['ok' => false, 'message' => 'Enter a valid ' . ML_OTP_LENGTH . '-digit code.'];
        }

        $emailEsc = $mydb->escape_value($email);
        $codeEsc = $mydb->escape_value($code);
        $purposeEsc = $mydb->escape_value($purpose);

        $mydb->setQuery("SELECT * FROM tbl_otp_codes WHERE EMAIL='{$emailEsc}' AND OTP_CODE='{$codeEsc}' AND PURPOSE='{$purposeEsc}' AND IS_USED=0 AND EXPIRES_AT > NOW() ORDER BY OTP_ID DESC LIMIT 1");
        $row = $mydb->loadSingleResult();
        if (!$row) {
            return ['ok' => false, 'message' => 'Invalid or expired code. Request a new one.'];
        }

        $mydb->setQuery("UPDATE tbl_otp_codes SET IS_USED=1 WHERE OTP_ID=" . (int) $row->OTP_ID);
        $mydb->executeQuery();
        return ['ok' => true, 'message' => 'Verified successfully.'];
    }

    public static function customerExists($email)
    {
        global $mydb;
        $emailEsc = $mydb->escape_value(trim(strtolower($email)));
        $mydb->setQuery("SELECT CUSTOMERID FROM tblcustomer WHERE LOWER(CUSUNAME)='{$emailEsc}' OR LOWER(EMAILADD)='{$emailEsc}' LIMIT 1");
        return (bool) $mydb->loadSingleResult();
    }

    public static function loginCustomerByEmail($email)
    {
        global $mydb;
        $emailEsc = $mydb->escape_value(trim(strtolower($email)));
        $mydb->setQuery("SELECT * FROM tblcustomer WHERE LOWER(CUSUNAME)='{$emailEsc}' OR LOWER(EMAILADD)='{$emailEsc}' LIMIT 1");
        $user = $mydb->loadSingleResult();
        if (!$user) {
            return false;
        }
        if (function_exists('session_regenerate_id')) {
            session_regenerate_id(true);
        }
        $_SESSION['CUSID'] = $user->CUSTOMERID;
        $_SESSION['CUSNAME'] = $user->FNAME . ' ' . $user->LNAME;
        $_SESSION['CUSUNAME'] = $user->CUSUNAME;
        unset($_SESSION['CUSUPASS']);
        return true;
    }
}
