<?php
/**
 * Sentiment Analysis Controller.
 */
require_once("../../backend/include/initialize.php");

if (!isset($_SESSION['USERID'])) {
    redirect(web_root . "admin/index.php");
}

$view = (isset($_GET['view']) && $_GET['view'] != '') ? $_GET['view'] : '';
$action = (isset($_GET['action']) && $_GET['action'] != '') ? $_GET['action'] : '';

$sandboxResult = null;
if ($action === 'sandbox') {
    $text = isset($_POST['review_text']) ? trim($_POST['review_text']) : '';
    $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 5;
    
    if (!empty($text)) {
        // Send to FastAPI to run sentiment analyzer
        $sandboxResult = AIClient::call('/api/sentiment/analyze', 'POST', [
            'review_text' => $text,
            'rating' => $rating
        ]);
        if (isset($sandboxResult['result'])) {
            $sandboxResult = $sandboxResult['result'];
        }
    }
}

$title = "Sentiment Analysis AI";
$content = 'view.php';

require_once("../theme/templates.php");
?>
