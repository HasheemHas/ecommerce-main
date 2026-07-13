<?php
// Detect if the server environment is localhost (XAMPP)
$is_localhost = false;
if (php_sapi_name() === 'cli') {
    $is_localhost = true;
} else if (isset($_SERVER['HTTP_HOST'])) {
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
    // Production credentials must be supplied by the hosting environment.
    // Never keep live database passwords in the repository.
    $db_server = getenv('DB_HOST') ?: "localhost";
    $db_user   = getenv('DB_USER') ?: "root";
    $db_pass   = getenv('DB_PASS') !== false ? getenv('DB_PASS') : "";
    $db_name   = getenv('DB_NAME') ?: "db_ecommerce";

    defined('server') ? null : define("server", $db_server);
    defined('user') ? null : define("user", $db_user);
    defined('pass') ? null : define("pass", $db_pass);
    defined('database_name') ? null : define("database_name", $db_name);
}

defined('database_port') ? null : define('database_port', (int) (getenv('DB_PORT') ?: 3306));

$this_file = str_replace('\\', '/', __File__) ;
$doc_root = isset($_SERVER['DOCUMENT_ROOT']) ? str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']) : '';

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

// AI Shopper / NVIDIA API Configuration
// Get from environment or use fallback
$nvidia_key = getenv('NVIDIA_API_KEY');
if (!$nvidia_key && file_exists(dirname(__FILE__) . '/.env.local')) {
    $env_lines = file(dirname(__FILE__) . '/.env.local', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($env_lines as $line) {
        if (strpos($line, 'NVIDIA_API_KEY=') === 0) {
            $nvidia_key = str_replace('NVIDIA_API_KEY=', '', $line);
            break;
        }
    }
}
defined('NVIDIA_API_KEY') ? null : define('NVIDIA_API_KEY', $nvidia_key);
?>
