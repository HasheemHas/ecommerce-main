-- Smart E-Commerce ML & Security Features
-- Run in phpMyAdmin on database: db_ecommerce

CREATE TABLE IF NOT EXISTS `tbl_otp_codes` (
  `OTP_ID` int(11) NOT NULL AUTO_INCREMENT,
  `EMAIL` varchar(120) NOT NULL,
  `OTP_CODE` varchar(45) NOT NULL,
  `PURPOSE` enum('login','signup','reset') NOT NULL DEFAULT 'login',
  `EXPIRES_AT` datetime NOT NULL,
  `IS_USED` tinyint(1) NOT NULL DEFAULT 0,
  `CREATED_AT` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`OTP_ID`),
  KEY `idx_otp_email` (`EMAIL`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `tbl_browse_history` (
  `HISTORY_ID` int(11) NOT NULL AUTO_INCREMENT,
  `CUSTOMERID` int(11) DEFAULT NULL,
  `PROID` int(11) NOT NULL,
  `CATEGID` int(11) DEFAULT NULL,
  `SESSION_ID` varchar(64) DEFAULT NULL,
  `VIEWED_AT` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`HISTORY_ID`),
  KEY `idx_browse_customer` (`CUSTOMERID`),
  KEY `idx_browse_proid` (`PROID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `tbl_login_attempts` (
  `ATTEMPT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `USERNAME` varchar(120) NOT NULL,
  `IP_ADDRESS` varchar(45) NOT NULL,
  `SUCCESS` tinyint(1) NOT NULL DEFAULT 0,
  `ATTEMPTED_AT` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ATTEMPT_ID`),
  KEY `idx_login_ip` (`IP_ADDRESS`),
  KEY `idx_login_user` (`USERNAME`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `tbl_payment_attempts` (
  `PAY_ATTEMPT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `CUSTOMERID` int(11) DEFAULT NULL,
  `ORDEREDNUM` int(11) DEFAULT NULL,
  `PAYMENT_METHOD` varchar(40) NOT NULL,
  `AMOUNT` double NOT NULL DEFAULT 0,
  `STATUS` enum('success','failed','blocked') NOT NULL DEFAULT 'failed',
  `FAILURE_REASON` varchar(255) DEFAULT NULL,
  `IP_ADDRESS` varchar(45) DEFAULT NULL,
  `ATTEMPTED_AT` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`PAY_ATTEMPT_ID`),
  KEY `idx_pay_customer` (`CUSTOMERID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `tbl_fraud_alerts` (
  `ALERT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `CUSTOMERID` int(11) DEFAULT NULL,
  `ALERT_TYPE` varchar(60) NOT NULL,
  `SEVERITY` enum('low','medium','high') NOT NULL DEFAULT 'medium',
  `DESCRIPTION` text NOT NULL,
  `META_JSON` text,
  `IS_RESOLVED` tinyint(1) NOT NULL DEFAULT 0,
  `CREATED_AT` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ALERT_ID`),
  KEY `idx_fraud_resolved` (`IS_RESOLVED`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `tbl_inventory_alerts` (
  `INV_ALERT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `PROID` int(11) NOT NULL,
  `ALERT_TYPE` enum('low_stock','fast_moving','slow_moving') NOT NULL,
  `MESSAGE` varchar(255) NOT NULL,
  `CREATED_AT` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`INV_ALERT_ID`),
  KEY `idx_inv_proid` (`PROID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
