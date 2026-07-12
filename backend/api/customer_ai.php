<?php
require_once(dirname(__DIR__) . "/include/initialize.php");
global $mydb;

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action === 'search-suggest') {
    $query = isset($_GET['q']) ? trim($_GET['q']) : '';
    if (strlen($query) < 2) {
        echo json_encode([
            'query' => $query,
            'corrected_spelling' => null,
            'suggestions' => [],
            'related_searches' => []
        ]);
        exit;
    }
    
    $query_lower = strtolower($query);
    
    // Fetch distinct PRODESC from tblproduct
    $mydb->setQuery("SELECT DISTINCT PRODESC FROM tblproduct");
    $rows = $mydb->loadResultList();
    $catalog_names = [];
    if ($rows) {
        foreach ($rows as $r) {
            if (!empty($r->PRODESC)) {
                $catalog_names[] = $r->PRODESC;
            }
        }
    }
    
    $corrections = [];
    $autocompletes = [];
    
    foreach ($catalog_names as $term) {
        $term_clean = strtolower($term);
        if (strpos($term_clean, $query_lower) === 0) {
            $autocompletes[] = $term;
        }
        
        $words = explode(' ', $term_clean);
        foreach ($words as $w) {
            $w = trim($w);
            if (empty($w)) continue;
            
            // levenshtein distance check
            $dist = levenshtein($query_lower, $w);
            if ($dist > 0 && $dist <= 2 && !in_array($w, $corrections)) {
                $corrections[] = $w;
            }
        }
    }
    
    echo json_encode([
        'query' => $query,
        'corrected_spelling' => !empty($corrections) ? $corrections[0] : null,
        'suggestions' => array_slice($autocompletes, 0, 6),
        'related_searches' => array_slice($corrections, 0, 5)
    ]);
    exit;
}

if ($action === 'cart-risk') {
    // Read raw input
    $inputData = file_get_contents('php://input');
    $payload = json_decode($inputData, true);
    
    $items_count = isset($payload['items_count']) ? (int)$payload['items_count'] : 0;
    $cart_total = isset($payload['cart_total']) ? (float)$payload['cart_total'] : 0.0;
    
    $risk_score = 50.0;
    if ($cart_total > 5000) {
        $risk_score += 20.0;
    }
    if ($items_count == 1) {
        $risk_score += 15.0;
    }
    if ($cart_total < 500) {
        $risk_score -= 15.0;
    }
    
    $risk_score = min(100.0, max(0.0, $risk_score));
    
    $coupon_offered = null;
    $discount_pct = 0;
    if ($risk_score >= 70.0) {
        $coupon_offered = "SAVE15";
        $discount_pct = 15;
    } elseif ($risk_score >= 50.0) {
        $coupon_offered = "SAVE10";
        $discount_pct = 10;
    }
    
    echo json_encode([
        "session_id" => "session_" . time(),
        "abandonment_risk_pct" => $risk_score,
        "risk_level" => ($risk_score >= 70) ? "High" : (($risk_score >= 40) ? "Medium" : "Low"),
        "trigger_coupon" => $coupon_offered,
        "discount_pct" => $discount_pct,
        "urgency_msg" => "Hurry! Items in your cart are selling fast. Check out now to secure your stock."
    ]);
    exit;
}

echo json_encode(['error' => 'Unknown action']);
exit;
?>
