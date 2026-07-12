<?php
require_once(dirname(__DIR__) . "/include/initialize.php");
global $mydb;

header('Content-Type: application/json');

if (!isset($_FILES['image'])) {
    echo json_encode(['status' => 'error', 'message' => 'No image uploaded.']);
    exit;
}

$upload_dir = dirname(__DIR__) . '/images/visual_search/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$file = $_FILES['image'];
$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = 'vs_' . time() . '_' . rand(100, 999) . '.' . $ext;
$target = $upload_dir . $filename;

if (move_uploaded_file($file['tmp_name'], $target)) {
    // NATIVE PHP Visual Search Match
    $img_lower = strtolower($file['name']); // Match against the original filename rather than vs_timestamp
    $tags = [];
    if (strpos($img_lower, "shoe") !== false || strpos($img_lower, "snkr") !== false || strpos($img_lower, "boot") !== false) {
        $tags = ["shoes", "reebok", "nike", "adidas"];
    } elseif (strpos($img_lower, "dress") !== false || strpos($img_lower, "shirt") !== false || strpos($img_lower, "jean") !== false || strpos($img_lower, "wear") !== false) {
        $tags = ["casual", "sleeveless", "printed", "shirt", "pants"];
    } elseif (strpos($img_lower, "electronics") !== false || strpos($img_lower, "phone") !== false || strpos($img_lower, "tv") !== false || strpos($img_lower, "tech") !== false) {
        $tags = ["smart", "phone", "led", "display"];
    } else {
        $tags = ["premium", "casual", "printed"];
    }

    $matched_products = [];
    if (!empty($tags)) {
        $likes = [];
        foreach ($tags as $t) {
            $tEsc = $mydb->escape_value($t);
            $likes[] = "PRODESC LIKE '%{$tEsc}%'";
        }
        $sql = "SELECT PROID, PRODESC, PROPRICE, IMAGES FROM tblproduct WHERE (" . implode(" OR ", $likes) . ") AND PROQTY > 0 LIMIT 6";
        $mydb->setQuery($sql);
        $matched_products = $mydb->loadResultList();
    }

    if (empty($matched_products)) {
        $mydb->setQuery("SELECT PROID, PRODESC, PROPRICE, IMAGES FROM tblproduct WHERE PROQTY > 0 ORDER BY RAND() LIMIT 4");
        $matched_products = $mydb->loadResultList();
    }

    $ids = [];
    foreach ($matched_products as $p) {
        $ids[] = (int)$p->PROID;
    }

    echo json_encode([
        'status' => 'success',
        'detected_tags' => $tags,
        'product_ids' => implode(',', $ids),
        'filename' => $filename
    ]);
    exit;
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to save file locally.']);
}
?>
