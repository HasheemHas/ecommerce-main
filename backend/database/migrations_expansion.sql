-- MySQL database schema expansion script for H-Mart

-- 1. Activity Log / Audit Trail
CREATE TABLE IF NOT EXISTS `audit_logs` (
  `log_id` INT AUTO_INCREMENT PRIMARY KEY,
  `admin_id` INT NOT NULL,
  `action` VARCHAR(50) NOT NULL, -- create, update, delete, login, export
  `target_table` VARCHAR(100) NOT NULL,
  `old_values` TEXT DEFAULT NULL, -- JSON format of changed properties
  `new_values` TEXT DEFAULT NULL, -- JSON format of updated properties
  `ip_address` VARCHAR(45) NOT NULL,
  `timestamp` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Email Marketing Tables
CREATE TABLE IF NOT EXISTS `email_lists` (
  `list_id` INT AUTO_INCREMENT PRIMARY KEY,
  `list_name` VARCHAR(100) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `email_campaigns` (
  `campaign_id` INT AUTO_INCREMENT PRIMARY KEY,
  `campaign_title` VARCHAR(150) NOT NULL,
  `subject_line` VARCHAR(255) NOT NULL,
  `content_html` TEXT NOT NULL,
  `status` VARCHAR(20) DEFAULT 'Draft', -- Draft, Scheduled, Sent
  `scheduled_at` DATETIME DEFAULT NULL,
  `sent_at` DATETIME DEFAULT NULL,
  `list_id` INT NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `email_opens_clicks` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `campaign_id` INT NOT NULL,
  `customer_id` INT NOT NULL,
  `action_type` VARCHAR(20) NOT NULL, -- open, click
  `link_url` VARCHAR(255) DEFAULT NULL,
  `recorded_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `email_queue` (
  `queue_id` INT AUTO_INCREMENT PRIMARY KEY,
  `campaign_id` INT NOT NULL,
  `customer_id` INT NOT NULL,
  `email_address` VARCHAR(100) NOT NULL,
  `status` VARCHAR(20) DEFAULT 'Pending', -- Pending, Sent, Failed
  `attempts` INT DEFAULT 0,
  `error_message` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `sent_at` DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Push Notifications Configuration & Log
CREATE TABLE IF NOT EXISTS `push_subscriptions` (
  `subscription_id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_type` VARCHAR(20) NOT NULL, -- Admin, Customer
  `user_id` INT NOT NULL,
  `endpoint` TEXT NOT NULL,
  `p256dh` VARCHAR(255) NOT NULL,
  `auth` VARCHAR(255) NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `push_notifications_log` (
  `log_id` INT AUTO_INCREMENT PRIMARY KEY,
  `target_segment` VARCHAR(50) NOT NULL, -- Admins, All Customers, VIPs
  `title` VARCHAR(150) NOT NULL,
  `body` TEXT NOT NULL,
  `sent_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. SMS Config & Alerts Log
CREATE TABLE IF NOT EXISTS `sms_alerts_config` (
  `config_id` INT AUTO_INCREMENT PRIMARY KEY,
  `alert_type` VARCHAR(50) NOT NULL, -- high_value_order, fraud, critical_stock, back_in_stock
  `enabled` TINYINT DEFAULT 1,
  `recipient_phone` VARCHAR(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `sms_logs` (
  `sms_id` INT AUTO_INCREMENT PRIMARY KEY,
  `phone_number` VARCHAR(30) NOT NULL,
  `message_body` TEXT NOT NULL,
  `status` VARCHAR(20) DEFAULT 'Sent', -- Sent, Failed
  `error_message` TEXT DEFAULT NULL,
  `sent_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Multi-language Translation Cache
CREATE TABLE IF NOT EXISTS `translations_cache` (
  `translation_id` INT AUTO_INCREMENT PRIMARY KEY,
  `lang_code` VARCHAR(10) NOT NULL, -- en, es, fr, de, ar
  `text_key` VARCHAR(255) NOT NULL,
  `translated_text` TEXT NOT NULL,
  INDEX (`lang_code`, `text_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Multi-currency Manager
CREATE TABLE IF NOT EXISTS `currencies` (
  `currency_id` INT AUTO_INCREMENT PRIMARY KEY,
  `currency_code` VARCHAR(10) UNIQUE NOT NULL, -- USD, EUR, INR
  `currency_symbol` VARCHAR(10) NOT NULL, -- $, €, ₹
  `exchange_rate` DOUBLE NOT NULL DEFAULT 1.0, -- relative to base currency (e.g. INR)
  `is_base` TINYINT DEFAULT 0,
  `status` VARCHAR(20) DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `exchange_rates` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `currency_code` VARCHAR(10) NOT NULL,
  `rate` DOUBLE NOT NULL,
  `last_updated` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. Backup Log
CREATE TABLE IF NOT EXISTS `backup_logs` (
  `backup_id` INT AUTO_INCREMENT PRIMARY KEY,
  `file_name` VARCHAR(255) NOT NULL,
  `file_size_bytes` BIGINT NOT NULL,
  `storage_location` VARCHAR(100) NOT NULL, -- Local, S3, FTP
  `status` VARCHAR(20) DEFAULT 'Success', -- Success, Failed
  `error_details` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 8. System Health Metrics & Alerts
CREATE TABLE IF NOT EXISTS `health_metrics` (
  `metric_id` INT AUTO_INCREMENT PRIMARY KEY,
  `cpu_usage_pct` DOUBLE NOT NULL,
  `memory_usage_pct` DOUBLE NOT NULL,
  `disk_usage_pct` DOUBLE NOT NULL,
  `mysql_ping_ms` INT NOT NULL,
  `microservice_ping_ms` INT NOT NULL,
  `recorded_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `health_alerts` (
  `alert_id` INT AUTO_INCREMENT PRIMARY KEY,
  `component` VARCHAR(50) NOT NULL, -- CPU, MySQL, Microservice
  `alert_message` TEXT NOT NULL,
  `status` VARCHAR(20) DEFAULT 'Active', -- Active, Resolved
  `notified_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `resolved_at` DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 9. Support Ticket System (Admin & Customer Support)
CREATE TABLE IF NOT EXISTS `support_tickets` (
  `ticket_id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,
  `order_number` INT DEFAULT NULL,
  `subject` VARCHAR(150) NOT NULL,
  `category` VARCHAR(50) NOT NULL, -- Return, Refund, Product Inquiry, Payment Issue
  `status` VARCHAR(30) DEFAULT 'Open', -- Open, Assigned, Resolved, Closed
  `priority` VARCHAR(20) DEFAULT 'Medium', -- Low, Medium, High
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `ticket_replies` (
  `reply_id` INT AUTO_INCREMENT PRIMARY KEY,
  `ticket_id` INT NOT NULL,
  `sender_type` VARCHAR(20) NOT NULL, -- Customer, Admin
  `sender_id` INT NOT NULL,
  `message_body` TEXT NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `ticket_assignments` (
  `assignment_id` INT AUTO_INCREMENT PRIMARY KEY,
  `ticket_id` INT NOT NULL,
  `agent_id` INT NOT NULL,
  `assigned_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 10. Multi-site / Country Manager
CREATE TABLE IF NOT EXISTS `sites` (
  `site_id` INT AUTO_INCREMENT PRIMARY KEY,
  `site_name` VARCHAR(100) NOT NULL,
  `country_code` VARCHAR(5) NOT NULL, -- US, ES, IN
  `currency_code` VARCHAR(5) NOT NULL,
  `language_code` VARCHAR(5) NOT NULL,
  `tax_rate` DOUBLE NOT NULL DEFAULT 0.0,
  `timezone` VARCHAR(100) NOT NULL,
  `status` VARCHAR(20) DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 11. Customer Activity logs
CREATE TABLE IF NOT EXISTS `customer_activity_log` (
  `activity_id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,
  `action` VARCHAR(100) NOT NULL, -- login, view_product, search, add_to_cart, purchase
  `details` TEXT DEFAULT NULL,
  `ip_address` VARCHAR(45) NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 12. Customer Notification Preferences
CREATE TABLE IF NOT EXISTS `customer_notification_preferences` (
  `preference_id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT UNIQUE NOT NULL,
  `order_updates_sms` TINYINT DEFAULT 1,
  `order_updates_email` TINYINT DEFAULT 1,
  `promotions_sms` TINYINT DEFAULT 0,
  `promotions_email` TINYINT DEFAULT 1,
  `back_in_stock_email` TINYINT DEFAULT 1,
  `price_drop_email` TINYINT DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 13. Customer Wishlists & Wishlist Items Expansion
CREATE TABLE IF NOT EXISTS `customer_wishlists` (
  `wishlist_id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,
  `wishlist_name` VARCHAR(100) NOT NULL,
  `is_default` TINYINT DEFAULT 0,
  `share_token` VARCHAR(100) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `wishlist_items` (
  `item_id` INT AUTO_INCREMENT PRIMARY KEY,
  `wishlist_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `added_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 14. Product Spec Comparison
CREATE TABLE IF NOT EXISTS `product_comparisons` (
  `comparison_id` INT AUTO_INCREMENT PRIMARY KEY,
  `session_id` VARCHAR(100) NOT NULL,
  `product_id` INT NOT NULL,
  `added_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 15. Customer Product Reviews & Ratings (Extending beyond BERT sentiment analysis)
CREATE TABLE IF NOT EXISTS `customer_reviews` (
  `review_id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT NOT NULL,
  `customer_id` INT NOT NULL,
  `rating` INT NOT NULL, -- 1 to 5
  `review_title` VARCHAR(150) NOT NULL,
  `review_text` TEXT NOT NULL,
  `review_photo` VARCHAR(255) DEFAULT NULL,
  `is_verified_purchase` TINYINT DEFAULT 0,
  `status` VARCHAR(20) DEFAULT 'Approved',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `review_votes` (
  `vote_id` INT AUTO_INCREMENT PRIMARY KEY,
  `review_id` INT NOT NULL,
  `customer_id` INT NOT NULL,
  `vote_type` VARCHAR(10) NOT NULL, -- helpful, unhelpful
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `review_qna` (
  `qna_id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT NOT NULL,
  `customer_id` INT NOT NULL,
  `question` TEXT NOT NULL,
  `answer` TEXT DEFAULT NULL,
  `answered_by_admin_id` INT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `answered_at` DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 16. Back In Stock Alerts & Price Drop Alerts
CREATE TABLE IF NOT EXISTS `back_in_stock_alerts` (
  `alert_id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `status` VARCHAR(20) DEFAULT 'Active', -- Active, Notified
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `price_drop_alerts` (
  `alert_id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `target_price` DOUBLE NOT NULL,
  `status` VARCHAR(20) DEFAULT 'Active', -- Active, Notified
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 17. Abandoned Cart Tracking & Recovery Campaign Metrics
CREATE TABLE IF NOT EXISTS `abandoned_carts` (
  `abandoned_id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,
  `cart_details_json` TEXT NOT NULL,
  `last_active_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` VARCHAR(30) DEFAULT 'Abandoned' -- Abandoned, Recovered, Emailed
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `recovery_attempts` (
  `attempt_id` INT AUTO_INCREMENT PRIMARY KEY,
  `abandoned_id` INT NOT NULL,
  `method` VARCHAR(20) NOT NULL, -- Email, Push
  `discount_offered_code` VARCHAR(50) DEFAULT NULL,
  `sent_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `recovery_conversions` (
  `conversion_id` INT AUTO_INCREMENT PRIMARY KEY,
  `abandoned_id` INT NOT NULL,
  `order_number` INT NOT NULL,
  `recovered_amount` DOUBLE NOT NULL,
  `recovered_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
