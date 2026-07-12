<?php
require_once (dirname(__FILE__) . "/../backend/include/initialize.php");
echo "web_root: " . web_root . "\n";
echo "server_root: " . server_root . "\n";
echo "document_root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'not set') . "\n";
echo "this_file: " . str_replace('\\', '/', __FILE__) . "\n";
echo "custom_shoe_exists: " . (file_exists(dirname(__FILE__) . '/../admin/products/uploaded_photos/custom_shoe.png') ? 'yes' : 'no') . "\n";
echo "mens_mock_exists: " . (file_exists(dirname(__FILE__) . '/../admin/products/uploaded_photos/mens_mock_1.jpg') ? 'yes' : 'no') . "\n";
echo "womens_mock_exists: " . (file_exists(dirname(__FILE__) . '/../admin/products/uploaded_photos/womens_mock_1.jpg') ? 'yes' : 'no') . "\n";
echo "mens_p001_exists: " . (file_exists(dirname(__FILE__) . '/../admin/products/uploaded_photos/mens_p001.jpg') ? 'yes' : 'no') . "\n";
echo "womens_p001_exists: " . (file_exists(dirname(__FILE__) . '/../admin/products/uploaded_photos/womens_p001.jpg') ? 'yes' : 'no') . "\n";
?>
