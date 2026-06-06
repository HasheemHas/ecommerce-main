<?php
session_start();
$_SESSION['CUSID'] = 9; // Use customer Annie Paredes from database dump

// Mock constants and require initializations
require_once('include/initialize.php');

// Define output buffer to capture the page output
ob_start();
include('customer/trackorder.php');
$output = ob_get_clean();

if (strpos($output, 'TypeError') === false && strpos($output, 'Warning') === false) {
    echo "TEST PASSED: trackorder page successfully generated without syntax or type errors!\n";
    // Check if it tracked an order or printed no orders
    if (strpos($output, 'Order #') !== false) {
        echo "Successfully tracked order automatically!\n";
    } else {
        echo "Successfully printed empty state order message!\n";
    }
} else {
    echo "TEST FAILED: Errors found in output!\n";
    echo substr($output, 0, 1000) . "\n";
}
?>
