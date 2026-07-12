<?php
/**
 * Database Connection Diagnostics Tool
 */
header('Content-Type: text/plain; charset=utf-8');

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "========================================\n";
echo " H-Mart Database Diagnostics Tool\n";
echo "========================================\n\n";

// 1. Check PHP Server Variables
echo "--- Environment Variables ---\n";
echo "DB_HOST env var: " . (getenv('DB_HOST') !== false ? getenv('DB_HOST') : "[NOT SET]") . "\n";
echo "DB_USER env var: " . (getenv('DB_USER') !== false ? getenv('DB_USER') : "[NOT SET]") . "\n";
echo "DB_PASS env var: " . (getenv('DB_PASS') !== false ? "[SET]" : "[NOT SET]") . "\n";
echo "DB_NAME env var: " . (getenv('DB_NAME') !== false ? getenv('DB_NAME') : "[NOT SET]") . "\n\n";

// 2. Include Configuration
if (file_exists(__DIR__ . '/../backend/include/config.php')) {
    require_once(__DIR__ . '/../backend/include/config.php');
    echo "--- Loaded config.php successfully ---\n";
    echo "Constant 'server': " . (defined('server') ? server : "[NOT DEFINED]") . "\n";
    echo "Constant 'user': " . (defined('user') ? user : "[NOT DEFINED]") . "\n";
    echo "Constant 'pass': " . (defined('pass') ? "[DEFINED]" : "[NOT DEFINED]") . "\n";
    echo "Constant 'database_name': " . (defined('database_name') ? database_name : "[NOT DEFINED]") . "\n\n";
} else {
    echo "Error: config.php not found!\n\n";
}

// 3. Test Database Connection
echo "--- Testing Connection ---\n";
if (defined('server') && defined('user') && defined('pass')) {
    echo "Attempting to connect to: " . server . " as user: " . user . "...\n";
    
    // Disable strict mode to catch error details manually
    mysqli_report(MYSQLI_REPORT_OFF);
    
    $start = microtime(true);
    $conn = @mysqli_connect(server, user, pass);
    $duration = round(microtime(true) - $start, 3);
    
    if ($conn) {
        echo "✅ Connection Success! (took {$duration} seconds)\n";
        
        $db_select = @mysqli_select_db($conn, database_name);
        if ($db_select) {
            echo "✅ Selected database '" . database_name . "' successfully!\n";
        } else {
            echo "❌ Failed to select database: " . mysqli_error($conn) . "\n";
        }
        mysqli_close($conn);
    } else {
        echo "❌ Connection Failed!\n";
        echo "Error Code: " . mysqli_connect_errno() . "\n";
        echo "Error Message: " . mysqli_connect_error() . "\n";
    }
} else {
    echo "Cannot test connection: credentials constants not defined.\n";
}
?>
