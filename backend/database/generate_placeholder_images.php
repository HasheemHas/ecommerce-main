<?php
/**
 * Generate Placeholder Images for Products
 * Visit: https://hmart.gt.tc/backend/database/generate_placeholder_images.php
 */

require_once(__DIR__ . '/../include/config.php');

if (php_sapi_name() !== 'cli' && (!isset($_GET['run']) || $_GET['run'] !== '1')) {
    echo "<h3>Placeholder Image Generator</h3>";
    echo "<p>This will create colorful placeholder images for all products.</p>";
    echo "<p>To run: <a href='?run=1'><button style='padding: 10px 20px; background: #4f46e5; color: white; border: none; border-radius: 5px; cursor: pointer;'>Generate Images</button></a></p>";
    return;
}

set_time_limit(0);
ini_set('max_execution_time', 0);

$photos_dir = dirname(dirname(__DIR__)) . DS . 'admin' . DS . 'products' . DS . 'uploaded_photos';

// Create directory if it doesn't exist
if (!file_exists($photos_dir)) {
    @mkdir($photos_dir, 0777, true);
    echo "Created directory: {$photos_dir}<br>";
}

$categories = [
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
];

$conn = mysqli_connect(server, user, pass, database_name);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');

echo "<div style='font-family: Arial; padding: 20px; max-width: 800px; margin: 0 auto;'>";
echo "<h2>🖼️ Generating Placeholder Images...</h2>";

try {
    if (!extension_loaded('gd')) {
        throw new Exception("GD extension not loaded. Please enable it in php.ini");
    }

    $query = "SELECT DISTINCT c.CATEGID, c.CATEGORIES FROM tblproduct p
              JOIN tblcategory c ON p.CATEGID = c.CATEGID
              WHERE p.PROID >= 300001";
    $result = mysqli_query($conn, $query);

    $generated = 0;
    $total = 0;

    while ($row = mysqli_fetch_assoc($result)) {
        $cat_id = $row['CATEGID'];
        $cat_name = strtoupper($row['CATEGORIES']);
        $color = isset($categories[$cat_name]) ? $categories[$cat_name] : "#95E1D3";

        // Get all products in this category
        $prodQuery = "SELECT PROID, PRODESC FROM tblproduct WHERE CATEGID = {$cat_id} AND PROID >= 300001 LIMIT 50";
        $prodResult = mysqli_query($conn, $prodQuery);

        $prod_count = 0;
        while ($prod = mysqli_fetch_assoc($prodResult)) {
            $proid = $prod['PROID'];
            $filename = strtolower($cat_name) . "_p" . str_pad(($proid % 1000), 3, '0', STR_PAD_LEFT) . ".jpg";
            $filepath = $photos_dir . DS . $filename;

            // Only generate if doesn't exist
            if (!file_exists($filepath)) {
                generatePlaceholderImage($filepath, $color, substr($prod['PRODESC'], 0, 25));
                $generated++;
            }
            $total++;
            $prod_count++;
        }

        echo "<p style='color: #666;'>✓ {$cat_name}: {$prod_count} products</p>";
    }

    echo "<div style='background: #e8f5e9; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<strong style='color: #2e7d32;'>✅ Success!</strong><br>";
    echo "Generated: <strong>{$generated}</strong> new placeholder images<br>";
    echo "Total products: <strong>{$total}</strong>";
    echo "</div>";

    // Now update database to use these images
    $updateQuery = "SELECT DISTINCT c.CATEGID, c.CATEGORIES FROM tblproduct p
                    JOIN tblcategory c ON p.CATEGID = c.CATEGID
                    WHERE p.PROID >= 300001";
    $updateResult = mysqli_query($conn, $updateQuery);

    $updated_db = 0;
    while ($row = mysqli_fetch_assoc($updateResult)) {
        $cat_name = strtolower($row['CATEGORIES']);
        $cat_id = $row['CATEGID'];

        $dbUpdateQuery = "UPDATE tblproduct
                         SET IMAGES = CONCAT('uploaded_photos/', LOWER('{$cat_name}'), '_p', LPAD((PROID % 1000), 3, '0'), '.jpg')
                         WHERE CATEGID = {$cat_id} AND PROID >= 300001";

        if (mysqli_query($conn, $dbUpdateQuery)) {
            $rows_affected = mysqli_affected_rows($conn);
            $updated_db += $rows_affected;
        }
    }

    echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<strong>Database Updated:</strong> {$updated_db} products<br>";
    echo "Images are now linked in the database!";
    echo "</div>";

    echo "<p><a href='../'><button style='padding: 10px 20px; background: #4f46e5; color: white; border: none; border-radius: 5px; cursor: pointer;'>Done</button></a></p>";

} catch (Exception $e) {
    echo "<div style='background: #ffebee; padding: 15px; border-radius: 5px; color: #c62828;'>";
    echo "❌ Error: " . htmlspecialchars($e->getMessage());
    echo "</div>";
}

mysqli_close($conn);
echo "</div>";

function generatePlaceholderImage($filepath, $bgColor, $text = "Product") {
    $width = 350;
    $height = 350;

    $image = imagecreatetruecolor($width, $height);

    // Convert hex to RGB
    $rgb = hexToRgb($bgColor);
    $bg = imagecolorallocate($image, $rgb[0], $rgb[1], $rgb[2]);

    // Fill background
    imagefill($image, 0, 0, $bg);

    // Add text
    $textColor = imagecolorallocate($image, 255, 255, 255);
    $font = 3;
    $text = substr($text, 0, 20);

    imagestring($image, $font, 20, 160, $text, $textColor);
    imagestring($image, $font, 20, 180, "H-Mart Product", $textColor);

    // Save
    imagejpeg($image, $filepath, 85);
    imagedestroy($image);

    return true;
}

function hexToRgb($hex) {
    $hex = str_replace("#", "", $hex);
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    return [$r, $g, $b];
}
?>
