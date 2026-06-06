<?php
// Detect if the server environment is localhost (XAMPP)
$is_localhost = false;
if (isset($_SERVER['HTTP_HOST'])) {
    $host = strtolower($_SERVER['HTTP_HOST']);
    if (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false || strpos($host, '[::1]') !== false) {
        $is_localhost = true;
    }
} else if (isset($_SERVER['SERVER_NAME'])) {
    $name = strtolower($_SERVER['SERVER_NAME']);
    if ($name == 'localhost' || $name == '127.0.0.1' || $name == '::1') {
        $is_localhost = true;
    }
}

if ($is_localhost) {
    // Local environment (XAMPP)
    defined('server') ? null : define("server", "localhost");
    defined('user') ? null : define("user", "root");
    defined('pass') ? null : define("pass", "");
    defined('database_name') ? null : define("database_name", "db_ecommerce");
} else {
    // Production environment (InfinityFree)
    defined('server') ? null : define("server", "sql103.infinityfree.com");
    defined('user') ? null : define("user", "if0_42101441");
    defined('pass') ? null : define("pass", "Ujmz9BohZg");
    defined('database_name') ? null : define("database_name", "if0_42101441_db_ecommerce");
}

$this_file = str_replace('\\', '/', __File__) ;
$doc_root = $_SERVER['DOCUMENT_ROOT'];

$base_web_root = str_replace (array($doc_root, "backend/include/config.php") , '' , $this_file);

// Determine if the current script is inside admin, backend, or api
$is_admin_or_backend = false;
if (isset($_SERVER['SCRIPT_NAME'])) {
    $script = strtolower($_SERVER['SCRIPT_NAME']);
    if (strpos($script, '/admin/') !== false || strpos($script, '/backend/') !== false || strpos($script, '/api/') !== false) {
        $is_admin_or_backend = true;
    }
}

if ($is_admin_or_backend) {
    $web_root = $base_web_root;
} else {
    $web_root = $base_web_root . "frontend/";
}

$server_root = str_replace ('backend/include/config.php' ,'', $this_file);


define ('web_root' , $web_root);
define('server_root' , $server_root);
defined('GEMINI_API_KEY') ? null : define('GEMINI_API_KEY', '');
?>