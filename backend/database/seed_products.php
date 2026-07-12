<?php
/**
 * Product and Promo Database Seeder.
 * Ported from Python's db_migration.py to PHP.
 */

// Load core initialize configuration to get DB credentials and site configuration
require_once(dirname(__DIR__) . '/include/initialize.php');

// Run only if executing from CLI or if explicitly requested via web
if (php_sapi_name() !== 'cli' && (!isset($_GET['run']) || $_GET['run'] !== '1')) {
    // If run via browser without run=1, show message
    echo "<h3>Product Seeder</h3><p>To run the product seeder, visit this page with <code>?run=1</code> query parameter, or run via CLI.</p>";
    return;
}

// Ensure PHP execution time is unlimited since image generation and insertion of 3360 products may take time
ini_set('max_execution_time', 0);
set_time_limit(0);

// Check if image variant generation should be skipped (e.g. on lightweight/production hosting)
$skip_images = false;
if (php_sapi_name() === 'cli') {
    if (isset($argv)) {
        foreach ($argv as $arg) {
            if (strpos($arg, 'skip_images') !== false) {
                $skip_images = true;
                break;
            }
        }
    }
} else {
    if (isset($_GET['skip_images']) && $_GET['skip_images'] == '1') {
        $skip_images = true;
    }
}

// Connect using config constants
$conn = mysqli_connect(server, user, pass, database_name);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error() . "\n");
}

mysqli_set_charset($conn, 'utf8mb4');

echo "Starting product seeding...\n";

// Target photos directory inside the workspace
$photos_dir = dirname(dirname(__DIR__)) . DS . 'admin' . DS . 'products' . DS . 'uploaded_photos';
if (!file_exists($photos_dir)) {
    mkdir($photos_dir, 0777, true);
}

// Category and Product Data structure mirroring Python's db_migration.py
$categories_data = [
    "SHOES" => [
        "brands" => ["Nike", "Adidas", "Puma", "Reebok", "Jordan", "New Balance"],
        "specs" => [
            "Breathable Mesh Upper, Responsive Foam Midsole",
            "Durable Rubber Outsole, Cushioned Insole, Lightweight Design",
            "Water-Resistant Coating, Enhanced Arch Support, Trail-Ready Grip"
        ],
        "models" => [
            "Nike" => ["Air Max 90", "Pegasus 40", "Air Force 1", "React Miller", "Dunk Low"],
            "Adidas" => ["Ultraboost Light", "Stan Smith", "Superstar", "NMD_R1", "Gazelle"],
            "Puma" => ["Cali Star", "RS-X Efekt", "Suede Classic", "Velocity Nitro 2", "Palermo"],
            "Reebok" => ["Club C 85", "Classic Leather", "Nano X3", "Zig Dynamica", "Floatride Energy"],
            "Jordan" => ["Air Jordan 1 Retro", "Jordan Max Aura 5", "Air Jordan 4", "Jordan Stay Loyal 3"],
            "New Balance" => ["574 Classic", "990v6", "327 Lifestyle", "Fresh Foam 1080", "550 Basketball"]
        ],
        "price_range" => [3000, 15000]
    ],
    "BAGS" => [
        "brands" => ["Herschel", "Jansport", "Samsonite", "Nike", "Adidas", "Puma"],
        "specs" => [
            "Water-Resistant Fabric, Padded Laptop Compartment",
            "RFID-Blocking Security Pocket, Ergonomic Shoulder Straps",
            "Multi-functional Organizer, Premium Metal Zippers, Expandable Design"
        ],
        "models" => [
            "Herschel" => ["Classic Backpack XL", "Little America", "Heritage Backpack", "Nova Mid-Volume", "Chapter Travel Kit"],
            "Jansport" => ["SuperBreak One", "Right Pack Premium", "Big Student", "Cool Student", "Half Pint Mini"],
            "Samsonite" => ["Pro Travel Spinner", "Omni PC Hardside", "Freeform Expandable", "Novaire Carry-On", "Classic Leather Backpack"],
            "Nike" => ["Heritage Drawstring", "Utility Elite Backpack", "Brasilia Training Duffel", "Sportswear Essentials Bag"],
            "Adidas" => ["Classic 3-Stripes", "Power VI Backpack", "Defender Duffel Bag", "Adicolor Sling Bag"],
            "Puma" => ["Challenger Duffel", "Phase Backpack", "Evercat Backpack", "Buzz Backpack"]
        ],
        "price_range" => [1500, 35000]
    ],
    "CLOTHING" => [
        "brands" => ["Zara", "Uniqlo", "H&M", "Levi's", "Nike", "Adidas"],
        "specs" => [
            "100% Premium Cotton, Breathable and Soft Touch",
            "Stretchable Slim Fit, Lightweight Knit fabric, Durable Stitching",
            "Moisture-Wicking Performance, Wrinkle-Free Finish, Relaxed Fit"
        ],
        "models" => [
            "Zara" => ["Oversized Poplin Shirt", "Slim Fit Denim Jeans", "Textured Knit Cardigan", "Linen Blend Blazer", "Faux Leather Jacket"],
            "Uniqlo" => ["AIRism Cotton T-Shirt", "Ultra Light Down Jacket", "Stretch Chino Pants", "Supima Cotton Crew Neck", "Rayon Long Sleeve Blouse"],
            "H&M" => ["Relaxed Fit Hoodie", "Regular Jeans", "Fine-knit Sweater", "Cargo Joggers", "Twill Shirt Jacket"],
            "Levi's" => ["501 Original Fit Jeans", "Trucker Denim Jacket", "Classic Western Shirt", "Graphic Tee", "Chino Shorts"],
            "Nike" => ["Sportswear Club Fleece", "Dri-FIT Training Tee", "Windrunner Jacket", "Essential Track Pants"],
            "Adidas" => ["Originals Trefoil Tee", "Essentials 3-Stripes Hoodie", "Tiro Track Jacket", "SST Track Pants"]
        ],
        "price_range" => [800, 8000]
    ],
    "INTERIORS" => [
        "brands" => ["IKEA", "Ashley Furniture", "Wayfair", "Pottery Barn", "Herman Miller"],
        "specs" => [
            "Solid Oak Wood Construction, Premium Linen Fabric Upholstery",
            "Ergonomic Lumbar Support, Adjustable Heights, Sleek Chrome Accents",
            "Modern Minimalist Styling, Scratch-Resistant Matte Finish"
        ],
        "models" => [
            "IKEA" => ["Strandmon Wing Chair", "Poäng Armchair", "Kallax Shelving Unit", "Billy Bookcase", "Malm Bed Frame"],
            "Ashley Furniture" => ["Chime Express Mattress", "Wystfield Coffee Table", "Bolton Dining Chair", "Hogan Recliner Sofa"],
            "Wayfair" => ["Geoffrey Velvet Sofa", "Alcantara TV Stand", "Barton Desk Chair", "Modern Floor Lamp"],
            "Pottery Barn" => ["Classic Leather Armchair", "Chesterfield Sofa", "Farmhouse Dining Table", "PB Comfort Sectional"],
            "Herman Miller" => ["Aeron Ergonomic Chair", "Embody Gaming Chair", "Sayl Task Chair", "Eames Lounge Chair & Ottoman"]
        ],
        "price_range" => [4000, 95000]
    ],
    "HOUSEHOLDS" => [
        "brands" => ["Dyson", "Philips", "Tefal", "Xiaomi", "Panasonic", "Rowenta"],
        "specs" => [
            "High-Efficiency HEPA Filtration, Smart App Remote Control",
            "Stainless Steel Construction, Eco-Friendly Energy Star Certified",
            "Ultra-Quiet Motor, Touch-Control Interface, Advanced Safety Shut-off"
        ],
        "models" => [
            "Dyson" => ["V15 Detect Cordless Vacuum", "Purifier Hot+Cool HP07", "Supersonic Hair Dryer", "V8 Animal Vacuum"],
            "Philips" => ["Air Fryer XXL", "Series 2200 Espresso Machine", "Smart Humidifier Series 2000", "Sonicare ProtectiveClean"],
            "Tefal" => ["Easy Fry Precision Air Fryer", "Pro Express Ultimate Steam Iron", "OptiGrill+ Smart Grill", "Ingenio Cookware Set"],
            "Xiaomi" => ["Smart Air Purifier 4 Pro", "Mi Robot Vacuum-Mop 2 Ultra", "Smart Humidifier 2", "Mi Electric Kettle Pro"],
            "Panasonic" => ["Inverter Microwave Oven", "FlashXpress Toaster Oven", "Nanoe Hair Dryer", "Multi-Cooker Rice Cooker"],
            "Rowenta" => ["Turbo Silence Table Fan", "Focus Excel Steam Iron", "Pure Air Purifier", "X-Pert Cordless Vacuum"]
        ],
        "price_range" => [1500, 45000]
    ],
    "FASHION" => [
        "brands" => ["Gucci", "Prada", "Ray-Ban", "Calvin Klein", "Fossil", "Tommy Hilfiger"],
        "specs" => [
            "Genuine Italian Leather, UV400 Protection Lenses",
            "Quartz Movement, Brushed Gold Stainless Steel Case",
            "Scratch-Resistant Sapphire Glass, Embossed Designer Branding"
        ],
        "models" => [
            "Gucci" => ["GG Marmont Leather Belt", "Double G Buckle Belt", "Oversized Square Sunglasses", "GG Jacquard Scarf"],
            "Prada" => ["Saffiano Leather Wallet", "Symbole Sunglasses", "Nylon Cosmetic Pouch", "Leather Card Holder"],
            "Ray-Ban" => ["Classic Aviator Sunglasses", "Wayfarer Classic", "Clubmaster Metal", "Round Metal Framework"],
            "Calvin Klein" => ["Minimalist Leather Watch", "Classic Logo Leather Belt", "Bifold Leather Wallet", "Fashion Sunglasses"],
            "Fossil" => ["Heritage Leather Watch", "Grant Chronograph Watch", "Logan Leather Zip Clutch", "Machine Chronograph"],
            "Tommy Hilfiger" => ["Casual Leather Belt", "Sport Chronograph Watch", "Classic Leather Wallet", "Rib-Knit Scarf"]
        ],
        "price_range" => [2000, 35000]
    ],
    "KIDS" => [
        "brands" => ["Lego", "Disney", "Fisher-Price", "Carter's", "Hasbro"],
        "specs" => [
            "Non-Toxic Eco-Friendly Materials, Certified Child Safe",
            "Soft 100% Organic Cotton, Promotes Creativity and Logic",
            "Interactive Sound and Light, Easy-to-Wash Fabric, Vibrant Colors"
        ],
        "models" => [
            "Lego" => ["Classic Creative Brick Box", "City Police Station Set", "Star Wars Millennium Falcon", "Friends Heartlake Downtown", "Duplo Town Train Set"],
            "Disney" => ["Mickey Mouse Plush Toy", "Princess Elsa Dress-up Costume", "Toy Story Woody Action Figure", "Lion King Storybook Collection"],
            "Fisher-Price" => ["Rock-a-Stack Classic Toy", "Baby's First Blocks Set", "Laugh & Learn Smart Stages Puppy", "Kick & Play Piano Gym"],
            "Carter's" => ["5-Pack Cotton Bodysuits", "2-Piece Fleece Pajama Set", "Stretch Denim Pants", "Hooded Towel and Washcloths"],
            "Hasbro" => ["Play-Doh Mega Pack", "Monopoly Junior Board Game", "Nerf Elite 2.0 Commander Blaster", "My Little Pony Styling Head"]
        ],
        "price_range" => [500, 9000]
    ],
    "WOMENS" => [
        "brands" => ["Chanel", "Zara", "H&M", "Uniqlo", "Michael Kors", "Coach"],
        "specs" => [
            "Premium Soft Silk Touch, Elegant Floral Print",
            "Adjustable Shoulder Straps, Relaxed Comfortable Fit",
            "High-Quality Knit Fabric, Designer Gold Hardware Accents"
        ],
        "models" => [
            "Chanel" => ["No.5 Eau de Parfum Spray", "Coco Mademoiselle Intense", "Rouge Allure Lipstick", "Hydra Beauty Cream"],
            "Zara" => ["Floral Print Wrap Dress", "Pleated Midi Skirt", "Oversized Blazer", "Cable-knit Sweater"],
            "H&M" => ["Linen Blend Dress", "Wide-leg High Waist Trousers", "Satin V-neck Blouse", "Denim Jacket"],
            "Uniqlo" => ["Rayon Long Sleeve Blouse", "Pleated Pants", "UV Protection Cardigan", "Cotton Linen Shirt"],
            "Michael Kors" => ["Jet Set Travel Large Tote", "Mercer Gallery Satchel", "Bradshaw Gold Watch", "Bifold Wallet"],
            "Coach" => ["Signature Canvas Shoulder Bag", "Polished Pebble Leather Tote", "Cassie Crossbody Bag", "Skinny Wallet"]
        ],
        "price_range" => [1200, 45000]
    ],
    "MENS" => [
        "brands" => ["Levi's", "Uniqlo", "Tommy Hilfiger", "Ralph Lauren", "Hugo Boss", "Zara"],
        "specs" => [
            "Breathable Premium Cotton, Wrinkle-Resistant Finish",
            "Classic Tailored Fit, Soft Knit texture, Modern Style Detailing",
            "Flexible Stretch Fabric, Contrast Collar Design, Reinforced Seams"
        ],
        "models" => [
            "Levi's" => ["501 Original Fit Jeans", "511 Slim Fit Jeans", "Classic Denim Jacket", "Short Sleeve Graphic Tee"],
            "Uniqlo" => ["Supima Cotton Polo Shirt", "Ultra Stretch Skinny Jeans", "Dry-EX Training Tee", "Flannel Checked Shirt"],
            "Tommy Hilfiger" => ["Classic Fit Polo Shirt", "V-Neck Cotton Sweater", "Leather Billfold Wallet", "Chino Shorts"],
            "Ralph Lauren" => ["Custom Slim Fit Mesh Polo", "Oxford Button-Down Shirt", "Cable-Knit Cotton Sweater", "Cotton Mesh Tee"],
            "Hugo Boss" => ["Tailored Slim Fit Dress Shirt", "Mercedes-Benz Polo Shirt", "Leather Wallet Gift Set", "Classic Sport Watch"],
            "Zara" => ["Basic Slim Fit Suit Blazer", "Textured Knit Polo Shirt", "Faux Suede Bomber Jacket", "Stretch Chino Trousers"]
        ],
        "price_range" => [1500, 30000]
    ],
    "SPORTSWEAR" => [
        "brands" => ["Under Armour", "Nike", "Adidas", "Puma", "Lululemon"],
        "specs" => [
            "Advanced Moisture-Wicking Fabric, 4-Way Stretch Compression",
            "Lightweight and Highly Breathable, Odor-Resistant Technology",
            "Anti-Chafing Flatlock Seams, Quick-Dry Treatment, Reflective Details"
        ],
        "models" => [
            "Under Armour" => ["Tech 2.0 Short-Sleeve Tee", "Woven Training Shorts", "HeatGear Baselayer Leggings", "Storm Armour Fleece Hoodie"],
            "Nike" => ["Dri-FIT Legend Training Tee", "Challenger Running Shorts", "Pro Dri-FIT Compression Top", "Therma-FIT Training Pants"],
            "Adidas" => ["Tiro 23 League Pants", "Own the Run Short Tee", "Designed for Training Shorts", "Techfit Tight Leggings"],
            "Puma" => ["Run Favorite Velocity Tee", "Train Favorite Knit Shorts", "Eversculpt High Waist Tights", "Active Woven Track Pants"],
            "Lululemon" => ["Metal Vent Tech Shirt 2.0", "Pace Breaker Lined Shorts", "License to Train Pants", "Surge Jogger Premium"]
        ],
        "price_range" => [1000, 9000]
    ],
    "MOBILE" => [
        "brands" => ["Apple", "Samsung", "OnePlus", "Google", "Xiaomi"],
        "specs" => [
            "128GB Storage, 8GB RAM, 5G Enabled",
            "256GB Storage, 12GB RAM, Super AMOLED Display",
            "512GB Storage, 16GB RAM, Pro Camera System, Premium Finish"
        ],
        "models" => [
            "Apple" => ["iPhone 15", "iPhone 15 Pro", "iPhone 15 Pro Max", "iPhone 14 Pro", "iPhone 13"],
            "Samsung" => ["Galaxy S24 Ultra", "Galaxy S24+", "Galaxy S23 FE", "Galaxy Z Fold 5", "Galaxy Z Flip 5"],
            "OnePlus" => ["12 Pro 5G", "12R Premium", "11 5G Mobile", "Nord CE 3 Lite"],
            "Google" => ["Pixel 8 Pro", "Pixel 8", "Pixel 7a", "Pixel Fold 5G"],
            "Xiaomi" => ["14 Ultra Premium", "Redmi Note 13 Pro+", "Poco F6 Pro", "13T Pro"]
        ],
        "price_range" => [15000, 130000]
    ],
    "ELECTRONICS" => [
        "brands" => ["Sony", "Samsung", "LG", "Panasonic", "Philips"],
        "specs" => [
            "4K Ultra HD, Smart TV, Dolby Vision Audio",
            "Full HD, High Quality Panel, DTS Cinema Sound",
            "Energy Efficient, Voice Remote, HDR10+ Display Technology"
        ],
        "models" => [
            "Sony" => ["Bravia XR OLED", "X90L 4K Full Array", "X80K Smart TV", "A80K Series OLED"],
            "Samsung" => ["Neo QLED 4K Smart", "The Frame QLED TV", "Crystal UHD 4K", "OLED S90C Series"],
            "LG" => ["OLED evo C3 Series", "QNED85 4K Smart", "UHD UT80 Smart TV", "Nanocell NANO75"],
            "Panasonic" => ["Viera Premium 4K LED", "OLED Smart Cinema Display", "4K HDR Smart TV"],
            "Philips" => ["Ambilight 4K UHD TV", "PML9507 MiniLED TV", "OLED 807 Series TV"]
        ],
        "price_range" => [20000, 170000]
    ],
    "LAPTOPS" => [
        "brands" => ["Dell", "HP", "Lenovo", "Apple", "Asus", "Acer"],
        "specs" => [
            "Intel Core i7, 16GB RAM, 512GB SSD Storage",
            "Intel Core i5, 8GB RAM, 256GB SSD Storage",
            "AMD Ryzen 7, 16GB RAM, 1TB NVMe Fast SSD",
            "Apple M3 Chip, 16GB Unified RAM, 512GB SSD"
        ],
        "models" => [
            "Dell" => ["XPS 13 9315", "XPS 15 9530", "Inspiron 16 Plus", "G15 Gaming Laptop"],
            "HP" => ["Spectre x360 Convertible", "Envy 16 Premium Laptop", "Pavilion 15 Laptop", "Omen 16 Gaming"],
            "Lenovo" => ["ThinkPad X1 Carbon Gen 11", "Yoga Book 9i Dual Screen", "IdeaPad Slim 5 Gen 8", "Legion Pro 5i Gaming"],
            "Apple" => ["MacBook Air 13 M3", "MacBook Air 15 M3", "MacBook Pro 14 M3 Pro", "MacBook Pro 16 M3 Max"],
            "Asus" => ["Zenbook 14 OLED", "Vivobook Pro 15 OLED", "ROG Zephyrus G14 Gaming", "TUF Gaming A15"],
            "Acer" => ["Swift Go 14 OLED", "Aspire 5 Slim Laptop", "Predator Helios 16 Gaming", "Nitro V 15 Gaming"]
        ],
        "price_range" => [30000, 200000]
    ],
    "AUDIO" => [
        "brands" => ["Sony", "Bose", "JBL", "Sennheiser", "Marshall", "Apple"],
        "specs" => [
            "Active Noise Cancelling (ANC), Bluetooth 5.3",
            "Hi-Res Audio, Wireless Charging Case, Extended Battery Life",
            "IPX7 Waterproof, Portable Dual Drivers, Deep Bass"
        ],
        "models" => [
            "Sony" => ["WH-1000XM5 ANC Headphone", "WF-1000XM5 Wireless Earbuds", "LinkBuds S ANC Earbuds", "SRS-XE300 Portable Speaker"],
            "Bose" => ["QuietComfort Ultra Headphones", "QuietComfort II Earbuds", "SoundLink Flex Bluetooth Speaker"],
            "JBL" => ["Flip 6 Portable Speaker", "Charge 5 Bluetooth Speaker", "Tune 770NC ANC Headphone", "Live Pro 2 TWS Earbuds"],
            "Sennheiser" => ["Momentum 4 Wireless Headphone", "Accentum Wireless Headphone", "IE 200 High-Fidelity In-Ear"],
            "Marshall" => ["Major IV Wireless On-Ear", "Emberton II Portable Speaker", "Motif II A.N.C. Earbuds"],
            "Apple" => ["AirPods Pro 2 USB-C", "AirPods Max Over-Ear", "AirPods 3rd Gen Wireless"]
        ],
        "price_range" => [3000, 40000]
    ],
    "CAMERAS" => [
        "brands" => ["Canon", "Nikon", "Sony", "Fujifilm", "Panasonic", "GoPro"],
        "specs" => [
            "24.2 MP, 4K Professional Video, Dual Pixel Autofocus",
            "33.0 MP, 8K Ultra-HD Video, In-Body Image Stabilization",
            "26.1 MP, Weather Sealed Retro Body, Premium Styling"
        ],
        "models" => [
            "Canon" => ["EOS R6 Mark II Mirrorless", "EOS R50 Vlogging Camera", "EOS R8 Full Frame Mirrorless", "PowerShot G7 X Mark III"],
            "Nikon" => ["Z6 II Mirrorless Camera", "Z50 Creator Kit DSLR", "Zf Retro Full-Frame Mirrorless", "D850 Professional DSLR"],
            "Sony" => ["Alpha a7 IV Mirrorless", "Alpha a6700 APS-C Mirrorless", "ZV-E10 Vlogger Camera", "Cyber-shot RX100 VII Premium"],
            "Fujifilm" => ["X-T5 Mirrorless Retro", "X-S20 Content Creator Camera", "INSTAX Mini Evo Hybrid", "X100VI Premium Compact"],
            "Panasonic" => ["Lumix GH6 Hybrid Video", "Lumix S5 II Full-Frame", "Lumix G100 Vlogger Camera"],
            "GoPro" => ["HERO12 Black Action Camera", "HERO11 Black Mini Camera", "MAX 360 Action Video Camera"]
        ],
        "price_range" => [15000, 250000]
    ],
    "GROCERY" => [
        "brands" => ["Amul", "Britannia", "Mother Dairy", "Lay's", "Nescafe", "Organic India", "Cadbury"],
        "specs" => [
            "100% Organic & Fresh, Delivered in 10 minutes",
            "No Preservatives Added, Freshly Sourced, Express Delivery",
            "Premium Quality Sourced Globally, Pure & Healthy"
        ],
        "models" => [
            "Amul" => ["Pure Fresh Milk 1L", "Salted Butter 500g", "Pasteurized Cheese Slices", "Fresh Cream 250ml"],
            "Britannia" => ["Sliced White Bread", "Good Day Cashew Cookies", "Bourbon Chocolate Biscuits", "Whole Wheat Bread"],
            "Mother Dairy" => ["Toned Milk 1L", "Probiotic Curd 400g", "Fresh Paneer 200g"],
            "Lay's" => ["Classic Salted Chips", "American Style Cream & Onion", "Spanish Tomato Tang Chips"],
            "Nescafe" => ["Classic Instant Coffee Jar", "Gold Blend Coffee Premium", "Sunrise Premium Coffee"],
            "Organic India" => ["Classic Green Tea", "Tulsi Ginger Tea Box", "Organic Honey Premium"],
            "Cadbury" => ["Dairy Milk Silk Chocolate", "Oreo Chocolate Cookies", "Celebrations Assorted Box"]
        ],
        "price_range" => [30, 800]
    ]
];

$colors = ["Space Gray", "Silver", "Charcoal Black", "Polar White", "Midnight Blue", "Forest Green", "Champagne Gold"];
$editions = ["Standard Edition", "Premium Bundle", "Pro Edition", "Creator Pack", "Special Edition"];

/**
 * Image variant generator using PHP GD library.
 */
function generate_image_variant($cat_name, $base_idx, $product_idx, $target_path, $photos_dir) {
    if (file_exists($target_path) && @filesize($target_path) > 0) {
        return true;
    }
    $base_file = strtolower($cat_name) . "_mock_" . $base_idx . ".jpg";
    $base_path = $photos_dir . DS . $base_file;
    
    if (!file_exists($base_path)) {
        $fallbacks = [
            'C:\\xampp\\htdocs\\uploaded_photos\\' . $base_file,
            dirname(dirname(dirname(dirname(__DIR__)))) . DS . 'uploaded_photos' . DS . $base_file,
            dirname(dirname(dirname(__DIR__))) . DS . 'uploaded_photos' . DS . $base_file
        ];
        foreach ($fallbacks as $fb) {
            if (file_exists($fb)) {
                $base_path = $fb;
                break;
            }
        }
    }
    
    // If specific base index mock file is not found, fallback to [category]_mock_1.jpg
    if (!file_exists($base_path)) {
        $base_path = $photos_dir . DS . strtolower($cat_name) . "_mock_1.jpg";
    }
    
    // If [category]_mock_1.jpg is also missing, download it automatically from Unsplash
    if (!file_exists($base_path)) {
        $urls = [
            "SHOES" => "https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=350&h=350&fit=crop",
            "BAGS" => "https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=350&h=350&fit=crop",
            "CLOTHING" => "https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=350&h=350&fit=crop",
            "INTERIORS" => "https://images.unsplash.com/photo-1524758631624-e2822e304c36?w=350&h=350&fit=crop",
            "HOUSEHOLDS" => "https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?w=350&h=350&fit=crop",
            "FASHION" => "https://images.unsplash.com/photo-1483985988355-763728e1935b?w=350&h=350&fit=crop",
            "KIDS" => "https://images.unsplash.com/photo-1515488042361-404e9250afef?w=350&h=350&fit=crop",
            "WOMENS" => "https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91?w=350&h=350&fit=crop",
            "MENS" => "https://images.unsplash.com/photo-1505022610485-0249ba5b3675?w=350&h=350&fit=crop",
            "SPORTSWEAR" => "https://images.unsplash.com/photo-1517838277536-f5f99be501cd?w=350&h=350&fit=crop",
            "MOBILE" => "https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=350&h=350&fit=crop",
            "ELECTRONICS" => "https://images.unsplash.com/photo-1498049794561-7780e7231661?w=350&h=350&fit=crop",
            "LAPTOPS" => "https://images.unsplash.com/photo-1496181130204-755241524eab?w=350&h=350&fit=crop",
            "AUDIO" => "https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=350&h=350&fit=crop",
            "CAMERAS" => "https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=350&h=350&fit=crop",
            "GROCERY" => "https://images.unsplash.com/photo-1542838132-92c53300491e?w=350&h=350&fit=crop"
        ];
        $cat_key = strtoupper($cat_name);
        $download_url = isset($urls[$cat_key]) ? $urls[$cat_key] : "https://images.unsplash.com/photo-1542838132-92c53300491e?w=350&h=350&fit=crop";
        
        $ch = curl_init($download_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        $img_data = curl_exec($ch);
        curl_close($ch);
        
        if ($img_data) {
            @file_put_contents($base_path, $img_data);
        }
    }
    
    if (!file_exists($base_path)) {
        return false;
    }
    
    if (!extension_loaded('gd')) {
        return @copy($base_path, $target_path);
    }
    
    try {
        $img = @imagecreatefromjpeg($base_path);
        if (!$img) {
            return @copy($base_path, $target_path);
        }
        
        $src_w = imagesx($img);
        $src_h = imagesy($img);
        
        $dst = imagecreatetruecolor(350, 350);
        imagecopyresampled($dst, $img, 0, 0, 0, 0, 350, 350, $src_w, $src_h);
        imagedestroy($img);
        
        // Use product_idx as seed for deterministic random values.
        // Every product gets a continuous unique brightness + contrast,
        // making duplicates impossible even with same base image.
        mt_srand($product_idx);
        $b_factor = 0.88 + (mt_rand() / mt_getrandmax()) * 0.24; // 0.88 – 1.12
        $c_factor = 0.88 + (mt_rand() / mt_getrandmax()) * 0.30; // 0.88 – 1.18
        
        // Brightness filter (-255 to 255)
        $b_val = (int)(($b_factor - 1.0) * 150);
        if ($b_val != 0) {
            imagefilter($dst, IMG_FILTER_BRIGHTNESS, $b_val);
        }
        
        // Contrast filter (-100 to 100). In GD negative means more contrast.
        $c_val = (int)(($c_factor - 1.0) * -100);
        if ($c_val != 0) {
            imagefilter($dst, IMG_FILTER_CONTRAST, $c_val);
        }
        
        $quality = 82 + (mt_rand() % 15); // 82–96 range
        $success = imagejpeg($dst, $target_path, $quality);
        imagedestroy($dst);
        return $success;
    } catch (\Exception $e) {
        return @copy($base_path, $target_path);
    }
}

// Download custom cover images if missing from target directory
$custom_images = [
    'custom_watch.png' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=350&h=350&fit=crop',
    'custom_shoe.png' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=350&h=350&fit=crop',
    'custom_bag.png' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=350&h=350&fit=crop'
];
foreach ($custom_images as $filename => $url) {
    $target = $photos_dir . DS . $filename;
    if (!file_exists($target)) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        $data = curl_exec($ch);
        curl_close($ch);
        if ($data) {
            @file_put_contents($target, $data);
        }
    }
}

// 1. Get or Create Categories
$category_ids = [];
foreach (array_keys($categories_data) as $cat_name) {
    $cat_name_escaped = mysqli_real_escape_string($conn, $cat_name);
    $res = mysqli_query($conn, "SELECT CATEGID FROM tblcategory WHERE CATEGORIES = '{$cat_name_escaped}'");
    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $category_ids[$cat_name] = (int)$row['CATEGID'];
        echo "Category '{$cat_name}' exists (ID: {$row['CATEGID']}).\n";
    } else {
        mysqli_query($conn, "INSERT INTO tblcategory (CATEGORIES, USERID) VALUES ('{$cat_name_escaped}', 0)");
        $new_id = (int)mysqli_insert_id($conn);
        $category_ids[$cat_name] = $new_id;
        echo "Inserted Category '{$cat_name}' (ID: {$new_id}).\n";
    }
}

// 2. Clean up previously migrated products to allow clean re-runs
$res = mysqli_query($conn, "SELECT MAX(PROID) as max_id FROM tblproduct");
$row = mysqli_fetch_assoc($res);
$start_proid = max(300001, ((int)$row['max_id']) + 1);

// We delete products and promos >= 300001
$res_count = mysqli_query($conn, "SELECT COUNT(*) as count FROM tblproduct WHERE PROID >= 300001");
$existing_count = mysqli_fetch_assoc($res_count)['count'];
if ($existing_count > 0) {
    echo "Removing {$existing_count} previously migrated products and promos...\n";
    if (!mysqli_query($conn, "DELETE FROM tblpromopro WHERE PROID >= 300001")) {
        echo "DELETE FROM tblpromopro failed: " . mysqli_error($conn) . "\n";
    }
    if (!mysqli_query($conn, "DELETE FROM tblproduct WHERE PROID >= 300001")) {
        echo "DELETE FROM tblproduct failed: " . mysqli_error($conn) . "\n";
    }
    mysqli_query($conn, "COMMIT");
}

// Close database connection to prevent idle timeout during long image generation loop
mysqli_close($conn);

// 3. Generate Products
$current_proid = 300001;
$products_per_category = 210;

$products_to_insert = [];
$promo_to_insert = [];
$generated_names = [];

echo "Generating product data...\n";

foreach ($categories_data as $cat_name => $cat_info) {
    $cat_id = $category_ids[$cat_name];
    echo "Processing category '{$cat_name}'...\n";
    
    for ($i = 0; $i < $products_per_category; $i++) {
        $brand = $cat_info["brands"][array_rand($cat_info["brands"])];
        $model = $cat_info["models"][$brand][array_rand($cat_info["models"][$brand])];
        $spec = $cat_info["specs"][array_rand($cat_info["specs"])];
        $color = $colors[array_rand($colors)];
        $edition = $editions[array_rand($editions)];
        
        $prod_name = "{$brand} {$model} ({$edition} - {$color})";
        
        // Ensure name uniqueness
        $suffix = 1;
        $original_name = $prod_name;
        while (in_array($prod_name, $generated_names)) {
            $prod_name = "{$original_name} V{$suffix}";
            $suffix++;
        }
        $generated_names[] = $prod_name;
        
        // Generate Price
        list($min_p, $max_p) = $cat_info["price_range"];
        $original_price = rand($min_p, $max_p);
        if ($original_price > 10000) {
            $original_price = ((int)($original_price / 1000)) * 1000 - 1;
        } else {
            $original_price = ((int)($original_price / 100)) * 100 - 1;
        }
        
        // Calculate Discount and Promo Price
        $discounts = [0, 5, 10, 15, 20, 25];
        $discount_pct = $discounts[array_rand($discounts)];
        $promo_price = $original_price;
        if ($discount_pct > 0) {
            $promo_price = (int)($original_price * (1 - $discount_pct / 100.0));
        }
        
        $qty = rand(5, 45);
        
        $num_bases = ($cat_name === "GROCERY") ? 5 : 10;
        $base_idx = ($i % $num_bases) + 1;
        
        if ($skip_images) {
            // Guarantee that the base Category mock image is downloaded and exists
            $base_file = strtolower($cat_name) . "_mock_1.jpg";
            $base_path = $photos_dir . DS . $base_file;
            if (!file_exists($base_path)) {
                generate_image_variant($cat_name, 1, 0, $base_path, $photos_dir);
            }
            $db_image_path = "uploaded_photos/" . strtolower($cat_name) . "_mock_1.jpg";
        } else {
            // Generate unique image filename and physical file
            $image_filename = strtolower($cat_name) . "_p" . sprintf("%03d", $i + 1) . ".jpg";
            $target_path = $photos_dir . DS . $image_filename;
            
            $success = generate_image_variant($cat_name, $base_idx, $i, $target_path, $photos_dir);
            
            $db_image_path = $success ? "uploaded_photos/{$image_filename}" : "uploaded_photos/" . strtolower($cat_name) . "_mock_{$base_idx}.jpg";
        }
        
        // Collect product and promo data
        $products_to_insert[] = [
            'PROID' => $current_proid,
            'PRODESC' => $prod_name,
            'INGREDIENTS' => $spec,
            'PROQTY' => $qty,
            'ORIGINALPRICE' => $original_price,
            'PROPRICE' => $promo_price,
            'CATEGID' => $cat_id,
            'IMAGES' => $db_image_path,
            'PROSTATS' => 'Available',
            'OWNERNAME' => 'janobe',
            'OWNERPHONE' => ''
        ];
        
        $promo_to_insert[] = [
            'PROID' => $current_proid,
            'PRODISCOUNT' => $discount_pct,
            'PRODISPRICE' => $promo_price,
            'PROBANNER' => 0,
            'PRONEW' => 1
        ];
        
        $current_proid++;
    }
}

// Re-open database connection for batch insertions to avoid "MySQL server has gone away" error
$conn = mysqli_connect(server, user, pass, database_name);
if (!$conn) {
    die("Database reconnection failed: " . mysqli_connect_error() . "\n");
}
mysqli_set_charset($conn, 'utf8mb4');

// 4. Batch Insert Products in chunks of 100
echo "Inserting products into database...\n";
$chunk_size = 100;
$total_products = count($products_to_insert);
for ($c = 0; $c < $total_products; $c += $chunk_size) {
    $chunk = array_slice($products_to_insert, $c, $chunk_size);
    $values = [];
    foreach ($chunk as $p) {
        $proid = (int)$p['PROID'];
        $desc = mysqli_real_escape_string($conn, $p['PRODESC']);
        $ing = mysqli_real_escape_string($conn, $p['INGREDIENTS']);
        $qty = (int)$p['PROQTY'];
        $orig = (float)$p['ORIGINALPRICE'];
        $price = (float)$p['PROPRICE'];
        $cat = (int)$p['CATEGID'];
        $img = mysqli_real_escape_string($conn, $p['IMAGES']);
        $stats = mysqli_real_escape_string($conn, $p['PROSTATS']);
        $owner = mysqli_real_escape_string($conn, $p['OWNERNAME']);
        $phone = mysqli_real_escape_string($conn, $p['OWNERPHONE']);
        
        $values[] = "({$proid}, '{$desc}', '{$ing}', {$qty}, {$orig}, {$price}, {$cat}, '{$img}', '{$stats}', '{$owner}', '{$phone}')";
    }
    
    $sql = "INSERT INTO tblproduct (PROID, PRODESC, INGREDIENTS, PROQTY, ORIGINALPRICE, PROPRICE, CATEGID, IMAGES, PROSTATS, OWNERNAME, OWNERPHONE) VALUES " . implode(', ', $values);
    if (!mysqli_query($conn, $sql)) {
        die("Error inserting products: " . mysqli_error($conn) . "\n");
    }
}

// 5. Batch Insert Promos in chunks of 100
echo "Inserting promos into database...\n";
$total_promos = count($promo_to_insert);
for ($c = 0; $c < $total_promos; $c += $chunk_size) {
    $chunk = array_slice($promo_to_insert, $c, $chunk_size);
    $values = [];
    foreach ($chunk as $pr) {
        $proid = (int)$pr['PROID'];
        $discount = (int)$pr['PRODISCOUNT'];
        $disprice = (float)$pr['PRODISPRICE'];
        $banner = (int)$pr['PROBANNER'];
        $new = (int)$pr['PRONEW'];
        
        $values[] = "({$proid}, {$discount}, {$disprice}, {$banner}, {$new})";
    }
    
    $sql = "INSERT INTO tblpromopro (PROID, PRODISCOUNT, PRODISPRICE, PROBANNER, PRONEW) VALUES " . implode(', ', $values);
    if (!mysqli_query($conn, $sql)) {
        die("Error inserting promos: " . mysqli_error($conn) . "\n");
    }
}

mysqli_query($conn, "COMMIT");
mysqli_close($conn);
echo "Product and Promo seeding completed successfully! Inserted {$total_products} products.\n";
