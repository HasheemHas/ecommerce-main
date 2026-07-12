<?php
/**
 * Fix Product Images - Updates all products to use placeholder images
 * Visit: https://hmart.gt.tc/backend/database/fix_product_images.php
 */

require_once(__DIR__ . '/../include/config.php');

$conn = mysqli_connect(server, user, pass, database_name);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');

?>
<!DOCTYPE html>
<html>
<head>
    <title>Fix Product Images</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; }
        .success { color: green; padding: 10px; background: #e8f5e9; border: 1px solid green; border-radius: 4px; margin: 10px 0; }
        .info { color: #1565c0; padding: 10px; background: #e3f2fd; border: 1px solid #2196f3; border-radius: 4px; margin: 10px 0; }
        h1 { color: #333; }
    </style>
</head>
<body>
<div class='container'>
    <h1>🖼️ Fix Product Images</h1>
    <p>This will update all products to use placeholder images from a CDN.</p>

<?php

$categories = [
    "SHOES" => "👟",
    "BAGS" => "👜",
    "CLOTHING" => "👕",
    "INTERIORS" => "🪑",
    "HOUSEHOLDS" => "🏠",
    "FASHION" => "👗",
    "KIDS" => "🧸",
    "WOMENS" => "👩",
    "MENS" => "👨",
    "SPORTSWEAR" => "⚽",
    "MOBILE" => "📱",
    "ELECTRONICS" => "📺",
    "LAPTOPS" => "💻",
    "AUDIO" => "🎧",
    "CAMERAS" => "📷",
    "GROCERY" => "🛒"
];

try {
    // Get all products without images or with broken image paths
    $query = "SELECT PROID, PRODESC, CATEGID FROM tblproduct WHERE PROID >= 300001";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        throw new Exception("Query failed: " . mysqli_error($conn));
    }

    $updated = 0;
    $total = 0;

    // Get category mappings
    $catQuery = "SELECT CATEGID, CATEGORIES FROM tblcategory";
    $catResult = mysqli_query($conn, $catQuery);
    $catMap = [];
    while ($row = mysqli_fetch_assoc($catResult)) {
        $catMap[$row['CATEGID']] = $row['CATEGORIES'];
    }

    // Update each product
    while ($row = mysqli_fetch_assoc($result)) {
        $proid = $row['PROID'];
        $categid = $row['CATEGID'];
        $cat = isset($catMap[$categid]) ? strtolower($catMap[$categid]) : 'product';

        // Use placeholder image from Picsum or similar service
        $image_url = "placeholder/{$cat}_" . str_pad(($proid % 20 + 1), 3, '0', STR_PAD_LEFT) . ".jpg";

        $image_url_escaped = mysqli_real_escape_string($conn, $image_url);
        $updateQuery = "UPDATE tblproduct SET IMAGES = '{$image_url_escaped}' WHERE PROID = {$proid}";

        if (mysqli_query($conn, $updateQuery)) {
            $updated++;
        }
        $total++;
    }

    echo "<div class='success'>✅ Updated {$updated}/{$total} products with placeholder images</div>";
    echo "<div class='info'>ℹ️ Products now show placeholder images. To use real images, upload them to: <code>/admin/products/uploaded_photos/</code></div>";

    // List product image paths
    echo "<div class='info'><strong>Sample Image Paths:</strong><br>";
    $sampleQuery = "SELECT PROID, PRODESC, IMAGES FROM tblproduct WHERE PROID >= 300001 LIMIT 5";
    $sampleResult = mysqli_query($conn, $sampleQuery);
    echo "<ul>";
    while ($row = mysqli_fetch_assoc($sampleResult)) {
        echo "<li>{$row['PRODESC']}: {$row['IMAGES']}</li>";
    }
    echo "</ul></div>";

} catch (Exception $e) {
    echo "<div style='color: red; padding: 10px; background: #ffebee; border: 1px solid red; border-radius: 4px;'>";
    echo "❌ Error: " . htmlspecialchars($e->getMessage());
    echo "</div>";
}

mysqli_close($conn);

?>
</div>
</body>
</html>
