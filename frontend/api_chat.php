<?php
header('Content-Type: application/json');

require_once("../backend/include/initialize.php");
global $mydb;

$apiKey = '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';
$url = 'https://integrate.api.nvidia.com/v1/chat/completions';

$data = json_decode(file_get_contents('php://input'), true);
$model = isset($data['model']) ? $data['model'] : 'meta/llama-3.1-8b-instruct';

if (!$data || !isset($data['message'])) {
    echo json_encode(['error' => 'No message provided']);
    exit;
}

$history = isset($data['history']) ? $data['history'] : [];
$newMessage = $data['message'];

// Detect image generation request
$isImageRequest = false;
$imagePrompt = "";
if (preg_match('/\b(generate|create|draw|paint|show|make)\s+(?:an?\s+)?(?:image|picture|photo|illustration|drawing|painting|art)\b/i', $newMessage)) {
    $isImageRequest = true;
    $cleanText = preg_replace('/\b(generate|create|draw|paint|make|show|me|an?|image|picture|photo|illustration|drawing|painting|art|of)\b/i', '', $newMessage);
    $imagePrompt = urlencode(trim($cleanText));
}

// 1. Fetch dynamic categories list from DB
$mydb->setQuery("SELECT DISTINCT CATEGORIES FROM tblcategory");
$catResults = $mydb->loadResultList();
$allCategories = [];
foreach ($catResults as $c) {
    $allCategories[] = strtoupper($c->CATEGORIES);
}

// Check if user is asking about their cart
$isCartInquiry = false;
if (preg_match('/\b(cart|bag|basket|added|in my cart|my cart|what\'s in my cart|show my cart|list my cart|check my cart|what have i added|what did i add|cart details)\b/i', $newMessage)) {
    $isCartInquiry = true;
}

$matchedProducts = [];
$productsResponse = [];
$productsContext = "";

if ($isCartInquiry) {
    $productsContext = "The user is asking about their current shopping cart.\n";
    if (isset($_SESSION['gcCart']) && is_array($_SESSION['gcCart']) && count($_SESSION['gcCart']) > 0) {
        $productsContext .= "Here are the items currently in their cart:\n";
        foreach ($_SESSION['gcCart'] as $item) {
            $pid = intval($item['productid']);
            $qty = intval($item['qty']);
            $price = floatval($item['price']);
            $mydb->setQuery("SELECT PRODESC FROM tblproduct WHERE PROID = {$pid}");
            $prod = $mydb->loadSingleResult();
            $pname = $prod ? $prod->PRODESC : "Product ID " . $pid;
            $productsContext .= "- Name: \"{$pname}\", Quantity: {$qty}, Price: ₹{$price}, ID: {$pid}\n";
        }
        $productsContext .= "Summarize the user's cart items clearly. Since the user only asked about their cart, do NOT search for or recommend other products.\n";
    } else {
        $productsContext .= "Their shopping cart is currently empty. Inform them politely.\n";
    }
} else {
    // 2. Perform DB keyword product search for grounding
    $words = preg_split('/[\s,\.\?\!\;]+/', strtolower($newMessage));
    $stopWords = ['i', 'want', 'to', 'buy', 'show', 'me', 'find', 'search', 'recommend', 'look', 'up', 'the', 'a', 'an', 'some', 'for', 'best', 'good', 'with', 'and', 'or', 'in', 'on', 'at', 'under', 'need', 'ingredients', 'recipe'];
    $searchWords = [];
    foreach ($words as $word) {
        if (strlen($word) > 2 && !in_array($word, $stopWords)) {
            $searchWords[] = $mydb->escape_value($word);
        }
    }

    if (!empty($searchWords)) {
        $conditions = [];
        foreach ($searchWords as $sw) {
            $conditions[] = "p.PRODESC LIKE '%{$sw}%' OR c.CATEGORIES LIKE '%{$sw}%'";
        }
        $whereSql = implode(' OR ', $conditions);
        
        $query = "SELECT p.PROID, p.PRODESC, p.PROQTY, p.IMAGES, c.CATEGORIES, pr.PRODISPRICE, p.PROPRICE 
                  FROM tblproduct p 
                  JOIN tblcategory c ON p.CATEGID = c.CATEGID 
                  LEFT JOIN tblpromopro pr ON p.PROID = pr.PROID
                  WHERE p.PROQTY > 0 AND ({$whereSql}) 
                  LIMIT 6";
        $mydb->setQuery($query);
        $matchedProducts = $mydb->loadResultList();
    }

    // If no direct matches but user mentioned a specific category, fetch items from it
    if (empty($matchedProducts)) {
        foreach ($allCategories as $cat) {
            if (stripos($newMessage, $cat) !== false) {
                $catEscaped = $mydb->escape_value($cat);
                $query = "SELECT p.PROID, p.PRODESC, p.PROQTY, p.IMAGES, c.CATEGORIES, pr.PRODISPRICE, p.PROPRICE 
                          FROM tblproduct p 
                          JOIN tblcategory c ON p.CATEGID = c.CATEGID 
                          LEFT JOIN tblpromopro pr ON p.PROID = pr.PROID
                          WHERE p.PROQTY > 0 AND c.CATEGORIES = '{$catEscaped}' 
                          LIMIT 6";
                $mydb->setQuery($query);
                $matchedProducts = $mydb->loadResultList();
                break;
            }
        }
    }
}
if (!empty($matchedProducts)) {
    $productsContext = "Here are the matching H-Mart products found in our database for the user's inquiry:\n";
    foreach ($matchedProducts as $p) {
        $price = (float)(isset($p->PRODISPRICE) && $p->PRODISPRICE > 0 ? $p->PRODISPRICE : $p->PROPRICE);
        $productsResponse[] = [
            'id' => $p->PROID,
            'name' => $p->PRODESC,
            'category' => $p->CATEGORIES,
            'price' => $price,
            'image' => str_replace('frontend/', '', web_root) . 'admin/products/' . $p->IMAGES,
            'url' => web_root . 'index.php?q=single-item&id=' . $p->PROID
        ];
        $productsContext .= "- Name: \"{$p->PRODESC}\", Category: \"{$p->CATEGORIES}\", Price: ₹{$price}, ID: {$p->PROID}\n";
    }
    $productsContext .= "If the user is asking to find or recommend products, introduce these specific products and discuss their pricing and match for their request. Do not make up external links, point them to our catalog.\n";
}

$categoriesListStr = implode(', ', $allCategories);

$systemPrompt = "You are H-Mart AI Assistant, an advanced, highly intelligent personal shopper for the H-Mart ecommerce store. 
You help users find products, recommend recipes/ingredients, and curate shopping lists based on their budget.
You are professional, friendly, and expert in food, shopping, and household items. Keep your responses concise, structured, and helpful.

H-Mart Available Product Categories: {$categoriesListStr}.
Do NOT recommend any categories or products that H-Mart does not sell. If a user asks for something outside H-Mart's categories, politely state that we don't have it and suggest related options we do carry.

{$productsContext}
Format your output using basic HTML tags (like <b>, <i>, <ul>, <li>, <br>) so it renders beautifully in a web chat interface. Do not use Markdown, only HTML.";

$messages = [
    ['role' => 'system', 'content' => $systemPrompt]
];

foreach ($history as $msg) {
    if (isset($msg['role']) && isset($msg['content'])) {
        $messages[] = [
            'role' => $msg['role'] === 'user' ? 'user' : 'assistant',
            'content' => $msg['content']
        ];
    }
}

$messages[] = ['role' => 'user', 'content' => $newMessage];

$postData = [
    'model' => $model,
    'messages' => $messages,
    'temperature' => 0.7,
    'max_tokens' => 1024
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt($ch, CURLOPT_TIMEOUT, 45);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    echo json_encode(['error' => 'API Error: ' . $httpCode, 'details' => json_decode($response)]);
    exit;
}

$responseData = json_decode($response, true);
if (isset($responseData['choices'][0]['message'])) {
    $responseData['products'] = $productsResponse;
    if ($isImageRequest && !empty($imagePrompt)) {
        $responseData['generated_image_url'] = "https://image.pollinations.ai/prompt/" . $imagePrompt;
    }
}

echo json_encode($responseData);
?>

