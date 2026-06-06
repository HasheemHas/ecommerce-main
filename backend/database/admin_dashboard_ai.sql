-- H-Mart Admin Dashboard Expansion Schema Migrations
-- Created: 2026-05-31

CREATE TABLE IF NOT EXISTS `demand_forecasts` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT NOT NULL,
  `forecast_date` DATE NOT NULL,
  `predicted_demand` DOUBLE NOT NULL,
  `recommended_reorder_qty` INT NOT NULL,
  `accuracy_metric` DOUBLE NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `churn_scores` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,
  `churn_probability` DOUBLE NOT NULL,
  `risk_level` VARCHAR(20) NOT NULL,
  `top_risk_factors` TEXT NOT NULL,
  `evaluated_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `product_reviews_sentiment` (
  `review_id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT NOT NULL,
  `customer_id` INT NOT NULL,
  `review_text` TEXT NOT NULL,
  `rating` INT NOT NULL,
  `sentiment_label` VARCHAR(20) NOT NULL,
  `sentiment_score` DOUBLE NOT NULL,
  `topics_extracted` TEXT DEFAULT NULL,
  `is_fake` TINYINT DEFAULT 0,
  `is_fake_confidence` DOUBLE DEFAULT 0.0,
  `reviewed_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX (`product_id`),
  INDEX (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `recommendations` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `recommendation_type` VARCHAR(50) NOT NULL,
  `score` DOUBLE NOT NULL,
  `generated_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX (`customer_id`),
  INDEX (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `recommendations_tracking` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `recommendation_type` VARCHAR(50) NOT NULL,
  `action` VARCHAR(20) NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `dynamic_pricing_suggestions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT NOT NULL,
  `base_price` DOUBLE NOT NULL,
  `suggested_price` DOUBLE NOT NULL,
  `expected_revenue_lift` DOUBLE NOT NULL,
  `confidence_score` DOUBLE NOT NULL,
  `reasons` TEXT NOT NULL,
  `status` VARCHAR(20) DEFAULT 'pending',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `price_ab_tests` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT NOT NULL,
  `price_a` DOUBLE NOT NULL,
  `price_b` DOUBLE NOT NULL,
  `group_a_sales` INT DEFAULT 0,
  `group_b_sales` INT DEFAULT 0,
  `group_a_revenue` DOUBLE DEFAULT 0.0,
  `group_b_revenue` DOUBLE DEFAULT 0.0,
  `start_date` DATE NOT NULL,
  `end_date` DATE DEFAULT NULL,
  `status` VARCHAR(20) DEFAULT 'running',
  INDEX (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `returns` (
  `return_id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,
  `order_number` INT NOT NULL,
  `return_status` VARCHAR(20) DEFAULT 'Pending',
  `request_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `reason_summary` VARCHAR(255) NOT NULL,
  `refund_amount` DOUBLE NOT NULL,
  INDEX (`customer_id`),
  INDEX (`order_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `return_items` (
  `return_item_id` INT AUTO_INCREMENT PRIMARY KEY,
  `return_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `quantity` INT NOT NULL,
  `return_reason_code` VARCHAR(50) NOT NULL,
  INDEX (`return_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `refunds` (
  `refund_id` INT AUTO_INCREMENT PRIMARY KEY,
  `return_id` INT NOT NULL,
  `transaction_reference` VARCHAR(100) NOT NULL,
  `refund_method` VARCHAR(50) NOT NULL,
  `refund_status` VARCHAR(20) DEFAULT 'Pending',
  `processed_at` DATETIME DEFAULT NULL,
  INDEX (`return_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `coupons` (
  `coupon_id` INT AUTO_INCREMENT PRIMARY KEY,
  `coupon_code` VARCHAR(50) UNIQUE NOT NULL,
  `type` VARCHAR(20) NOT NULL,
  `value` DOUBLE NOT NULL,
  `start_date` DATE NOT NULL,
  `expiry_date` DATE NOT NULL,
  `usage_limit` INT NOT NULL,
  `times_used` INT DEFAULT 0,
  `status` VARCHAR(20) DEFAULT 'active',
  `min_spend` DOUBLE DEFAULT 0.0,
  `max_spend` DOUBLE DEFAULT 999999.0,
  `target_segment` VARCHAR(50) DEFAULT 'All'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `coupon_usage` (
  `usage_id` INT AUTO_INCREMENT PRIMARY KEY,
  `coupon_id` INT NOT NULL,
  `customer_id` INT NOT NULL,
  `order_number` INT NOT NULL,
  `discount_applied` DOUBLE NOT NULL,
  `used_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX (`coupon_id`),
  INDEX (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `shipping_tracking` (
  `tracking_id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_number` INT UNIQUE NOT NULL,
  `carrier` VARCHAR(50) NOT NULL,
  `tracking_number` VARCHAR(100) NOT NULL,
  `status` VARCHAR(30) DEFAULT 'Order Placed',
  `origin_lat` DOUBLE NOT NULL,
  `origin_lng` DOUBLE NOT NULL,
  `current_lat` DOUBLE NOT NULL,
  `current_lng` DOUBLE NOT NULL,
  `dest_lat` DOUBLE NOT NULL,
  `dest_lng` DOUBLE NOT NULL,
  `eta_delivery` DATETIME NOT NULL,
  `actual_delivery` DATETIME DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX (`order_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `shipping_updates` (
  `update_id` INT AUTO_INCREMENT PRIMARY KEY,
  `tracking_id` INT NOT NULL,
  `location` VARCHAR(255) NOT NULL,
  `status_details` TEXT NOT NULL,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX (`tracking_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `vendors` (
  `vendor_id` INT AUTO_INCREMENT PRIMARY KEY,
  `vendor_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(50) NOT NULL,
  `address` TEXT NOT NULL,
  `rating` DOUBLE DEFAULT 5.0,
  `status` VARCHAR(20) DEFAULT 'Active',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `vendor_products` (
  `vendor_product_id` INT AUTO_INCREMENT PRIMARY KEY,
  `vendor_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `cost_price` DOUBLE NOT NULL,
  `lead_time_days` INT NOT NULL,
  INDEX (`vendor_id`),
  INDEX (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `purchase_orders` (
  `po_id` INT AUTO_INCREMENT PRIMARY KEY,
  `po_number` VARCHAR(50) UNIQUE NOT NULL,
  `vendor_id` INT NOT NULL,
  `status` VARCHAR(30) DEFAULT 'Draft',
  `total_amount` DOUBLE NOT NULL,
  `expected_delivery` DATE NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX (`vendor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `vendor_payouts` (
  `payout_id` INT AUTO_INCREMENT PRIMARY KEY,
  `vendor_id` INT NOT NULL,
  `po_id` INT NOT NULL,
  `amount` DOUBLE NOT NULL,
  `status` VARCHAR(20) DEFAULT 'Unpaid',
  `processed_at` DATETIME DEFAULT NULL,
  INDEX (`vendor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `low_stock_alerts` (
  `alert_id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT NOT NULL,
  `threshold` INT NOT NULL,
  `current_stock` INT NOT NULL,
  `status` VARCHAR(20) DEFAULT 'Active',
  `notified_at` DATETIME DEFAULT NULL,
  `resolved_at` DATETIME DEFAULT NULL,
  INDEX (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `python_ai_logs` (
  `log_id` INT AUTO_INCREMENT PRIMARY KEY,
  `endpoint` VARCHAR(100) NOT NULL,
  `request_payload` TEXT DEFAULT NULL,
  `response_payload` TEXT DEFAULT NULL,
  `execution_time_ms` INT NOT NULL,
  `success` TINYINT DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
