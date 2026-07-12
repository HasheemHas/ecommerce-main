<?php
/**
 * Backend API processor for H-Mart Chatbot.
 * Handles order status tracking, catalog search, Gemini API call, or local rules fallback.
 */
header('Content-Type: application/json');

// Include system initializer
$initPath = dirname(__DIR__) . '/include/initialize.php';
if (file_exists($initPath)) {
    require_once($initPath);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Initialization script not found.'
    ]);
    exit;
}

// Read raw POST body data
$inputData = file_get_contents(php_sapi_name() === 'cli' ? 'php://stdin' : 'php://input');
$payload = json_decode($inputData, true);

if (!$payload || !isset($payload['message'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid input payload.'
    ]);
    exit;
}

$userMsg = trim($payload['message']);
$response = [
    'status' => 'success',
    'text' => '',
    'products' => []
];

// 1. Session Context Setup
$customerId = isset($_SESSION['CUSID']) ? (int)$_SESSION['CUSID'] : 0;
$customerName = isset($_SESSION['CUSNAME']) ? $_SESSION['CUSNAME'] : 'Guest';

global $mydb;

// Helper function to fetch products by query
function getProductCards($sqlQuery) {
    global $mydb;
    $mydb->setQuery($sqlQuery);
    $items = $mydb->loadResultList();
    $cards = [];
    if ($items) {
        foreach ($items as $item) {
            $cards[] = [
                'name' => $item->PRODESC,
                'category' => isset($item->CATEGORIES) ? $item->CATEGORIES : 'Category',
                'price' => (float)(isset($item->PRODISPRICE) ? $item->PRODISPRICE : (isset($item->PROPRICE) ? $item->PROPRICE : 0.0)),
                'image' => str_replace('frontend/', '', web_root) . 'admin/products/' . $item->IMAGES,
                'url' => web_root . 'index.php?q=single-item&id=' . $item->PROID
            ];
        }
    }
    return $cards;
}

// 2. Direct Keyword Interception (Order Tracking & Catalog Search)

// Case A: Order Tracking request
$orderNumber = null;
if (preg_match('/(?:track|status|order)\s*#?\s*(\d+)/i', $userMsg, $matches)) {
    $orderNumber = $matches[1];
} elseif (preg_match('/^\s*#?(\d+)\s*$/', $userMsg, $matches)) {
    // Message is just a number (e.g. they typed "93" in response to prompt)
    $orderNumber = $matches[1];
}

if ($orderNumber !== null) {
    // Sanitize order number
    $safeOrderNum = (int)$orderNumber;
    
    // Query order summary details
    $sql = "SELECT * FROM `tblsummary` WHERE `ORDEREDNUM` = {$safeOrderNum} LIMIT 1";
    $mydb->setQuery($sql);
    $orderSummary = $mydb->loadSingleResult();

    if ($orderSummary) {
        // Optional security check: if user is logged in, verify it's theirs
        // But to make it convenient, we'll track any valid order number, and personalize if it's theirs
        $status = htmlspecialchars($orderSummary->ORDEREDSTATS);
        $remarks = htmlspecialchars($orderSummary->ORDEREDREMARKS);
        $date = date('F j, Y, g:i a', strtotime($orderSummary->ORDEREDDATE));
        $payMethod = htmlspecialchars($orderSummary->PAYMENTMETHOD);
        
        $response['text'] = "📦 **Order #{$safeOrderNum} Details:**\n\n" .
                            "- **Status:** `{$status}`\n" .
                            "- **Updates:** {$remarks}\n" .
                            "- **Placed on:** {$date}\n" .
                            "- **Payment Method:** {$payMethod}\n\n" .
                            "You can view more details and past purchases on your [Order History Page](index.php?q=trackorder).";
    } else {
        $response['text'] = "I couldn't find an order with the number **#{$safeOrderNum}** in our database. Please double check the number and try again.";
    }
    
    echo json_encode($response);
    exit;
}

// Check for trigger words related to tracking without a number
if (preg_match('/^(?:track\s+order|order\s+status|where\s+is\s+my\s+order)/i', $userMsg)) {
    $response['text'] = "I can definitely help you check your order! 📦 Please reply with your **Order Number** (e.g., type `track order 93` or just enter the number).";
    echo json_encode($response);
    exit;
}


// Case B: Product Inquiry & Recommendations
$searchQuery = null;
if (preg_match('/(?:search|find|buy|show|recommend|look\s*up)\s*(.*)/i', $userMsg, $matches)) {
    $searchQuery = trim($matches[1]);
}

// Fetch all available category names to see if they match the message
$mydb->setQuery("SELECT `CATEGORIES` FROM `tblcategory` ");
$categoriesList = $mydb->loadResultList();
$matchedCategory = null;

if ($categoriesList) {
    foreach ($categoriesList as $catRow) {
        $catName = $catRow->CATEGORIES;
        if (stripos($userMsg, $catName) !== false) {
            $matchedCategory = $catName;
            break;
        }
    }
}

// Trigger recommendations or searches
if ($searchQuery !== null || $matchedCategory !== null || stripos($userMsg, 'product') !== false || stripos($userMsg, 'recommend') !== false || stripos($userMsg, 'browse') !== false) {
    
    $searchTerm = $searchQuery ? $searchQuery : $matchedCategory;
    $searchTermEscaped = $searchTerm ? $mydb->escape_value($searchTerm) : '';

    if (!empty($searchTermEscaped)) {
        // Query products matching keyword in description or category
        $sql = "SELECT p.*, c.CATEGORIES, pr.PRODISPRICE FROM `tblproduct` p 
                JOIN `tblcategory` c ON p.CATEGID = c.CATEGID 
                LEFT JOIN `tblpromopro` pr ON p.PROID = pr.PROID
                WHERE p.PROQTY > 0 AND (p.PRODESC LIKE '%{$searchTermEscaped}%' OR c.CATEGORIES LIKE '%{$searchTermEscaped}%') 
                ORDER BY p.PROID DESC LIMIT 3";
        $products = getProductCards($sql);
        
        if (count($products) > 0) {
            $response['text'] = "I found these items in our shop related to **\"{$searchTerm}\"**:";
            $response['products'] = $products;
        } else {
            // Fallback to general recommendations if search yielded nothing
            $sql = "SELECT p.*, c.CATEGORIES FROM `tblpromopro` pr, `tblproduct` p, `tblcategory` c 
                    WHERE pr.PROID=p.PROID AND p.CATEGID=c.CATEGID AND p.PROQTY > 0 
                    ORDER BY RAND() LIMIT 3";
            $response['text'] = "I couldn't find items matching **\"{$searchTerm}\"**, but here are some of our popular products you might like:";
            $response['products'] = getProductCards($sql);
        }
    } else {
        // User asked for recommendations generally
        $recommended = [];
        try {
            $recommended = RecommendationEngine::getRecommendations($customerId > 0 ? $customerId : null, 3);
        } catch (Throwable $e) {
            $recommended = [];
        }
        
        if ($recommended && count($recommended) > 0) {
            $response['text'] = "Based on your preferences, here are our top recommendations for you:";
            foreach ($recommended as $item) {
                $response['products'][] = [
                    'name' => $item->PRODESC,
                    'category' => $item->CATEGORIES,
                    'price' => (float)$item->PRODISPRICE,
                    'image' => str_replace('frontend/', '', web_root) . 'admin/products/' . $item->IMAGES,
                    'url' => web_root . 'index.php?q=single-item&id=' . $item->PROID
                ];
            }
        } else {
            // Random popular products
            $sql = "SELECT p.*, c.CATEGORIES FROM `tblpromopro` pr, `tblproduct` p, `tblcategory` c 
                    WHERE pr.PROID=p.PROID AND p.CATEGID=c.CATEGID AND p.PROQTY > 0 
                    ORDER BY RAND() LIMIT 3";
            $response['text'] = "Here are some of our popular products:";
            $response['products'] = getProductCards($sql);
        }
    }
    
    echo json_encode($response);
    exit;
}


// 3. Conversational Response (Gemini API or Fallback Rule-Based Engine)

if (defined('GEMINI_API_KEY') && GEMINI_API_KEY !== '') {
    // Assemble system prompt with H-Mart store policies, catalog and user details
    $categoryNames = [];
    if ($categoriesList) {
        foreach ($categoriesList as $catRow) {
            $categoryNames[] = $catRow->CATEGORIES;
        }
    }
    $categoriesStr = implode(', ', $categoryNames);

    $systemContext = "You are the friendly 'H-Mart Assistant', a customer support AI for H-Mart Store. " .
                     "Keep responses concise (maximum 150 words), and use bullet points and emojis. Do not use Markdown headers like ## or ###. " .
                     "Store Info: " .
                     "- Categories: {$categoriesStr}. " .
                     "- Shipping: We deliver to Kabankalan City (₹50 fee) and Himamaylan City (₹70 fee). " .
                     "- Return Policy: 30 days money-back guarantee for unused goods with receipt. " .
                     "- Contact: support@hmart.com or phone +91-9876543210. " .
                     "Active Customer context: Name: {$customerName}, ID: {$customerId}. " .
                     "If they ask to track orders, remind them to type 'track order [number]'. " .
                     "If they want to search products, tell them to type 'search [item]'.";

    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=" . GEMINI_API_KEY;

    $postData = [
        "contents" => [
            [
                "role" => "user",
                "parts" => [
                    ["text" => $systemContext . "\n\nUser Message: " . $userMsg]
                ]
            ]
        ],
        "generationConfig" => [
            "temperature" => 0.7,
            "maxOutputTokens" => 300
        ]
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200 && $result) {
        $resultObj = json_decode($result, true);
        if (isset($resultObj['candidates'][0]['content']['parts'][0]['text'])) {
            $response['text'] = trim($resultObj['candidates'][0]['content']['parts'][0]['text']);
            echo json_encode($response);
            exit;
        }
    }
}

// 4. Robust NLP Rule-Based Fallback Engine (Triggered if Gemini fails or is not configured)

$userMsgLower = strtolower($userMsg);

if (preg_match('/\b(hi|hello|hey|greetings|greet|good morning|good afternoon)\b/i', $userMsgLower)) {
    $response['text'] = "Hello! 👋 I am the H-Mart Assistant. How can I help you today?\n\n" .
                        "Here are some things I can help you with:\n" .
                        "- 📦 **Track an order:** Type `track order [number]`\n" .
                        "- 🔍 **Search products:** Type `search [item]`\n" .
                        "- 💳 Check our **shipping or return policies**";
} elseif (preg_match('/\b(return|refund|exchange|replace)\b/i', $userMsgLower)) {
    $response['text'] = "💳 **H-Mart Return & Refund Policy:**\n\n" .
                        "We offer a **30-day money-back guarantee** for all items. Products must be unused, in their original packaging, and accompanied by the receipt.\n\n" .
                        "To initiate a return, please contact support at **support@hmart.com**.";
} elseif (preg_match('/\b(shipping|delivery|deliver|shipping fee|delivery fee|cost|charge)\b/i', $userMsgLower)) {
    $response['text'] = "🚚 **H-Mart Shipping & Delivery Information:**\n\n" .
                        "- **Kabankalan City:** ₹50 delivery fee (1-2 business days)\n" .
                        "- **Himamaylan City:** ₹70 delivery fee (1-2 business days)\n" .
                        "- **Free Shipping:** For orders totaling ₹2000 or more.\n\n" .
                        "Orders are packed fresh and dispatched directly to your address.";
} elseif (preg_match('/\b(contact|support|customer service|email|phone|call|help)\b/i', $userMsgLower)) {
    $response['text'] = "📞 **Contact H-Mart Customer Support:**\n\n" .
                        "- 📧 **Email:** support@hmart.com\n" .
                        "- 📱 **Phone:** +91-9876543210 (Mon-Sat, 9AM - 6PM)\n" .
                        "- 💬 **Contact Page:** You can submit questions via our [Contact Form](index.php?q=contact).\n\n" .
                        "We typically reply to email inquiries within 24 hours!";
} elseif (preg_match('/\b(payment|pay|cod|cash|card|credit)\b/i', $userMsgLower)) {
    $response['text'] = "💳 **Payment Methods at H-Mart:**\n\n" .
                        "We support **Cash on Delivery (COD)** for all local deliveries. You can also securely pay online using credit cards, debit cards, or net banking during the checkout process.";
} elseif (preg_match('/\b(hour|hours|time|open|close|location|address)\b/i', $userMsgLower)) {
    $response['text'] = "🏪 **Store Hours & Location:**\n\n" .
                        "- **Hours:** 8:00 AM to 9:00 PM daily.\n" .
                        "- **Location:** H-Mart Supermarket, Main Road, Kabankalan City.\n\n" .
                        "Come visit us to browse our fresh local produce!";
} elseif (preg_match('/\b(thank|thanks|thank you)\b/i', $userMsgLower)) {
    $response['text'] = "You're very welcome! 😊 Let me know if you have any other questions. Happy shopping!";
} else {
    // Default helpful summary response
    $response['text'] = "I'm here to help you get the most out of H-Mart! 🛍️\n\n" .
                        "You can write queries like:\n" .
                        "- `track order 94` to see shipping progress.\n" .
                        "- `search shoes` or `search fresh` to look up items.\n" .
                        "- Ask about 'shipping fees' or 'returns'.\n\n" .
                        "For direct human support, email us at **support@hmart.com**.";
}

echo json_encode($response);
exit;
