<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $title;?> | H-Mart Admin</title>
<link rel="icon" type="image/svg+xml" href="<?php echo web_root; ?>favicon.svg?v=4">
<link rel="shortcut icon" type="image/svg+xml" href="<?php echo web_root; ?>favicon.svg?v=4">

<!-- Bootstrap Core CSS -->
<link href="<?php echo web_root; ?>admin/css/bootstrap.min.css" rel="stylesheet">
<!-- Custom Fonts -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<!-- DataTables CSS -->
<link href="<?php echo web_root; ?>admin/css/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo web_root; ?>css/mobile-responsive.css" rel="stylesheet">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<script>
    (function() {
        const theme = localStorage.getItem('admin-theme') || 'light';
        if (theme === 'dark') {
            document.documentElement.classList.add('dark-mode');
        }
    })();
</script>

<style>
    :root {
        --primary: #0f172a;
        --primary-light: #1e3a8a;
        --sidebar-bg: #f8fafc;
        --bg-color: #f1f5f9;
        --text-main: #1e293b;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
        --card-bg: #ffffff;
        --card-header-bg: #ffffff;
        --hover-bg: #e2e8f0;
        --table-header-bg: #f8fafc;
        --shadow: 0 4px 6px rgba(0,0,0,0.02);
    }
    .dark-mode {
        --primary: #f8fafc;
        --primary-light: #60a5fa;
        --sidebar-bg: #0f172a;
        --bg-color: #0b0f19;
        --text-main: #cbd5e1;
        --text-muted: #94a3b8;
        --border-color: #1e293b;
        --card-bg: #0f172a;
        --card-header-bg: #1e293b;
        --hover-bg: #1e293b;
        --table-header-bg: #1e293b;
        --shadow: 0 4px 6px rgba(0,0,0,0.20);
    }
    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--bg-color);
        margin: 0;
        padding: 0;
        color: var(--text-main);
        overflow-x: hidden;
    }
    
    /* Layout */
    .admin-layout {
        display: flex;
        height: 100vh;
        overflow: hidden;
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--bg-color);
        margin: 0;
        padding: 0;
        color: var(--text-main);
        overflow-x: hidden;
    }
    
    /* Layout */
    .admin-layout {
        display: flex;
        height: 100vh;
        overflow: hidden;
    }

    /* Sidebar */
    .admin-sidebar {
        width: 260px;
        background-color: var(--sidebar-bg);
        border-right: 1px solid var(--border-color);
        display: flex;
        flex-direction: column;
        flex-shrink: 0;
        z-index: 100;
        transition: margin-left 0.3s ease;
    }
    .admin-layout.sidebar-collapsed .admin-sidebar {
        margin-left: -260px;
    }
    .admin-toggle-btn {
        background: transparent;
        border: none;
        color: var(--text-muted);
        font-size: 18px;
        cursor: pointer;
        padding: 8px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .admin-toggle-btn:hover { background: var(--hover-bg); color: var(--text-main); }
    .admin-layout:not(.sidebar-collapsed) .admin-header .admin-toggle-btn { display: none; }
    .sidebar-brand {
        padding: 30px 25px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .sidebar-brand-logo {
        width: 40px;
        height: 40px;
        flex-shrink: 0;
    }
    .sidebar-brand h2 {
        margin: 0;
        font-size: 22px;
        font-weight: 800;
        color: var(--primary-light);
        letter-spacing: -0.5px;
    }
    .sidebar-brand p {
        margin: 4px 0 0 0;
        font-size: 11px;
        text-transform: uppercase;
        color: var(--text-muted);
        font-weight: 600;
        letter-spacing: 1px;
    }
    .sidebar-menu {
        list-style: none;
        padding: 0 15px;
        margin: 0;
        flex: 1;
        overflow-y: auto;
    }
    .sidebar-menu li {
        margin-bottom: 5px;
    }
    .sidebar-menu a {
        display: flex;
        align-items: center;
        padding: 12px 15px;
        color: var(--text-main);
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    .sidebar-menu a i {
        width: 24px;
        font-size: 16px;
        color: var(--text-muted);
        transition: color 0.2s ease;
    }
    .sidebar-menu a:hover {
        background-color: var(--hover-bg);
    }
    .sidebar-menu a.active {
        background-color: var(--primary-light);
        color: white;
    }
    .sidebar-menu a.active i {
        color: white;
    }
    
    .sidebar-user {
        padding: 20px;
        border-top: 1px solid var(--border-color);
        margin: 15px;
        background: var(--hover-bg);
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        text-decoration: none;
    }
    .sidebar-user img {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
    }
    .sidebar-user-info {
        display: flex;
        flex-direction: column;
    }
    .sidebar-user-info .name {
        font-size: 13px;
        font-weight: 700;
        color: var(--text-main);
    }
    .sidebar-user-info .role {
        font-size: 11px;
        color: var(--text-muted);
    }

    /* Main Area */
    .admin-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    
    /* Header */
    .admin-header {
        height: 70px;
        background: var(--card-bg);
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 30px;
        flex-shrink: 0;
    }
    .header-search {
        display: flex;
        align-items: center;
        background: var(--bg-color);
        padding: 10px 15px;
        border-radius: 20px;
        width: 400px;
    }
    .header-search i {
        color: var(--text-muted);
        margin-right: 10px;
    }
    .header-search input {
        border: none;
        background: transparent;
        width: 100%;
        outline: none;
        font-size: 13px;
        color: var(--text-main);
    }
    .header-actions {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .header-icon {
        color: var(--text-muted);
        font-size: 18px;
        cursor: pointer;
        position: relative;
    }
    .header-icon .badge {
        position: absolute;
        top: -5px;
        right: -8px;
        background: #ef4444;
        color: white;
        font-size: 10px;
        padding: 2px 5px;
        border-radius: 10px;
    }
    .exit-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 15px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        color: var(--text-main);
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s;
    }
    .exit-btn:hover {
        background: var(--bg-color);
        text-decoration: none;
        color: var(--text-main);
    }

    /* Content Area */
    .admin-content-wrapper {
        flex: 1;
        overflow-y: auto;
        padding: 30px;
    }
    
    /* Bootstrap Overrides for Admin Content */
    .admin-content-wrapper .panel {
        border: 1px solid var(--border-color);
        border-radius: 12px;
        box-shadow: var(--shadow);
        background: var(--card-bg);
        overflow: hidden;
    }
    .admin-content-wrapper .panel-default > .panel-heading {
        background: var(--card-header-bg);
        border-bottom: 1px solid var(--border-color);
        padding: 15px 20px;
        font-weight: 700;
        color: var(--primary);
        font-size: 16px;
    }
    .admin-content-wrapper .table {
        margin-bottom: 0;
    }
    .admin-content-wrapper .table th {
        border-bottom-width: 1px;
        color: var(--text-muted);
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        padding: 15px;
    }
    .admin-content-wrapper .table td {
        vertical-align: middle;
        padding: 15px;
        font-size: 14px;
        border-color: var(--border-color);
    }
    .admin-content-wrapper .btn {
        border-radius: 6px;
        font-weight: 500;
        font-size: 13px;
        padding: 6px 12px;
    }
    .admin-content-wrapper .btn-primary {
        background: var(--primary-light);
        border-color: var(--primary-light);
    }
    .page-header {
        margin-top: 0;
        border-bottom: none;
        font-weight: 800;
        color: var(--primary);
        font-size: 28px;
    }

    /* Dark Mode Global Styles Overrides */
    .dark-mode body {
        background-color: var(--bg-color);
        color: var(--text-main);
    }
    .dark-mode .admin-sidebar {
        background-color: var(--sidebar-bg);
    }
    .dark-mode .cus-table-card,
    .dark-mode .settings-table-card,
    .dark-mode .delivery-card,
    .dark-mode .discount-card,
    .dark-mode .panel,
    .dark-mode .well,
    .dark-mode .modal-content {
        background-color: var(--card-bg) !important;
        border-color: var(--border-color) !important;
        box-shadow: var(--shadow) !important;
    }
    .dark-mode .cus-table-header,
    .dark-mode .discount-card-title,
    .dark-mode .panel-heading,
    .dark-mode .modal-header {
        background-color: var(--card-header-bg) !important;
        border-bottom-color: var(--border-color) !important;
        color: var(--primary) !important;
    }
    .dark-mode .modal-footer {
        background-color: var(--card-header-bg) !important;
        border-top-color: var(--border-color) !important;
    }
    .dark-mode .customers-table tbody tr,
    .dark-mode .settings-table tbody tr,
    .dark-mode .table tbody tr {
        border-bottom-color: var(--border-color) !important;
        background-color: var(--card-bg);
    }
    .dark-mode .customers-table tbody tr:hover,
    .dark-mode .settings-table tbody tr:hover,
    .dark-mode .table tbody tr:hover {
        background-color: var(--hover-bg) !important;
    }
    .dark-mode .customers-table thead th,
    .dark-mode .settings-table thead th,
    .dark-mode .table thead th {
        background-color: var(--table-header-bg) !important;
        border-bottom-color: var(--border-color) !important;
        color: var(--text-muted) !important;
    }
    .dark-mode .cus-name,
    .dark-mode .product-name-cell,
    .dark-mode .price-cell,
    .dark-mode .discount-card-title,
    .dark-mode .discount-info-list li strong,
    .dark-mode label,
    .dark-mode h1, .dark-mode h2, .dark-mode h3, .dark-mode h4, .dark-mode h5, .dark-mode h6 {
        color: var(--primary) !important;
    }
    .dark-mode .customers-table tbody td,
    .dark-mode .settings-table tbody td,
    .dark-mode .table tbody td,
    .dark-mode .discount-info-list li,
    .dark-mode p {
        color: var(--text-main) !important;
    }
    .dark-mode input[type="text"],
    .dark-mode input[type="password"],
    .dark-mode input[type="email"],
    .dark-mode input[type="number"],
    .dark-mode select,
    .dark-mode textarea {
        background-color: var(--bg-color) !important;
        color: var(--text-main) !important;
        border-color: var(--border-color) !important;
    }
    .dark-mode input[readonly] {
        background-color: var(--hover-bg) !important;
        opacity: 0.8;
    }
    .dark-mode .discount-details-grid {
        background-color: var(--table-header-bg) !important;
        border-color: var(--border-color) !important;
    }
    .dark-mode .discount-image-wrapper {
        background-color: #ffffff !important;
        border-color: var(--border-color) !important;
    }
    .dark-mode .btn-cancel-hmart,
    .dark-mode .btn-default {
        background-color: var(--table-header-bg) !important;
        color: var(--text-main) !important;
        border-color: var(--border-color) !important;
    }
    .dark-mode .btn-cancel-hmart:hover,
    .dark-mode .btn-default:hover {
        background-color: var(--hover-bg) !important;
    }
    .dark-mode .settings-tabs {
        border-bottom-color: var(--border-color) !important;
    }
    .dark-mode .settings-tab-btn.active {
        color: var(--primary-light) !important;
        border-bottom-color: var(--primary-light) !important;
    }
    .dark-mode .settings-search-bar {
        background-color: var(--table-header-bg) !important;
        border-color: var(--border-color) !important;
    }
    .dark-mode .settings-search-bar input {
        color: var(--text-main) !important;
    }
    .dark-mode #searchDropdown {
        background-color: var(--card-bg) !important;
        border-color: var(--border-color) !important;
        box-shadow: 0 8px 24px rgba(0,0,0,0.40) !important;
    }
    
    /* Global Product Search classes */
    .search-header {
        padding: 10px 15px;
        font-size: 11px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        border-bottom: 1px solid var(--border-color);
        background: var(--table-header-bg);
    }
    .search-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 15px;
        text-decoration: none;
        color: var(--text-main);
        border-bottom: 1px solid var(--border-color);
        transition: background 0.15s;
    }
    .search-item:hover {
        background: var(--hover-bg);
        text-decoration: none;
        color: var(--text-main);
    }
    .search-item img {
        width: 36px;
        height: 36px;
        border-radius: 6px;
        object-fit: cover;
        border: 1px solid var(--border-color);
    }
    .search-item-info {
        flex: 1;
        min-width: 0;
    }
    .search-item-name {
        font-weight: 600;
        font-size: 13px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: var(--text-main);
    }
    .search-item-cat {
        font-size: 11px;
        color: var(--text-muted);
    }
    .search-item-price {
        font-weight: 700;
        font-size: 13px;
        color: var(--primary-light);
    }
    .dark-mode .sidebar-menu a.active {
        background-color: var(--primary-light) !important;
        color: #0f172a !important;
    }
    .dark-mode .sidebar-menu a.active i {
        color: #0f172a !important;
    }
    .dark-mode .sidebar-user-info .name {
        color: var(--text-main) !important;
    }
    
    /* ── Sweep all remaining hardcoded white card backgrounds ── */
    .dark-mode .kpi-card,
    .dark-mode .chart-box,
    .dark-mode .table-card,
    .dark-mode .inv-stat-card,
    .dark-mode .inv-chart-box,
    .dark-mode .inv-table-wrap,
    .dark-mode .inv-table-wrap-scroll,
    .dark-mode .fraud-stat-card,
    .dark-mode .fraud-table-wrap,
    .dark-mode .report-card,
    .dark-mode .edit-card,
    .dark-mode .btn-export,
    .dark-mode .order-card {
        background: var(--card-bg) !important;
        border-color: var(--border-color) !important;
        color: var(--text-main) !important;
    }
    /* Sidebar body background */
    .dark-mode body,
    .dark-mode .admin-content-wrapper {
        background-color: var(--bg-color) !important;
        color: var(--text-main) !important;
    }
    /* ═══ GLOBAL TABLE DARK MODE — ALL PAGES ═══
       Bootstrap sets background on <tr> and <td> directly.
       We must target cells (td/th) not just rows to win the specificity battle. */

    /* All table cells — even rows (default Bootstrap = white) */
    .dark-mode .table > tbody > tr > td,
    .dark-mode .table > tbody > tr > th,
    .dark-mode .table > thead > tr > th,
    .dark-mode .table > thead > tr > td,
    .dark-mode .table > tfoot > tr > td,
    .dark-mode .table > tfoot > tr > th {
        background-color: var(--card-bg) !important;
        color: var(--text-main) !important;
        border-color: var(--border-color) !important;
    }

    /* Odd rows — slightly lighter for zebra effect */
    .dark-mode .table-striped > tbody > tr:nth-of-type(odd) > td,
    .dark-mode .table-striped > tbody > tr:nth-of-type(odd) > th {
        background-color: var(--table-header-bg) !important;
    }

    /* Hover row */
    .dark-mode .table-hover > tbody > tr:hover > td,
    .dark-mode .table-hover > tbody > tr:hover > th {
        background-color: var(--hover-bg) !important;
    }

    /* Table borders */
    .dark-mode .table-bordered,
    .dark-mode .table-bordered > thead > tr > th,
    .dark-mode .table-bordered > thead > tr > td,
    .dark-mode .table-bordered > tbody > tr > th,
    .dark-mode .table-bordered > tbody > tr > td,
    .dark-mode .table-bordered > tfoot > tr > th,
    .dark-mode .table-bordered > tfoot > tr > td {
        border-color: var(--border-color) !important;
    }

    /* DataTables wrapper — search, length, info labels */
    .dark-mode .dataTables_wrapper .dataTables_length label,
    .dark-mode .dataTables_wrapper .dataTables_filter label,
    .dark-mode .dataTables_wrapper .dataTables_info,
    .dark-mode .dataTables_wrapper .dataTables_processing {
        color: var(--text-muted) !important;
    }
    .dark-mode .dataTables_wrapper .dataTables_length select,
    .dark-mode .dataTables_wrapper .dataTables_filter input {
        background: var(--bg-color) !important;
        color: var(--text-main) !important;
        border-color: var(--border-color) !important;
        border-radius: 6px;
    }

    /* DataTables pagination buttons */
    .dark-mode .dataTables_wrapper .dataTables_paginate .paginate_button {
        background: var(--card-bg) !important;
        color: var(--text-main) !important;
        border-color: var(--border-color) !important;
    }
    .dark-mode .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: var(--hover-bg) !important;
        color: var(--text-main) !important;
        border-color: var(--border-color) !important;
    }
    .dark-mode .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dark-mode .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: var(--primary-light) !important;
        color: #fff !important;
        border-color: var(--primary-light) !important;
    }
    .dark-mode .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
    .dark-mode .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
        color: var(--text-muted) !important;
        background: var(--card-bg) !important;
    }

    /* Bootstrap panels catch-all */
    .dark-mode .panel,
    .dark-mode .panel-body,
    .dark-mode .panel-heading,
    .dark-mode .panel-footer {
        background: var(--card-bg) !important;
        color: var(--text-main) !important;
        border-color: var(--border-color) !important;
    }

    /* Bootstrap well */
    .dark-mode .well {
        background: var(--card-bg) !important;
        border-color: var(--border-color) !important;
        color: var(--text-main) !important;
    }

    /* Bootstrap modal content */
    .dark-mode .modal-content {
        background: var(--card-bg) !important;
        color: var(--text-main) !important;
        border-color: var(--border-color) !important;
    }
    .dark-mode .modal-header,
    .dark-mode .modal-footer {
        background: var(--card-header-bg) !important;
        border-color: var(--border-color) !important;
    }
    .dark-mode .modal-title {
        color: var(--text-main) !important;
    }

    /* Bootstrap form controls inside dark modal/page */
    .dark-mode .form-control {
        background: var(--bg-color) !important;
        color: var(--text-main) !important;
        border-color: var(--border-color) !important;
    }
    .dark-mode .form-control:focus {
        background: var(--card-bg) !important;
        color: var(--text-main) !important;
        border-color: var(--primary-light) !important;
        box-shadow: 0 0 0 3px rgba(96,165,250,0.15) !important;
    }

    /* Bootstrap dropdown menus */
    .dark-mode .dropdown-menu {
        background: var(--card-bg) !important;
        border-color: var(--border-color) !important;
    }
    .dark-mode .dropdown-menu > li > a {
        color: var(--text-main) !important;
    }
    .dark-mode .dropdown-menu > li > a:hover {
        background: var(--hover-bg) !important;
        color: var(--text-main) !important;
    }

    /* Bootstrap nav tabs */
    .dark-mode .nav-tabs > li > a {
        color: var(--text-muted) !important;
        border-color: var(--border-color) !important;
    }
    .dark-mode .nav-tabs > li.active > a,
    .dark-mode .nav-tabs > li.active > a:hover,
    .dark-mode .nav-tabs > li.active > a:focus {
        background: var(--card-bg) !important;
        color: var(--text-main) !important;
        border-color: var(--border-color) !important;
        border-bottom-color: var(--card-bg) !important;
    }
    .dark-mode .tab-content {
        background: var(--card-bg) !important;
        color: var(--text-main) !important;
        border-color: var(--border-color) !important;
    }
    /* Toggle button hover style */
    #admin-dark-toggle {
        cursor: pointer;
        padding: 6px;
        border-radius: 8px;
        transition: background 0.2s;
    }
    #admin-dark-toggle:hover {
        background: var(--hover-bg);
    }
    #admin-dark-toggle i {
        font-size: 18px;
        color: var(--text-muted);
    }
</style>
</head>

<?php
admin_confirm_logged_in();

$query = "SELECT * FROM tblsummary WHERE ORDEREDSTATS = 'Pending'";
$mydb->setQuery($query);
$cur = $mydb->executeQuery();
$rowscount = $mydb->num_rows($cur);
$res = isset($rowscount)? $rowscount : 0;

$order_badge = '';
if($res > 0){
    $order_badge = '<span class="badge" style="background:#ef4444; margin-left:auto;">'.$res.'</span>';
}

$user = New User();
$singleuser = $user->single_user($_SESSION['USERID']);
$user_image = web_root.'admin/user/'.($singleuser->USERIMAGE ? $singleuser->USERIMAGE : 'photos/default.png');
$current_page = basename(dirname($_SERVER['PHP_SELF']));
?>
      
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-brand" style="justify-content: space-between;">
                <div style="display: flex; gap: 12px; align-items: center;">
                    <img src="<?php echo web_root; ?>favicon.svg?v=4" class="sidebar-brand-logo" alt="H-Mart">
                    <div>
                        <h2>H-Mart Admin</h2>
                        <p>Management Suite</p>
                    </div>
                </div>
                <button class="admin-toggle-btn" onclick="toggleAdminSidebar()" title="Toggle Sidebar">
                    <i class="fa fa-navicon"></i>
                </button>
            </div>
            
            <ul class="sidebar-menu">
                <li class="menu-label" style="padding: 10px 15px 5px; font-size: 10px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.8px;">Core</li>
                <li>
                    <a href="<?php echo web_root; ?>admin/dashboard/index.php" class="<?php echo ($current_page == 'dashboard') ? 'active' : ''; ?>">
                        <i class="fa fa-dashboard"></i> Analytics
                    </a>
                </li>
                <li>
                    <a href="<?php echo web_root; ?>admin/inventory/index.php" class="<?php echo ($current_page == 'inventory') ? 'active' : ''; ?>">
                        <i class="fa fa-cubes"></i> Inventory AI
                    </a>
                </li>
                <li>
                    <a href="<?php echo web_root; ?>admin/fraud/index.php" class="<?php echo ($current_page == 'fraud') ? 'active' : ''; ?>">
                        <i class="fa fa-shield"></i> Fraud Detection
                    </a>
                </li>
                <li>
                    <a href="<?php echo web_root; ?>admin/products/index.php" class="<?php echo ($current_page == 'products') ? 'active' : ''; ?>">
                        <i class="fa fa-archive"></i> Products
                    </a>
                </li>
                <li>
                    <a href="<?php echo web_root; ?>admin/orders/index.php" class="<?php echo ($current_page == 'orders') ? 'active' : ''; ?>">
                        <i class="fa fa-shopping-cart"></i> Orders <?php echo $order_badge; ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo web_root; ?>admin/category/index.php" class="<?php echo ($current_page == 'category') ? 'active' : ''; ?>">
                        <i class="fa fa-tags"></i> Categories
                    </a>
                </li>
                
                <li class="menu-label" style="padding: 10px 15px 5px; font-size: 10px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.8px;">AI Modules</li>
                <li>
                    <a href="<?php echo web_root; ?>admin/demand_forecasting/index.php" class="<?php echo ($current_page == 'demand_forecasting') ? 'active' : ''; ?>">
                        <i class="fa fa-line-chart"></i> Demand Forecast
                    </a>
                </li>
                <li>
                    <a href="<?php echo web_root; ?>admin/churn_prediction/index.php" class="<?php echo ($current_page == 'churn_prediction') ? 'active' : ''; ?>">
                        <i class="fa fa-frown-o"></i> Churn Prediction
                    </a>
                </li>
                <li>
                    <a href="<?php echo web_root; ?>admin/sentiment_analysis/index.php" class="<?php echo ($current_page == 'sentiment_analysis') ? 'active' : ''; ?>">
                        <i class="fa fa-comments-o"></i> Review Sentiments
                    </a>
                </li>
                <li>
                    <a href="<?php echo web_root; ?>admin/recommendations/index.php" class="<?php echo ($current_page == 'recommendations') ? 'active' : ''; ?>">
                        <i class="fa fa-heart-o"></i> Recommendations
                    </a>
                </li>
                <li>
                    <a href="<?php echo web_root; ?>admin/dynamic_pricing/index.php" class="<?php echo ($current_page == 'dynamic_pricing') ? 'active' : ''; ?>">
                        <i class="fa fa-money"></i> Dynamic Pricing
                    </a>
                </li>

                <li class="menu-label" style="padding: 10px 15px 5px; font-size: 10px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.8px;">Operations</li>
                <li>
                    <a href="<?php echo web_root; ?>admin/returns_refunds/index.php" class="<?php echo ($current_page == 'returns_refunds') ? 'active' : ''; ?>">
                        <i class="fa fa-reply"></i> Returns & Refunds
                    </a>
                </li>
                <li>
                    <a href="<?php echo web_root; ?>admin/coupons/index.php" class="<?php echo ($current_page == 'coupons') ? 'active' : ''; ?>">
                        <i class="fa fa-gift"></i> Coupon Manager
                    </a>
                </li>
                <li>
                    <a href="<?php echo web_root; ?>admin/shipping/index.php" class="<?php echo ($current_page == 'shipping') ? 'active' : ''; ?>">
                        <i class="fa fa-truck"></i> Shipping Tracker
                    </a>
                </li>
                <li>
                    <a href="<?php echo web_root; ?>admin/vendors/index.php" class="<?php echo ($current_page == 'vendors') ? 'active' : ''; ?>">
                        <i class="fa fa-briefcase"></i> Vendor Directory
                    </a>
                </li>
                <li>
                    <a href="<?php echo web_root; ?>admin/stock_alerts/index.php" class="<?php echo ($current_page == 'stock_alerts') ? 'active' : ''; ?>">
                        <i class="fa fa-exclamation-triangle"></i> Stock Alerts
                    </a>
                </li>
                <li>
                    <a href="<?php echo web_root; ?>admin/email_marketing/index.php" class="<?php echo ($current_page == 'email_marketing') ? 'active' : ''; ?>">
                        <i class="fa fa-envelope-o"></i> Email Marketing
                    </a>
                </li>
                <li>
                    <a href="<?php echo web_root; ?>admin/push_notifications/index.php" class="<?php echo ($current_page == 'push_notifications') ? 'active' : ''; ?>">
                        <i class="fa fa-bell-o"></i> Push Notifications
                    </a>
                </li>
                <li>
                    <a href="<?php echo web_root; ?>admin/sms_alerts/index.php" class="<?php echo ($current_page == 'sms_alerts') ? 'active' : ''; ?>">
                        <i class="fa fa-commenting-o"></i> SMS Alerts
                    </a>
                </li>
                <li>
                    <a href="<?php echo web_root; ?>admin/multi_language/index.php" class="<?php echo ($current_page == 'multi_language') ? 'active' : ''; ?>">
                        <i class="fa fa-language"></i> Multi-Language
                    </a>
                </li>
                <li>
                    <a href="<?php echo web_root; ?>admin/multi_currency/index.php" class="<?php echo ($current_page == 'multi_currency') ? 'active' : ''; ?>">
                        <i class="fa fa-usd"></i> Multi-Currency
                    </a>
                </li>
                <li>
                    <a href="<?php echo web_root; ?>admin/support_tickets/index.php" class="<?php echo ($current_page == 'support_tickets') ? 'active' : ''; ?>">
                        <i class="fa fa-ticket"></i> Support Tickets
                    </a>
                </li>

                <?php if ($_SESSION['U_ROLE']=='Administrator') { ?>
                <li class="menu-label" style="padding: 10px 15px 5px; font-size: 10px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.8px;">System</li>
                <li>
                    <a href="<?php echo web_root; ?>admin/customer/index.php" class="<?php echo ($current_page == 'customer') ? 'active' : ''; ?>">
                        <i class="fa fa-users"></i> Customers
                    </a>
                </li>
                <li>
                    <a href="<?php echo web_root; ?>admin/user/index.php" class="<?php echo ($current_page == 'user') ? 'active' : ''; ?>">
                        <i class="fa fa-user"></i> Users
                    </a>
                </li>
                <li>
                    <a href="<?php echo web_root; ?>admin/report/index.php" class="<?php echo ($current_page == 'report') ? 'active' : ''; ?>">
                        <i class="fa fa-bar-chart"></i> Reports
                    </a>
                </li>
                <li>
                    <a href="<?php echo web_root; ?>admin/settings/index.php" class="<?php echo ($current_page == 'settings') ? 'active' : ''; ?>">
                        <i class="fa fa-cog"></i> Settings
                    </a>
                </li>
                <li>
                    <a href="<?php echo web_root; ?>admin/audit_logs/index.php" class="<?php echo ($current_page == 'audit_logs') ? 'active' : ''; ?>">
                        <i class="fa fa-history"></i> Audit Trail
                    </a>
                </li>
                <li>
                    <a href="<?php echo web_root; ?>admin/backup_manager/index.php" class="<?php echo ($current_page == 'backup_manager') ? 'active' : ''; ?>">
                        <i class="fa fa-database"></i> Backup Manager
                    </a>
                </li>
                <li>
                    <a href="<?php echo web_root; ?>admin/system_health/index.php" class="<?php echo ($current_page == 'system_health') ? 'active' : ''; ?>">
                        <i class="fa fa-heartbeat"></i> System Health
                    </a>
                </li>
                <li>
                    <a href="<?php echo web_root; ?>admin/site_manager/index.php" class="<?php echo ($current_page == 'site_manager') ? 'active' : ''; ?>">
                        <i class="fa fa-globe"></i> Site Settings
                    </a>
                </li>
                <?php } ?>
            </ul>

            <a href="<?php echo web_root; ?>admin/user/index.php?view=edit&id=<?php echo $_SESSION['USERID']; ?>" class="sidebar-user">
                <img src="<?php echo $user_image; ?>" alt="Admin Profile" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" style="width:38px; height:38px; border-radius:50%; object-fit:cover; flex-shrink:0;">
                <div style="display:none; width:38px; height:38px; border-radius:50%; background:#1e3a8a; align-items:center; justify-content:center; flex-shrink:0;">
                    <i class="fa fa-user" style="color:#fff; font-size:16px;"></i>
                </div>
                <div class="sidebar-user-info">
                    <span class="name"><?php echo $_SESSION['U_NAME']; ?></span>
                    <span class="role"><?php echo $_SESSION['U_ROLE']; ?></span>
                </div>
            </a>
        </aside>

        <!-- Main Area -->
        <div class="admin-main">
            <!-- Header -->
            <header class="admin-header">
                <div class="header-search" style="position:relative; gap: 15px;">
                    <button class="admin-toggle-btn" onclick="toggleAdminSidebar()" title="Toggle Sidebar" style="margin-right: 5px;">
                        <i class="fa fa-navicon"></i>
                    </button>
                    <i class="fa fa-search" style="position: relative; margin-left: 5px;"></i>
                    <input type="text" id="globalSearch" placeholder="Search products, orders, or users..." 
                           autocomplete="off" oninput="globalSearchHandler(this.value)">
                    <div id="searchDropdown" style="
                        display:none; position:absolute; top:46px; left:0; width:420px;
                        background:#fff; border:1px solid #e2e8f0; border-radius:12px;
                        box-shadow:0 8px 24px rgba(0,0,0,0.10); z-index:9999; overflow:hidden;
                        font-family:'Inter',sans-serif;">
                    </div>
                </div>
                
                <div class="header-actions">
                    <div class="header-icon" id="admin-dark-toggle" title="Toggle Dark/Light Mode" style="margin-right: 5px;">
                        <i class="fa fa-moon-o" id="admin-dark-icon"></i>
                    </div>
                    <div class="header-icon" id="bell-dropdown-toggle" style="position:relative; cursor:pointer;">
                        <i class="fa fa-bell-o"></i>
                        <span class="badge" id="notification-badge" style="display:<?php echo ($res > 0) ? 'inline-block' : 'none'; ?>;"><?php echo $res; ?></span>
                        <div id="notificationDropdown" style="
                            display:none; position:absolute; top:46px; right:0; width:340px;
                            background:#fff; border:1px solid #e2e8f0; border-radius:12px;
                            box-shadow:0 8px 24px rgba(0,0,0,0.15); z-index:9999; overflow:hidden;
                            font-family:'Inter',sans-serif; text-align:left; cursor:default;">
                        </div>
                    </div>
                    <div class="header-icon">
                        <i class="fa fa-question-circle-o"></i>
                    </div>
                    
                    <a href="<?php echo web_root; ?>admin/logout.php" class="exit-btn">
                        <i class="fa fa-sign-out"></i> Exit
                    </a>
                </div>
            </header>

            <!-- Content -->
            <div class="admin-content-wrapper">
                <?php 
                // Unified modern page-title system implemented directly inside individual pages for absolute style consistency and no duplication.
                ?>
                
                <?php check_message(); ?> 

                <?php require_once $content; ?>
            </div>
    </div>
</div>

<!-- Mobile Sidebar Overlay -->
<div class="admin-sidebar-overlay" onclick="closeAdminSidebar()"></div>

<script>
function toggleAdminSidebar() {
    const sidebar = document.querySelector('.admin-sidebar');
    const overlay = document.querySelector('.admin-sidebar-overlay');
    const layout = document.querySelector('.admin-layout');
    if (window.innerWidth <= 768) {
        sidebar.classList.toggle('open');
        overlay.classList.toggle('open');
        document.body.style.overflow = sidebar.classList.contains('open') ? 'hidden' : '';
    } else {
        layout.classList.toggle('sidebar-collapsed');
    }
}
function closeAdminSidebar() {
    document.querySelector('.admin-sidebar').classList.remove('open');
    document.querySelector('.admin-sidebar-overlay').classList.remove('open');
    document.body.style.overflow = '';
}
</script>

<!-- Modal Profile Image -->
    <div class="modal fade" id="usermodal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" data-dismiss="modal" type="button">×</button>
                    <h4 class="modal-title" id="myModalLabel">Profile Image.</h4>
                </div>
                <form action="<?php echo web_root; ?>admin/user/controller.php?action=photos" enctype="multipart/form-data" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="rows">
                                <div class="col-md-12">
                                    <div class="rows">
                                        <img title="profile image" width="500" height="360" src="<?php echo $user_image; ?>">  
                                    </div>
                                </div><br/>
                                <div class="col-md-12">
                                    <div class="rows">
                                        <div class="col-md-8">
                                            <input type="hidden" name="MIDNO" id="MIDNO" value="<?php echo $_SESSION['USERID']; ?>">
                                            <input name="MAX_FILE_SIZE" type="hidden" value="1000000"> 
                                            <input id="photo" name="photo" type="file">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" data-dismiss="modal" type="button">Close</button> 
                        <button class="btn btn-primary" name="savephoto" type="submit">Upload Photo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<!-- jQuery -->
<script src="<?php echo web_root; ?>admin/jquery/jquery.min.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="<?php echo web_root; ?>admin/js/bootstrap.min.js"></script>
<!-- DataTables JavaScript -->
<script src="<?php echo web_root; ?>admin/js/jquery.dataTables.min.js"></script>
<script src="<?php echo web_root; ?>admin/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript" language="javascript" src="<?php echo web_root; ?>admin/js/janobe.js"></script> 
<script type="text/javascript">
$(document).on("click", ".PROID", function () {
    var proid = $(this).data('id')
    $(".modal-body #proid").val( proid )
});
</script>

<!-- Initialize DataTables and Global Search -->
<script>
function globalSearchHandler(val) {
    var dropdown = document.getElementById('searchDropdown');
    if (!val || val.trim().length === 0) {
        dropdown.style.display = 'none';
        dropdown.innerHTML = '';
        return;
    }
    
    var url = '<?php echo web_root; ?>admin/ajax_search.php?q=' + encodeURIComponent(val);
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                dropdown.innerHTML = '<div style="padding:15px; text-align:center; color:#64748b; font-size:13px;">No results found</div>';
                dropdown.style.display = 'block';
                return;
            }
            
            var html = '<div class="search-header" style="padding: 10px 15px; background: #f8fafc; font-weight: 700; font-size: 11px; text-transform: uppercase; color: #64748b;">Matching Results</div>';
            data.forEach(item => {
                html += '<a href="' + item.url + '" class="search-item" style="display:flex; align-items:center; padding: 12px 15px; border-bottom: 1px solid #e2e8f0; text-decoration:none; color: #1e293b;">';
                html += '<i class="fa ' + item.icon + '" style="font-size: 16px; color: #64748b; margin-right: 12px; width: 20px; text-align:center;"></i>';
                html += '<div class="search-item-info" style="flex:1;">';
                html += '  <div class="search-item-name" style="font-size: 13px; font-weight: 600;">' + item.title + '</div>';
                html += '  <div class="search-item-cat" style="font-size: 11px; color: #64748b;">' + item.type + '</div>';
                html += '</div>';
                html += '</a>';
            });
            
            dropdown.innerHTML = html;
            dropdown.style.display = 'block';
        })
        .catch(error => {
            console.error('Error fetching search results:', error);
        });
}

// Polling for Notifications
function fetchNotifications() {
    fetch('<?php echo web_root; ?>admin/ajax_notifications.php')
        .then(response => response.json())
        .then(data => {
            const badge = document.getElementById('notification-badge');
            const dropdown = document.getElementById('notificationDropdown');
            if (data.count > 0) {
                badge.style.display = 'inline-block';
                badge.innerText = data.count;
            } else {
                badge.style.display = 'none';
            }
            
            if (data.count === 0) {
                dropdown.innerHTML = '<div style="padding:15px; text-align:center; color:#64748b; font-size:13px;">No new notifications</div>';
            } else {
                let html = '<div class="search-header" style="padding: 10px 15px; background: #f8fafc; font-weight: 700; font-size: 11px; text-transform: uppercase; color: #64748b; border-bottom: 1px solid #e2e8f0;">Recent Alerts</div>';
                data.items.forEach(item => {
                    let color = item.type === "stock" ? "#ef4444" : "#3b82f6";
                    html += '<a href="' + item.url + '" style="display:flex; align-items:flex-start; padding: 12px 15px; border-bottom: 1px solid #e2e8f0; text-decoration:none; color: #1e293b;">';
                    html += '<i class="fa ' + item.icon + '" style="font-size: 14px; color: ' + color + '; margin-top:3px; margin-right: 12px; width: 16px; text-align:center;"></i>';
                    html += '<div style="flex:1;">';
                    html += '  <div style="font-size: 13px; font-weight: 600; margin-bottom:2px;">' + item.title + '</div>';
                    html += '  <div style="font-size: 12px; color: #64748b;">' + item.message + '</div>';
                    html += '</div>';
                    html += '</a>';
                });
                dropdown.innerHTML = html;
            }
        });
}

// Start polling every 30 seconds
setInterval(fetchNotifications, 30000);
fetchNotifications();

document.getElementById('bell-dropdown-toggle').addEventListener('click', function(e) {
    e.stopPropagation();
    var dropdown = document.getElementById('notificationDropdown');
    dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
});

// Close search dropdown on click outside
document.addEventListener('click', function(e) {
    var searchInput = document.getElementById('globalSearch');
    var dropdown = document.getElementById('searchDropdown');
    if (searchInput && dropdown && e.target !== searchInput && e.target !== dropdown && !dropdown.contains(e.target)) {
        dropdown.style.display = 'none';
    }
});

// toggleAdminSidebar is now defined in the head script for mobile support

$(document).ready(function() {
    if ($('#dash-table').length) {
        $('#dash-table').DataTable();
    }
    
    if ($('#date_picker').length) {
        $('#date_picker').datetimepicker({
            format: 'mm/dd/yyyy',
            language:  'en',
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            minView: 2,
            forceParse: 0,
            changeMonth: true,
            changeYear: true,
            yearRange: '1945:'+(new Date).getFullYear() 
        });
    }
    
    // Admin Dark/Light Mode Switcher Logic
    const toggleBtn = document.getElementById('admin-dark-toggle');
    const toggleIcon = document.getElementById('admin-dark-icon');
    if (toggleBtn && toggleIcon) {
        if (document.documentElement.classList.contains('dark-mode')) {
            toggleIcon.classList.remove('fa-moon-o');
            toggleIcon.classList.add('fa-sun-o');
        }
        toggleBtn.addEventListener('click', function() {
            const isDark = document.documentElement.classList.toggle('dark-mode');
            localStorage.setItem('admin-theme', isDark ? 'dark' : 'light');
            if (isDark) {
                toggleIcon.classList.remove('fa-moon-o');
                toggleIcon.classList.add('fa-sun-o');
            } else {
                toggleIcon.classList.remove('fa-sun-o');
                toggleIcon.classList.add('fa-moon-o');
            }
        });
    }
});
</script>  
    
</body> 
</html>
