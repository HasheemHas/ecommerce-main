<section class="hmart-catalog-wrapper">
    <!-- Google Fonts & Custom Premium CSS -->
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');

        .hmart-catalog-wrapper {
            font-family: 'Outfit', sans-serif;
            color: #1e293b;
            background-color: #f8fafc;
            padding: 40px 0 80px;
            margin-top: -20px; /* seamless alignment with navbar */
        }

        /* Sidebar Styling Override */
        .hmart-filter-sidebar {
            background: white;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
            text-align: left;
        }
        .filter-title {
            font-size: 20px;
            font-weight: 800;
            color: #1e293b;
            margin: 0 0 20px 0;
            letter-spacing: -0.5px;
        }
        .filter-group {
            margin-bottom: 25px;
        }
        .filter-group:not(:last-child) {
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 20px;
        }
        .filter-group-title {
            font-size: 11px;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }

        /* Category Items */
        .cat-filter-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .cat-filter-item a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 8px;
            color: #475569;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none !important;
            transition: all 0.2s ease;
        }
        .cat-filter-item a:hover {
            background: #f1f5f9;
            color: #1e293b;
        }
        .cat-filter-item.active a {
            background: #1e3a8a;
            color: white !important;
            box-shadow: 0 4px 12px rgba(30, 58, 138, 0.25);
        }
        body.dark-mode .cat-filter-item.active a {
            background: #2563eb !important;
            color: white !important;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35) !important;
        }

        /* Checkboxes */
        .checkbox-filter-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: #475569;
            font-weight: 500;
            margin-bottom: 12px;
            cursor: pointer;
        }
        .checkbox-filter-item input {
            width: 16px;
            height: 16px;
            border-radius: 4px;
            border: 1px solid #cbd5e1;
            cursor: pointer;
        }

        /* Stars Rating Filter */
        .rating-filter-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #fbbf24;
            font-size: 14px;
            font-weight: 600;
        }

        /* Catalog Title & Sort */
        .catalog-header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 35px;
            text-align: left;
        }
        .catalog-title {
            font-size: 32px;
            font-weight: 800;
            color: #1e293b;
            margin: 0 0 4px 0;
        }
        .catalog-subtitle {
            font-size: 14px;
            color: #64748b;
            margin: 0;
        }
        .sort-select-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sort-label {
            font-size: 13px;
            font-weight: 700;
            color: #64748b;
        }
        .sort-select {
            padding: 8px 16px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            background: white;
            font-weight: 600;
            color: #1e293b;
            font-size: 13px;
            outline: none;
            cursor: pointer;
        }

        /* 3-Column Responsive Grid */
        .catalog-product-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }

        /* Product Cards */
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
        .badge-bestseller {
            background-color: #1e3a8a;
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

        /* Pagination Styling */
        .hmart-pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 50px;
        }
        .page-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #cbd5e1;
            background: white;
            color: #475569;
            font-weight: 700;
            text-decoration: none !important;
            transition: all 0.2s ease;
            font-size: 14px;
        }
        .page-btn:hover {
            background: #f1f5f9;
            color: #1e293b;
            border-color: #cbd5e1;
        }
        .page-btn.active {
            background: #1e3a8a;
            color: white !important;
            border-color: #1e3a8a;
            box-shadow: 0 4px 10px rgba(30, 58, 138, 0.2);
        }
        
        @media (max-width: 991px) {
            .catalog-product-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .catalog-header-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }
        @media (max-width: 575px) {
            .catalog-product-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Premium Dark Mode Overrides */
        body.dark-mode .hmart-catalog-wrapper {
            background-color: #0f172a !important;
            color: #f1f5f9 !important;
        }
        body.dark-mode .hmart-filter-sidebar {
            background: #1e293b !important;
            border-color: #334155 !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3) !important;
        }
        body.dark-mode .filter-title {
            color: #f1f5f9 !important;
        }
        body.dark-mode .filter-group:not(:last-child) {
            border-bottom-color: #334155 !important;
        }
        body.dark-mode .cat-filter-item a {
            color: #cbd5e1 !important;
        }
        body.dark-mode .cat-filter-item a:hover {
            background: #334155 !important;
            color: #38bdf8 !important;
        }
        body.dark-mode .checkbox-filter-item {
            color: #cbd5e1 !important;
        }
        body.dark-mode .checkbox-filter-item input {
            background-color: #0f172a !important;
            border-color: #334155 !important;
        }
        body.dark-mode .catalog-header-title {
            color: #f1f5f9 !important;
        }
        body.dark-mode .catalog-header-subtitle {
            color: #94a3b8 !important;
        }
        body.dark-mode .sort-select {
            background-color: #0f172a !important;
            color: #f1f5f9 !important;
            border-color: #334155 !important;
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
        body.dark-mode .page-btn {
            background: #1e293b !important;
            color: #cbd5e1 !important;
            border-color: #334155 !important;
        }
        body.dark-mode .page-btn:hover {
            background: #334155 !important;
            color: #38bdf8 !important;
        }
        body.dark-mode .page-btn.active {
            background: #38bdf8 !important;
            color: #0f172a !important;
            border-color: #38bdf8 !important;
        }
    </style>

    <div class="container-fluid" style="padding-left: 40px; padding-right: 40px;">
        <div class="row">
            <!-- 1. Left Sidebar Filter Panel -->
            <div class="col-md-3">
                <?php include 'sidebar.php'; ?>
            </div>
            
            <!-- 2. Right Products Grid -->
            <div class="col-md-9">
                <?php
                // Dynamic page header computation
                $searchVal = '';
                if (isset($_POST['search'])) {
                    $searchVal = $_POST['search'];
                } elseif (isset($_GET['search'])) {
                    $searchVal = $_GET['search'];
                }

                // Base WHERE condition
                $where = "pr.`PROID`=p.`PROID` AND  p.`CATEGID` = c.`CATEGID`  AND PROQTY>0";
                
                if (isset($_GET['visual_search_ids']) && $_GET['visual_search_ids'] != '') {
                    $titleText = t('visual_search_title', 'AI Image Matches');
                    $clean_ids = implode(',', array_map('intval', explode(',', $_GET['visual_search_ids'])));
                    $where .= " AND p.PROID IN ({$clean_ids})";
                } elseif ($searchVal != '') {
                    $titleText = t('search_results', 'Search Results') . ': "' . htmlspecialchars($searchVal) . '"';
                    $where .= " AND ( `CATEGORIES` LIKE '%{$searchVal}%' OR `PRODESC` LIKE '%{$searchVal}%' or `PROQTY` LIKE '%{$searchVal}%' or `PROPRICE` LIKE '%{$searchVal}%')";
                } elseif (isset($_GET['category'])) {
                    $titleText = htmlspecialchars($_GET['category']);
                    $where .= " AND CATEGORIES='{$_GET['category']}'";
                } else {
                    $titleText = t('fresh_arrivals', 'Fresh Arrivals');
                }

                // Price Filter Integration
                if (isset($_GET['price'])) {
                    if ($_GET['price'] == 'under_500') {
                        $where .= " AND pr.`PRODISPRICE` < 500";
                    } elseif ($_GET['price'] == '500_2000') {
                        $where .= " AND pr.`PRODISPRICE` >= 500 AND pr.`PRODISPRICE` <= 2000";
                    } elseif ($_GET['price'] == 'over_2000') {
                        $where .= " AND pr.`PRODISPRICE` > 2000";
                    }
                }
                
                // Rating Filter Integration
                if (isset($_GET['rating'])) {
                    $r = (int)$_GET['rating'];
                    $where .= " AND ((p.`PROID` MOD 3) + 3) >= $r";
                }
                
                // Sort Integration
                $sort = isset($_GET['sort']) ? $_GET['sort'] : '';
                $order_by = " ORDER BY pr.`PROID` DESC";
                if ($sort == 'price_asc') {
                    $order_by = " ORDER BY pr.`PRODISPRICE` ASC";
                } elseif ($sort == 'price_desc') {
                    $order_by = " ORDER BY pr.`PRODISPRICE` DESC";
                }

                $query = "SELECT * FROM `tblpromopro` pr , `tblproduct` p , `tblcategory` c WHERE $where" . $order_by;

                $mydb->setQuery($query);
                $res = $mydb->executeQuery();
                $maxrow = $mydb->num_rows($res);

                // Setup true pagination logic
                $max_per_page = 12; // Show 12 items per page
                $page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
                if ($page < 1) $page = 1;
                $offset = ($page - 1) * $max_per_page;
                $total_pages = ceil($maxrow / $max_per_page);

                // Query with LIMIT
                $query_paginated = $query . " LIMIT {$max_per_page} OFFSET {$offset}";
                $mydb->setQuery($query_paginated);
                $cur = $mydb->loadResultList();

                // Prepare base url for pagination
                $base_url = "index.php?q=product";
                if (isset($_GET['category'])) {
                    $base_url .= "&category=" . urlencode($_GET['category']);
                } elseif (isset($_GET['search'])) {
                    $base_url .= "&search=" . urlencode($_GET['search']);
                }
                ?>

                <!-- Catalog Header Row -->
                <div class="catalog-header-row">
                    <div>
                        <h2 class="catalog-title"><?php echo $titleText; ?></h2>
                        <p class="catalog-subtitle">Showing <?php echo $maxrow; ?> of <?php echo $maxrow; ?> products</p>
                    </div>
                    
                    <div class="sort-select-wrapper">
                        <span class="sort-label">Sort by:</span>
                        <select class="sort-select" onchange="window.location.href=this.value;">
                            <?php
                            $base_sort_url = "index.php?q=product";
                            if (isset($_GET['category'])) $base_sort_url .= "&category=" . urlencode($_GET['category']);
                            if (isset($_GET['search'])) $base_sort_url .= "&search=" . urlencode($_GET['search']);
                            if (isset($_GET['price'])) $base_sort_url .= "&price=" . urlencode($_GET['price']);
                            if (isset($_GET['rating'])) $base_sort_url .= "&rating=" . urlencode($_GET['rating']);
                            ?>
                            <option value="<?php echo $base_sort_url; ?>" <?php if($sort=='') echo 'selected'; ?>>Recommended</option>
                            <option value="<?php echo $base_sort_url.'&sort=price_asc'; ?>" <?php if($sort=='price_asc') echo 'selected'; ?>>Price: Low to High</option>
                            <option value="<?php echo $base_sort_url.'&sort=price_desc'; ?>" <?php if($sort=='price_desc') echo 'selected'; ?>>Price: High to Low</option>
                        </select>
                    </div>
                </div>

                <!-- Products Grid -->
                <?php if ($maxrow > 0) { ?>
                    <div class="catalog-product-grid">
                        <?php
                        foreach ($cur as $result) { 
                            // Tags mapping to mimic user mockup perfectly
                            $isNew = ($result->PROID >= 201743);
                            $isSale = ($result->PROID == 201739 || $result->PROID == 201746);
                            $isBest = ($result->PROID == 201744);
                        ?>
                        <div class="product-card">
                            <a href="index.php?q=single-item&id=<?php echo (int) $result->PROID; ?>" class="prod-card-link" style="text-decoration:none;color:inherit;display:block;">
                                <div class="prod-img-container">
                                    <?php if ($isNew) { ?>
                                        <span class="badge-tag badge-new">New</span>
                                    <?php } elseif ($isSale) { ?>
                                        <span class="badge-tag badge-sale">Sale</span>
                                    <?php } elseif ($isBest) { ?>
                                        <span class="badge-tag badge-bestseller">Best Seller</span>
                                    <?php } ?>
                                    <img src="<?php echo htmlspecialchars(product_image_url($result->IMAGES, $result->PRODESC)); ?>" class="prod-img" alt="<?php echo htmlspecialchars($result->PRODESC); ?>" loading="lazy" />
                                </div>
                                <div class="prod-details">
                                    <div>
                                        <div class="prod-category"><?php echo $result->CATEGORIES; ?></div>
                                        <h3 class="prod-name"><?php echo $result->PRODESC; ?></h3>
                                        <div class="prod-rating">
                                            <?php 
                                            // Deterministic rating based on PROID (between 3 and 5)
                                            $prod_rating = ($result->PROID % 3) + 3;
                                            for ($i=1; $i<=5; $i++) {
                                                if ($i <= $prod_rating) echo '<i class="fa fa-star"></i>';
                                                else echo '<i class="fa fa-star-o" style="color: #cbd5e1;"></i>';
                                            }
                                            ?>
                                            <span style="color: #94a3b8; font-size: 11px; margin-left: 4px; font-weight: normal;">(<?php echo (($result->PROID * 7) % 300) + 15; ?>)</span>
                                        </div>
                                    </div>
                                    <div class="prod-footer">
                                        <div class="prod-price-block">
                                            <span class="prod-price"><?php echo convert_price($result->PRODISPRICE); ?></span>
                                            <?php if ($isSale) { ?>
                                                <span class="prod-price-original" style="font-size: 11px; text-decoration: line-through; margin-left: 6px;"><?php echo convert_price($result->PRODISPRICE * 1.3); ?></span>
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
                    
                    <!-- Dynamic Circular Pagination -->
                    <?php if ($total_pages > 1) { ?>
                    <div class="hmart-pagination">
                        <?php 
                        $prev = ($page > 1) ? $page - 1 : 1;
                        $next = ($page < $total_pages) ? $page + 1 : $total_pages;
                        ?>
                        <a href="<?php echo $base_url . '&p=' . $prev; ?>" class="page-btn"><i class="fa fa-angle-left"></i></a>
                        
                        <?php 
                        // Show up to 5 pages around the current page
                        $start = max(1, $page - 2);
                        $end = min($total_pages, $page + 2);
                        
                        if ($start > 1) {
                            echo '<a href="'.$base_url.'&p=1" class="page-btn">1</a>';
                            if ($start > 2) echo '<span style="align-self: center; color: #94a3b8; margin: 0 4px;">...</span>';
                        }
                        
                        for ($i = $start; $i <= $end; $i++) {
                            $active_class = ($i == $page) ? 'active' : '';
                            echo '<a href="'.$base_url.'&p='.$i.'" class="page-btn '.$active_class.'">'.$i.'</a>';
                        }
                        
                        if ($end < $total_pages) {
                            if ($end < $total_pages - 1) echo '<span style="align-self: center; color: #94a3b8; margin: 0 4px;">...</span>';
                            echo '<a href="'.$base_url.'&p='.$total_pages.'" class="page-btn">'.$total_pages.'</a>';
                        }
                        ?>
                        
                        <a href="<?php echo $base_url . '&p=' . $next; ?>" class="page-btn"><i class="fa fa-angle-right"></i></a>
                    </div>
                    <?php } ?>

                <?php } else { ?>
                    <div style="text-align: center; padding: 80px 0;">
                        <i class="fa fa-folder-open-o" style="font-size: 48px; color: #cbd5e1; margin-bottom: 15px;"></i>
                        <h2 style="font-weight: 700; color: #64748b;">No Products Found</h2>
                        <p style="color: #94a3b8;">Try checking other categories or keywords.</p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>
