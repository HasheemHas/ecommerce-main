<!DOCTYPE html>
<html lang="<?php echo isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en'; ?>" <?php echo (isset($_SESSION['lang']) && $_SESSION['lang'] == 'ar') ? 'dir="rtl"' : ''; ?>>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Home | Hmart</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="css/prettyPhoto.css" rel="stylesheet">
    <link href="css/price-range.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
  <link href="css/main.css" rel="stylesheet">
  <link href="css/responsive.css" rel="stylesheet">
  <link href="css/darkmode.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->       
    <!-- H-Mart Premium Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?php echo web_root; ?>favicon.svg?v=4">
    <link rel="shortcut icon" type="image/svg+xml" href="<?php echo web_root; ?>favicon.svg?v=4">
</head><!--/head-->

<body onload="totalprice()" >
<script>
  if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    document.body.classList.add('dark-mode');
  }
</script>

<!-- Outfit Font and Custom Premium Navbar Style Overrides -->
<style type="text/css">
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');

    /* RTL Arabic support */
    html[dir="rtl"] body {
        direction: rtl;
        text-align: right;
    }
    html[dir="rtl"] .navbar-container-inner,
    html[dir="rtl"] .nav-middle-links-container,
    html[dir="rtl"] .nav-right-actions {
        flex-direction: row-reverse !important;
    }
    html[dir="rtl"] .search-pill-input {
        padding: 10px 20px 10px 45px !important;
    }
    html[dir="rtl"] .search-pill-icon {
        left: auto !important;
        right: 18px !important;
    }

    .hmart-premium-navbar {
        font-family: 'Outfit', sans-serif;
        background-color: white;
        border-bottom: 1px solid #f1f5f9;
        padding: 12px 0;
        position: sticky;
        top: 0;
        z-index: 1000;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.02);
        transition: all 0.3s ease;
    }
    
    .hmart-premium-navbar .container-fluid {
        padding-left: 40px !important;
        padding-right: 40px !important;
    }
    @media (max-width: 767px) {
        .hmart-premium-navbar .container-fluid {
            padding-left: 16px !important;
            padding-right: 16px !important;
        }
    }
    
    .navbar-container-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: nowrap;
    }

    /* Logo link */
    .nav-logo-link {
        text-decoration: none !important;
        display: flex;
        align-items: center;
        flex-shrink: 0;
        line-height: 1;
    }
    .nav-logo-img {
        display: block;
        width: 132px;
        height: auto;
        max-height: 38px;
    }
    .nav-logo-img-dark {
        display: none;
    }
    body.dark-mode .nav-logo-img-light {
        display: none;
    }
    body.dark-mode .nav-logo-img-dark {
        display: block;
    }
    @media (max-width: 767px) {
        .nav-logo-img {
            width: 108px;
        }
    }

    /* Middle Menu links */
    .nav-middle-links-container {
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        gap: 22px !important;
        margin: 0 !important;
        padding: 0 !important;
        flex-shrink: 0;
    }
    @media (max-width: 1100px) {
        .nav-middle-links-container { gap: 14px !important; }
        .nav-menu-link { font-size: 14px !important; }
    }
    .nav-menu-link {
        display: inline-block !important;
        font-size: 15px !important;
        font-weight: 600 !important;
        color: #475569 !important;
        text-decoration: none !important;
        transition: color 0.2s ease !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    .nav-menu-link:hover, .nav-menu-link.active {
        color: #1e3a8a !important;
    }
    @media (max-width: 767px) {
        .nav-middle-links-container {
            display: none !important;
        }
    }

    /* Pill Search form */
    .nav-search-form {
        flex: 1;
        min-width: 140px;
        max-width: 360px;
        margin: 0 8px;
    }
    .search-pill-wrapper {
        position: relative;
        width: 100%;
    }
    .search-pill-input {
        width: 100%;
        background-color: #f1f5f9;
        border: 1px solid transparent;
        padding: 10px 20px 10px 45px;
        border-radius: 30px;
        font-size: 14px;
        font-weight: 500;
        color: #1e293b;
        outline: none;
        transition: all 0.2s ease;
    }
    .search-pill-input:focus {
        background-color: white;
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }
    .search-pill-icon {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        font-size: 15px;
        pointer-events: none;
    }

    .nav-right-actions {
        display: flex;
        align-items: center;
        gap: 14px;
        flex-shrink: 0;
    }
    .nav-select-control {
        min-width: 92px;
        height: 34px;
        border: 1px solid #dbe3ef;
        background-color: #ffffff;
        color: #334155;
        border-radius: 999px;
        padding: 0 32px 0 12px;
        font-family: 'Outfit', sans-serif;
        font-size: 12px;
        font-weight: 700;
        line-height: 1;
        cursor: pointer;
        outline: none;
        appearance: none;
        -webkit-appearance: none;
        background-image: linear-gradient(45deg, transparent 50%, #475569 50%), linear-gradient(135deg, #475569 50%, transparent 50%);
        background-position: calc(100% - 16px) 13px, calc(100% - 11px) 13px;
        background-size: 5px 5px, 5px 5px;
        background-repeat: no-repeat;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
        transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
    }
    .nav-select-control:hover,
    .nav-select-control:focus {
        border-color: #93c5fd;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.08);
    }
    .nav-select-control option {
        color: #334155;
        background: #ffffff;
        font-weight: 600;
    }
    body.dark-mode .nav-select-control {
        background-color: #1e293b;
        border-color: #334155;
        color: #e2e8f0;
        background-image: linear-gradient(45deg, transparent 50%, #cbd5e1 50%), linear-gradient(135deg, #cbd5e1 50%, transparent 50%);
    }
    @media (max-width: 767px) {
        .nav-select-control {
            min-width: 72px;
            height: 32px;
            padding-left: 10px;
            padding-right: 28px;
            font-size: 11px;
        }
    }
    
    /* Cart Badge Count */
    .cart-count-badge {
        position: absolute;
        top: 3px;
        right: 3px;
        background: #1e3a8a;
        color: white;
        font-size: 10px;
        font-weight: 800;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid white;
    }

    /* Dark Mode override compatibility */
    body.dark-mode .hmart-premium-navbar {
        background-color: #1e293b;
        border-bottom-color: #334155;
    }
    body.dark-mode .nav-menu-link {
        color: #cbd5e1 !important;
    }
    body.dark-mode .nav-menu-link:hover, body.dark-mode .nav-menu-link.active {
        color: #38bdf8 !important;
    }
    body.dark-mode .search-pill-input {
        background-color: #334155;
        color: white;
    }
    body.dark-mode .search-pill-input:focus {
        background-color: #1e293b;
        border-color: #475569;
    }

    
    /* Search Suggestions Dropdown */
    .search-suggestions-dropdown {
        position: absolute;
        top: 110%;
        left: 0;
        right: 0;
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        z-index: 1000;
        overflow: hidden;
        display: none;
        border: 1px solid #e2e8f0;
    }
    .suggestion-item {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        cursor: pointer;
        transition: background 0.2s;
    }
    .suggestion-item:hover {
        background: #f8fafc;
    }
    .suggestion-item:not(:last-child) {
        border-bottom: 1px solid #f1f5f9;
    }
    .suggestion-img {
        width: 40px;
        height: 40px;
        border-radius: 6px;
        object-fit: cover;
        margin-right: 12px;
    }
    .suggestion-details {
        display: flex;
        flex-direction: column;
    }
    .suggestion-name {
        font-size: 13.5px;
        font-weight: 600;
        color: #1e293b;
    }
    .suggestion-price {
        font-size: 12px;
        color: #ef4444;
        font-weight: 700;
    }
    body.dark-mode .search-suggestions-dropdown {
        background: #1e293b;
        border-color: #334155;
    }
    body.dark-mode .suggestion-item:hover {
        background: #0f172a;
    }
    body.dark-mode .suggestion-item:not(:last-child) {
        border-color: #334155;
    }
    body.dark-mode .suggestion-name {
        color: #f1f5f9;
    }

    /* Premium Hmart Footer Redesign Override Styling */
    .hmart-premium-footer {
        font-family: 'Outfit', sans-serif;
        background-color: #0f172a;
        color: #94a3b8;
        padding: 50px 0 0px;
        border-top: 1px solid #1e293b;
    }
    .footer-no-links {
        padding: 24px 0 !important;
    }
    .footer-no-links .footer-bottom-divider {
        border-top: none !important;
        margin-top: 0 !important;
        padding: 0 !important;
    }
    .footer-brand-section h2 {
        font-size: 26px;
        font-weight: 800;
        color: white;
        margin: 0 0 12px 0;
        letter-spacing: -0.5px;
    }
    .footer-brand-desc {
        font-size: 13.5px;
        line-height: 1.6;
        color: #94a3b8;
        margin-bottom: 25px;
    }
    .footer-features-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    .feature-card {
        display: flex;
        align-items: center;
        gap: 12px;
        background: #1e293b;
        padding: 12px 15px;
        border-radius: 12px;
        border: 1px solid #334155;
    }
    .feature-card-icon {
        font-size: 20px;
        color: #3b82f6;
        flex-shrink: 0;
    }
    .feature-card-title {
        font-size: 13.5px;
        font-weight: 700;
        color: white;
        margin: 0;
    }
    .feature-card-desc {
        font-size: 11px;
        color: #94a3b8;
        margin: 0;
    }
    .footer-links-widget h3 {
        font-size: 16px;
        font-weight: 700;
        color: white;
        margin: 0 0 15px 0;
        position: relative;
        padding-bottom: 6px;
    }
    .footer-links-widget h3::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 30px;
        height: 2px;
        background-color: #3b82f6;
    }
    .footer-links-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .footer-links-item {
        margin-bottom: 8px;
    }
    .footer-links-a {
        color: #94a3b8 !important;
        font-size: 13.5px;
        text-decoration: none !important;
        transition: all 0.2s ease;
        padding: 0;
        display: inline-block;
    }
    .footer-links-a:hover {
        color: #3b82f6 !important;
        transform: translateX(4px);
    }
    .footer-bottom-divider {
        border-top: 1px solid #1e293b;
        margin-top: 40px;
        padding: 20px 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }
    .footer-copyright {
        font-size: 13px;
        color: #64748b;
        margin: 0;
    }
    .footer-powered {
        font-size: 13px;
        color: #64748b;
        margin: 0;
    }
    .footer-powered span {
        color: #3b82f6;
        font-weight: 700;
    }
    body:not(.dark-mode) .hmart-premium-footer {
        background-color: #f8fafc;
        color: #475569;
        border-top-color: #e2e8f0;
    }
    body:not(.dark-mode) .footer-brand-section h2 {
        color: #1e293b;
    }
    body:not(.dark-mode) .footer-brand-desc {
        color: #64748b;
    }
    body:not(.dark-mode) .feature-card {
        background: white;
        border-color: #e2e8f0;
        box-shadow: 0 4px 10px rgba(0,0,0,0.01);
    }
    body:not(.dark-mode) .feature-card-title {
        color: #1e293b;
    }
    body:not(.dark-mode) .feature-card-desc {
        color: #64748b;
    }
    body:not(.dark-mode) .footer-links-widget h3 {
        color: #1e293b;
    }
    body:not(.dark-mode) .footer-links-a {
        color: #64748b !important;
    }
    body:not(.dark-mode) .footer-links-a:hover {
        color: #1e3a8a !important;
    }
    body:not(.dark-mode) .footer-bottom-divider {
        border-top-color: #e2e8f0;
    }

    /* Header Icon Button Styling */
    .nav-action-icon-btn {
        color: #475569 !important;
        font-size: 14.5px !important;
        font-weight: 600 !important;
        text-decoration: none !important;
        padding: 6px 8px;
        white-space: nowrap;
        transition: all 0.2s ease;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 6px !important;
        vertical-align: middle !important;
        line-height: 1 !important;
    }
    .nav-action-icon-btn i {
        font-size: 15px !important;
        color: #475569 !important;
        transition: all 0.2s ease;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        line-height: 1 !important;
        margin: 0 !important;
        padding: 0 !important;
        vertical-align: middle !important;
    }
    .nav-action-icon-btn:hover {
        color: #1e3a8a !important;
        text-decoration: none !important;
    }
    .nav-action-icon-btn:hover i {
        color: #1e3a8a !important;
        transform: scale(1.1);
    }
    
    /* Dark Mode overrides for navbar actions */
    body.dark-mode .nav-action-icon-btn {
        color: #cbd5e1 !important;
    }
    body.dark-mode .nav-action-icon-btn i {
        color: #cbd5e1 !important;
    }
    body.dark-mode .nav-action-icon-btn:hover {
        color: #38bdf8 !important;
    }
    body.dark-mode .nav-action-icon-btn:hover i {
        color: #38bdf8 !important;
    }
    
    .nav-cart-badge-count {
        position: absolute;
        top: -6px;
        right: -8px;
        background: #ef4444;
        color: white;
        font-size: 8px;
        font-weight: 800;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
        padding: 0;
        border: 1px solid white;
    }
    body.dark-mode .nav-cart-badge-count {
        border-color: #1e293b;
    }
    
    .nav-icon-label {
        display: inline-block !important;
        line-height: 1 !important;
        margin: 0 !important;
        padding: 0 !important;
        vertical-align: middle !important;
    }

    @media (max-width: 767px) {
        .nav-icon-label {
            display: none !important;
        }
        .nav-action-icon-btn {
            padding: 8px 6px;
        }
        .nav-right-actions {
            gap: 8px;
        }
    }

    /* Footer Theme Toggle Styling */
    .footer-theme-toggle-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background-color: rgba(30, 58, 138, 0.05);
        color: #1e3a8a !important;
        font-weight: 700;
        font-size: 13px;
        padding: 8px 16px;
        border-radius: 20px;
        border: 1px solid #cbd5e1;
        cursor: pointer;
        text-decoration: none !important;
        transition: all 0.2s ease;
    }
    .footer-theme-toggle-btn:hover {
        background-color: rgba(30, 58, 138, 0.1);
        transform: translateY(-1px);
    }
    body.dark-mode .footer-theme-toggle-btn {
        background-color: rgba(56, 189, 248, 0.1);
        color: #38bdf8 !important;
        border-color: #334155;
    }
    body.dark-mode .footer-theme-toggle-btn:hover {
        background-color: rgba(56, 189, 248, 0.2);
    }
</style>

<?php
$cart_count = 0;
if (isset($_SESSION['gcCart']) && is_array($_SESSION['gcCart'])){
    $cart_count = count($_SESSION['gcCart']);
}
?>

<header class="hmart-premium-navbar">
    <div class="container-fluid">
        <div class="navbar-container-inner">
            <!-- 1. Brand Logo -->
            <a href="<?php echo web_root?>" class="nav-logo-link">
                <img src="<?php echo web_root; ?>img/hmart-bag-logo.svg" class="nav-logo-img nav-logo-img-light" alt="H-Mart">
                <img src="<?php echo web_root; ?>img/hmart-bag-logo-dark.svg" class="nav-logo-img nav-logo-img-dark" alt="H-Mart">
            </a>

            <!-- 2. Middle Navigation Links -->
            <?php 
            $curr_q = isset($_GET['q']) ? $_GET['q'] : '';
            $curr_cat = isset($_GET['category']) ? $_GET['category'] : '';
            ?>
            <div class="nav-middle-links-container">
                <a href="index.php?q=aishopper" class="nav-menu-link <?php echo ($curr_q == 'aishopper') ? 'active' : ''; ?>"><i class="fa fa-magic"></i> AI Shopper</a>
                <a href="index.php?q=product&category=HOUSEHOLDS" class="nav-menu-link <?php echo ($curr_cat == 'HOUSEHOLDS' || $curr_cat == 'Household') ? 'active' : ''; ?>">Household</a>
                <a href="index.php?q=product" class="nav-menu-link <?php echo ($curr_q == 'product' && $curr_cat == '') ? 'active' : ''; ?>">Products</a>
                <a href="index.php?q=contact" class="nav-menu-link <?php echo ($curr_q == 'contact') ? 'active' : ''; ?>">Weekly Ads</a>
            </div>

            <!-- 3. Pill Search Bar -->
            <form action="index.php?q=product" method="POST" class="nav-search-form" style="position: relative;" enctype="multipart/form-data">
                <div class="search-pill-wrapper">
                    <i class="fa fa-search search-pill-icon" style="cursor: pointer; pointer-events: auto;" onclick="this.closest('form').submit();"></i>
                    <input type="text" id="live-search-input" name="search" class="search-pill-input" placeholder="<?php echo t('search_placeholder', 'Search for fresh produce, snacks...'); ?>" value="<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['search']) : (isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''); ?>" autocomplete="off" required style="padding-right: 40px;">
                    <i class="fa fa-camera" style="position: absolute; right: 18px; top: 50%; transform: translateY(-50%); color: #64748b; font-size: 15px; cursor: pointer; z-index: 10;" onclick="document.getElementById('visual-search-file').click();" title="<?php echo t('visual_search_tooltip', 'Search by Image (AI)'); ?>"></i>
                    <input type="file" id="visual-search-file" accept="image/*" style="display:none;" onchange="uploadVisualSearchImage(this)">
                </div>
                <div id="search-suggestions" class="search-suggestions-dropdown"></div>
            </form>

            <div class="nav-right-actions">
                <?php if (isset($_SESSION['CUSID'])) { ?>
                    <!-- Account -->
                    <a href="index.php?q=profile" class="nav-action-icon-btn" title="Account">
                        <i class="fa fa-user"></i>
                        <span class="nav-icon-label">Account</span>
                    </a>
                    <!-- Orders -->
                    <a href="index.php?q=trackorder" class="nav-action-icon-btn" title="Orders">
                        <i class="fa fa-list-alt"></i>
                        <span class="nav-icon-label">Orders</span>
                    </a>
                    <!-- Cart -->
                    <a href="index.php?q=cart" class="nav-action-icon-btn nav-cart" title="Cart">
                        <div style="position: relative; display: inline-block;">
                            <i class="fa fa-shopping-cart"></i>
                            <?php if ($cart_count > 0) { ?>
                                <span class="nav-cart-badge-count"><?php echo $cart_count; ?></span>
                            <?php } ?>
                        </div>
                        <span class="nav-icon-label">Cart</span>
                    </a>
                    <!-- Logout -->
                    <a href="logout.php" class="nav-action-icon-btn" title="Logout">
                        <i class="fa fa-sign-out"></i>
                        <span class="nav-icon-label">Logout</span>
                    </a>
                <?php } else { ?>
                    <!-- Login -->
                    <a href="index.php?q=login" class="nav-action-icon-btn" title="Login">
                        <i class="fa fa-sign-in"></i>
                        <span class="nav-icon-label">Login</span>
                    </a>
                    <!-- Sign Up -->
                    <a href="index.php?q=signup" class="nav-action-icon-btn" title="Sign Up">
                        <i class="fa fa-user-plus"></i>
                        <span class="nav-icon-label">Sign Up</span>
                    </a>
                    <!-- Cart -->
                    <a href="index.php?q=cart" class="nav-action-icon-btn nav-cart" title="Cart">
                        <div style="position: relative; display: inline-block;">
                            <i class="fa fa-shopping-cart"></i>
                            <?php if ($cart_count > 0) { ?>
                                <span class="nav-cart-badge-count"><?php echo $cart_count; ?></span>
                            <?php } ?>
                        </div>
                        <span class="nav-icon-label">Cart</span>
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>
</header>

 
   



          <?php 
            require_once $content; 
            include "LogSignModal.php";
         ?> 





<footer class="hmart-premium-footer <?php echo ($content != 'home.php') ? 'footer-no-links' : ''; ?>">
    <div class="container">
        <?php if ($content == 'home.php') { ?>
        <div class="row">
            <!-- Column 1: Service -->
            <div class="col-md-3 col-sm-6 col-xs-6">
                <div class="footer-links-widget">
                    <h3>Service</h3>
                    <ul class="footer-links-list">
                        <li class="footer-links-item"><a href="index.php?q=contact" class="footer-links-a">Online Help</a></li>
                        <li class="footer-links-item"><a href="index.php?q=contact" class="footer-links-a">Contact Us</a></li>
                        <li class="footer-links-item"><a href="index.php?q=profile" class="footer-links-a">Order Status</a></li>
                        <li class="footer-links-item"><a href="index.php?q=product" class="footer-links-a">Product Catalog</a></li>
                    </ul>
                </div>
            </div>

            <!-- Column 2: Quick Shop -->
            <div class="col-md-3 col-sm-6 col-xs-6">
                <div class="footer-links-widget">
                    <h3>Quick Shop</h3>
                    <ul class="footer-links-list">
                        <li class="footer-links-item"><a href="index.php?q=product&category=SHOES" class="footer-links-a">SHOES</a></li>
                        <li class="footer-links-item"><a href="index.php?q=product&category=BAGS" class="footer-links-a">BAGS</a></li>
                        <li class="footer-links-item"><a href="index.php?q=product&category=CLOTHING" class="footer-links-a">CLOTHING</a></li>
                        <li class="footer-links-item"><a href="index.php?q=product&category=INTERIORS" class="footer-links-a">INTERIORS</a></li>
                    </ul>
                </div>
            </div>

            <!-- Column 3: Policies -->
            <div class="col-md-3 col-sm-6 col-xs-6">
                <div class="footer-links-widget">
                    <h3>Policies</h3>
                    <ul class="footer-links-list">
                        <li class="footer-links-item"><a href="index.php?q=terms" class="footer-links-a">Terms of Use</a></li>
                        <li class="footer-links-item"><a href="index.php?q=privacy" class="footer-links-a">Privacy Policy</a></li>
                        <li class="footer-links-item"><a href="index.php?q=refund" class="footer-links-a">Refund Policy</a></li>
                        <li class="footer-links-item"><a href="index.php?q=delivery" class="footer-links-a">Delivery Information</a></li>
                    </ul>
                </div>
            </div>

            <!-- Column 4: About Us -->
            <div class="col-md-3 col-sm-6 col-xs-6">
                <div class="footer-links-widget">
                    <h3>About Us</h3>
                    <ul class="footer-links-list">
                        <li class="footer-links-item"><a href="index.php?q=about" class="footer-links-a">Company Information</a></li>
                        <li class="footer-links-item"><a href="index.php?q=careers" class="footer-links-a">Careers</a></li>
                        <li class="footer-links-item"><a href="index.php?q=location" class="footer-links-a">Store Location</a></li>
                        <li class="footer-links-item"><a href="index.php?q=affiliate" class="footer-links-a">Affiliate Program</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <?php } ?>


        <!-- Divider and Copyright Row -->
        <div class="footer-bottom-divider">
            <?php if ($content == 'home.php') { ?>
            <div class="footer-theme-toggle-wrapper">
                <a href="javascript:void(0);" id="darkmode-toggle-footer" class="footer-theme-toggle-btn" title="Toggle Light/Dark Theme">
                    <i class="fa fa-moon-o"></i> <span>Dark</span>
                </a>
            </div>
            <?php } ?>
            <p class="footer-copyright">&copy; <?php echo date('Y'); ?> H-Mart. All Rights Reserved.</p>
            <p class="footer-powered">Powered by <a href="index.php" style="color: #3b82f6; font-weight: 700; text-decoration: none !important;">H-Mart Store</a></p>
        </div>
    </div>
</footer>

 <!-- modalorder -->
 <div class="modal fade" id="myOrdered">
 </div>


 <?php include "LogSignModal.php"; ?> 
<!-- end -->
 
    <!-- jQuery -->
    <script src="<?php echo web_root; ?>jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo web_root; ?>js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript --> 
    <!-- DataTables JavaScript -->
    <script src="<?php echo web_root; ?>js/jquery.dataTables.min.js"></script>
    <script src="<?php echo web_root; ?>js/dataTables.bootstrap.min.js"></script>


<script type="text/javascript" language="javascript" src="<?php echo web_root; ?>js/ekko-lightbox.js"></script> 
<script type="text/javascript" src="<?php echo web_root; ?>js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo web_root; ?>js/locales/bootstrap-datetimepicker.uk.js" charset="UTF-8"></script>

   
<script src="<?php echo web_root; ?>js/jquery.scrollUp.min.js"></script>
<script src="<?php echo web_root; ?>js/price-range.js"></script>
<script src="<?php echo web_root; ?>js/jquery.prettyPhoto.js"></script>
<script src="<?php echo web_root; ?>js/main.js"></script> 

  <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
    <script type="text/javascript" src="js/gmaps.js"></script>
  <script src="js/contact.js"></script>

    <!-- Custom Theme JavaScript --> 
<script type="text/javascript" language="javascript" src="<?php echo web_root; ?>js/janobe.js"></script> 
 <script type="text/javascript">
  $(document).on("click", ".proid", function () {
    // var id = $(this).attr('id');
      var proid = $(this).data('id')
    // alert(proid)
       $(".modal-body #proid").val( proid )

      });

</script>
 <script>
    // tooltip demo
    $('.tooltip-demo').tooltip({
        selector: "[data-toggle=tooltip]",
        container: "body"
    })

    // popover demo
    $("[data-toggle=popover]")
        .popover()
    </script>


      <script>
        $('.carousel').carousel({
            interval: 5000 //changes the speed
        })
    </script>

<script type="text/javascript">


$('#date_picker').datetimepicker({
  format: 'mm/dd/yyyy',
    language:  'en',
    weekStart: 1,
    todayBtn:  1,
    autoclose: 1,
    todayHighlight: 1,
    startView: 2,
    minView: 2,
    forceParse: 0
    });

 
 
 
function validatedate(){ 
 
 

    var todaysDate = new Date() ;

    var txtime =  document.getElementById('ftime').value
    // var myDate = new Date(dateme); 

    var tprice = document.getElementById('alltot').value 
    var BRGY = document.getElementById('BRGY').value
    var onum = document.getElementById('ORDERNUMBER').value

     
     var mytime = parseInt(txtime)  ;
     var todaytime =  todaysDate.getHours()  ;
       if (txtime==""){
     alert("You must set the time enable to submit the order.")
     }else 
     if (mytime<todaytime){ 
        alert("Selected time is invalid. Set another time.")
      }else{
        window.location = "index.php?page=7&price="+tprice+"&time="+txtime+"&BRGY="+BRGY+"&ordernumber="+onum; 
      }
  }
</script>  


    <script type="text/javascript">
  $('.form_curdate').datetimepicker({
        language:  'en',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    });
  $('.form_bdatess').datetimepicker({
        language:  'en',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    });
</script>
<script>
 


  function checkall(selector)
  {
    if(document.getElementById('chkall').checked==true)
    {
      var chkelement=document.getElementsByName(selector);
      for(var i=0;i<chkelement.length;i++)
      {
        chkelement.item(i).checked=true;
      }
    }
    else
    {
      var chkelement=document.getElementsByName(selector);
      for(var i=0;i<chkelement.length;i++)
      {
        chkelement.item(i).checked=false;
      }
    }
  }
   function checkNumber(textBox){
        while (textBox.value.length > 0 && isNaN(textBox.value)) {
          textBox.value = textBox.value.substring(0, textBox.value.length - 1)
        }
        textBox.value = trim(textBox.value);
      }
      //
      function checkText(textBox)
      {
        var alphaExp = /^[a-zA-Z]+$/;
        while (textBox.value.length > 0 && !textBox.value.match(alphaExp)) {
          textBox.value = textBox.value.substring(0, textBox.value.length - 1)
        }
        textBox.value = trim(textBox.value);
      }
   
      // Premium Hmart Light/Dark Mode Toggle logic
      (function() {
        const toggleBtnHeader = document.getElementById('darkmode-toggle');
        const toggleBtnFooter = document.getElementById('darkmode-toggle-footer');
        
        function applyTheme(theme) {
          if (theme === 'dark') {
            document.body.classList.add('dark-mode');
            if (toggleBtnHeader) toggleBtnHeader.textContent = 'Light';
            if (toggleBtnFooter) {
              const icon = toggleBtnFooter.querySelector('i');
              const label = toggleBtnFooter.querySelector('span');
              if (icon) icon.className = 'fa fa-sun-o';
              if (label) label.textContent = 'Light';
            }
          } else {
            document.body.classList.remove('dark-mode');
            if (toggleBtnHeader) toggleBtnHeader.textContent = 'Dark';
            if (toggleBtnFooter) {
              const icon = toggleBtnFooter.querySelector('i');
              const label = toggleBtnFooter.querySelector('span');
              if (icon) icon.className = 'fa fa-moon-o';
              if (label) label.textContent = 'Dark';
            }
          }
        }

        const savedTheme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        applyTheme(savedTheme);

        const handleToggle = function() {
          const currentTheme = document.body.classList.contains('dark-mode') ? 'dark' : 'light';
          const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
          localStorage.setItem('theme', newTheme);
          applyTheme(newTheme);
        };

        if (toggleBtnHeader) toggleBtnHeader.addEventListener('click', handleToggle);
        if (toggleBtnFooter) toggleBtnFooter.addEventListener('click', handleToggle);
      })();

      function switchModalTab(tab) {
        if (tab === 'login') {
          $('.nav-pills a[href="#home"]').tab('show');
        } else if (tab === 'signup') {
          $('.nav-pills a[href="#profile"]').tab('show');
        }
      }
        
  </script>     

<!-- H-Mart Customer AI Features & Interactions -->
<script>
// 1. AI Visual Search Photo Upload & Processing
function uploadVisualSearchImage(input) {
    if (!input.files || !input.files[0]) return;
    
    let formData = new FormData();
    formData.append('image', input.files[0]);
    
    // Show visual loading indicator
    let inputEl = document.getElementById('live-search-input');
    let originalPlaceholder = inputEl.placeholder;
    inputEl.placeholder = "AI Matching items from image...";
    inputEl.disabled = true;
    
    fetch('<?php echo str_replace('frontend/', 'backend/api/', web_root); ?>visual_search_upload.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        inputEl.disabled = false;
        inputEl.placeholder = originalPlaceholder;
        if (data.status === 'success' && data.product_ids) {
            window.location.href = 'index.php?q=product&visual_search_ids=' + data.product_ids;
        } else {
            alert(data.message || 'No matches found for uploaded image.');
        }
    })
    .catch(err => {
        inputEl.disabled = false;
        inputEl.placeholder = originalPlaceholder;
        console.error('Visual search error:', err);
    });
}

// 2. AI Smart Search Suggestions with spelling corrector
document.getElementById('live-search-input').addEventListener('keyup', function() {
    let query = this.value.trim();
    let dropdown = document.getElementById('search-suggestions');
    
    if (query.length >= 2) {
        // Fetch suggestions and corrections from Python AI Service
        fetch('http://localhost:8000/api/customer/search-suggest?q=' + encodeURIComponent(query))
        .then(response => response.json())
        .then(data => {
            dropdown.innerHTML = '';
            
            // Show spelling corrector link if suggestion exists
            if (data.corrected_spelling && data.corrected_spelling !== query.toLowerCase()) {
                let corrHtml = `
                <div style="padding: 10px 15px; background: #fffbeb; font-size: 12px; color: #b45309; border-bottom: 1px solid #fef3c7; font-weight:600;">
                    Did you mean: <a href="javascript:void(0);" onclick="document.getElementById('live-search-input').value = '${data.corrected_spelling}'; document.getElementById('live-search-input').dispatchEvent(new Event('keyup'));" style="color: #d97706; text-decoration: underline;">${data.corrected_spelling}</a>?
                </div>`;
                dropdown.insertAdjacentHTML('beforeend', corrHtml);
            }
            
            fetch('<?php echo str_replace('frontend/', 'backend/api/', web_root); ?>search_api.php?query=' + encodeURIComponent(query))
            .then(res => res.json())
            .then(items => {
                if (items.length > 0) {
                    items.forEach(item => {
                        let html = `
                        <div class="suggestion-item" onclick="document.getElementById('live-search-input').value = '${item.name}'; document.querySelector('.nav-search-form').submit();">
                            <img src="${item.image}" class="suggestion-img" alt="${item.name}" onerror="this.src='<?php echo str_replace('frontend/', '', web_root); ?>images/default.jpg';">
                            <div class="suggestion-details">
                                <span class="suggestion-name">${item.name}</span>
                                <span class="suggestion-price">INR ${item.price}</span>
                            </div>
                        </div>`;
                        dropdown.insertAdjacentHTML('beforeend', html);
                    });
                    dropdown.style.display = 'block';
                } else if (data.suggestions && data.suggestions.length > 0) {
                    data.suggestions.forEach(item => {
                        let html = `
                        <div class="suggestion-item" onclick="document.getElementById('live-search-input').value = '${item}'; document.querySelector('.nav-search-form').submit();">
                            <div class="suggestion-details" style="padding: 4px 0;">
                                <span class="suggestion-name" style="font-size: 13px; font-weight: 600;"><i class="fa fa-search text-muted" style="margin-right:8px;"></i> ${item}</span>
                            </div>
                        </div>`;
                        dropdown.insertAdjacentHTML('beforeend', html);
                    });
                    dropdown.style.display = 'block';
                } else {
                    dropdown.style.display = dropdown.innerHTML ? 'block' : 'none';
                }
            });
        })
        .catch(err => {
            fetch('<?php echo str_replace('frontend/', 'backend/api/', web_root); ?>search_api.php?query=' + encodeURIComponent(query))
            .then(res => res.json())
            .then(items => {
                dropdown.innerHTML = '';
                if (items.length > 0) {
                    items.forEach(item => {
                        let html = `
                        <div class="suggestion-item" onclick="document.getElementById('live-search-input').value = '${item.name}'; document.querySelector('.nav-search-form').submit();">
                            <img src="${item.image}" class="suggestion-img" alt="${item.name}" onerror="this.src='<?php echo str_replace('frontend/', '', web_root); ?>images/default.jpg';">
                            <div class="suggestion-details">
                                <span class="suggestion-name">${item.name}</span>
                                <span class="suggestion-price">INR ${item.price}</span>
                            </div>
                        </div>`;
                        dropdown.insertAdjacentHTML('beforeend', html);
                    });
                    dropdown.style.display = 'block';
                } else {
                    dropdown.style.display = 'none';
                }
            });
        });
    } else {
        dropdown.style.display = 'none';
    }
});

document.addEventListener('click', function(e) {
    if (!e.target.closest('.nav-search-form')) {
        document.getElementById('search-suggestions').style.display = 'none';
    }
});

// 3. AI Exit-Intent Cart Abandonment recovery modal
let exitIntentTriggered = false;
document.addEventListener("mouseleave", function(e) {
    if (e.clientY < 0 && !exitIntentTriggered) {
        // Only trigger if customer has items in their cart session
        let cartBadge = document.querySelector('.nav-cart-badge-count');
        if (cartBadge && parseInt(cartBadge.textContent) > 0) {
            exitIntentTriggered = true;
            
            // Query AI Service for discount coupon
            fetch('http://localhost:8000/api/customer/cart-risk/session_' + Date.now(), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    items_count: parseInt(cartBadge.textContent),
                    cart_total: 1500.0 // Approximate default cart total for scoring
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.trigger_coupon) {
                    showExitIntentCoupon(data.trigger_coupon, data.discount_pct, data.urgency_msg);
                }
            })
            .catch(err => {
                // Fallback discount
                showExitIntentCoupon("SAVE10", 10, "Secure your cart items now before they sell out!");
            });
        }
    }
});

function showExitIntentCoupon(code, pct, msg) {
    // Dynamically inject a beautiful overlay modal
    let modalHtml = `
    <div id="exitIntentModal" class="modal fade" role="dialog" style="display:none; z-index:99999;">
        <div class="modal-dialog" style="max-width: 450px; margin-top: 10%;">
            <div class="modal-content" style="border-radius:18px; border:none; text-align:center; padding:30px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); background:#fff; font-family:'Outfit',sans-serif;">
                <div style="font-size: 50px; color:#1e3a8a; margin-bottom:15px;"><i class="fa fa-gift"></i></div>
                <h2 style="font-weight:800; font-size:24px; color:#1e293b; margin:0 0 10px 0;">Wait! Don't Leave Your Cart Behind</h2>
                <p style="font-size:14px; color:#64748b; line-height:1.6; margin-bottom:20px;">${msg}</p>
                <div style="background:#f1f5f9; border-radius:12px; padding:15px; border:2px dashed #cbd5e1; margin-bottom:20px;">
                    <div style="font-size:11px; text-transform:uppercase; font-weight:700; color:#94a3b8; letter-spacing:0.8px;">Your Personal Discount Coupon</div>
                    <div style="font-size:28px; font-weight:800; color:#1e3a8a; margin:4px 0;">${pct}% OFF</div>
                    <code style="font-size:18px; font-weight:800; color:#b45309; background:transparent;">${code}</code>
                </div>
                <button onclick="copyExitCoupon('${code}')" class="btn btn-primary btn-block" style="background:#1e3a8a; border:none; padding:12px; border-radius:8px; font-weight:700; font-size:14px;">Copy Code & Checkout</button>
                <div style="margin-top:12px;">
                    <a href="javascript:void(0);" onclick="$('#exitIntentModal').modal('hide')" style="font-size:12px; color:#94a3b8; text-decoration:none; font-weight:600;">No thanks, let me browse</a>
                </div>
            </div>
        </div>
    </div>`;
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    $('#exitIntentModal').modal('show');
}

function copyExitCoupon(code) {
    navigator.clipboard.writeText(code).then(() => {
        alert("Coupon code copied! Redirecting to checkout...");
        $('#exitIntentModal').modal('hide');
        window.location.href = "index.php?q=cart";
    });
}
</script>

<?php 
if (isset($_GET['q']) && $_GET['q'] != 'aishopper' || !isset($_GET['q'])) {
    include 'components/chat.php'; 
}
?>
</body>
</html>
