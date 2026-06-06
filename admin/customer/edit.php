<?php  
  if (!isset($_SESSION['USERID'])){
      redirect(web_root."admin/index.php");
  }

  $customerid = $_GET['id'];
  $customer = New Customer();
  $res = $customer->single_customer($customerid);
?>

<style>
.form-card {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 30px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.02);
    max-width: 800px;
    margin: 0 auto;
}
.form-card-title {
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
}
.form-group-hmart label {
    font-size: 13px;
    font-weight: 600;
    color: #475569;
}
.form-group-hmart input, .form-group-hmart select {
    padding: 10px 14px;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    font-size: 14px;
    color: #1e293b;
    outline: none;
    transition: all 0.2s;
}
.form-group-hmart input:focus, .form-group-hmart select:focus {
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
        <h1 style="font-weight:800; color:var(--primary); font-size:28px; margin:0 0 5px 0;">Edit Customer Details</h1>
        <p style="color:var(--text-muted); margin:0; font-size:14px;">Modify personal details, address, and verification status for customer account.</p>
    </div>
</div>

<div class="form-card">
    <h3 class="form-card-title"><i class="fa fa-pencil" style="color:#1e3a8a;"></i>Update Customer #<?php echo $customerid; ?></h3>
    
    <form action="controller.php?action=edit" method="POST">
        <input type="hidden" name="CUSTOMERID" value="<?php echo $customerid; ?>">
        
        <div class="form-grid">
            <div class="form-group-hmart">
                <label for="FNAME">First Name</label>
                <input type="text" id="FNAME" name="FNAME" value="<?php echo htmlspecialchars($res->FNAME); ?>" required>
            </div>
            
            <div class="form-group-hmart">
                <label for="LNAME">Last Name</label>
                <input type="text" id="LNAME" name="LNAME" value="<?php echo htmlspecialchars($res->LNAME); ?>" required>
            </div>

            <div class="form-group-hmart">
                <label for="MNAME">Middle Name (Optional)</label>
                <input type="text" id="MNAME" name="MNAME" value="<?php echo htmlspecialchars($res->MNAME); ?>">
            </div>

            <div class="form-group-hmart">
                <label for="CUSUNAME">Email / Username</label>
                <input type="email" id="CUSUNAME" name="CUSUNAME" value="<?php echo htmlspecialchars($res->CUSUNAME); ?>" required>
            </div>

            <div class="form-group-hmart">
                <label for="PHONE">Phone Number</label>
                <input type="text" id="PHONE" name="PHONE" value="<?php echo htmlspecialchars($res->PHONE); ?>" required>
            </div>

            <div class="form-group-hmart">
                <label for="TERMS">Verification Status</label>
                <select id="TERMS" name="TERMS" required>
                    <option value="1" <?php echo ($res->TERMS == 1) ? 'selected' : ''; ?>>Verified (Active)</option>
                    <option value="0" <?php echo ($res->TERMS == 0) ? 'selected' : ''; ?>>Unverified / Suspended</option>
                </select>
            </div>
        </div>

        <h4 style="margin: 25px 0 15px 0; font-size:14px; font-weight:700; color:#1e3a8a; border-bottom:1px solid #f1f5f9; padding-bottom:8px;"><i class="fa fa-home"></i> Delivery Address</h4>
        
        <div class="form-grid">
            <div class="form-group-hmart">
                <label for="CUSHOMENUM">Home / House Number</label>
                <input type="text" id="CUSHOMENUM" name="CUSHOMENUM" value="<?php echo htmlspecialchars($res->CUSHOMENUM); ?>">
            </div>

            <div class="form-group-hmart">
                <label for="STREETADD">Street Address</label>
                <input type="text" id="STREETADD" name="STREETADD" value="<?php echo htmlspecialchars($res->STREETADD); ?>">
            </div>

            <div class="form-group-hmart">
                <label for="BRGYADD">Barangay / Neighborhood</label>
                <input type="text" id="BRGYADD" name="BRGYADD" value="<?php echo htmlspecialchars($res->BRGYADD); ?>">
            </div>

            <div class="form-group-hmart">
                <label for="CITYADD">City</label>
                <input type="text" id="CITYADD" name="CITYADD" value="<?php echo htmlspecialchars($res->CITYADD); ?>">
            </div>

            <div class="form-group-hmart">
                <label for="PROVINCE">Province</label>
                <input type="text" id="PROVINCE" name="PROVINCE" value="<?php echo htmlspecialchars($res->PROVINCE); ?>">
            </div>

            <div class="form-group-hmart">
                <label for="ZIPCODE">Zip Code</label>
                <input type="text" id="ZIPCODE" name="ZIPCODE" value="<?php echo htmlspecialchars($res->ZIPCODE); ?>">
            </div>
        </div>

        <div class="form-actions-hmart">
            <a href="index.php" class="btn-cancel-hmart">Cancel</a>
            <button type="submit" name="save" class="btn-save-hmart">Save Changes</button>
        </div>
    </form>
</div>
