<div class="hmart-home-wrapper">
    <!-- Google Fonts & Custom Premium CSS -->
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,700;1,700&display=swap');

        .hmart-home-wrapper {
            font-family: 'Outfit', sans-serif;
            color: #1e293b;
            background-color: #f8fafc;
            overflow-x: hidden;
            margin-top: -20px; /* seamless integration with header navbar */
        }

        /* Hero Section */
        .hmart-hero {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            padding: 70px 0 90px;
            position: relative;
        }
        .hero-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 40px;
        }
        .hero-text {
            flex: 1.2;
            text-align: left;
        }
        .hero-subtitle {
            color: #b45309;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 12px;
            font-weight: 700;
        }
        .hero-title {
            font-size: 52px;
            font-weight: 800;
            color: #1e3a8a;
            line-height: 1.15;
            margin-bottom: 20px;
        }
        .hero-desc {
            font-size: 16px;
            color: #475569;
            line-height: 1.6;
            margin-bottom: 35px;
            max-width: 540px;
        }
        .hero-buttons {
            display: flex;
            gap: 15px;
        }
        .btn-primary-hmart {
            background-color: #1e3a8a;
            color: white !important;
            font-weight: 600;
            padding: 14px 30px;
            border-radius: 8px;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 4px 10px rgba(30, 58, 138, 0.25);
            text-decoration: none !important;
            display: inline-block;
        }
        .btn-primary-hmart:hover {
            background-color: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(37, 99, 235, 0.35);
        }
        .btn-secondary-hmart {
            background: transparent;
            color: #1e3a8a !important;
            font-weight: 600;
            padding: 14px 30px;
            border-radius: 8px;
            border: 2px solid #1e3a8a;
            transition: all 0.3s ease;
            text-decoration: none !important;
            display: inline-block;
        }
        .btn-secondary-hmart:hover {
            background-color: rgba(30, 58, 138, 0.05);
            transform: translateY(-2px);
        }
        .hero-graphic {
            flex: 0.8;
            display: flex;
            justify-content: center;
        }
        .hero-img-wrapper {
            background: #eedfd2;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
            max-width: 380px;
            position: relative;
        }
        .hero-img {
            width: 100%;
            border-radius: 12px;
            object-fit: cover;
            display: block;
        }

        /* Slider Specific CSS */
        .hero-slider {
            position: relative;
            width: 100%;
            overflow: hidden;
        }
        .hero-slide {
            display: none;
            animation: fadeSlide 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .hero-slide.active {
            display: block;
        }
        @keyframes fadeSlide {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .slider-controls {
            position: absolute;
            top: 50%;
            left: -20px;
            right: -20px;
            transform: translateY(-50%);
            display: flex;
            justify-content: space-between;
            pointer-events: none;
            z-index: 10;
        }
        .slider-btn {
            background: white;
            border: none;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            color: #1e3a8a;
            cursor: pointer;
            pointer-events: auto;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }
        .slider-btn:hover {
            background: #1e3a8a;
            color: white;
            transform: scale(1.05);
        }
        .slider-dots {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 35px;
        }
        .dot {
            width: 10px;
            height: 10px;
            background: #cbd5e1;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .dot.active {
            background: #1e3a8a;
            width: 25px;
            border-radius: 10px;
        }
        body.dark-mode .slider-btn {
            background: #1e293b;
            color: #38bdf8;
            box-shadow: 0 4px 15px rgba(0,0,0,0.5);
        }
        body.dark-mode .slider-btn:hover {
            background: #38bdf8;
            color: #0f172a;
        }
        body.dark-mode .dot {
            background: #475569;
        }
        body.dark-mode .dot.active {
            background: #38bdf8;
        }

        /* Info Strip */
        .hmart-info-strip {
            background: white;
            border-radius: 16px;
            padding: 30px 20px;
            box-shadow: 0 10px 35px rgba(0,0,0,0.04);
            margin-top: -40px;
            position: relative;
            z-index: 10;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #f1f5f9;
        }
        .info-item {
            display: flex;
            align-items: center;
            gap: 15px;
            flex: 1;
            justify-content: center;
        }
        .info-item:not(:last-child) {
            border-right: 1px solid #e2e8f0;
        }
        .info-icon {
            font-size: 24px;
            color: #b45309;
            background: #fef3c7;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .info-text {
            text-align: left;
        }
        .info-title {
            font-weight: 700;
            font-size: 15px;
            color: #1e293b;
            margin: 0 0 2px 0;
        }
        .info-subtitle {
            font-size: 13px;
            color: #64748b;
            margin: 0;
        }

        /* Category Grid */
        .category-section {
            padding: 70px 0 50px;
        }
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 35px;
        }
        .section-title-block {
            text-align: left;
        }
        .section-title {
            font-size: 32px;
            font-weight: 800;
            color: #1e3a8a;
            margin: 0 0 6px 0;
        }
        .section-subtitle {
            font-size: 15px;
            color: #64748b;
            margin: 0;
        }
        .view-all-link {
            font-weight: 700;
            color: #1e3a8a;
            text-decoration: none !important;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: color 0.2s ease;
            font-size: 15px;
        }
        .view-all-link:hover {
            color: #2563eb;
        }
        
        .category-grid {
            display: grid;
            grid-template-columns: 1.3fr 1fr;
            grid-template-rows: 210px 210px;
            gap: 25px;
        }
        .category-card {
            position: relative;
            border-radius: 18px;
            overflow: hidden;
            color: white;
            padding: 35px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            text-align: left;
        }
        .category-card img {
            position: absolute;
            right: 25px;
            top: 50%;
            transform: translateY(-50%);
            max-height: 85%;
            max-width: 45%;
            object-fit: cover;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }
        .category-card:hover img {
            transform: translateY(-50%) scale(1.06);
        }
        .category-card.shoes-card {
            grid-row: span 2;
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
        }
        .category-card.shoes-card img {
            max-width: 50%;
            max-height: 75%;
            right: 40px;
        }
        .category-card.bags-card {
            background: linear-gradient(135deg, #b45309 0%, #d97706 100%);
        }
        .category-card.clothing-card {
            background: linear-gradient(135deg, #0f766e 0%, #14b8a6 100%);
        }
        .cat-card-title {
            font-size: 26px;
            font-weight: 800;
            margin: 0 0 6px 0;
            line-height: 1.2;
        }
        .cat-card-desc {
            font-size: 14px;
            color: rgba(255,255,255,0.85);
            margin: 0 0 20px 0;
        }
        .cat-card-btn {
            background: white;
            color: #1e3a8a !important;
            font-weight: 700;
            padding: 8px 24px;
            border-radius: 20px;
            border: none;
            align-self: flex-start;
            font-size: 13px;
            text-decoration: none !important;
            transition: all 0.2s ease;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .cat-card-btn:hover {
            background: #f8fafc;
            transform: translateY(-1px);
        }

        /* ── Fast Delivery Banner (Zepto/Instamart Style) ── */
        .fast-delivery-banner {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-radius: 20px;
            padding: 40px 50px;
            margin: 30px auto 60px;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: white;
            box-shadow: 0 15px 40px rgba(16, 185, 129, 0.2);
        }
        .fast-delivery-content {
            z-index: 2;
            max-width: 60%;
            text-align: left;
        }
        .fast-delivery-content h2 {
            font-size: 38px;
            font-weight: 800;
            margin: 0 0 10px 0;
            line-height: 1.1;
        }
        .fast-delivery-content p {
            font-size: 16px;
            margin: 0 0 20px 0;
            opacity: 0.9;
        }
        .fast-delivery-btn {
            background: white;
            color: #059669 !important;
            padding: 12px 28px;
            border-radius: 30px;
            font-weight: 700;
            text-decoration: none !important;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: transform 0.2s;
        }
        .fast-delivery-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .fast-delivery-graphic {
            position: absolute;
            right: 0;
            bottom: -20px;
            height: 140%;
            opacity: 0.2;
            pointer-events: none;
            z-index: 1;
        }
        .fast-delivery-badge {
            background: #f59e0b;
            color: white;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: inline-block;
            margin-bottom: 12px;
        }
        body.dark-mode .fast-delivery-banner {
            box-shadow: 0 15px 40px rgba(0,0,0,0.5);
        }

        /* Featured Products Grid */
        .featured-section {
            padding: 60px 0 90px;
        }
        .featured-title-wrapper {
            text-align: center;
            margin-bottom: 45px;
        }
        .featured-title {
            font-size: 34px;
            font-weight: 800;
            color: #1e3a8a;
            display: inline-block;
            position: relative;
            padding-bottom: 12px;
            margin: 0;
        }
        .featured-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 30%;
            width: 40%;
            height: 4px;
            background-color: #b45309;
            border-radius: 2px;
        }
        
        .product-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
        }
        .product-card {
            background: white;
            border-radius: 18px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
            text-align: left;
        }
        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.05);
            border-color: #cbd5e1;
        }
        .prod-img-container {
            background: #f8fafc;
            padding: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 220px;
            position: relative;
            overflow: hidden;
        }
        .prod-img {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
            transition: transform 0.3s ease;
        }
        .product-card:hover .prod-img {
            transform: scale(1.05);
        }
        .badge-tag {
            position: absolute;
            top: 15px;
            left: 15px;
            font-size: 10px;
            font-weight: 800;
            padding: 4px 10px;
            border-radius: 20px;
            text-transform: uppercase;
            color: white;
            z-index: 2;
        }
        .badge-new {
            background-color: #b45309;
        }
        .badge-sale {
            background-color: #2563eb;
        }
        .prod-details {
            padding: 22px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            justify-content: space-between;
        }
        .prod-category {
            font-size: 11px;
            color: #94a3b8;
            text-transform: uppercase;
            font-weight: 700;
            margin-bottom: 6px;
            letter-spacing: 0.5px;
        }
        .prod-name {
            font-size: 16px;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 8px 0;
            line-height: 1.4;
            height: 44px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        .prod-rating {
            color: #fbbf24;
            font-size: 12px;
            margin-bottom: 18px;
        }
        .prod-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
        }
        .prod-price-block {
            display: flex;
            align-items: baseline;
        }
        .prod-price {
            font-size: 20px;
            font-weight: 800;
            color: #1e3a8a;
        }
        .prod-price-original {
            font-size: 13px;
            text-decoration: line-through;
            color: #94a3b8;
            margin-left: 6px;
        }
        .prod-add-btn {
            background: #1e3a8a;
            color: white !important;
            border: none;
            padding: 8px 18px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .prod-add-btn:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
        }
        body.dark-mode .prod-add-btn {
            background: #2563eb !important;
        }
        body.dark-mode .prod-add-btn:hover {
            background: #3b82f6 !important;
        }

        /* Newsletter Banner */
        .newsletter-banner {
            background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 100%);
            color: white;
            padding: 60px 0;
        }
        .newsletter-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 40px;
        }
        .news-text {
            flex: 1.2;
            text-align: left;
        }
        .news-title {
            font-size: 32px;
            font-weight: 800;
            margin: 0 0 8px 0;
            line-height: 1.2;
        }
        .news-desc {
            font-size: 15px;
            color: rgba(255,255,255,0.85);
            margin: 0;
        }
        .news-form {
            flex: 0.8;
            display: flex;
            gap: 12px;
        }
        .news-input {
            flex: 1;
            padding: 12px 20px;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.2);
            background: rgba(255,255,255,0.15);
            color: white;
            font-size: 14px;
            outline: none;
            transition: border 0.2s ease;
        }
        .news-input:focus {
            border-color: white;
        }
        .news-input::placeholder {
            color: rgba(255,255,255,0.5);
        }
        .news-btn {
            background: #b45309;
            color: white;
            border: none;
            font-weight: 700;
            padding: 12px 28px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s ease;
        }
        .news-btn:hover {
            background: #d97706;
        }
        
        @media (max-width: 991px) {
            .hero-content, .newsletter-content {
                flex-direction: column;
                text-align: center;
            }
            .hero-text, .news-text {
                text-align: center;
            }
            .hero-desc {
                margin-left: auto;
                margin-right: auto;
            }
            .hero-buttons {
                justify-content: center;
            }
            .hmart-info-strip {
                flex-direction: column;
                gap: 20px;
            }
            .info-item {
                width: 100%;
            }
            .info-item:not(:last-child) {
                border-right: none;
                border-bottom: 1px solid #e2e8f0;
                padding-bottom: 15px;
            }
            .category-grid {
                grid-template-columns: 1fr;
                grid-template-rows: auto auto auto;
            }
            .category-card.shoes-card {
                grid-row: auto;
                min-height: 220px;
            }
            .product-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .news-form {
                width: 100%;
            }
        }
        @media (max-width: 575px) {
            .product-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Premium Dark Mode Styling Overrides */
        body.dark-mode .hmart-home-wrapper {
            background-color: #0f172a !important;
            color: #f1f5f9 !important;
        }
        body.dark-mode .hmart-hero {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important;
        }
        body.dark-mode .hero-title {
            color: #f1f5f9 !important;
        }
        body.dark-mode .hero-desc {
            color: #cbd5e1 !important;
        }
        body.dark-mode .btn-secondary-hmart {
            color: #38bdf8 !important;
            border-color: #38bdf8 !important;
        }
        body.dark-mode .btn-secondary-hmart:hover {
            background-color: rgba(56, 189, 248, 0.1) !important;
        }
        body.dark-mode .btn-primary-hmart {
            background-color: #38bdf8 !important;
            color: #0f172a !important;
            box-shadow: 0 4px 10px rgba(56, 189, 248, 0.25) !important;
        }
        body.dark-mode .btn-primary-hmart:hover {
            background-color: #0ea5e9 !important;
        }
        body.dark-mode .hero-img-wrapper {
            background: #334155 !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3) !important;
        }
        body.dark-mode .hmart-info-strip {
            background: #1e293b !important;
            border-color: #334155 !important;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.3) !important;
        }
        body.dark-mode .info-item:not(:last-child) {
            border-right-color: #334155 !important;
        }
        body.dark-mode .info-icon {
            background: rgba(245, 158, 11, 0.1) !important;
            color: #f59e0b !important;
        }
        body.dark-mode .info-title {
            color: #f1f5f9 !important;
        }
        body.dark-mode .info-subtitle {
            color: #cbd5e1 !important;
        }
        body.dark-mode .section-title {
            color: #f1f5f9 !important;
        }
        body.dark-mode .section-subtitle {
            color: #94a3b8 !important;
        }
        body.dark-mode .view-all-link {
            color: #38bdf8 !important;
        }
        body.dark-mode .view-all-link:hover {
            color: #0ea5e9 !important;
        }
        body.dark-mode .featured-title {
            color: #f1f5f9 !important;
        }
        body.dark-mode .product-card {
            background: #1e293b !important;
            border-color: #334155 !important;
        }
        body.dark-mode .product-card:hover {
            border-color: #38bdf8 !important;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3) !important;
        }
        body.dark-mode .prod-img-container {
            background: #0f172a !important;
        }
        body.dark-mode .prod-name {
            color: #f1f5f9 !important;
        }
        body.dark-mode .prod-price {
            color: #38bdf8 !important;
        }
        body.dark-mode .prod-price-original {
            color: #64748b !important;
        }
        body.dark-mode .prod-category {
            color: #cbd5e1 !important;
        }
    </style>

    <!-- 1. Hero Showcase (Slider) -->
    <div class="hmart-hero">
        <div class="container" style="position: relative;">
            <div id="hero-slider" class="hero-slider">
                
                <!-- Slide 1 -->
                <div class="hero-slide active">
                    <div class="hero-content">
                        <div class="hero-text">
                            <div class="hero-subtitle">H-Mart Premiere</div>
                            <h1 class="hero-title">100% Secure Checkout</h1>
                            <p class="hero-desc">Shop with confidence using our newly integrated secure digital payment terminals supporting all credit/debit cards and QR-based instant transfers.</p>
                            <div class="hero-buttons">
                                <a href="index.php?q=product" class="btn-primary-hmart">Shop Now</a>
                                <a href="index.php?q=product" class="btn-secondary-hmart">View Deals</a>
                            </div>
                        </div>
                        <div class="hero-graphic">
                            <div class="hero-img-wrapper">
                                <img src="<?php echo str_replace('frontend/', '', web_root); ?>admin/products/uploaded_photos/custom_watch.png" class="hero-img" alt="Hmart Luxury Watch" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="hero-slide">
                    <div class="hero-content">
                        <div class="hero-text">
                            <div class="hero-subtitle">New Arrivals</div>
                            <h1 class="hero-title">Premium Fresh Produce</h1>
                            <p class="hero-desc">Get the freshest farm-to-table organic vegetables and fruits delivered straight to your door. High quality, crisp, and completely pesticide-free.</p>
                            <div class="hero-buttons">
                                <a href="index.php?q=product&category=HOUSEHOLDS" class="btn-primary-hmart">Explore Fresh</a>
                                <a href="index.php?q=product" class="btn-secondary-hmart">View All</a>
                            </div>
                        </div>
                        <div class="hero-graphic">
                            <div class="hero-img-wrapper" style="background: #e0f2fe;">
                                <!-- Using one of the recently downloaded diverse images as a mockup -->
                                <img src="<?php echo str_replace('frontend/', '', web_root); ?>admin/products/uploaded_photos/households_mock_2.jpg" class="hero-img" alt="Fresh Produce" onerror="this.src='<?php echo str_replace('frontend/', '', web_root); ?>admin/products/uploaded_photos/shoes_mock_1.jpg'" />
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Slide 3 -->
                <div class="hero-slide">
                    <div class="hero-content">
                        <div class="hero-text">
                            <div class="hero-subtitle">Exclusive Collection</div>
                            <h1 class="hero-title">Step Into Style</h1>
                            <p class="hero-desc">Discover our newest collection of premium footwear. Crafted for comfort, designed for aesthetics. Perfect for any occasion.</p>
                            <div class="hero-buttons">
                                <a href="index.php?q=product&category=SHOES" class="btn-primary-hmart">Shop Shoes</a>
                            </div>
                        </div>
                        <div class="hero-graphic">
                            <div class="hero-img-wrapper" style="background: #fef08a;">
                                <img src="<?php echo str_replace('frontend/', '', web_root); ?>admin/products/uploaded_photos/shoes_mock_3.jpg" class="hero-img" alt="Shoes Collection" onerror="this.src='<?php echo str_replace('frontend/', '', web_root); ?>admin/products/uploaded_photos/shoes_mock_1.jpg'" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 4: Express Grocery -->
                <div class="hero-slide">
                    <div class="hero-content">
                        <div class="hero-text">
                            <div class="hero-subtitle" style="color:#10b981; font-weight:800; letter-spacing:1.5px;"><i class="fa fa-bolt"></i> 10-MIN EXPRESS DELIVERY</div>
                            <h1 class="hero-title" style="color:#065f46; font-size:48px;">Grocery at Your Doorstep</h1>
                            <p class="hero-desc">Instamart & Zepto style instant delivery. Fresh milk, bread, butter, organic eggs, munchies, and daily essentials delivered in 10 minutes!</p>
                            <div class="hero-buttons">
                                <a href="index.php?q=product&category=GROCERY" class="btn-primary-hmart" style="background-color:#10b981; box-shadow:0 4px 10px rgba(16, 185, 129, 0.25);">Order Express Grocery</a>
                                <a href="#express-grocery" class="btn-secondary-hmart" style="color:#10b981 !important; border-color:#10b981;">View Fresh Deals</a>
                            </div>
                        </div>
                        <div class="hero-graphic">
                            <div class="hero-img-wrapper" style="background: #ecfdf5;">
                                <img src="<?php echo str_replace('frontend/', '', web_root); ?>admin/products/uploaded_photos/grocery_mock_1.jpg" class="hero-img" alt="Express Grocery" onerror="this.src='<?php echo str_replace('frontend/', '', web_root); ?>admin/products/uploaded_photos/grocery_mock_1.jpg'" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 5: Alia Bhatt Marketing -->
                <div class="hero-slide">
                    <div class="hero-content">
                        <div class="hero-text">
                            <div class="hero-subtitle" style="color: #db2777; font-weight: 800; letter-spacing: 2px;">EXCLUSIVE COLLABORATION</div>
                            <h1 class="hero-title" style="color: #9d174d;">The Alia Bhatt Couture</h1>
                            <p class="hero-desc">Elevate your ethnic fashion with the exclusive H-Mart designer collection. Premium fabrics, brilliant colors, and timeless silhouettes selected by Alia Bhatt.</p>
                            <div class="hero-buttons">
                                <a href="index.php?q=product&category=CLOTHING" class="btn-primary-hmart" style="background-color: #db2777; box-shadow: 0 4px 10px rgba(219, 39, 119, 0.25);">Shop Couture Collection</a>
                            </div>
                        </div>
                        <div class="hero-graphic">
                            <div class="hero-img-wrapper" style="background: #fdf2f8;">
                                <img src="<?php echo str_replace('frontend/', '', web_root); ?>admin/products/uploaded_photos/womens_mock_1.jpg" class="hero-img" alt="Alia Bhatt Couture" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 6: Ranbir Kapoor Marketing -->
                <div class="hero-slide">
                    <div class="hero-content">
                        <div class="hero-text">
                            <div class="hero-subtitle" style="color: #1e3a8a; font-weight: 800; letter-spacing: 2px;">METRO STYLE SELECT</div>
                            <h1 class="hero-title" style="color: #1e3a8a;">The Ranbir Kapoor Edit</h1>
                            <p class="hero-desc">Discover sleek menswear, luxury blazers, and premium leather footwear handpicked for the modern gentleman's wardrobe, inspired by Ranbir Kapoor's signature style.</p>
                            <div class="hero-buttons">
                                <a href="index.php?q=product&category=SHOES" class="btn-primary-hmart">Shop the Edit</a>
                            </div>
                        </div>
                        <div class="hero-graphic">
                            <div class="hero-img-wrapper" style="background: #eff6ff;">
                                <img src="<?php echo str_replace('frontend/', '', web_root); ?>admin/products/uploaded_photos/mens_mock_1.jpg" class="hero-img" alt="Ranbir Kapoor Edit" />
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
            <!-- Slider Controls -->
            <div class="slider-controls">
                <button class="slider-btn prev-btn" onclick="moveSlide(-1)"><i class="fa fa-chevron-left"></i></button>
                <button class="slider-btn next-btn" onclick="moveSlide(1)"><i class="fa fa-chevron-right"></i></button>
            </div>
            
            <div class="slider-dots">
                <span class="dot active" onclick="currentSlide(0)"></span>
                <span class="dot" onclick="currentSlide(1)"></span>
                <span class="dot" onclick="currentSlide(2)"></span>
                <span class="dot" onclick="currentSlide(3)"></span>
                <span class="dot" onclick="currentSlide(4)"></span>
                <span class="dot" onclick="currentSlide(5)"></span>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- 2. White Floating Info Strip -->
        <div class="hmart-info-strip">
            <div class="info-item">
                <div class="info-icon"><i class="fa fa-truck"></i></div>
                <div class="info-text">
                    <h4 class="info-title">Free Shipping</h4>
                    <p class="info-subtitle">On all orders over ₹1,000</p>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon"><i class="fa fa-headphones"></i></div>
                <div class="info-text">
                    <h4 class="info-title">24/7 Support</h4>
                    <p class="info-subtitle">Expert help anytime</p>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon"><i class="fa fa-shield"></i></div>
                <div class="info-text">
                    <h4 class="info-title">Secure Payments</h4>
                    <p class="info-subtitle">100% money back guarantee</p>
                </div>
            </div>
        </div>

        <!-- ── Fast Grocery Delivery Banner ── -->
        <div class="fast-delivery-banner">
            <img src="<?php echo web_root; ?>img/grocery-bag.svg" alt="Groceries" class="fast-delivery-graphic" onerror="this.style.display='none'">
            <i class="fa fa-shopping-basket fast-delivery-graphic" style="font-size: 200px; opacity: 0.15; right: 50px; bottom: -30px;"></i>
            
            <div class="fast-delivery-content">
                <div class="fast-delivery-badge">⚡ H-Mart Express</div>
                <h2>Groceries at your door in 10 minutes.</h2>
                <p>Fresh produce, daily essentials, and household items delivered faster than you can say "Zepto". Experience ultra-fast delivery today.</p>
                <a href="<?php echo web_root; ?>index.php?q=product&category=Grocery" class="fast-delivery-btn">
                    Shop Groceries Now <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- 3. Shop by Category Grid -->
        <div class="category-section">
            <div class="section-header">
                <div class="section-title-block">
                    <h2 class="section-title">Shop by Category</h2>
                    <p class="section-subtitle">Explore our curated collections of essentials</p>
                </div>
                <a href="index.php?q=product" class="view-all-link">View All <i class="fa fa-arrow-right"></i></a>
            </div>
            
            <div class="category-grid">
                <div class="category-card shoes-card">
                    <div>
                        <h3 class="cat-card-title">Premium Shoes</h3>
                        <p class="cat-card-desc">Engineered for comfort and style</p>
                        <a href="index.php?q=product&category=SHOES" class="cat-card-btn">Explore</a>
                    </div>
                    <img src="<?php echo str_replace('frontend/', '', web_root); ?>admin/products/uploaded_photos/custom_shoe.png" alt="Shoes Category" />
                </div>
                
                <div class="category-card bags-card">
                    <div>
                        <h3 class="cat-card-title">Designer Bags</h3>
                        <p class="cat-card-desc">Premium luxury for every day</p>
                        <a href="index.php?q=product&category=BAGS" class="cat-card-btn">Explore</a>
                    </div>
                    <img src="<?php echo str_replace('frontend/', '', web_root); ?>admin/products/uploaded_photos/custom_bag.png" alt="Bags Category" />
                </div>
                
                <div class="category-card clothing-card">
                    <div>
                        <h3 class="cat-card-title">Casual Clothing</h3>
                        <p class="cat-card-desc">Comfort meets modern design</p>
                        <a href="index.php?q=product&category=CLOTHING" class="cat-card-btn">Explore</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Express Grocery Instant Section (Instamart/Zepto style) -->
        <div id="express-grocery" class="featured-section" style="padding: 30px 0 50px;">
            <div class="section-header" style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:25px;">
                <div class="section-title-block">
                    <h2 class="section-title" style="display:flex; align-items:center; gap:8px;"><i class="fa fa-bolt" style="color:#10b981;"></i> Instamart Express Grocery</h2>
                    <p class="section-subtitle">Delivered to your doorstep in just 10 minutes!</p>
                </div>
                <a href="index.php?q=product&category=GROCERY" class="view-all-link" style="color:#10b981 !important; font-weight:700;">Explore Groceries <i class="fa fa-arrow-right"></i></a>
            </div>

            <div class="product-grid">
                <?php
                // Fetch 4 grocery items from the database
                $grocery_query = "SELECT * FROM `tblpromopro` pr , `tblproduct` p , `tblcategory` c
                                  WHERE pr.`PROID`=p.`PROID` AND p.`CATEGID` = c.`CATEGID` AND c.`CATEGORIES` = 'GROCERY' AND PROQTY>0 
                                  ORDER BY p.PROID DESC LIMIT 4";
                $mydb->setQuery($grocery_query);
                $grocery_cur = $mydb->loadResultList();

                foreach ($grocery_cur as $result) {
                ?>
                <div class="product-card">
                    <a href="index.php?q=single-item&id=<?php echo (int) $result->PROID; ?>" style="text-decoration:none;color:inherit;display:block;">
                        <div class="prod-img-container">
                            <span class="badge-tag" style="background:#10b981; color:white; font-size:10px; font-weight:800; padding:4px 10px; border-radius:4px; position:absolute; top:12px; left:12px; z-index:5;"><i class="fa fa-bolt"></i> 10 MINS</span>
                            <img src="<?php echo str_replace('frontend/', '', web_root).'admin/products/'.$result->IMAGES; ?>" class="prod-img" alt="<?php echo htmlspecialchars($result->PRODESC); ?>" />
                        </div>
                        <div class="prod-details">
                            <span class="prod-category">EXPRESS GROCERY</span>
                            <h3 class="prod-name" style="font-size:14px; font-weight:600; line-height:1.4; height:40px; overflow:hidden; text-overflow:ellipsis; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; margin:8px 0;"><?php echo htmlspecialchars($result->PRODESC); ?></h3>
                            <div class="prod-price-row">
                                <span class="prod-price">₹<?php echo number_format($result->PROPRICE, 2); ?></span>
                                <?php if ($result->ORIGINALPRICE > $result->PROPRICE) { ?>
                                    <span class="prod-price-original">₹<?php echo number_format($result->ORIGINALPRICE, 2); ?></span>
                                <?php } ?>
                            </div>
                        </div>
                    </a>
                </div>
                <?php } ?>
            </div>
        </div>

        <!-- 4. Featured Items Grid (Dynamic Catalog Database Load) -->
        <div class="featured-section">
            <div class="featured-title-wrapper">
                <h2 class="featured-title">Featured Items</h2>
            </div>
            
            <div class="product-grid">
                <?php
                // Fetch dynamic catalog items sorted with newest products first
                $query = "SELECT * FROM `tblpromopro` pr , `tblproduct` p , `tblcategory` c
                          WHERE pr.`PROID`=p.`PROID` AND  p.`CATEGID` = c.`CATEGID` AND PROQTY>0 
                          ORDER BY pr.PRONEW DESC, p.PROID DESC LIMIT 8";
                $mydb->setQuery($query);
                $cur = $mydb->loadResultList();
                
                foreach ($cur as $result) { 
                    // Generate tags based on ID thresholds or random logic to match mockups
                    $isNew = ($result->PROID >= 201743);
                    $isSale = ($result->PROID == 201739 || $result->PROID == 201746);
                ?>
                <div class="product-card">
                    <a href="index.php?q=single-item&id=<?php echo (int) $result->PROID; ?>" style="text-decoration:none;color:inherit;display:block;">
                        <div class="prod-img-container">
                            <?php if ($isNew) { ?>
                                <span class="badge-tag badge-new">New</span>
                            <?php } elseif ($isSale) { ?>
                                <span class="badge-tag badge-sale">Sale</span>
                            <?php } ?>
                            <img src="<?php echo str_replace('frontend/', '', web_root).'admin/products/'.$result->IMAGES; ?>" class="prod-img" alt="<?php echo htmlspecialchars($result->PRODESC); ?>" />
                        </div>
                        <div class="prod-details">
                            <div>
                                <div class="prod-category"><?php echo $result->CATEGORIES; ?></div>
                                <h3 class="prod-name"><?php echo $result->PRODESC; ?></h3>
                                <div class="prod-rating">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <span style="color: #94a3b8; font-size: 11px; margin-left: 4px; font-weight: normal;">(<?php echo rand(20, 150); ?>)</span>
                                </div>
                            </div>
                            <div class="prod-footer">
                                <div class="prod-price-block">
                                    <span class="prod-price">₹<?php echo number_format($result->PRODISPRICE, 2); ?></span>
                                    <?php if ($isSale) { ?>
                                        <span class="prod-price-original">₹<?php echo number_format($result->PRODISPRICE * 1.3, 2); ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </a>
                    <form method="POST" action="../backend/cart/controller.php?action=add" style="padding:0 12px 14px; display: flex; gap: 8px;">
                        <input type="hidden" name="PROPRICE" value="<?php echo $result->PRODISPRICE; ?>">
                        <input type="hidden" name="PROQTY" value="<?php echo $result->PROQTY; ?>">
                        <input type="hidden" name="PROID" value="<?php echo $result->PROID; ?>">
                        <button type="submit" name="btnorder" class="prod-add-btn" style="flex: 1; justify-content: center; padding: 8px 10px; font-size: 11px;">Add to Cart</button>
                        <button type="submit" name="buynow" class="prod-add-btn" style="flex: 1; justify-content: center; padding: 8px 10px; font-size: 11px; background-color: #b45309;">Buy Now</button>
                    </form>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>

        <?php require_once(__DIR__ . '/components/recommended_products.php'); ?>

        <!-- 5. Join H-Mart Family Newsletter Section -->
    <div class="newsletter-banner">
        <div class="container">
            <div class="newsletter-content">
                <div class="news-text">
                    <h2 class="news-title">Join the H-Mart Family</h2>
                    <p class="news-desc">Get exclusive deals, recipes, and more delivered straight to your inbox.</p>
                </div>
                <div class="news-form">
                    <input type="email" class="news-input" placeholder="Enter your email" required />
                    <button class="news-btn">Subscribe</button>
                </div>
            </div>
        </div>
    </div>
    </div>
    
    <!-- Home JavaScript -->
    <script>
    // Hero Slider Logic
    let slideIndex = 0;
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.dot');
    let slideInterval;
    
    function showSlide(n) {
        if (!slides.length) return;
        if (n >= slides.length) slideIndex = 0;
        if (n < 0) slideIndex = slides.length - 1;
        
        slides.forEach(s => s.classList.remove('active'));
        dots.forEach(d => d.classList.remove('active'));
        
        slides[slideIndex].classList.add('active');
        if(dots[slideIndex]) dots[slideIndex].classList.add('active');
    }
    
    function moveSlide(n) {
        showSlide(slideIndex += n);
        resetInterval();
    }
    
    function currentSlide(n) {
        showSlide(slideIndex = n);
        resetInterval();
    }
    
    function startInterval() {
        slideInterval = setInterval(() => {
            showSlide(slideIndex += 1);
        }, 5000);
    }
    
    function resetInterval() {
        clearInterval(slideInterval);
        startInterval();
    }
    
    // Initialize auto slide
    startInterval();
    </script>
</div>