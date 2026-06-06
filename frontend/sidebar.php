<div class="hmart-filter-sidebar">
    <h3 class="filter-title">Shop By Category</h3>
    
    <!-- Category List Group -->
    <div class="filter-group">
        <ul class="cat-filter-list">
            <?php
            $activeCategory = isset($_GET['category']) ? $_GET['category'] : '';
            $allActive = ($activeCategory == '') ? 'active' : '';
            ?>
            <li class="cat-filter-item <?php echo $allActive; ?>">
                <a href="index.php?q=product">
                    <i class="fa fa-th-large"></i> All Categories
                </a>
            </li>
            
            <?php 
            $mydb->setQuery("SELECT * FROM `tblcategory`");
            $cur = $mydb->loadResultList();
            foreach ($cur as $result) {
                // Style specific icons for categories
                $icon = 'fa-tag'; // Default fallback
                $catLower = strtolower($result->CATEGORIES);
                if (strpos($catLower, 'shoe') !== false) {
                    $icon = 'fa-tag'; 
                } elseif (strpos($catLower, 'grocery') !== false) {
                    $icon = 'fa-shopping-basket';
                } elseif (strpos($catLower, 'bag') !== false) {
                    $icon = 'fa-shopping-bag';
                } elseif (strpos($catLower, 'women') !== false) {
                    $icon = 'fa-female';
                } elseif (strpos($catLower, 'men') !== false) {
                    $icon = 'fa-male';
                } elseif (strpos($catLower, 'cloth') !== false || strpos($catLower, 'fashion') !== false) {
                    $icon = 'fa-tags';
                } elseif (strpos($catLower, 'interior') !== false || strpos($catLower, 'household') !== false) {
                    $icon = 'fa-home';
                } elseif (strpos($catLower, 'kid') !== false) {
                    $icon = 'fa-child';
                } elseif (strpos($catLower, 'sport') !== false) {
                    $icon = 'fa-trophy';
                } elseif (strpos($catLower, 'mobile') !== false || strpos($catLower, 'phone') !== false) {
                    $icon = 'fa-mobile';
                } elseif (strpos($catLower, 'laptop') !== false) {
                    $icon = 'fa-laptop';
                } elseif (strpos($catLower, 'audio') !== false || strpos($catLower, 'sound') !== false) {
                    $icon = 'fa-headphones';
                } elseif (strpos($catLower, 'camera') !== false) {
                    $icon = 'fa-camera';
                } elseif (strpos($catLower, 'electronic') !== false) {
                    $icon = 'fa-desktop';
                }
                
                $isActive = ($activeCategory == $result->CATEGORIES) ? 'active' : '';
                
                echo '<li class="cat-filter-item '.$isActive.'">
                        <a href="index.php?q=product&category='.$result->CATEGORIES.'">
                            <i class="fa '.$icon.'"></i> '.$result->CATEGORIES.'
                        </a>
                      </li>';
            }
            ?>
        </ul>
    </div>
    
    <form method="GET" action="index.php">
        <input type="hidden" name="q" value="product">
        <?php if (isset($_GET['category'])) { ?>
            <input type="hidden" name="category" value="<?php echo htmlspecialchars($_GET['category']); ?>">
        <?php } ?>
        <?php if (isset($_GET['search'])) { ?>
            <input type="hidden" name="search" value="<?php echo htmlspecialchars($_GET['search']); ?>">
        <?php } ?>
        
        <!-- Price Range Filter Group -->
        <div class="filter-group">
            <div class="filter-group-title">Price Range</div>
            <?php $priceFilter = isset($_GET['price']) ? $_GET['price'] : ''; ?>
            
            <label class="checkbox-filter-item">
                <input type="radio" name="price" value="under_500" <?php if($priceFilter == 'under_500') echo 'checked'; ?> onchange="this.form.submit()"> Under ₹500
            </label>
            <label class="checkbox-filter-item">
                <input type="radio" name="price" value="500_2000" <?php if($priceFilter == '500_2000') echo 'checked'; ?> onchange="this.form.submit()"> ₹500 - ₹2,000
            </label>
            <label class="checkbox-filter-item">
                <input type="radio" name="price" value="over_2000" <?php if($priceFilter == 'over_2000') echo 'checked'; ?> onchange="this.form.submit()"> Over ₹2,000
            </label>
        </div>
        
        <!-- Rating Filter Group -->
        <div class="filter-group" style="border-bottom: none; padding-bottom: 0; margin-bottom: 0;">
            <div class="filter-group-title">Rating</div>
            <?php $ratingFilter = isset($_GET['rating']) ? $_GET['rating'] : ''; ?>
            
            <label class="checkbox-filter-item" style="display: flex; align-items: center; cursor: pointer; margin-bottom: 10px;">
                <input type="radio" name="rating" value="4" <?php if($ratingFilter == '4') echo 'checked'; ?> onchange="this.form.submit()" style="margin-right: 8px;">
                <div class="rating-filter-item" style="display: inline;">
                    <i class="fa fa-star" style="color: #f59e0b;"></i>
                    <i class="fa fa-star" style="color: #f59e0b;"></i>
                    <i class="fa fa-star" style="color: #f59e0b;"></i>
                    <i class="fa fa-star" style="color: #f59e0b;"></i>
                    <i class="fa fa-star-o" style="color: #cbd5e1;"></i>
                    <span style="color: #475569; font-size: 13px; font-weight: 600; margin-left: 4px;">& Up</span>
                </div>
            </label>
            
            <label class="checkbox-filter-item" style="display: flex; align-items: center; cursor: pointer;">
                <input type="radio" name="rating" value="3" <?php if($ratingFilter == '3') echo 'checked'; ?> onchange="this.form.submit()" style="margin-right: 8px;">
                <div class="rating-filter-item" style="display: inline;">
                    <i class="fa fa-star" style="color: #f59e0b;"></i>
                    <i class="fa fa-star" style="color: #f59e0b;"></i>
                    <i class="fa fa-star" style="color: #f59e0b;"></i>
                    <i class="fa fa-star-o" style="color: #cbd5e1;"></i>
                    <i class="fa fa-star-o" style="color: #cbd5e1;"></i>
                    <span style="color: #475569; font-size: 13px; font-weight: 600; margin-left: 4px;">& Up</span>
                </div>
            </label>
        </div>
        
        <?php if ($priceFilter != '' || $ratingFilter != '') { ?>
        <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #e2e8f0;">
            <a href="index.php?q=product<?php echo isset($_GET['category']) ? '&category='.urlencode($_GET['category']) : ''; ?><?php echo isset($_GET['search']) ? '&search='.urlencode($_GET['search']) : ''; ?>" style="font-size: 13px; color: #ef4444; text-decoration: none; font-weight: 600;"><i class="fa fa-times"></i> Clear Filters</a>
        </div>
        <script>
            // Ensure clear filters works cleanly in dark mode
            if(document.body.classList.contains('dark-mode')) {
                document.querySelector('.filter-group').nextElementSibling.style.borderColor = '#334155';
            }
        </script>
        <?php } ?>
    </form>
</div>