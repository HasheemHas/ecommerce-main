<?php
$recommended = [];
try {
    $recommended = RecommendationEngine::getRecommendations(null, 8);
} catch (Exception $e) {
    $recommended = [];
}
if (empty($recommended)) {
    global $mydb;
    $mydb->setQuery("SELECT * FROM `tblpromopro` pr, `tblproduct` p, `tblcategory` c
        WHERE pr.PROID=p.PROID AND p.CATEGID=c.CATEGID AND p.PROQTY>0 ORDER BY p.PROID DESC LIMIT 8");
    $recommended = $mydb->loadResultList();
}
if (empty($recommended)) {
    return;
}
?>
<div id="recommended-section" class="featured-section" style="margin-top: 50px;">
    <div class="featured-title-wrapper">
        <h2 class="featured-title">Recommended For You</h2>
        <p style="color:#64748b;font-size:14px;margin-top:6px;">Picked based on what you browse and buy</p>
    </div>
    <div class="product-grid">
        <?php foreach ($recommended as $result) {
            $isNew = ($result->PROID >= 201743);
        ?>
        <div class="product-card">
            <a href="index.php?q=single-item&id=<?php echo (int) $result->PROID; ?>" style="text-decoration:none;color:inherit;display:block;">
                <div class="prod-img-container">
                    <?php if ($isNew) { ?><span class="badge-tag badge-new">Recommended</span><?php } ?>
                    <img src="<?php echo str_replace('frontend/', '', web_root).'admin/products/'.$result->IMAGES; ?>" class="prod-img" alt="<?php echo htmlspecialchars($result->PRODESC); ?>" />
                </div>
                <div class="prod-details">
                    <div class="prod-category"><?php echo $result->CATEGORIES; ?></div>
                    <h3 class="prod-name"><?php echo $result->PRODESC; ?></h3>
                    <div class="prod-footer">
                        <span class="prod-price">₹<?php echo number_format($result->PRODISPRICE, 2); ?></span>
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
