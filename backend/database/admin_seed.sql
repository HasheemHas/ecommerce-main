-- H-Mart Admin Dashboard Expansion Seed Data
-- Created: 2026-05-31

-- 1. Vendors Seed
INSERT IGNORE INTO `vendors` (`vendor_id`, `vendor_name`, `email`, `phone`, `address`, `rating`, `status`) VALUES
(1, 'Fresh Farms Ltd', 'info@freshfarms.com', '+91-9988776655', 'Farm Estate, Zone A, Bacolod City', 4.8, 'Active'),
(2, 'StyleHub Importers', 'wholesale@stylehub.com', '+91-8877665544', 'Sector 12, Industrial Hub, Manila', 4.2, 'Active'),
(3, 'SmartLogistics & Goods', 'orders@smartlogistics.com', '+91-7766554433', 'Warehouse Road, Block B, Iloilo City', 4.5, 'Active');

-- 2. Vendor Products Seed (mapping existing products)
INSERT IGNORE INTO `vendor_products` (`vendor_id`, `product_id`, `cost_price`, `lead_time_days`) VALUES
(2, 201737, 80.00, 5),
(2, 201738, 120.00, 6),
(2, 201739, 180.00, 4),
(2, 201740, 70.00, 5),
(2, 201741, 45.00, 3),
(3, 201742, 190.00, 8);

-- 3. Purchase Orders Seed
INSERT IGNORE INTO `purchase_orders` (`po_id`, `po_number`, `vendor_id`, `status`, `total_amount`, `expected_delivery`, `created_at`) VALUES
(1, 'PO-2026-0001', 2, 'Sent', 4850.00, DATE_ADD(CURRENT_DATE, INTERVAL 5 DAY), CURRENT_TIMESTAMP),
(2, 'PO-2026-0002', 3, 'Draft', 1900.00, DATE_ADD(CURRENT_DATE, INTERVAL 8 DAY), CURRENT_TIMESTAMP);

-- 4. Vendor Payouts Seed
INSERT IGNORE INTO `vendor_payouts` (`vendor_id`, `po_id`, `amount`, `status`, `processed_at`) VALUES
(2, 1, 4850.00, 'Unpaid', NULL);

-- 5. Returns & Refunds Seed
INSERT IGNORE INTO `returns` (`return_id`, `customer_id`, `order_number`, `return_status`, `request_date`, `reason_summary`, `refund_amount`) VALUES
(1, 9, 93, 'Pending', DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 2 DAY), 'Received wrong size for the Korean Casual Dress.', 119.00),
(2, 9, 94, 'Refunded', DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 10 DAY), 'Damaged top with a slight tear.', 199.00);

INSERT IGNORE INTO `return_items` (`return_id`, `product_id`, `quantity`, `return_reason_code`) VALUES
(1, 201737, 1, 'wrong_item'),
(2, 201738, 1, 'damaged');

INSERT IGNORE INTO `refunds` (`return_id`, `transaction_reference`, `refund_method`, `refund_status`, `processed_at`) VALUES
(2, 'REF-2019-0822-948', 'Cash on Delivery', 'Success', DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 9 DAY));

-- 6. Coupons Seed
INSERT IGNORE INTO `coupons` (`coupon_id`, `coupon_code`, `type`, `value`, `start_date`, `expiry_date`, `usage_limit`, `times_used`, `status`, `min_spend`, `max_spend`, `target_segment`) VALUES
(1, 'HMART10', 'percent', 10.0, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 6 MONTH), 500, 1, 'active', 200.0, 5000.0, 'All'),
(2, 'FRESH50', 'fixed', 50.0, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 6 MONTH), 100, 0, 'active', 500.0, 9999.0, 'VIP'),
(3, 'MISSYOU25', 'percent', 25.0, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 6 MONTH), 50, 0, 'active', 100.0, 2000.0, 'Churn_Risk');

INSERT IGNORE INTO `coupon_usage` (`coupon_id`, `customer_id`, `order_number`, `discount_applied`) VALUES
(1, 9, 94, 19.90);

-- 7. Shipping Tracking Seed
INSERT IGNORE INTO `shipping_tracking` (`tracking_id`, `order_number`, `carrier`, `tracking_number`, `status`, `origin_lat`, `origin_lng`, `current_lat`, `current_lng`, `dest_lat`, `dest_lng`, `eta_delivery`, `actual_delivery`) VALUES
(1, 93, 'H-Mart Delivery', 'HM-SH-0093', 'In Transit', 10.6698, 122.9563, 10.1524, 122.8912, 10.0264, 122.8123, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 1 DAY), NULL),
(2, 94, 'DHL Express', 'DH-SH-0094', 'Delivered', 10.6698, 122.9563, 10.0984, 122.8715, 10.0984, 122.8715, DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 DAY), DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 DAY));

INSERT IGNORE INTO `shipping_updates` (`tracking_id`, `location`, `status_details`, `updated_at`) VALUES
(1, 'Bacolod Main Warehouse', 'Package sorted and scanned.', DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 12 HOUR)),
(1, 'Bago City Transit Station', 'In transit towards southern destination.', DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 4 HOUR)),
(2, 'Bacolod Main Warehouse', 'Shipment picked up.', DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 2 DAY)),
(2, 'Himamaylan Hub', 'Arrived at delivery terminal.', DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 DAY)),
(2, 'Himamaylan Customer Home', 'Delivered and signed by Annie.', DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 DAY));

-- 8. Low Stock Alerts Seed
INSERT IGNORE INTO `low_stock_alerts` (`product_id`, `threshold`, `current_stock`, `status`, `notified_at`) VALUES
(201737, 10, 5, 'Active', DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 DAY)),
(201740, 5, 1, 'Active', DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 12 HOUR));

-- 9. Demand Forecasting Seed (30-day forecast curves)
INSERT IGNORE INTO `demand_forecasts` (`product_id`, `forecast_date`, `predicted_demand`, `recommended_reorder_qty`, `accuracy_metric`) VALUES
(201737, DATE_ADD(CURRENT_DATE, INTERVAL 1 DAY), 12.5, 0, 92.4),
(201737, DATE_ADD(CURRENT_DATE, INTERVAL 5 DAY), 14.2, 15, 92.4),
(201737, DATE_ADD(CURRENT_DATE, INTERVAL 15 DAY), 18.0, 20, 92.4),
(201738, DATE_ADD(CURRENT_DATE, INTERVAL 1 DAY), 8.1, 0, 89.5),
(201738, DATE_ADD(CURRENT_DATE, INTERVAL 5 DAY), 9.5, 10, 89.5),
(201739, DATE_ADD(CURRENT_DATE, INTERVAL 1 DAY), 19.3, 25, 94.1);

-- 10. Churn Predictions Seed
INSERT IGNORE INTO `churn_scores` (`customer_id`, `churn_probability`, `risk_level`, `top_risk_factors`) VALUES
(1, 14.5, 'Low', '[]'),
(2, 34.0, 'Medium', '["Purchase gap exceeds 45 days"]'),
(3, 78.2, 'High', '["No orders placed in last 90 days", "Negative review sentiment logged"]'),
(9, 12.0, 'Low', '[]');

-- 11. Review Sentiment Seed
INSERT IGNORE INTO `product_reviews_sentiment` (`review_id`, `product_id`, `customer_id`, `review_text`, `rating`, `sentiment_label`, `sentiment_score`, `topics_extracted`, `is_fake`, `is_fake_confidence`) VALUES
(1, 201737, 9, 'Really beautiful dress, fits perfectly and the fabric is very soft.', 5, 'Positive', 0.98, '["size", "fit", "fabric", "dress"]', 0, 2.5),
(2, 201738, 9, 'Okay product, but stitching was a bit loose. Delivery was fast though.', 3, 'Neutral', 0.51, '["stitching", "delivery"]', 0, 12.0),
(3, 201740, 3, 'Worst experience. The color faded completely on the first wash and it shrank! Do not buy!', 1, 'Negative', 0.99, '["color", "shrank", "wash", "quality"]', 0, 5.0);

-- 12. Recommendations Seed
INSERT IGNORE INTO `recommendations` (`customer_id`, `product_id`, `recommendation_type`, `score`) VALUES
(9, 201739, 'ALS', 0.89),
(9, 201741, 'ItemCF', 0.74),
(9, 201742, 'Trending', 0.95);

-- 13. Admin User Seed
INSERT IGNORE INTO `tbluseraccount` (`USERID`, `U_NAME`, `U_USERNAME`, `U_PASS`, `U_ROLE`, `USERIMAGE`) VALUES
(128, 'Admin', 'admin@hmart.com', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'Administrator', '');
