<?php
require_once(dirname(__DIR__) . '/include/initialize.php');
header('Content-Type: application/json');

$proid = isset($_POST['proid']) ? (int) $_POST['proid'] : (int) (isset($_GET['proid']) ? $_GET['proid'] : 0);
$categid = isset($_POST['categid']) ? (int) $_POST['categid'] : null;
if ($proid > 0) {
    RecommendationEngine::trackView($proid, $categid);
}
echo json_encode(['ok' => true]);
