<?php
require_once("../include/initialize.php");

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
    // Send request to FastAPI service
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://localhost:8000/api/customer/visual-search");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['image_name' => $filename]));
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($response && $http_code == 200) {
        $result = json_decode($response, true);
        if ($result && isset($result['results'])) {
            $ids = [];
            foreach ($result['results'] as $item) {
                if (isset($item['product_id'])) {
                    $ids[] = $item['product_id'];
                }
            }
            echo json_encode([
                'status' => 'success',
                'detected_tags' => $result['detected_tags'] ?? [],
                'product_ids' => implode(',', $ids),
                'filename' => $filename
            ]);
            exit;
        }
    }
    
    echo json_encode(['status' => 'error', 'message' => 'AI service failed to process search.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to save file locally.']);
}
?>
