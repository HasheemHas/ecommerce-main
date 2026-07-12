<?php
require_once (dirname(__FILE__) . "/../backend/include/initialize.php");
$conn = mysqli_connect(server, user, pass, database_name);
$res = mysqli_query($conn, "SELECT COUNT(*) as count FROM tblproduct");
$row = mysqli_fetch_assoc($res);
echo "Total products: " . $row['count'] . "\n";

$res2 = mysqli_query($conn, "SELECT COUNT(*) as count FROM tblproduct WHERE PROID >= 300001");
$row2 = mysqli_fetch_assoc($res2);
echo "Seeded products (>= 300001): " . $row2['count'] . "\n";

$res3 = mysqli_query($conn, "SELECT PROID, PRODESC, IMAGES FROM tblproduct ORDER BY PROID DESC LIMIT 5");
echo "Latest products:\n";
while ($r = mysqli_fetch_assoc($res3)) {
    print_r($r);
}
?>
