<?php  
if (!isset($_SESSION['CUSID'])){
    redirect("index.php");
}

$customer = New Customer();
$res = $customer->single_customer($_SESSION['CUSID']);

// Calculate totals savings (confirmed order totals)
$query = "SELECT SUM(PAYMENT) as total_spent FROM `tblsummary` WHERE `CUSTOMERID`=".$_SESSION['CUSID']." AND `ORDEREDSTATS`='Confirmed'";
$mydb->setQuery($query);
$spent_res = $mydb->loadSingleResult();
$savings_val = ($spent_res && $spent_res->total_spent) ? $spent_res->total_spent * 0.12 : 432.50; // mock 12% cash back savings or fallback
?>

<!-- Google Fonts & Custom CSS -->
<style type="text/css">
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');

    .hmart-profile-wrapper {
        font-family: 'Outfit', sans-serif;
        color: #1e293b;
        background-color: #f8fafc;
        padding: 40px 0 80px;
        margin-top: -20px; /* seamless navbar alignment */
    }

    /* 1. Left Sidebar Card styling */
    .profile-sidebar-card {
        background: white;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        padding: 35px 25px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.01);
        text-align: center;
        margin-bottom: 30px;
    }
    
    /* Sleek Avatar box with green active dot */
    .avatar-wrapper {
        position: relative;
        width: 90px;
        height: 90px;
        margin: 0 auto 15px;
        cursor: pointer;
    }
    .avatar-img-main {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #f1f5f9;
        transition: transform 0.2s ease;
    }
    .avatar-wrapper:hover .avatar-img-main {
        transform: scale(1.05);
    }
    .active-dot-indicator {
        position: absolute;
        bottom: 4px;
        right: 4px;
        width: 15px;
        height: 15px;
        background: #10b981;
        border: 3px solid white;
        border-radius: 50%;
    }
    .profile-user-name {
        font-size: 20px;
        font-weight: 800;
        color: #1e293b;
        margin: 0 0 6px 0;
    }
    .membership-level-badge {
        display: inline-block;
        background: #fef3c7;
        color: #b45309;
        font-size: 10px;
        font-weight: 800;
        padding: 4px 12px;
        border-radius: 20px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-bottom: 25px;
    }
    .manage-membership-btn {
        width: 100%;
        background: #f97316;
        color: white !important;
        font-weight: 700;
        padding: 11px;
        border-radius: 8px;
        border: none;
        font-size: 13px;
        cursor: pointer;
        transition: background-color 0.2s ease;
        margin-bottom: 30px;
    }
    .manage-membership-btn:hover {
        background: #ea580c;
    }

    /* Sidebar Navigation List */
    .sidebar-navigation-list {
        list-style: none;
        padding: 0;
        margin: 0;
        text-align: left;
    }
    .sidebar-nav-item {
        margin-bottom: 8px;
    }
    .sidebar-nav-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 16px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 700;
        color: #64748b;
        text-decoration: none !important;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .sidebar-nav-link:hover {
        background: #f1f5f9;
        color: #1e293b;
    }
    .sidebar-nav-link.active {
        background: #1e3a8a;
        color: white;
    }
    .sidebar-nav-link.active i {
        color: white;
    }
    .sidebar-nav-divider {
        border-top: 1px solid #f1f5f9;
        margin: 25px 0;
    }

    /* 2. Right Dashboard elements */
    .dashboard-super-header {
        font-size: 11px;
        font-weight: 800;
        color: #2563eb;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-bottom: 8px;
        text-align: left;
    }
    .dashboard-main-welcome {
        font-size: 34px;
        font-weight: 800;
        color: #1e293b;
        margin: 0 0 6px 0;
        text-align: left;
    }
    .dashboard-subtitle {
        font-size: 15px;
        color: #64748b;
        margin: 0 0 35px 0;
        text-align: left;
    }

    /* Three horizontal stats cards row */
    .stats-cards-row {
        display: flex;
        gap: 20px;
        margin-bottom: 35px;
    }
    .stats-card-box {
        flex: 1;
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 22px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.01);
        text-align: left;
    }
    .stats-card-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }
    .stats-icon-badge {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }
    .stats-badge-savings {
        background: rgba(16, 185, 129, 0.08);
        color: #10b981;
    }
    .stats-badge-points {
        background: #1e3a8a;
        color: white;
    }
    .stats-badge-active {
        background: rgba(249, 115, 22, 0.08);
        color: #f97316;
    }
    .stats-pill-green {
        background: #d1fae5;
        color: #065f46;
        font-size: 10px;
        font-weight: 800;
        padding: 2px 8px;
        border-radius: 12px;
    }
    .stats-label {
        font-size: 11px;
        font-weight: 800;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }
    .stats-value {
        font-size: 24px;
        font-weight: 800;
        color: #1e293b;
        margin: 0;
    }

    /* Card Layouts */
    .profile-dashboard-card {
        background: white;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.01);
        margin-bottom: 30px;
        text-align: left;
    }
    .card-header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }
    .card-section-title {
        font-size: 20px;
        font-weight: 800;
        color: #1e293b;
        margin: 0;
    }
    .card-section-desc {
        font-size: 13px;
        color: #64748b;
        margin: 4px 0 0 0;
    }
    .card-edit-action {
        font-size: 13px;
        font-weight: 700;
        color: #1e3a8a;
        text-decoration: none !important;
        display: flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
    }

    /* Member details text columns */
    .member-info-grid {
        display: flex;
        gap: 20px;
        margin-bottom: 25px;
    }
    .member-info-col {
        flex: 1;
    }
    .member-details-field-box {
        background: #f1f5f9;
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        border: 1px solid #e2e8f0;
    }

    /* Action Buttons bottom row */
    .dashboard-actions-row {
        display: flex;
        gap: 15px;
        border-top: 1px solid #f1f5f9;
        padding-top: 25px;
    }
    .action-btn-solid {
        background: #1e3a8a;
        color: white !important;
        font-weight: 700;
        padding: 11px 20px;
        border-radius: 8px;
        font-size: 13px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: background-color 0.2s ease;
    }
    .action-btn-solid:hover {
        background: #1d4ed8;
    }
    .action-btn-outline {
        background: white;
        color: #475569 !important;
        font-weight: 700;
        padding: 11px 20px;
        border-radius: 8px;
        font-size: 13px;
        border: 1px solid #cbd5e1;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }
    .action-btn-outline:hover {
        background: #f8fafc;
        border-color: #94a3b8;
    }

    /* Order History Table */
    .order-reference-badge {
        background: rgba(37, 99, 235, 0.08);
        color: #2563eb;
        font-size: 12px;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 20px;
    }
    .table-order-history {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }
    .table-order-history th {
        font-size: 11px;
        font-weight: 800;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 16px;
        border-bottom: 1px solid #f1f5f9;
    }
    .table-order-history td {
        font-size: 13px;
        font-weight: 600;
        color: #1e293b;
        padding: 16px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    
    /* Status Pill Badges */
    .status-badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
    }
    .status-pill-delivered {
        background: #d1fae5;
        color: #065f46;
    }
    .status-pill-pending {
        background: #ffedd5;
        color: #c2410c;
    }
    .status-pill-cancelled {
        background: #f1f5f9;
        color: #475569;
    }
    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    /* Table Details CTA */
    .table-details-btn {
        background: #f1f5f9;
        color: #475569 !important;
        font-size: 12px;
        font-weight: 700;
        padding: 6px 12px;
        border-radius: 6px;
        text-decoration: none !important;
        transition: all 0.2s ease;
    }
    .table-details-btn:hover {
        background: #e2e8f0;
        color: #1e293b !important;
    }

    .view-all-history-link {
        display: block;
        text-align: center;
        font-size: 13px;
        font-weight: 700;
        color: #1e3a8a;
        text-decoration: none !important;
        margin-top: 25px;
        transition: color 0.2s ease;
        cursor: pointer;
    }
    .view-all-history-link:hover {
        color: #2563eb;
    }

    /* Overhaul of nested forms to match premium dashboard input fields */
    .profile-dashboard-card form h3 {
        display: none !important; /* hide redundant titles */
    }
    .profile-dashboard-card form .col-md-4.control-label {
        font-size: 12px;
        font-weight: 700;
        color: #475569;
        text-align: left;
        margin-bottom: 6px;
        display: block;
        width: 100%;
    }
    .profile-dashboard-card form .form-control {
        width: 100%;
        padding: 10px 14px;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        font-size: 14px;
        outline: none;
        background: white;
        color: #1e293b;
        font-weight: 500;
        box-shadow: none;
        transition: border 0.2s ease;
    }
    .profile-dashboard-card form .form-control:focus {
        border-color: #1e3a8a;
    }
    .profile-dashboard-card form .submit.btn {
        background: #1e3a8a !important;
        border: none !important;
        color: white !important;
        font-weight: 700 !important;
        padding: 12px 30px !important;
        border-radius: 8px !important;
        font-size: 14px !important;
        cursor: pointer !important;
        box-shadow: none !important;
        transition: background 0.2s ease !important;
    }
    .profile-dashboard-card form .submit.btn:hover {
        background: #1d4ed8 !important;
    }

    @media (max-width: 767px) {
        .stats-cards-row {
            flex-direction: column;
        }
        .member-info-grid {
            flex-direction: column;
        }
    }

    /* Premium Dark Mode Override Styling */
    body.dark-mode .hmart-profile-wrapper {
        background-color: #0f172a !important;
        color: #f1f5f9 !important;
    }
    body.dark-mode .profile-sidebar-card {
        background: #1e293b !important;
        border-color: #334155 !important;
        color: #f1f5f9 !important;
        box-shadow: 0 4px 20px rgba(0,0,0,0.2) !important;
    }
    body.dark-mode .profile-user-name {
        color: #f1f5f9 !important;
    }
    body.dark-mode .sidebar-nav-link {
        color: #cbd5e1 !important;
    }
    body.dark-mode .sidebar-nav-link:hover {
        background: #334155 !important;
        color: #38bdf8 !important;
    }
    body.dark-mode .sidebar-nav-link.active {
        background: #38bdf8 !important;
        color: #0f172a !important;
    }
    body.dark-mode .sidebar-nav-divider {
        border-top-color: #334155 !important;
    }
    body.dark-mode .active-dot-indicator {
        border-color: #1e293b !important;
    }
    body.dark-mode .dashboard-main-welcome {
        color: #f1f5f9 !important;
    }
    body.dark-mode .dashboard-subtitle {
        color: #cbd5e1 !important;
    }
    body.dark-mode .stats-card-box {
        background: #1e293b !important;
        border-color: #334155 !important;
        color: #f1f5f9 !important;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2) !important;
    }
    body.dark-mode .stats-value {
        color: #f1f5f9 !important;
    }
    body.dark-mode .profile-dashboard-card {
        background: #1e293b !important;
        border-color: #334155 !important;
        color: #f1f5f9 !important;
        box-shadow: 0 4px 20px rgba(0,0,0,0.2) !important;
    }
    body.dark-mode .card-section-title {
        color: #f1f5f9 !important;
    }
    body.dark-mode .card-section-desc {
        color: #94a3b8 !important;
    }
    body.dark-mode .card-edit-action {
        color: #38bdf8 !important;
    }
    body.dark-mode .member-details-field-box {
        background: #0f172a !important;
        border-color: #334155 !important;
        color: #cbd5e1 !important;
    }
    body.dark-mode .dashboard-actions-row {
        border-top-color: #334155 !important;
    }
    body.dark-mode .action-btn-outline {
        background: #1e293b !important;
        color: #cbd5e1 !important;
        border-color: #475569 !important;
    }
    body.dark-mode .action-btn-outline:hover {
        background: #334155 !important;
        color: white !important;
    }
    body.dark-mode .table-order-history th {
        color: #94a3b8 !important;
        border-bottom-color: #334155 !important;
    }
    body.dark-mode .table-order-history td {
        color: #cbd5e1 !important;
        border-bottom-color: #334155 !important;
    }
    body.dark-mode .table-details-btn {
        background: #334155 !important;
        color: #cbd5e1 !important;
    }
    body.dark-mode .table-details-btn:hover {
        background: #475569 !important;
        color: white !important;
    }
    body.dark-mode .profile-dashboard-card form .col-md-4.control-label {
        color: #cbd5e1 !important;
    }
    body.dark-mode .profile-dashboard-card form .form-control {
        background: #0f172a !important;
        border-color: #334155 !important;
        color: #f1f5f9 !important;
    }
    body.dark-mode .profile-dashboard-card form .form-control:focus {
        border-color: #38bdf8 !important;
    }
    body.dark-mode .profile-dashboard-card form .submit.btn {
        background: #38bdf8 !important;
        color: #0f172a !important;
    }
    body.dark-mode .profile-dashboard-card form .submit.btn:hover {
        background: #0ea5e9 !important;
    }
    body.dark-mode .view-all-history-link {
        color: #38bdf8 !important;
    }
    body.dark-mode .view-all-history-link:hover {
        color: #0ea5e9 !important;
    }
    body.dark-mode .breadcrumb li a {
        color: #38bdf8 !important;
    }
    body.dark-mode .breadcrumb li {
        color: #cbd5e1 !important;
    }
    body.dark-mode .breadcrumb > li + li::before {
        color: #64748b !important;
    }
    body.dark-mode .radio-inline {
        border-color: #475569 !important;
        color: #cbd5e1 !important;
        background: #1e293b !important;
    }
    body.dark-mode .radio-inline strong {
        color: #cbd5e1 !important;
    }
    body.dark-mode .modal-body .table th {
        background-color: #0f172a !important;
        color: #f1f5f9 !important;
        border-color: #334155 !important;
    }
    body.dark-mode .modal-body .table td {
        border-color: #334155 !important;
        color: #cbd5e1 !important;
    }
</style>

<div class="hmart-profile-wrapper">
    <div class="container">
        <!-- Breadcrumbs -->
        <ol class="breadcrumb" style="background:none; padding:0; margin-bottom: 30px; text-align: left;">
            <li><a href="index.php" style="color: #64748b; font-weight: 600;">Home</a></li>
            <li class="active" style="color: #1e3a8a; font-weight: 700;">Profile</li>
        </ol>

        <!-- Message Alerts -->
        <?php check_message(); ?>

        <div class="row">
            <!-- 1. Left Column: Sidebar -->
            <div class="col-md-3">
                <div class="profile-sidebar-card">
                    <!-- Circular Avatar (triggers Modal on click) -->
                    <div class="avatar-wrapper" data-target="#myModal" data-toggle="modal" title="Change profile photo">
                        <?php 
$photo = $res->CUSPHOTO;
$actual_file = '../backend/customer/' . $photo;
if (empty($photo) || $photo == 'NONE' || $photo == 'none' || !file_exists($actual_file) || is_dir($actual_file)) {
    $gender = strtolower($res->GENDER);
    $img_src = ($gender === "female") ? "https://api.dicebear.com/9.x/avataaars/svg?seed=Mia" : "https://api.dicebear.com/9.x/avataaars/svg?seed=Felix";
} else {
    $img_src = $actual_file;
}
?>
<img src="<?php echo $img_src; ?>" class="avatar-img-main" alt="Avatar" onerror="this.src='https://ui-avatars.com/api/?name=User&background=random';">
                        <div class="active-dot-indicator"></div>
                    </div>
                    
                    <h3 class="profile-user-name" id="sidebar-user-name"><?php echo $res->FNAME .' '.$res->LNAME; ?></h3>
                    <?php 
                    $tier = isset($res->membership_tier) && !empty($res->membership_tier) ? $res->membership_tier : 'Silver';
                    $badgeColor = '#fef3c7'; // default Gold
                    $textColor = '#b45309';
                    if (strcasecmp($tier, 'Silver') === 0) {
                        $badgeColor = '#e2e8f0';
                        $textColor = '#475569';
                    } elseif (strcasecmp($tier, 'VIP') === 0 || strcasecmp($tier, 'Platinum') === 0) {
                        $badgeColor = '#fae8ff';
                        $textColor = '#a21caf';
                    }
                    ?>
                    <span class="membership-level-badge" style="background: <?php echo $badgeColor; ?>; color: <?php echo $textColor; ?>;">
                        <?php echo htmlspecialchars($tier); ?> Member
                    </span>
                    
                    <button class="manage-membership-btn" data-toggle="modal" data-target="#membershipModal">Manage Membership</button>
                    
                    <!-- Sidebar navigation list -->
                    <ul class="sidebar-navigation-list">
                        <li class="sidebar-nav-item">
                            <a id="nav-overview" onclick="switchDashboardSection('overview')" class="sidebar-nav-link active">
                                <i class="fa fa-dashboard"></i> Overview
                            </a>
                        </li>
                        <li class="sidebar-nav-item">
                            <a id="nav-profile" onclick="switchDashboardSection('profile')" class="sidebar-nav-link">
                                <i class="fa fa-user"></i> My Profile
                            </a>
                        </li>
                        <li class="sidebar-nav-item">
                            <a id="nav-orders" onclick="switchDashboardSection('orders')" class="sidebar-nav-link">
                                <i class="fa fa-shopping-bag"></i> Order History
                            </a>
                        </li>
                        <li class="sidebar-nav-item">
                            <a id="nav-wishlist" onclick="switchDashboardSection('wishlist')" class="sidebar-nav-link">
                                <i class="fa fa-heart"></i> My Wishlist
                            </a>
                        </li>
                        <li class="sidebar-nav-item">
                            <a onclick="switchDashboardSection('overview'); togglePasswordSection();" class="sidebar-nav-link">
                                <i class="fa fa-cog"></i> Account Settings
                            </a>
                        </li>
                        
                        <div class="sidebar-nav-divider"></div>
                        
                        <li class="sidebar-nav-item">
                            <a href="index.php?q=contact" class="sidebar-nav-link">
                                <i class="fa fa-question-circle"></i> Help Center
                            </a>
                        </li>
                        <li class="sidebar-nav-item">
                            <a href="logout.php" class="sidebar-nav-link">
                                <i class="fa fa-sign-out"></i> Sign Out
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- 2. Right Column: Dashboard Details Panel -->
            <div class="col-md-9">
                
                <!-- SECTION 1: OVERVIEW PANEL (DEFAULT) -->
                <div id="section-overview" class="profile-section-panel">
                    <div class="dashboard-super-header">Member Dashboard</div>
                    <h1 class="dashboard-main-welcome">Welcome Back, <?php echo $res->FNAME; ?></h1>
                    <p class="dashboard-subtitle">Your premium H-Mart experience, curated just for you.</p>

                    <!-- Stats Cards Row -->
                    <div class="stats-cards-row">
                        <!-- Savings Card -->
                        <div class="stats-card-box">
                            <div class="stats-card-top">
                                <div class="stats-icon-badge stats-badge-savings">
                                    <i class="fa fa-money"></i>
                                </div>
                                <span class="stats-pill-green">+12% vs last mo.</span>
                            </div>
                            <div class="stats-label">Lifetime Savings</div>
                            <h2 class="stats-value">₹<?php echo number_format($savings_val, 2); ?></h2>
                        </div>

                        <!-- Reward Points Card -->
                        <div class="stats-card-box" style="background: #1e3a8a; color: white;">
                            <div class="stats-card-top">
                                <div class="stats-icon-badge stats-badge-points">
                                    <i class="fa fa-star"></i>
                                </div>
                            </div>
                            <div class="stats-label" style="color: rgba(255,255,255,0.6);">Reward Points</div>
                            <h2 class="stats-value" style="color: white;">2,480 PTS</h2>
                        </div>

                        <!-- Active Orders Card -->
                        <?php
                        $pending_q = "SELECT COUNT(*) as pending_count FROM `tblsummary` WHERE `CUSTOMERID`=".$_SESSION['CUSID']." AND `ORDEREDSTATS`='Pending'";
                        $mydb->setQuery($pending_q);
                        $pending_res = $mydb->loadSingleResult();
                        $has_active = ($pending_res && $pending_res->pending_count > 0);
                        ?>
                        <div class="stats-card-box">
                            <div class="stats-card-top">
                                <div class="stats-icon-badge stats-badge-active">
                                    <i class="fa fa-truck"></i>
                                </div>
                            </div>
                            <div class="stats-label">Active Order</div>
                            <h2 class="stats-value"><?php echo $has_active ? 'Arriving Today' : 'No active orders'; ?></h2>
                        </div>
                    </div>

                    <!-- Member Details Card -->
                    <div class="profile-dashboard-card">
                        <div class="card-header-row">
                            <div>
                                <h2 class="card-section-title">Member Details</h2>
                                <p class="card-section-desc">Update your account and delivery information</p>
                            </div>
                            <a onclick="switchDashboardSection('profile')" class="card-edit-action">
                                <i class="fa fa-pencil"></i> Edit Profile
                            </a>
                        </div>

                        <div class="member-info-grid">
                            <div class="member-info-col">
                                <label class="input-label">Full Name</label>
                                <div class="member-details-field-box"><?php echo htmlspecialchars($res->FNAME .' '.$res->LNAME); ?></div>
                            </div>
                            <div class="member-info-col">
                                <label class="input-label">Email/Username</label>
                                <div class="member-details-field-box"><?php echo htmlspecialchars($res->CUSUNAME); ?></div>
                            </div>
                        </div>

                        <!-- Dynamic change password toggle form section -->
                        <div id="passwordCollapseSection" style="display: none; background: #f8fafc; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 25px;">
                            <h3 style="font-size: 16px; font-weight: 700; color: #1e293b; margin-top: 0; margin-bottom: 12px;">Change Password</h3>
                            <form action="<?php echo web_root; ?>../backend/customer/controller.php?action=changepassword" method="POST" style="max-width: 320px;"> 
                              <input type="password" class="checkout-input" name="CUSPASS" required placeholder="New Password" style="background: white; margin-bottom: 12px;">
                              <button class="action-btn-solid" type="submit" name="save" style="padding: 8px 16px;">Save Changes</button>
                              <button class="action-btn-outline" type="button" onclick="togglePasswordSection()" style="padding: 8px 16px;">Cancel</button>
                            </form>
                        </div>

                        <div class="dashboard-actions-row">
                            <button class="action-btn-solid" onclick="togglePasswordSection()">
                                <i class="fa fa-lock"></i> Change Password
                            </button>
                            <button class="action-btn-outline" onclick="alert('Notification and mailing preferences panel!')">Preferences</button>
                        </div>
                    </div>

                    <!-- Recent Orders Card -->
                    <div class="profile-dashboard-card">
                        <div class="card-header-row">
                            <div>
                                <h2 class="card-section-title">Recent Orders</h2>
                                <p class="card-section-desc">Recently purchased groceries and household items</p>
                            </div>
                            <div style="display: flex; gap: 10px;">
                                <button class="action-btn-outline" style="padding: 8px; width: 34px; height: 34px; display: flex; align-items: center; justify-content: center;" title="Filter orders"><i class="fa fa-filter"></i></button>
                                <button class="action-btn-outline" style="padding: 8px; width: 34px; height: 34px; display: flex; align-items: center; justify-content: center;" title="Download statement" onclick="alert('Downloading statement!')"><i class="fa fa-download"></i></button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table-order-history">
                                <thead>
                                    <tr>
                                        <th style="text-align: left; padding-left: 0;">Order Reference</th>
                                        <th>Date</th>
                                        <th>Total Amount</th>
                                        <th>Status</th>
                                        <th style="text-align: right; padding-right: 0;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT * FROM `tblsummary` WHERE `CUSTOMERID`=".$_SESSION['CUSID']." ORDER BY `ORDEREDNUM` desc LIMIT 4";
                                    $mydb->setQuery($query);
                                    $recent_orders = $mydb->loadResultList();

                                    if (count($recent_orders) > 0) {
                                        foreach ($recent_orders as $result) {
                                            $statusClass = 'status-pill-pending';
                                            if ($result->ORDEREDSTATS == 'Confirmed' || $result->ORDEREDSTATS == 'Delivered') {
                                                $statusClass = 'status-pill-delivered';
                                            } elseif ($result->ORDEREDSTATS == 'Cancelled') {
                                                $statusClass = 'status-pill-cancelled';
                                            }
                                    ?>
                                            <tr>
                                                <td style="text-align: left; padding-left: 0;">
                                                    <span class="order-reference-badge">#HM-<?php echo $result->ORDEREDNUM; ?></span>
                                                </td>
                                                <td><?php echo date_format(date_create($result->ORDEREDDATE), "M d, Y"); ?></td>
                                                <td style="font-weight: 800; color: #1e3a8a;">₹<?php echo number_format($result->PAYMENT, 2); ?></td>
                                                <td>
                                                    <span class="status-badge-pill <?php echo $statusClass; ?>">
                                                        <span class="status-dot"></span>
                                                        <?php echo $result->ORDEREDSTATS; ?>
                                                    </span>
                                                </td>
                                                <td style="text-align: right; padding-right: 0; display: flex; gap: 6px; justify-content: flex-end; align-items: center;">
                                                    <a href="#" class="orderid table-details-btn" data-id="<?php echo $result->ORDEREDNUM; ?>" data-target="#myOrdered" data-toggle="modal">
                                                        Details
                                                    </a>
                                                    <?php
                                                    // Dynamic tier checks for inline Cancel button
                                                    $canCancelInline = false;
                                                    if (strcasecmp($tier, 'VIP') === 0 || strcasecmp($tier, 'Platinum') === 0) {
                                                        $canCancelInline = in_array($result->ORDEREDSTATS, ['Pending', 'Confirmed', 'Shipped']);
                                                    } elseif (strcasecmp($tier, 'Gold') === 0) {
                                                        $canCancelInline = in_array($result->ORDEREDSTATS, ['Pending', 'Confirmed']);
                                                    } else { // Silver
                                                        $canCancelInline = ($result->ORDEREDSTATS === 'Pending');
                                                    }

                                                    if ($canCancelInline) { ?>
                                                        <a href="#" class="orderid table-details-btn" style="background: #fee2e2; color: #dc2626 !important;" data-id="<?php echo $result->ORDEREDNUM; ?>" data-action="cancel" data-target="#myOrdered" data-toggle="modal">
                                                            Cancel
                                                        </a>
                                                    <?php } ?>
                                                    <?php
                                                    // Dynamic tier checks for inline Return button
                                                    $eligibleStatusForReturnInline = in_array($result->ORDEREDSTATS, ['Confirmed', 'Delivered', 'Shipped']);
                                                    $orderDateInline = date_create($result->ORDEREDDATE);
                                                    $nowInline = date_create(date("Y-m-d H:i:s"));
                                                    $diffInline = date_diff($orderDateInline, $nowInline);
                                                    $daysElapsedInline = $diffInline->days;

                                                    $maxDaysInline = 7;
                                                    if (strcasecmp($tier, 'VIP') === 0 || strcasecmp($tier, 'Platinum') === 0) {
                                                        $maxDaysInline = 30;
                                                    } elseif (strcasecmp($tier, 'Gold') === 0) {
                                                        $maxDaysInline = 15;
                                                    }
                                                    $isWithinReturnWindowInline = ($daysElapsedInline <= $maxDaysInline);

                                                    if ($eligibleStatusForReturnInline && $isWithinReturnWindowInline) { ?>
                                                        <a href="#" class="orderid table-details-btn" style="background: #fffbeb; color: #d97706 !important;" data-id="<?php echo $result->ORDEREDNUM; ?>" data-action="return" data-target="#myOrdered" data-toggle="modal">
                                                            Return
                                                        </a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                    ?>
                                        <tr>
                                            <td colspan="5" style="text-align: center; padding: 40px 0; color: #94a3b8;">
                                                <i class="fa fa-shopping-basket" style="font-size: 32px; margin-bottom: 12px; display: block;"></i>
                                                No recent orders found.
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                        <a onclick="switchDashboardSection('orders')" class="view-all-history-link">View Full Activity History <i class="fa fa-angle-right"></i></a>
                    </div>
                </div>

                <!-- SECTION 2: MY PROFILE UPDATE FORM -->
                <div id="section-profile" class="profile-section-panel" style="display: none;">
                    <div class="profile-dashboard-card">
                        <div class="card-header-row" style="margin-bottom: 20px;">
                            <div>
                                <h2 class="card-section-title">Update Profile Details</h2>
                                <p class="card-section-desc">Keep your delivery details and contact information accurate</p>
                            </div>
                        </div>
                        
                        <!-- Renders the standard signup form completely styled like premium inputs! -->
                        <?php include "signup.php"; ?>
                    </div>
                </div>

                <!-- SECTION 3: FULL ORDER HISTORY -->
                <div id="section-orders" class="profile-section-panel" style="display: none;">
                    <div class="profile-dashboard-card">
                        <div class="card-header-row" style="margin-bottom: 20px;">
                            <div>
                                <h2 class="card-section-title">All Orders Summary</h2>
                                <p class="card-section-desc">Track status and review bills for all previous orders</p>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table-order-history">
                                <thead>
                                    <tr>
                                        <th style="text-align: left; padding-left: 0;">Order Reference</th>
                                        <th>Date</th>
                                        <th>Total Amount</th>
                                        <th>Payment Method</th>
                                        <th>Status</th>
                                        <th>Remarks</th>
                                        <th style="text-align: right; padding-right: 0;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT * FROM `tblsummary` WHERE `CUSTOMERID`=".$_SESSION['CUSID']." ORDER BY `ORDEREDNUM` desc";
                                    $mydb->setQuery($query);
                                    $all_orders = $mydb->loadResultList();

                                    if (count($all_orders) > 0) {
                                        foreach ($all_orders as $result) {
                                            $statusClass = 'status-pill-pending';
                                            if ($result->ORDEREDSTATS == 'Confirmed' || $result->ORDEREDSTATS == 'Delivered') {
                                                $statusClass = 'status-pill-delivered';
                                            } elseif ($result->ORDEREDSTATS == 'Cancelled') {
                                                $statusClass = 'status-pill-cancelled';
                                            }
                                    ?>
                                            <tr>
                                                <td style="text-align: left; padding-left: 0;">
                                                    <span class="order-reference-badge">#HM-<?php echo $result->ORDEREDNUM; ?></span>
                                                </td>
                                                <td><?php echo date_format(date_create($result->ORDEREDDATE), "M d, Y h:i A"); ?></td>
                                                <td style="font-weight: 800; color: #1e3a8a;">₹<?php echo number_format($result->PAYMENT, 2); ?></td>
                                                <td><?php echo $result->PAYMENTMETHOD; ?></td>
                                                <td>
                                                    <span class="status-badge-pill <?php echo $statusClass; ?>">
                                                        <span class="status-dot"></span>
                                                        <?php echo $result->ORDEREDSTATS; ?>
                                                    </span>
                                                </td>
                                                <td style="color: #64748b; font-size: 12px;"><?php echo $result->ORDEREDREMARKS; ?></td>
                                                <td style="text-align: right; padding-right: 0; display: flex; gap: 6px; justify-content: flex-end; align-items: center;">
                                                    <a href="#" class="orderid table-details-btn" data-id="<?php echo $result->ORDEREDNUM; ?>" data-target="#myOrdered" data-toggle="modal">
                                                        Details
                                                    </a>
                                                    <?php
                                                    // Dynamic tier checks for inline Cancel button
                                                    $canCancelInline = false;
                                                    if (strcasecmp($tier, 'VIP') === 0 || strcasecmp($tier, 'Platinum') === 0) {
                                                        $canCancelInline = in_array($result->ORDEREDSTATS, ['Pending', 'Confirmed', 'Shipped']);
                                                    } elseif (strcasecmp($tier, 'Gold') === 0) {
                                                        $canCancelInline = in_array($result->ORDEREDSTATS, ['Pending', 'Confirmed']);
                                                    } else { // Silver
                                                        $canCancelInline = ($result->ORDEREDSTATS === 'Pending');
                                                     }

                                                    if ($canCancelInline) { ?>
                                                        <a href="#" class="orderid table-details-btn" style="background: #fee2e2; color: #dc2626 !important;" data-id="<?php echo $result->ORDEREDNUM; ?>" data-action="cancel" data-target="#myOrdered" data-toggle="modal">
                                                            Cancel
                                                        </a>
                                                    <?php } ?>
                                                    <?php
                                                    // Dynamic tier checks for inline Return button
                                                    $eligibleStatusForReturnInline = in_array($result->ORDEREDSTATS, ['Confirmed', 'Delivered', 'Shipped']);
                                                    $orderDateInline = date_create($result->ORDEREDDATE);
                                                    $nowInline = date_create(date("Y-m-d H:i:s"));
                                                    $diffInline = date_diff($orderDateInline, $nowInline);
                                                    $daysElapsedInline = $diffInline->days;

                                                    $maxDaysInline = 7;
                                                    if (strcasecmp($tier, 'VIP') === 0 || strcasecmp($tier, 'Platinum') === 0) {
                                                        $maxDaysInline = 30;
                                                    } elseif (strcasecmp($tier, 'Gold') === 0) {
                                                        $maxDaysInline = 15;
                                                    }
                                                    $isWithinReturnWindowInline = ($daysElapsedInline <= $maxDaysInline);

                                                    if ($eligibleStatusForReturnInline && $isWithinReturnWindowInline) { ?>
                                                        <a href="#" class="orderid table-details-btn" style="background: #fffbeb; color: #d97706 !important;" data-id="<?php echo $result->ORDEREDNUM; ?>" data-action="return" data-target="#myOrdered" data-toggle="modal">
                                                            Return
                                                        </a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                    ?>
                                        <tr>
                                            <td colspan="7" style="text-align: center; padding: 40px 0; color: #94a3b8;">
                                                No orders found.
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- SECTION 4: MY WISHLIST -->
                <div id="section-wishlist" class="profile-section-panel" style="display: none;">
                    <div class="profile-dashboard-card">
                        <div class="card-header-row" style="margin-bottom: 20px;">
                            <div>
                                <h2 class="card-section-title">My Wishlist</h2>
                                <p class="card-section-desc">Products you've saved for later purchases</p>
                            </div>
                        </div>
                        
                        <?php include "customer/wishlist.php"; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Standard Dynamic Photo Upload Modal -->
<div class="modal fade" id="myModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header" style="background: #1e3a8a; color: white;">
                <button class="close" data-dismiss="modal" type="button" style="color: white; opacity: 0.8;">&times;</button>
                <h4 class="modal-title" id="myModalLabel" style="font-weight: 800;">Choose Image.</h4>
            </div>
            <form action="../backend/customer/controller.php?action=photos" enctype="multipart/form-data" method="post">
                <div class="modal-body" style="padding: 25px;">
                    <div class="form-group">
                        <label style="font-weight: 700; color: #475569; margin-bottom: 8px;">Select a profile picture:</label>
                        <input name="MAX_FILE_SIZE" type="hidden" value="1000000">
                        <input id="photo" name="photo" type="file" required class="form-control" style="padding: 10px;">
                    </div>
                </div>
                <div class="modal-footer" style="background: #f8fafc;">
                    <button class="action-btn-outline" data-dismiss="modal" type="button" style="padding: 8px 16px;">Close</button> 
                    <button class="action-btn-solid" name="savephoto" type="submit" style="padding: 8px 16px;">Upload Photo</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Premium Membership Tier Upgrade/Downgrade Modal -->
<div class="modal fade" id="membershipModal" tabindex="-1" role="dialog" aria-labelledby="membershipModalLabel">
    <div class="modal-dialog" style="width: 50%;">
        <div class="modal-content" style="border-radius: 20px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
            <div class="modal-header" style="background: #1e3a8a; color: white; padding: 20px 25px;">
                <button class="close" data-dismiss="modal" type="button" style="color: white; opacity: 0.8; font-size: 28px;">&times;</button>
                <h4 class="modal-title" id="membershipModalLabel" style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 22px;">H-Mart Membership Suite</h4>
            </div>
            <div class="modal-body" style="padding: 25px; font-family: 'Outfit', sans-serif;">
                <p style="color: #64748b; font-size: 14.5px; margin-bottom: 20px;">
                    Select your membership tier below to instantly upgrade or downgrade. Tier benefits are applied dynamically to all order cancellations, return windows, and refund workflows.
                </p>
                
                <!-- Benefit Matrix Table -->
                <div class="table-responsive" style="margin-bottom: 25px;">
                    <table class="table" style="border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; width: 100%;">
                        <thead>
                            <tr style="background: #f8fafc;">
                                <th style="font-weight: 700; font-size: 13px; padding: 10px;">Benefits</th>
                                <th style="font-weight: 700; font-size: 13px; text-align: center; color: #475569; padding: 10px;">Silver</th>
                                <th style="font-weight: 700; font-size: 13px; text-align: center; color: #b45309; padding: 10px;">Gold</th>
                                <th style="font-weight: 700; font-size: 13px; text-align: center; color: #a21caf; padding: 10px;">VIP</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="font-weight: 600; font-size: 13px; color: #1e293b; padding: 10px;">Cancellation Limit</td>
                                <td style="text-align: center; font-size: 13px; color: #64748b; padding: 10px;">Pending only</td>
                                <td style="text-align: center; font-size: 13px; color: #64748b; padding: 10px;">Pending & Confirmed</td>
                                <td style="text-align: center; font-size: 13px; color: #64748b; padding: 10px;">Pending, Confirmed, & Shipped</td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600; font-size: 13px; color: #1e293b; padding: 10px;">Return Period</td>
                                <td style="text-align: center; font-size: 13px; color: #64748b; padding: 10px;">7 Days</td>
                                <td style="text-align: center; font-size: 13px; color: #64748b; padding: 10px;">15 Days</td>
                                <td style="text-align: center; font-size: 13px; color: #64748b; padding: 10px;">30 Days</td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600; font-size: 13px; color: #1e293b; padding: 10px;">Refund Approval</td>
                                <td style="text-align: center; font-size: 13px; color: #64748b; padding: 10px;">Manual (Standard)</td>
                                <td style="text-align: center; font-size: 13px; color: #64748b; padding: 10px;">Manual (Priority)</td>
                                <td style="text-align: center; font-size: 13px; color: #a21caf; font-weight: 700; padding: 10px;">Instant Auto-Refund</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Update Tier Form -->
                <form action="../backend/customer/controller.php?action=upgrademembership" method="POST">
                    <div class="form-group">
                        <label style="font-weight: 700; color: #475569; margin-bottom: 10px; font-size: 14px;">Select Membership Tier:</label>
                        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                            <label class="radio-inline" style="margin: 0; padding: 10px 15px; border: 1px solid #cbd5e1; border-radius: 8px; flex: 1; min-width: 100px; text-align: center; cursor: pointer; transition: all 0.2s;">
                                <input type="radio" name="membership_tier" value="Silver" <?php echo (strcasecmp($tier, 'Silver') === 0) ? 'checked' : ''; ?> style="margin-top: 2px;">
                                <strong style="display: block; margin-top: 4px; color: #475569;">Silver</strong>
                            </label>
                            <label class="radio-inline" style="margin: 0; padding: 10px 15px; border: 1px solid #cbd5e1; border-radius: 8px; flex: 1; min-width: 100px; text-align: center; cursor: pointer; transition: all 0.2s;">
                                <input type="radio" name="membership_tier" value="Gold" <?php echo (strcasecmp($tier, 'Gold') === 0) ? 'checked' : ''; ?> style="margin-top: 2px;">
                                <strong style="display: block; margin-top: 4px; color: #b45309;">Gold</strong>
                            </label>
                            <label class="radio-inline" style="margin: 0; padding: 10px 15px; border: 1px solid #cbd5e1; border-radius: 8px; flex: 1; min-width: 100px; text-align: center; cursor: pointer; transition: all 0.2s;">
                                <input type="radio" name="membership_tier" value="VIP" <?php echo (strcasecmp($tier, 'VIP') === 0 || strcasecmp($tier, 'Platinum') === 0) ? 'checked' : ''; ?> style="margin-top: 2px;">
                                <strong style="display: block; margin-top: 4px; color: #a21caf;">VIP</strong>
                            </label>
                        </div>
                    </div>
            </div>
            <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 15px 25px;">
                <button class="action-btn-outline" data-dismiss="modal" type="button" style="padding: 8px 16px;">Cancel</button> 
                <button class="action-btn-solid" type="submit" style="padding: 8px 16px;">Update Membership</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    function togglePasswordSection() {
        const pSection = document.getElementById('passwordCollapseSection');
        if (pSection.style.display === 'none') {
            pSection.style.display = 'block';
        } else {
            pSection.style.display = 'none';
        }
    }

    // Switch right side dashboard section panels instantly (SPA routing experience!)
    function switchDashboardSection(sectionId) {
        // Hide all section panels
        const panels = document.querySelectorAll('.profile-section-panel');
        panels.forEach(panel => {
            panel.style.display = 'none';
        });

        // Show targeted section
        const targetPanel = document.getElementById('section-' + sectionId);
        if (targetPanel) {
            targetPanel.style.display = 'block';
        }

        // Update active class on sidebar links
        const navLinks = document.querySelectorAll('.sidebar-nav-link');
        navLinks.forEach(link => {
            link.classList.remove('active');
        });

        const activeLink = document.getElementById('nav-' + sectionId);
        if (activeLink) {
            activeLink.classList.add('active');
        }
        
        // Auto scroll to top of details area smoothly on mobile devices
        if (window.innerWidth < 768) {
            window.scrollTo({
                top: document.querySelector('.col-md-9').offsetTop - 20,
                behavior: 'smooth'
            });
        }
    }

    // Hash check navigation on mount
    document.addEventListener("DOMContentLoaded", function() {
        const hash = window.location.hash;
        if (hash === '#settings') {
            switchDashboardSection('profile');
        } else if (hash === '#home') {
            switchDashboardSection('orders');
        } else if (hash === '#wishlist') {
            switchDashboardSection('wishlist');
        }
    });
</script>