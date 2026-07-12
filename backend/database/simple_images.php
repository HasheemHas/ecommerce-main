<?php
header('Content-Type: text/html; charset=utf-8');

if (php_sapi_name() !== 'cli' && (!isset($_GET['run']) || $_GET['run'] !== '1')) {
    echo "<h3>Generate Placeholder Images</h3>";
    echo "<p><a href='?run=1' style='padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 5px;'>Click to Generate</a></p>";
    exit;
}

require_once("../include/initialize.php");
global $mydb;

set_time_limit(300);
ini_set('max_execution_time', 300);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Generate Images</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f0f0f0; }
        .box { max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 5px; }
        .success { color: green; padding: 10px; background: #e8f5e9; margin: 10px 0; }
        .info { color: #1565c0; padding: 10px; background: #e3f2fd; margin: 10px 0; }
    </style>
</head>
<body>
<div class="box">
    <h1>🖼️ Generating Images...</h1>

<?php

$photos_dir = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'products' . DIRECTORY_SEPARATOR . 'uploaded_photos';

if (!is_dir($photos_dir)) {
    @mkdir($photos_dir, 0777, true);
}

$colors = array(
    "SHOES" => "#FF6B6B",
    "BAGS" => "#4ECDC4",
    "CLOTHING" => "#FFE66D",
    "INTERIORS" => "#95E1D3",
    "HOUSEHOLDS" => "#C7CEEA",
    "FASHION" => "#FF9999",
    "KIDS" => "#FF9999",
    "WOMENS" => "#FFB6B9",
    "MENS" => "#8EC5FC",
    "SPORTSWEAR" => "#A8EDEA",
    "MOBILE" => "#FED6E3",
    "ELECTRONICS" => "#74B9FF",
    "LAPTOPS" => "#A29BFE",
    "AUDIO" => "#FDCB6E",
    "CAMERAS" => "#E17055",
    "GROCERY" => "#00B894"
);

$generated = 0;

foreach ($colors as $cat => $color) {
    $query = "SELECT PROID, PRODESC FROM tblproduct
              WHERE CATEGID IN (SELECT CATEGID FROM tblcategory WHERE CATEGORIES = '{$cat}')
              AND PROID >= 300001 LIMIT 50";

    $mydb->setQuery($query);
    $products = $mydb->loadResultList();

    foreach ($products as $prod) {
        $filename = strtolower($cat) . "_p" . str_pad(($prod->PROID % 1000), 3, '0', STR_PAD_LEFT) . ".jpg";
        $filepath = $photos_dir . DIRECTORY_SEPARATOR . $filename;

        if (!file_exists($filepath)) {
            $img = imagecreatetruecolor(350, 350);
            $rgb = hexToRgb($color);
            $bg = imagecolorallocate($img, $rgb[0], $rgb[1], $rgb[2]);
            imagefill($img, 0, 0, $bg);

            $white = imagecolorallocate($img, 255, 255, 255);
            imagestring($img, 3, 20, 160, substr($prod->PRODESC, 0, 20), $white);
            imagestring($img, 3, 20, 180, "H-Mart", $white);

            imagejpeg($img, $filepath, 85);
            imagedestroy($img);
            $generated++;
        }
    }
}

echo "<div class='success'>✓ Generated {$generated} images</div>";

// Update database
$mydb->setQuery("UPDATE tblproduct
                SET IMAGES = CONCAT('uploaded_photos/', LOWER(
                    (SELECT CATEGORIES FROM tblcategory WHERE CATEGID = tblproduct.CATEGID)
                ), '_p', LPAD((PROID % 1000), 3, '0'), '.jpg')
                WHERE PROID >= 300001");

if ($mydb->executeQuery()) {
    echo "<div class='info'>✓ Database updated with image paths</div>";
}

echo "<div class='success'><strong>✅ Done!</strong></div>";
echo "<p><a href='" . web_root . "index.php'>Back to Site</a></p>";

function hexToRgb($hex) {
    $hex = str_replace("#", "", $hex);
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    return array($r, $g, $b);
}

?>
</div>
</body>
</html>
