<?php
require_once(dirname(__DIR__) . '/include/initialize.php');
header('Content-Type: application/json');

$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 8;
$customerId = isset($_GET['customer_id']) ? (int) $_GET['customer_id'] : null;
$items = RecommendationEngine::getRecommendations($customerId, $limit);
$out = [];
foreach ($items as $p) {
    $out[] = [
        'proid' => (int) $p->PROID,
        'name' => $p->PRODESC,
        'category' => $p->CATEGORIES,
        'price' => (float) $p->PRODISPRICE,
        'image' => product_image_url($p->IMAGES, $p->PRODESC),
        'url' => web_root . 'index.php?q=single-item&id=' . $p->PROID,
    ];
}
echo json_encode(['ok' => true, 'products' => $out]);
