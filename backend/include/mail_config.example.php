<?php
/**
 * Copy this file to mail_config.local.php and fill in your SMTP details.
 * Gmail: use an App Password (Google Account → Security → App passwords)
 */
defined('SMTP_ENABLED') ? null : define('SMTP_ENABLED', true);
defined('SMTP_HOST') ? null : define('SMTP_HOST', 'smtp.gmail.com');
defined('SMTP_PORT') ? null : define('SMTP_PORT', 587);
defined('SMTP_USER') ? null : define('SMTP_USER', 'your-email@gmail.com');
defined('SMTP_PASS') ? null : define('SMTP_PASS', 'your-app-password');
defined('SMTP_FROM_EMAIL') ? null : define('SMTP_FROM_EMAIL', 'your-email@gmail.com');
defined('SMTP_FROM_NAME') ? null : define('SMTP_FROM_NAME', 'H-Mart');
defined('SMTP_ENCRYPTION') ? null : define('SMTP_ENCRYPTION', 'tls');
