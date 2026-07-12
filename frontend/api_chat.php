<?php
header('Content-Type: application/json');

// Allow larger POST bodies for base64 images (up to 20MB)
ini_set('post_max_size', '20M');
ini_set('upload_max_filesize', '20M');
ini_set('memory_limit', '256M');
ini_set('max_execution_time', 120);

// Catch all errors/fatal errors and return as JSON
error_reporting(E_ALL);
ini_set('display_errors', 0);
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        if (ob_get_length()) ob_clean();
        echo json_encode(['error' => 'Server error: ' . $error['message'], 'details' => $error['file'] . ':' . $error['line']]);
    }
});
set_error_handler(function($severity, $message, $file, $line) {
    echo json_encode(['error' => $message, 'details' => $file . ':' . $line]);
    exit;
});

require_once(dirname(__DIR__) . "/backend/include/initialize.php");
global $mydb;

$input = file_get_contents(php_sapi_name() === 'cli' ? 'php://stdin' : 'php://input');
$data = json_decode($input, true);
if (!$data || !isset($data['message'])) {
    echo json_encode(['error' => 'No message provided']);
    exit;
}

$model = isset($data['model']) ? $data['model'] : 'meta/llama-3.1-8b-instruct';
$history = isset($data['history']) ? $data['history'] : [];
$newMessage = $data['message'];
$images = isset($data['images']) ? $data['images'] : [];

$apiKey = defined('NVIDIA_API_KEY') ? NVIDIA_API_KEY : getenv('NVIDIA_API_KEY');
$use_fallback = false;
if (!$apiKey) {
    $use_fallback = true;
}
$url = 'https://integrate.api.nvidia.com/v1/chat/completions';

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

// Build user message content - array format if images attached, string otherwise
$userContent = $newMessage;
if (!empty($images)) {
    $contentParts = [];
    if (!empty($newMessage)) {
        $contentParts[] = ['type' => 'text', 'text' => $newMessage];
    } else {
        $contentParts[] = ['type' => 'text', 'text' => 'Analyze this image and describe what you see. If it looks like a product, identify it and help me find similar items in our catalog.'];
    }
    foreach ($images as $img) {
        $contentParts[] = [
            'type' => 'image_url',
            'image_url' => ['url' => $img['data']]
        ];
    }
    $userContent = $contentParts;
}

$messages[] = ['role' => 'user', 'content' => $userContent];

$postData = [
    'model' => $model,
    'messages' => $messages,
    'temperature' => 0.7,
    'max_tokens' => 1024
];

$response = false;
$curlError = "";
$httpCode = 0;

// Try up to 2 times: first with images (if any), then without images as fallback
$retry_without_images = false;

if (!$use_fallback) {
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
    $curlError = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // If model doesn't support image input, retry without images
    if ($httpCode === 200 && !empty($images)) {
        $respData = json_decode($response, true);
        $respContent = $respData['choices'][0]['message']['content'] ?? '';
        if (stripos($respContent, 'does not support image') !== false) {
            $retry_without_images = true;
        }
    }
    
    // If API request failed entirely
    if ($curlError || $httpCode !== 200) {
        $use_fallback = true;
    }
}

// Retry without images if vision model isn't supported
if ($retry_without_images && !$use_fallback) {
    $images = []; // Strip images
    // Rebuild message as plain text
    $userContent = $newMessage ?: "I uploaded an image. Please help me find products similar to what's in the image.";
    $messages[count($messages) - 1]['content'] = $userContent;
    // Use standard text model
    $postData['model'] = 'meta/llama-3.1-8b-instruct';
    $postData['messages'] = $messages;
    
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
    $curlError = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($curlError || $httpCode !== 200) {
        $use_fallback = true;
    }
}

if ($use_fallback) {
    // Fallback NLP Engine
    $userMsgLower = strtolower($newMessage);
    $responseText = "";

    if (preg_match('/\b(hi|hello|hey|greetings|greet|good morning|good afternoon)\b/i', $userMsgLower)) {
        $responseText = "Hello! 👋 I am your H-Mart AI Assistant. How can I help you today?<br><br>" .
                        "Here are some things I can help you with:<br>" .
                        "• 🔍 <b>Search products:</b> Try searching for items (e.g. \"show me shoes\" or \"find bags\")<br>" .
                        "• 🛒 <b>Check your cart:</b> Ask \"what is in my cart?\"<br>" .
                        "• 💳 Ask about our <b>shipping or return policies</b>";
    } elseif (preg_match('/\b(return|refund|exchange|replace)\b/i', $userMsgLower)) {
        $responseText = "💳 <b>H-Mart Return & Refund Policy:</b><br><br>" .
                        "We offer a <b>30-day money-back guarantee</b> for all items. Products must be unused, in their original packaging, and accompanied by the receipt.<br><br>" .
                        "To initiate a return, please contact support at <b>support@hmart.com</b>.";
    } elseif (preg_match('/\b(shipping|delivery|deliver|shipping fee|delivery fee|cost|charge)\b/i', $userMsgLower)) {
        $responseText = "🚚 <b>H-Mart Shipping & Delivery Information:</b><br><br>" .
                        "• <b>Kabankalan City:</b> ₹50 delivery fee (1-2 business days)<br>" .
                        "• <b>Himamaylan City:</b> ₹70 delivery fee (1-2 business days)<br>" .
                        "• <b>Free Shipping:</b> For orders totaling ₹2000 or more.<br><br>" .
                        "Orders are packed fresh and dispatched directly to your address.";
    } elseif (preg_match('/\b(contact|support|customer service|email|phone|call|help)\b/i', $userMsgLower)) {
        $responseText = "📞 <b>Contact H-Mart Customer Support:</b><br><br>" .
                        "• 📧 <b>Email:</b> support@hmart.com<br>" .
                        "• 📱 <b>Phone:</b> +91-9876543210 (Mon-Sat, 9AM - 6PM)<br>" .
                        "• 💬 <b>Contact Page:</b> You can submit questions via our Contact Form.<br><br>" .
                        "We typically reply to email inquiries within 24 hours!";
    } elseif (preg_match('/\b(payment|pay|cod|cash|card|credit)\b/i', $userMsgLower)) {
        $responseText = "💳 <b>Payment Methods at H-Mart:</b><br><br>" .
                        "We support <b>Cash on Delivery (COD)</b> for all local deliveries. You can also securely pay online using credit cards, debit cards, or net banking during the checkout process.";
    } elseif (preg_match('/\b(hour|hours|time|open|close|location|address)\b/i', $userMsgLower)) {
        $responseText = "🏪 <b>Store Hours & Location:</b><br><br>" .
                        "• <b>Hours:</b> 8:00 AM to 9:00 PM daily.<br>" .
                        "• <b>Location:</b> H-Mart Supermarket, Main Road, Kabankalan City.<br><br>" .
                        "Come visit us to browse our fresh local produce!";
    } elseif (preg_match('/\b(thank|thanks|thank you)\b/i', $userMsgLower)) {
        $responseText = "You're very welcome! 😊 Let me know if you have any other questions. Happy shopping!";
    } else {
        if (!empty($productsResponse)) {
            $responseText = "I found some items in our catalog that match your request. Let me know if you'd like to adjust your search or explore other categories!";
        } else {
            $responseText = "I'm here to help you get the most out of H-Mart! 🛍️<br><br>" .
                            "You can write queries like:<br>" .
                            "• \"show me shoes\" or \"search fresh\" to look up items.<br>" .
                            "• \"what is in my cart?\" to see your basket.<br>" .
                            "• Ask about \"shipping fees\" or \"returns\".<br><br>" .
                            "For direct support, email us at <b>support@hmart.com</b>.";
        }
    }

    $responseData = [
        'choices' => [
            [
                'message' => [
                    'role' => 'assistant',
                    'content' => $responseText
                ]
            ]
        ],
        'products' => $productsResponse
    ];
    if ($isImageRequest && !empty($imagePrompt)) {
        $responseData['generated_image_url'] = "https://image.pollinations.ai/prompt/" . $imagePrompt;
    }
    echo json_encode($responseData);
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
exit;
?>
