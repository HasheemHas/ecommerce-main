<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailException;

class Mailer
{
    private static $configLoaded = false;

    public static function loadConfig()
    {
        if (self::$configLoaded) {
            return;
        }
        $local = LIB_PATH . DS . 'mail_config.local.php';
        $example = LIB_PATH . DS . 'mail_config.example.php';
        if (file_exists($local)) {
            require_once $local;
        } elseif (file_exists($example)) {
            require_once $example;
        }
        self::$configLoaded = true;
    }

    public static function isConfigured()
    {
        self::loadConfig();
        return defined('SMTP_ENABLED') && SMTP_ENABLED
            && defined('SMTP_HOST') && SMTP_HOST !== ''
            && defined('SMTP_USER') && SMTP_USER !== '' && SMTP_USER !== 'your-email@gmail.com'
            && defined('SMTP_PASS') && SMTP_PASS !== '' && SMTP_PASS !== 'your-app-password';
    }

    public static function send($toEmail, $subject, $htmlBody, $textBody = '')
    {
        self::loadConfig();

        if (!self::isConfigured()) {
            return [
                'ok' => false,
                'message' => 'Email is not configured. Copy include/mail_config.example.php to include/mail_config.local.php and add your SMTP settings.',
            ];
        }

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USER;
            $mail->Password = SMTP_PASS;
            $mail->Port = (int) SMTP_PORT;
            $mail->SMTPSecure = defined('SMTP_ENCRYPTION') ? SMTP_ENCRYPTION : PHPMailer::ENCRYPTION_STARTTLS;
            $mail->CharSet = 'UTF-8';

            $mail->setFrom(
                defined('SMTP_FROM_EMAIL') ? SMTP_FROM_EMAIL : SMTP_USER,
                defined('SMTP_FROM_NAME') ? SMTP_FROM_NAME : 'H-Mart'
            );
            $mail->addAddress($toEmail);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $htmlBody;
            $mail->AltBody = $textBody ?: strip_tags($htmlBody);

            $mail->send();
            return ['ok' => true, 'message' => 'Email sent successfully.'];
        } catch (MailException $e) {
            return ['ok' => false, 'message' => 'Could not send email: ' . $mail->ErrorInfo];
        }
    }
}
