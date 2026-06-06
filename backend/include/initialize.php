<?php
//define the core paths
//Define them as absolute peths to make sure that require_once works as expected

//DIRECTORY_SEPARATOR is a PHP Pre-defined constants:
//(\ for windows, / for Unix)
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

defined('SITE_ROOT') ? null : define('SITE_ROOT', dirname(__DIR__));

defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT . DS . 'include');

//load the database configuration first.
require_once(LIB_PATH.DS."config.php");
require_once(LIB_PATH.DS."function.php");
require_once(LIB_PATH.DS."session.php");
require_once(LIB_PATH.DS."accounts.php");
require_once(LIB_PATH.DS."autonumbers.php");
require_once(LIB_PATH.DS."products.php");
require_once(LIB_PATH.DS."stockin.php");
require_once(LIB_PATH.DS."categories.php");
require_once(LIB_PATH.DS."sidebarFunction.php"); 
require_once(LIB_PATH.DS."promos.php");
require_once(LIB_PATH.DS."customers.php");
require_once(LIB_PATH.DS."orders.php");
require_once(LIB_PATH.DS."summary.php");
require_once(LIB_PATH.DS."settings.php");
require_once(LIB_PATH.DS."ml_config.php");
require_once(LIB_PATH.DS."ml_bridge.php");
require_once(LIB_PATH.DS."mailer.php");
require_once(LIB_PATH.DS."email_templates.php");
require_once(LIB_PATH.DS."otp_service.php");
require_once(LIB_PATH.DS."recommendation_engine.php");
require_once(LIB_PATH.DS."fraud_detector.php");
require_once(LIB_PATH.DS."inventory_analytics.php");
require_once(LIB_PATH.DS."analytics_dashboard.php");
require_once(LIB_PATH.DS."ai_client.php");

require_once(LIB_PATH.DS."database.php");
?>