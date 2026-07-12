<?php
require_once(dirname(__DIR__) . "/include/initialize.php");

if (!isset($_SESSION['CUSID'])) {
    message("Please login first to ask questions.", "error");
    redirect($_SERVER['HTTP_REFERER']);
}

if (isset($_POST['submit_question'])) {
    global $mydb;
    $product_id = (int)$_POST['product_id'];
    $customer_id = (int)$_SESSION['CUSID'];
    $question = $_POST['question_text'];
    
    $question_esc = $mydb->escape_value($question);
    
    $mydb->setQuery("INSERT INTO `review_qna` (`product_id`, `customer_id`, `question`) 
                     VALUES ({$product_id}, {$customer_id}, '{$question_esc}')");
    $mydb->executeQuery();
    
    message("Thank you! Your question has been posted. Our team or other customers will answer shortly.", "success");
}

redirect($_SERVER['HTTP_REFERER']);
?>
