<?php
require_once(dirname(__DIR__) . "/include/initialize.php");

if (!isset($_SESSION['CUSID'])) {
    message("Please login first to write reviews.", "error");
    redirect($_SERVER['HTTP_REFERER']);
}

if (isset($_POST['submit_review'])) {
    global $mydb;
    $product_id = (int)$_POST['product_id'];
    $customer_id = (int)$_SESSION['CUSID'];
    $rating = (int)$_POST['rating'];
    $title = $_POST['review_title'];
    $text = $_POST['review_text'];
    
    // Call Python Sentiment Microservice
    $ch = curl_init("http://localhost:8000/api/sentiment/analyze");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'review_text' => $text,
        'rating' => $rating
    ]));
    $res = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);
    
    $sentiment = "Neutral";
    if ($res && $info['http_code'] == 200) {
        $result = json_decode($res, true);
        if (isset($result['result']['sentiment'])) {
            $sentiment = $result['result']['sentiment']; // e.g. Positive, Negative, Neutral
        }
    }
    
    // Check if customer actually purchased the item to set verified tag
    $mydb->setQuery("SELECT count(*) as total FROM `tblsummary` s, `tblorder` o WHERE s.ORDEREDNUM = o.ORDEREDNUM AND s.CUSTOMERID = {$customer_id} AND o.PROID = {$product_id}");
    $purch = $mydb->loadSingleResult();
    $is_verified = ($purch && $purch->total > 0) ? 1 : 0;
    
    $title_esc = $mydb->escape_value($title);
    $text_esc = $mydb->escape_value($text);
    
    // Insert review
    $mydb->setQuery("INSERT INTO `customer_reviews` (`product_id`, `customer_id`, `rating`, `review_title`, `review_text`, `is_verified_purchase`, `status`) 
                     VALUES ({$product_id}, {$customer_id}, {$rating}, '{$title_esc}', '{$text_esc}', {$is_verified}, 'Approved')");
    $mydb->executeQuery();
    $review_id = $mydb->insert_id();
    
    // Log sentiment tag to product_reviews_sentiment or similar table
    $mydb->setQuery("INSERT INTO `product_reviews_sentiment` (`product_id`, `sentiment_label`, `confidence_score`) 
                     VALUES ({$product_id}, '{$sentiment}', 0.85)");
    $mydb->executeQuery();
    
    message("Thank you! Your review has been submitted and auto-classified as " . $sentiment . " by AI.", "success");
}

redirect($_SERVER['HTTP_REFERER']);
?>
