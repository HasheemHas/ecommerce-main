<?php
require_once("../include/initialize.php");

header('Content-Type: application/json');

if (!isset($_SESSION['CUSID'])) {
    echo json_encode(['status' => 'error', 'message' => 'Please login first.']);
    exit;
}

$review_id = isset($_GET['review_id']) ? (int)$_GET['review_id'] : 0;
$vote_type = isset($_GET['vote_type']) && $_GET['vote_type'] === 'unhelpful' ? 'unhelpful' : 'helpful';
$customer_id = (int)$_SESSION['CUSID'];

if ($review_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid review ID.']);
    exit;
}

global $mydb;

// Check if customer already voted on this review
$mydb->setQuery("SELECT * FROM `review_votes` WHERE `review_id` = {$review_id} AND `customer_id` = {$customer_id}");
$voted = $mydb->loadSingleResult();

if ($voted) {
    if ($voted->vote_type === $vote_type) {
        echo json_encode(['status' => 'error', 'message' => 'You have already voted this way.']);
        exit;
    } else {
        // Update vote
        $mydb->setQuery("UPDATE `review_votes` SET `vote_type` = '{$vote_type}' WHERE `vote_id` = {$voted->vote_id}");
        $mydb->executeQuery();
    }
} else {
    // Insert new vote
    $mydb->setQuery("INSERT INTO `review_votes` (`review_id`, `customer_id`, `vote_type`) VALUES ({$review_id}, {$customer_id}, '{$vote_type}')");
    $mydb->executeQuery();
}

// Fetch new counts
$mydb->setQuery("SELECT count(*) as total FROM `review_votes` WHERE `review_id` = {$review_id} AND `vote_type` = 'helpful'");
$helpful_res = $mydb->loadSingleResult();
$mydb->setQuery("SELECT count(*) as total FROM `review_votes` WHERE `review_id` = {$review_id} AND `vote_type` = 'unhelpful'");
$unhelpful_res = $mydb->loadSingleResult();

echo json_encode([
    'status' => 'success', 
    'message' => 'Vote recorded.',
    'helpful_count' => $helpful_res ? $helpful_res->total : 0,
    'unhelpful_count' => $unhelpful_res ? $unhelpful_res->total : 0
]);
?>
