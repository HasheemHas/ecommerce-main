<?php  
  if (!isset($_SESSION['USERID'])){
      redirect(web_root."admin/index.php");
  }

  $PROID = $_GET['id']; 
  $query = "SELECT * FROM `tblproduct` p, `tblcategory` c, `tblpromopro` pr
            WHERE p.`CATEGID`=c.`CATEGID` AND p.`PROID`=pr.`PROID` AND p.`PROID`=" . (int)$PROID;
  $mydb->setQuery($query);
  $cur = $mydb->loadResultList();

  foreach ($cur as $result) { 
?>
<style>
.discount-card {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 30px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.02);
    max-width: 800px;
    margin: 0 auto;
}
.discount-card-title {
    font-size: 18px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 25px;
    border-bottom: 1px solid #f1f5f9;
    padding-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.discount-details-grid {
    display: grid;
    grid-template-columns: 180px 1fr;
    gap: 30px;
    margin-bottom: 30px;
    background: #f8fafc;
    padding: 20px;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
}
@media (max-width: 767px) {
    .discount-details-grid { grid-template-columns: 1fr; }
}
.discount-image-wrapper {
    width: 100%;
    height: 140px;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
}
.discount-image-wrapper img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}
.discount-info-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 8px;
    text-align: left;
}
.discount-info-list li {
    font-size: 14px;
    color: #475569;
}
.discount-info-list li strong {
    color: #0f172a;
    font-weight: 600;
}
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}
@media (max-width: 767px) {
    .form-grid { grid-template-columns: 1fr; }
}
.form-group-hmart {
    display: flex;
    flex-direction: column;
    gap: 8px;
    text-align: left;
}
.form-group-hmart label {
    font-size: 13px;
    font-weight: 600;
    color: #475569;
}
.input-addon-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}
.input-addon-icon {
    position: absolute;
    left: 14px;
    color: #64748b;
    font-weight: 600;
    font-size: 14px;
}
.input-addon-right {
    position: absolute;
    right: 14px;
    color: #64748b;
    font-weight: 600;
    font-size: 14px;
}
.input-addon-wrapper input {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    font-size: 14px;
    color: #1e293b;
    outline: none;
    transition: all 0.2s;
}
.input-addon-wrapper input:focus {
    border-color: #1e3a8a;
    box-shadow: 0 0 0 3px rgba(30,58,138,0.1);
}
.form-actions-hmart {
    margin-top: 30px;
    border-top: 1px solid #f1f5f9;
    padding-top: 20px;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
}
.btn-save-hmart {
    padding: 10px 24px;
    background: #1e3a8a;
    color: white;
    font-weight: 600;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    transition: background 0.2s;
}
.btn-save-hmart:hover { background: #1e40af; }
.btn-cancel-hmart {
    padding: 10px 24px;
    background: #f1f5f9;
    color: #475569;
    font-weight: 600;
    border-radius: 8px;
    border: 1px solid #cbd5e1;
    text-decoration: none;
    transition: all 0.2s;
}
.btn-cancel-hmart:hover { background: #e2e8f0; color: #1e293b; text-decoration: none; }
</style>

<!-- Title Row -->
<div class="page-title-row" style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:25px;">
    <div>
        <h1 style="font-weight:800; color:var(--primary); font-size:28px; margin:0 0 5px 0;">Set Product Discount</h1>
        <p style="color:var(--text-muted); margin:0; font-size:14px;">Apply dynamic seasonal discounts and promotional prices to catalog items.</p>
    </div>
</div>

<div class="discount-card">
    <h3 class="discount-card-title"><i class="fa fa-tag" style="color:#1e3a8a;"></i>Configure Discount</h3>

    <form method="POST" action="controller.php?action=discount">
        <input type="hidden" name="PROID" id="PROID" value="<?php echo $result->PROID; ?>">
        <input type="hidden" name="PROPRICE" id="PROPRICE" value="<?php echo $result->PROPRICE; ?>">
        <input type="hidden" name="PROQTY" id="PROQTY" value="<?php echo $result->PROQTY; ?>">

        <div class="discount-details-grid">
            <div class="discount-image-wrapper">
                <img src="<?php echo web_root . 'admin/products/'.  $result->IMAGES;?>" alt="Product Image" onerror="this.src='<?php echo web_root; ?>images/default.jpg'">
            </div>
            <div>
                <h4 style="margin:0 0 12px 0; font-weight:700; color:#0f172a; font-size:16px; text-align: left;"><?php echo htmlspecialchars($result->PRODESC); ?></h4>
                <ul class="discount-info-list">
                    <li><strong>Category:</strong> <?php echo htmlspecialchars($result->CATEGORIES); ?></li>
                    <li><strong>Original Price:</strong> ₹<?php echo number_format($result->PROPRICE, 2); ?></li>
                    <li><strong>Current Discount:</strong> <?php echo $result->PRODISCOUNT; ?>%</li>
                    <li><strong>Current Discounted Price:</strong> ₹<?php echo number_format($result->PRODISPRICE, 2); ?></li>
                </ul>
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group-hmart">
                <label for="PRODISCOUNT">Discount Percentage (%)</label>
                <div class="input-addon-wrapper">
                    <input type="text" class="disper" name="PRODISCOUNT" id="PRODISCOUNT" placeholder="0" value="<?php echo $result->PRODISCOUNT; ?>" style="padding-right: 35px;" required>
                    <span class="input-addon-right">%</span>
                </div>
            </div>

            <div class="form-group-hmart">
                <label for="PRODISPRICE">Calculated Discounted Price (?)</label>
                <div class="input-addon-wrapper">
                    <span class="input-addon-icon">₹</span>
                    <input type="text" name="PRODISPRICE" id="PRODISPRICE" placeholder="0.0" value="<?php echo $result->PRODISPRICE; ?>" readonly style="padding-left: 30px; background: #f8fafc; cursor: not-allowed;">
                </div>
            </div>
        </div>

        <div class="form-actions-hmart">
            <a href="index.php" class="btn-cancel-hmart">Cancel</a>
            <button type="submit" name="submit" class="btn-save-hmart">Apply Discount</button>
        </div>
    </form>
</div>
<?php } ?>