<?php
/**
 * Smart E-Commerce ML & security configuration
 */
defined('ML_OTP_LENGTH') ? null : define('ML_OTP_LENGTH', 6);
defined('ML_OTP_EXPIRY_MINUTES') ? null : define('ML_OTP_EXPIRY_MINUTES', 10);
defined('ML_OTP_MAX_PER_HOUR') ? null : define('ML_OTP_MAX_PER_HOUR', 100);
defined('ML_LOW_STOCK_THRESHOLD') ? null : define('ML_LOW_STOCK_THRESHOLD', 5);
defined('ML_FAST_MOVING_DAYS') ? null : define('ML_FAST_MOVING_DAYS', 30);
defined('ML_FAST_MOVING_MIN_QTY') ? null : define('ML_FAST_MOVING_MIN_QTY', 10);
defined('ML_SLOW_MOVING_DAYS') ? null : define('ML_SLOW_MOVING_DAYS', 60);
defined('ML_MAX_FAILED_LOGINS') ? null : define('ML_MAX_FAILED_LOGINS', 5);
defined('ML_MAX_FAILED_PAYMENTS') ? null : define('ML_MAX_FAILED_PAYMENTS', 3);
defined('ML_UNUSUAL_ORDER_MULTIPLIER') ? null : define('ML_UNUSUAL_ORDER_MULTIPLIER', 5);
defined('ML_DEMO_OTP_IN_RESPONSE') ? null : define('ML_DEMO_OTP_IN_RESPONSE', false);
defined('ML_OTP_RESEND_SECONDS') ? null : define('ML_OTP_RESEND_SECONDS', 2);

