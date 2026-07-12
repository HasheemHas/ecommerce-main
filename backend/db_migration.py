import sys
import os
import random
import colorsys
import pymysql
from PIL import Image, ImageEnhance

# Database connection credentials
DB_HOST = "localhost"
DB_USER = "root"
DB_PASS = ""
DB_NAME = "db_ecommerce"

PHOTOS_DIR = r"c:\xampp\htdocs\ecommerce\admin\products\uploaded_photos"

# Categories data structure for generator
CATEGORIES_DATA = {
    "SHOES": {
        "brands": ["Nike", "Adidas", "Puma", "Reebok", "Jordan", "New Balance"],
        "specs": [
            "Breathable Mesh Upper, Responsive Foam Midsole",
            "Durable Rubber Outsole, Cushioned Insole, Lightweight Design",
            "Water-Resistant Coating, Enhanced Arch Support, Trail-Ready Grip"
        ],
        "models": {
            "Nike": ["Air Max 90", "Pegasus 40", "Air Force 1", "React Miller", "Dunk Low"],
            "Adidas": ["Ultraboost Light", "Stan Smith", "Superstar", "NMD_R1", "Gazelle"],
            "Puma": ["Cali Star", "RS-X Efekt", "Suede Classic", "Velocity Nitro 2", "Palermo"],
            "Reebok": ["Club C 85", "Classic Leather", "Nano X3", "Zig Dynamica", "Floatride Energy"],
            "Jordan": ["Air Jordan 1 Retro", "Jordan Max Aura 5", "Air Jordan 4", "Jordan Stay Loyal 3"],
            "New Balance": ["574 Classic", "990v6", "327 Lifestyle", "Fresh Foam 1080", "550 Basketball"]
        },
        "price_range": (3000, 15000)
    },
    "BAGS": {
        "brands": ["Herschel", "Jansport", "Samsonite", "Nike", "Adidas", "Puma"],
        "specs": [
            "Water-Resistant Fabric, Padded Laptop Compartment",
            "RFID-Blocking Security Pocket, Ergonomic Shoulder Straps",
            "Multi-functional Organizer, Premium Metal Zippers, Expandable Design"
        ],
        "models": {
            "Herschel": ["Classic Backpack XL", "Little America", "Heritage Backpack", "Nova Mid-Volume", "Chapter Travel Kit"],
            "Jansport": ["SuperBreak One", "Right Pack Premium", "Big Student", "Cool Student", "Half Pint Mini"],
            "Samsonite": ["Pro Travel Spinner", "Omni PC Hardside", "Freeform Expandable", "Novaire Carry-On", "Classic Leather Backpack"],
            "Nike": ["Heritage Drawstring", "Utility Elite Backpack", "Brasilia Training Duffel", "Sportswear Essentials Bag"],
            "Adidas": ["Classic 3-Stripes", "Power VI Backpack", "Defender Duffel Bag", "Adicolor Sling Bag"],
            "Puma": ["Challenger Duffel", "Phase Backpack", "Evercat Backpack", "Buzz Backpack"]
        },
        "price_range": (1500, 35000)
    },
    "CLOTHING": {
        "brands": ["Zara", "Uniqlo", "H&M", "Levi's", "Nike", "Adidas"],
        "specs": [
            "100% Premium Cotton, Breathable and Soft Touch",
            "Stretchable Slim Fit, Lightweight Knit fabric, Durable Stitching",
            "Moisture-Wicking Performance, Wrinkle-Free Finish, Relaxed Fit"
        ],
        "models": {
            "Zara": ["Oversized Poplin Shirt", "Slim Fit Denim Jeans", "Textured Knit Cardigan", "Linen Blend Blazer", "Faux Leather Jacket"],
            "Uniqlo": ["AIRism Cotton T-Shirt", "Ultra Light Down Jacket", "Stretch Chino Pants", "Supima Cotton Crew Neck", "Rayon Long Sleeve Blouse"],
            "H&M": ["Relaxed Fit Hoodie", "Regular Jeans", "Fine-knit Sweater", "Cargo Joggers", "Twill Shirt Jacket"],
            "Levi's": ["501 Original Fit Jeans", "Trucker Denim Jacket", "Classic Western Shirt", "Graphic Tee", "Chino Shorts"],
            "Nike": ["Sportswear Club Fleece", "Dri-FIT Training Tee", "Windrunner Jacket", "Essential Track Pants"],
            "Adidas": ["Originals Trefoil Tee", "Essentials 3-Stripes Hoodie", "Tiro Track Jacket", "SST Track Pants"]
        },
        "price_range": (800, 8000)
    },
    "INTERIORS": {
        "brands": ["IKEA", "Ashley Furniture", "Wayfair", "Pottery Barn", "Herman Miller"],
        "specs": [
            "Solid Oak Wood Construction, Premium Linen Fabric Upholstery",
            "Ergonomic Lumbar Support, Adjustable Heights, Sleek Chrome Accents",
            "Modern Minimalist Styling, Scratch-Resistant Matte Finish"
        ],
        "models": {
            "IKEA": ["Strandmon Wing Chair", "Poäng Armchair", "Kallax Shelving Unit", "Billy Bookcase", "Malm Bed Frame"],
            "Ashley Furniture": ["Chime Express Mattress", "Wystfield Coffee Table", "Bolton Dining Chair", "Hogan Recliner Sofa"],
            "Wayfair": ["Geoffrey Velvet Sofa", "Alcantara TV Stand", "Barton Desk Chair", "Modern Floor Lamp"],
            "Pottery Barn": ["Classic Leather Armchair", "Chesterfield Sofa", "Farmhouse Dining Table", "PB Comfort Sectional"],
            "Herman Miller": ["Aeron Ergonomic Chair", "Embody Gaming Chair", "Sayl Task Chair", "Eames Lounge Chair & Ottoman"]
        },
        "price_range": (4000, 95000)
    },
    "HOUSEHOLDS": {
        "brands": ["Dyson", "Philips", "Tefal", "Xiaomi", "Panasonic", "Rowenta"],
        "specs": [
            "High-Efficiency HEPA Filtration, Smart App Remote Control",
            "Stainless Steel Construction, Eco-Friendly Energy Star Certified",
            "Ultra-Quiet Motor, Touch-Control Interface, Advanced Safety Shut-off"
        ],
        "models": {
            "Dyson": ["V15 Detect Cordless Vacuum", "Purifier Hot+Cool HP07", "Supersonic Hair Dryer", "V8 Animal Vacuum"],
            "Philips": ["Air Fryer XXL", "Series 2200 Espresso Machine", "Smart Humidifier Series 2000", "Sonicare ProtectiveClean"],
            "Tefal": ["Easy Fry Precision Air Fryer", "Pro Express Ultimate Steam Iron", "OptiGrill+ Smart Grill", "Ingenio Cookware Set"],
            "Xiaomi": ["Smart Air Purifier 4 Pro", "Mi Robot Vacuum-Mop 2 Ultra", "Smart Humidifier 2", "Mi Electric Kettle Pro"],
            "Panasonic": ["Inverter Microwave Oven", "FlashXpress Toaster Oven", "Nanoe Hair Dryer", "Multi-Cooker Rice Cooker"],
            "Rowenta": ["Turbo Silence Table Fan", "Focus Excel Steam Iron", "Pure Air Purifier", "X-Pert Cordless Vacuum"]
        },
        "price_range": (1500, 45000)
    },
    "FASHION": {
        "brands": ["Gucci", "Prada", "Ray-Ban", "Calvin Klein", "Fossil", "Tommy Hilfiger"],
        "specs": [
            "Genuine Italian Leather, UV400 Protection Lenses",
            "Quartz Movement, Brushed Gold Stainless Steel Case",
            "Scratch-Resistant Sapphire Glass, Embossed Designer Branding"
        ],
        "models": {
            "Gucci": ["GG Marmont Leather Belt", "Double G Buckle Belt", "Oversized Square Sunglasses", "GG Jacquard Scarf"],
            "Prada": ["Saffiano Leather Wallet", "Symbole Sunglasses", "Nylon Cosmetic Pouch", "Leather Card Holder"],
            "Ray-Ban": ["Classic Aviator Sunglasses", "Wayfarer Classic", "Clubmaster Metal", "Round Metal Framework"],
            "Calvin Klein": ["Minimalist Leather Watch", "Classic Logo Leather Belt", "Bifold Leather Wallet", "Fashion Sunglasses"],
            "Fossil": ["Heritage Leather Watch", "Grant Chronograph Watch", "Logan Leather Zip Clutch", "Machine Chronograph"],
            "Tommy Hilfiger": ["Casual Leather Belt", "Sport Chronograph Watch", "Classic Leather Wallet", "Rib-Knit Scarf"]
        },
        "price_range": (2000, 35000)
    },
    "KIDS": {
        "brands": ["Lego", "Disney", "Fisher-Price", "Carter's", "Hasbro"],
        "specs": [
            "Non-Toxic Eco-Friendly Materials, Certified Child Safe",
            "Soft 100% Organic Cotton, Promotes Creativity and Logic",
            "Interactive Sound and Light, Easy-to-Wash Fabric, Vibrant Colors"
        ],
        "models": {
            "Lego": ["Classic Creative Brick Box", "City Police Station Set", "Star Wars Millennium Falcon", "Friends Heartlake Downtown", "Duplo Town Train Set"],
            "Disney": ["Mickey Mouse Plush Toy", "Princess Elsa Dress-up Costume", "Toy Story Woody Action Figure", "Lion King Storybook Collection"],
            "Fisher-Price": ["Rock-a-Stack Classic Toy", "Baby's First Blocks Set", "Laugh & Learn Smart Stages Puppy", "Kick & Play Piano Gym"],
            "Carter's": ["5-Pack Cotton Bodysuits", "2-Piece Fleece Pajama Set", "Stretch Denim Pants", "Hooded Towel and Washcloths"],
            "Hasbro": ["Play-Doh Mega Pack", "Monopoly Junior Board Game", "Nerf Elite 2.0 Commander Blaster", "My Little Pony Styling Head"]
        },
        "price_range": (500, 9000)
    },
    "WOMENS": {
        "brands": ["Chanel", "Zara", "H&M", "Uniqlo", "Michael Kors", "Coach"],
        "specs": [
            "Premium Soft Silk Touch, Elegant Floral Print",
            "Adjustable Shoulder Straps, Relaxed Comfortable Fit",
            "High-Quality Knit Fabric, Designer Gold Hardware Accents"
        ],
        "models": {
            "Chanel": ["No.5 Eau de Parfum Spray", "Coco Mademoiselle Intense", "Rouge Allure Lipstick", "Hydra Beauty Cream"],
            "Zara": ["Floral Print Wrap Dress", "Pleated Midi Skirt", "Oversized Blazer", "Cable-knit Sweater"],
            "H&M": ["Linen Blend Dress", "Wide-leg High Waist Trousers", "Satin V-neck Blouse", "Denim Jacket"],
            "Uniqlo": ["Rayon Long Sleeve Blouse", "Pleated Pants", "UV Protection Cardigan", "Cotton Linen Shirt"],
            "Michael Kors": ["Jet Set Travel Large Tote", "Mercer Gallery Satchel", "Bradshaw Gold Watch", "Bifold Wallet"],
            "Coach": ["Signature Canvas Shoulder Bag", "Polished Pebble Leather Tote", "Cassie Crossbody Bag", "Skinny Wallet"]
        },
        "price_range": (1200, 45000)
    },
    "MENS": {
        "brands": ["Levi's", "Uniqlo", "Tommy Hilfiger", "Ralph Lauren", "Hugo Boss", "Zara"],
        "specs": [
            "Breathable Premium Cotton, Wrinkle-Resistant Finish",
            "Classic Tailored Fit, Soft Knit texture, Modern Style Detailing",
            "Flexible Stretch Fabric, Contrast Collar Design, Reinforced Seams"
        ],
        "models": {
            "Levi's": ["501 Original Fit Jeans", "511 Slim Fit Jeans", "Classic Denim Jacket", "Short Sleeve Graphic Tee"],
            "Uniqlo": ["Supima Cotton Polo Shirt", "Ultra Stretch Skinny Jeans", "Dry-EX Training Tee", "Flannel Checked Shirt"],
            "Tommy Hilfiger": ["Classic Fit Polo Shirt", "V-Neck Cotton Sweater", "Leather Billfold Wallet", "Chino Shorts"],
            "Ralph Lauren": ["Custom Slim Fit Mesh Polo", "Oxford Button-Down Shirt", "Cable-Knit Cotton Sweater", "Cotton Mesh Tee"],
            "Hugo Boss": ["Tailored Slim Fit Dress Shirt", "Mercedes-Benz Polo Shirt", "Leather Wallet Gift Set", "Classic Sport Watch"],
            "Zara": ["Basic Slim Fit Suit Blazer", "Textured Knit Polo Shirt", "Faux Suede Bomber Jacket", "Stretch Chino Trousers"]
        },
        "price_range": (1500, 30000)
    },
    "SPORTSWEAR": {
        "brands": ["Under Armour", "Nike", "Adidas", "Puma", "Lululemon"],
        "specs": [
            "Advanced Moisture-Wicking Fabric, 4-Way Stretch Compression",
            "Lightweight and Highly Breathable, Odor-Resistant Technology",
            "Anti-Chafing Flatlock Seams, Quick-Dry Treatment, Reflective Details"
        ],
        "models": {
            "Under Armour": ["Tech 2.0 Short-Sleeve Tee", "Woven Training Shorts", "HeatGear Baselayer Leggings", "Storm Armour Fleece Hoodie"],
            "Nike": ["Dri-FIT Legend Training Tee", "Challenger Running Shorts", "Pro Dri-FIT Compression Top", "Therma-FIT Training Pants"],
            "Adidas": ["Tiro 23 League Pants", "Own the Run Short Tee", "Designed for Training Shorts", "Techfit Tight Leggings"],
            "Puma": ["Run Favorite Velocity Tee", "Train Favorite Knit Shorts", "Eversculpt High Waist Tights", "Active Woven Track Pants"],
            "Lululemon": ["Metal Vent Tech Shirt 2.0", "Pace Breaker Lined Shorts", "License to Train Pants", "Surge Jogger Premium"]
        },
        "price_range": (1000, 9000)
    },
    "MOBILE": {
        "brands": ["Apple", "Samsung", "OnePlus", "Google", "Xiaomi"],
        "specs": [
            "128GB Storage, 8GB RAM, 5G Enabled",
            "256GB Storage, 12GB RAM, Super AMOLED Display",
            "512GB Storage, 16GB RAM, Pro Camera System, Premium Finish"
        ],
        "models": {
            "Apple": ["iPhone 15", "iPhone 15 Pro", "iPhone 15 Pro Max", "iPhone 14 Pro", "iPhone 13"],
            "Samsung": ["Galaxy S24 Ultra", "Galaxy S24+", "Galaxy S23 FE", "Galaxy Z Fold 5", "Galaxy Z Flip 5"],
            "OnePlus": ["12 Pro 5G", "12R Premium", "11 5G Mobile", "Nord CE 3 Lite"],
            "Google": ["Pixel 8 Pro", "Pixel 8", "Pixel 7a", "Pixel Fold 5G"],
            "Xiaomi": ["14 Ultra Premium", "Redmi Note 13 Pro+", "Poco F6 Pro", "13T Pro"]
        },
        "price_range": (15000, 130000)
    },
    "ELECTRONICS": {
        "brands": ["Sony", "Samsung", "LG", "Panasonic", "Philips"],
        "specs": [
            "4K Ultra HD, Smart TV, Dolby Vision Audio",
            "Full HD, High Quality Panel, DTS Cinema Sound",
            "Energy Efficient, Voice Remote, HDR10+ Display Technology"
        ],
        "models": {
            "Sony": ["Bravia XR OLED", "X90L 4K Full Array", "X80K Smart TV", "A80K Series OLED"],
            "Samsung": ["Neo QLED 4K Smart", "The Frame QLED TV", "Crystal UHD 4K", "OLED S90C Series"],
            "LG": ["OLED evo C3 Series", "QNED85 4K Smart", "UHD UT80 Smart TV", "Nanocell NANO75"],
            "Panasonic": ["Viera Premium 4K LED", "OLED Smart Cinema Display", "4K HDR Smart TV"],
            "Philips": ["Ambilight 4K UHD TV", "PML9507 MiniLED TV", "OLED 807 Series TV"]
        },
        "price_range": (20000, 170000)
    },
    "LAPTOPS": {
        "brands": ["Dell", "HP", "Lenovo", "Apple", "Asus", "Acer"],
        "specs": [
            "Intel Core i7, 16GB RAM, 512GB SSD Storage",
            "Intel Core i5, 8GB RAM, 256GB SSD Storage",
            "AMD Ryzen 7, 16GB RAM, 1TB NVMe Fast SSD",
            "Apple M3 Chip, 16GB Unified RAM, 512GB SSD"
        ],
        "models": {
            "Dell": ["XPS 13 9315", "XPS 15 9530", "Inspiron 16 Plus", "G15 Gaming Laptop"],
            "HP": ["Spectre x360 Convertible", "Envy 16 Premium Laptop", "Pavilion 15 Laptop", "Omen 16 Gaming"],
            "Lenovo": ["ThinkPad X1 Carbon Gen 11", "Yoga Book 9i Dual Screen", "IdeaPad Slim 5 Gen 8", "Legion Pro 5i Gaming"],
            "Apple": ["MacBook Air 13 M3", "MacBook Air 15 M3", "MacBook Pro 14 M3 Pro", "MacBook Pro 16 M3 Max"],
            "Asus": ["Zenbook 14 OLED", "Vivobook Pro 15 OLED", "ROG Zephyrus G14 Gaming", "TUF Gaming A15"],
            "Acer": ["Swift Go 14 OLED", "Aspire 5 Slim Laptop", "Predator Helios 16 Gaming", "Nitro V 15 Gaming"]
        },
        "price_range": (30000, 200000)
    },
    "AUDIO": {
        "brands": ["Sony", "Bose", "JBL", "Sennheiser", "Marshall", "Apple"],
        "specs": [
            "Active Noise Cancelling (ANC), Bluetooth 5.3",
            "Hi-Res Audio, Wireless Charging Case, Extended Battery Life",
            "IPX7 Waterproof, Portable Dual Drivers, Deep Bass"
        ],
        "models": {
            "Sony": ["WH-1000XM5 ANC Headphone", "WF-1000XM5 Wireless Earbuds", "LinkBuds S ANC Earbuds", "SRS-XE300 Portable Speaker"],
            "Bose": ["QuietComfort Ultra Headphones", "QuietComfort II Earbuds", "SoundLink Flex Bluetooth Speaker"],
            "JBL": ["Flip 6 Portable Speaker", "Charge 5 Bluetooth Speaker", "Tune 770NC ANC Headphone", "Live Pro 2 TWS Earbuds"],
            "Sennheiser": ["Momentum 4 Wireless Headphone", "Accentum Wireless Headphone", "IE 200 High-Fidelity In-Ear"],
            "Marshall": ["Major IV Wireless On-Ear", "Emberton II Portable Speaker", "Motif II A.N.C. Earbuds"],
            "Apple": ["AirPods Pro 2 USB-C", "AirPods Max Over-Ear", "AirPods 3rd Gen Wireless"]
        },
        "price_range": (3000, 40000)
    },
    "CAMERAS": {
        "brands": ["Canon", "Nikon", "Sony", "Fujifilm", "Panasonic", "GoPro"],
        "specs": [
            "24.2 MP, 4K Professional Video, Dual Pixel Autofocus",
            "33.0 MP, 8K Ultra-HD Video, In-Body Image Stabilization",
            "26.1 MP, Weather Sealed Retro Body, Premium Styling"
        ],
        "models": {
            "Canon": ["EOS R6 Mark II Mirrorless", "EOS R50 Vlogging Camera", "EOS R8 Full Frame Mirrorless", "PowerShot G7 X Mark III"],
            "Nikon": ["Z6 II Mirrorless Camera", "Z50 Creator Kit DSLR", "Zf Retro Full-Frame Mirrorless", "D850 Professional DSLR"],
            "Sony": ["Alpha a7 IV Mirrorless", "Alpha a6700 APS-C Mirrorless", "ZV-E10 Vlogger Camera", "Cyber-shot RX100 VII Premium"],
            "Fujifilm": ["X-T5 Mirrorless Retro", "X-S20 Content Creator Camera", "INSTAX Mini Evo Hybrid", "X100VI Premium Compact"],
            "Panasonic": ["Lumix GH6 Hybrid Video", "Lumix S5 II Full-Frame", "Lumix G100 Vlogger Camera"],
            "GoPro": ["HERO12 Black Action Camera", "HERO11 Black Mini Camera", "MAX 360 Action Video Camera"]
        },
        "price_range": (15000, 250000)
    },
    "GROCERY": {
        "brands": ["Amul", "Britannia", "Mother Dairy", "Lay's", "Nescafe", "Organic India", "Cadbury"],
        "specs": [
            "100% Organic & Fresh, Delivered in 10 minutes",
            "No Preservatives Added, Freshly Sourced, Express Delivery",
            "Premium Quality Sourced Globally, Pure & Healthy"
        ],
        "models": {
            "Amul": ["Pure Fresh Milk 1L", "Salted Butter 500g", "Pasteurized Cheese Slices", "Fresh Cream 250ml"],
            "Britannia": ["Sliced White Bread", "Good Day Cashew Cookies", "Bourbon Chocolate Biscuits", "Whole Wheat Bread"],
            "Mother Dairy": ["Toned Milk 1L", "Probiotic Curd 400g", "Fresh Paneer 200g"],
            "Lay's": ["Classic Salted Chips", "American Style Cream & Onion", "Spanish Tomato Tang Chips"],
            "Nescafe": ["Classic Instant Coffee Jar", "Gold Blend Coffee Premium", "Sunrise Premium Coffee"],
            "Organic India": ["Classic Green Tea", "Tulsi Ginger Tea Box", "Organic Honey Premium"],
            "Cadbury": ["Dairy Milk Silk Chocolate", "Oreo Chocolate Cookies", "Celebrations Assorted Box"]
        },
        "price_range": (30, 800)
    }
}

COLORS = ["Space Gray", "Silver", "Charcoal Black", "Polar White", "Midnight Blue", "Forest Green", "Champagne Gold"]
EDITIONS = ["Standard Edition", "Premium Bundle", "Pro Edition", "Creator Pack", "Special Edition"]

try:
    resample_filter = Image.Resampling.LANCZOS
except AttributeError:
    resample_filter = Image.ANTIALIAS

def generate_image_variant(cat_name, base_idx, product_idx, target_path):
    """Generates a truly unique product image per item using the real Unsplash mock base images.
    Every product gets a different source base image AND unique brightness/contrast/saturation transformation."""
    base_file = f"{cat_name.lower()}_mock_{base_idx}.jpg"
    base_path = os.path.join(PHOTOS_DIR, base_file)
    
    if not os.path.exists(base_path):
        print(f"  [WARN] Base image not found: {base_path}")
        return False
        
    try:
        img = Image.open(base_path).convert("RGB")
        
        # Resize to 350x350 for fast loading and reduced space requirements
        img = img.resize((350, 350), resample_filter)
        
        # Build 150 truly distinct combinations (5 brightness * 5 saturation * 6 contrast):
        brights = [0.90, 0.95, 1.00, 1.05, 1.10]
        sats = [0.85, 0.92, 1.00, 1.08, 1.15]
        contrast_levels = [0.90, 0.95, 1.00, 1.05, 1.10, 1.15]
        
        combos = []
        for b in brights:
            for s in sats:
                for c in contrast_levels:
                    combos.append((b, s, c))
        
        # Use product_idx directly to select combo (product_idx is globally unique
        # per (base_idx, i) pair, so no two products with the same base image collide)
        import hashlib
        combo_seed = int(hashlib.md5(str(product_idx).encode()).hexdigest()[:8], 16)
        bright_factor, sat_factor, contrast = combos[combo_seed % len(combos)]
        
        # Adjust Brightness and Saturation
        img = ImageEnhance.Brightness(img).enhance(bright_factor)
        img = ImageEnhance.Color(img).enhance(sat_factor)
        
        # Apply unique Contrast and Sharpness
        img = ImageEnhance.Contrast(img).enhance(contrast)
        
        sharpness_levels = [0.8, 1.0, 1.2, 1.4, 1.6]
        sharpness = sharpness_levels[combo_seed % len(sharpness_levels)]
        result = ImageEnhance.Sharpness(img).enhance(sharpness)
        
        # Save file — vary JPEG quality slightly so file sizes differ and confirm uniqueness
        quality = 82 + (product_idx % 10)  # 82–91 range
        result.save(target_path, "JPEG", quality=quality)
        return True
    except Exception as e:
        print(f"  [ERROR] Processing image {base_file}: {e}")
        return False

def main():
    print("=" * 60)
    print("Starting H-Mart Database Migration & Unique Image Generation...")
    print("=" * 60)
    
    if not os.path.exists(PHOTOS_DIR):
        print(f"Creating photos directory: {PHOTOS_DIR}")
        os.makedirs(PHOTOS_DIR, exist_ok=True)
    
    # Connect to MySQL
    try:
        conn = pymysql.connect(
            host=DB_HOST,
            user=DB_USER,
            password=DB_PASS,
            database=DB_NAME,
            charset='utf8mb4',
            cursorclass=pymysql.cursors.DictCursor
        )
        print("Connected to database successfully.")
    except Exception as e:
        print(f"Error connecting to database: {e}")
        print("Please make sure MySQL is running on XAMPP and the database 'db_ecommerce' exists.")
        sys.exit(1)
        
    try:
        with conn.cursor() as cursor:
            # 1. Insert/Verify Categories
            category_ids = {}
            for cat_name in CATEGORIES_DATA.keys():
                cursor.execute("SELECT CATEGID FROM tblcategory WHERE CATEGORIES = %s", (cat_name,))
                result = cursor.fetchone()
                if result:
                    category_ids[cat_name] = result['CATEGID']
                    print(f"Category '{cat_name}' exists (ID: {result['CATEGID']}).")
                else:
                    cursor.execute(
                        "INSERT INTO tblcategory (CATEGORIES, USERID) VALUES (%s, 0)", 
                        (cat_name,)
                    )
                    conn.commit()
                    new_id = cursor.lastrowid
                    category_ids[cat_name] = new_id
                    print(f"Inserted Category '{cat_name}' (ID: {new_id}).")
            
            # 2. Get Max Product ID to avoid primary key conflicts
            cursor.execute("SELECT MAX(PROID) as max_id FROM tblproduct")
            res = cursor.fetchone()
            start_proid = max(300001, (res['max_id'] or 0) + 1)
            print(f"Generating unique product IDs starting from: {start_proid}")
            
            # Clean up previously migrated products to allow clean re-runs
            cursor.execute("SELECT COUNT(*) as count FROM tblproduct WHERE PROID >= 300001")
            existing_count = cursor.fetchone()['count']
            if existing_count > 0:
                print(f"Removing {existing_count} previously migrated products and promos...")
                cursor.execute("DELETE FROM tblpromopro WHERE PROID >= 300001")
                cursor.execute("DELETE FROM tblproduct WHERE PROID >= 300001")
                conn.commit()
            
            # 3. Generate Products and Images
            products_to_insert = []
            promo_to_insert = []
            
            current_proid = start_proid
            products_per_category = 210  # 15 * 210 = 3150 products
            
            print(f"\nGenerating products and unique images for {len(CATEGORIES_DATA)} categories...")
            
            generated_names = set()
            for cat_name, cat_info in CATEGORIES_DATA.items():
                cat_id = category_ids[cat_name]
                print(f">> Processing '{cat_name}'...")
                
                for i in range(products_per_category):
                    brand = random.choice(cat_info["brands"])
                    model = random.choice(cat_info["models"][brand])
                    spec = random.choice(cat_info["specs"])
                    color = random.choice(COLORS)
                    edition = random.choice(EDITIONS)
                    
                    prod_name = f"{brand} {model} ({edition} - {color})"
                    
                    # Ensure name uniqueness
                    suffix = 1
                    original_name = prod_name
                    while prod_name in generated_names:
                        prod_name = f"{original_name} V{suffix}"
                        suffix += 1
                    generated_names.add(prod_name)
                    
                    # Generate Price
                    min_p, max_p = cat_info["price_range"]
                    original_price = random.randint(min_p, max_p)
                    if original_price > 10000:
                        original_price = (original_price // 1000) * 1000 - 1
                    else:
                        original_price = (original_price // 100) * 100 - 1
                        
                    # Calculate Discount and Promo Price
                    discount_pct = random.choice([0, 5, 10, 15, 20, 25])
                    promo_price = original_price
                    if discount_pct > 0:
                        promo_price = int(original_price * (1 - discount_pct / 100.0))
                    
                    qty = random.randint(5, 45)
                    
                    # Generate unique image filename and physical file.
                    # KEY FIX: base_idx changes on EVERY product (not every 10),
                    # so each product pulls from a different real Unsplash photo.
                    # product_idx (= i) drives MD5-based combo selection so every
                    # product gets a unique variant even on the same base image.
                    if cat_name == "GROCERY":
                        num_bases = 5
                    else:
                        num_bases = 10
                    base_idx = (i % num_bases) + 1
                    product_idx = i  # unique per product — drives variant combo via MD5 hash
                    
                    image_filename = f"{cat_name.lower()}_p{i + 1:03d}.jpg"
                    target_path = os.path.join(PHOTOS_DIR, image_filename)
                    
                    # Generate a truly unique variant image for this product
                    success = generate_image_variant(cat_name, base_idx, product_idx, target_path)
                    
                    # If variant generation failed, use the base mock image directly as fallback
                    db_image_path = f"uploaded_photos/{image_filename}" if success else f"uploaded_photos/{cat_name.lower()}_mock_{base_idx}.jpg"
                    
                    # Add product item
                    products_to_insert.append((
                        current_proid,
                        prod_name,
                        spec,
                        qty,
                        original_price,
                        promo_price,
                        cat_id,
                        db_image_path,
                        "Available",
                        "janobe",
                        ""
                    ))
                    
                    # Add promo item
                    promo_to_insert.append((
                        current_proid,
                        discount_pct,
                        promo_price,
                        0,  # PROBANNER
                        1   # PRONEW
                    ))
                    
                    current_proid += 1
            
            # 4. Batch Insert Products
            print("\nInserting products in batch...")
            cursor.executemany(
                """
                INSERT INTO tblproduct (
                    PROID, PRODESC, INGREDIENTS, PROQTY, ORIGINALPRICE, 
                    PROPRICE, CATEGID, IMAGES, PROSTATS, OWNERNAME, OWNERPHONE
                ) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
                """,
                products_to_insert
            )
            
            # 5. Batch Insert Promos
            print("Inserting promo discount entries in batch...")
            cursor.executemany(
                """
                INSERT INTO tblpromopro (
                    PROID, PRODISCOUNT, PRODISPRICE, PROBANNER, PRONEW
                ) VALUES (%s, %s, %s, %s, %s)
                """,
                promo_to_insert
            )
            
            conn.commit()
            print("=" * 60)
            print(f"Migration completed successfully! Inserted {len(products_to_insert)} products.")
            print("=" * 60)
            
    except Exception as e:
        conn.rollback()
        print(f"Error during migration execution: {e}")
    finally:
        conn.close()

if __name__ == "__main__":
    main()
