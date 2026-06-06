<!-- sign up modal -->
<div class="modal fade" id="smyModal" tabindex="-1">
  <div class="modal-dialog auth-modal-dialog">
    <div class="modal-content auth-modal-content">
      <div class="modal-body" style="padding: 40px 35px 35px 35px;">
        <button class="close" data-dismiss="modal" type="button" style="position: absolute; right: 20px; top: 15px; font-size: 28px; z-index: 10; color: inherit; opacity: 0.6; outline: none;">&times;</button>
        
        <!-- Logo Branding Header -->
        <div class="auth-brand-logo">H-Mart</div>
        <div class="auth-brand-tagline">Fresh groceries delivered straight to your doorstep.</div>

        <!-- Custom Tab Switcher Pills -->
        <ul class="nav nav-pills">
            <li class="active"><a href="#home" data-toggle="tab">Sign In</a></li>
            <li><a href="#profile" data-toggle="tab">Create Account</a></li>
        </ul>

        <!-- Tab Content Panes -->
        <div class="tab-content" style="border: none !important; padding: 0 !important; margin: 0 !important; background: transparent !important;">
            
            <!-- Tab Pane 1: Login -->
            <div class="tab-pane fade in active" id="home">
                <form action="<?php echo web_root; ?>login.php" method="POST" autocomplete="off">
                    <input class="proid" type="hidden" name="proid" value="">
                    
                    <div class="auth-form-group">
                        <label class="auth-input-label">Username</label>
                        <div class="auth-input-wrapper">
                            <i class="fa fa-user auth-input-icon"></i>
                            <input name="U_USERNAME" id="U_USERNAME" placeholder="Enter username" type="text" class="auth-form-control" required autocomplete="username">
                        </div>
                    </div>

                    <div class="auth-form-group" style="margin-bottom: 25px;">
                        <label class="auth-input-label">Password</label>
                        <div class="auth-input-wrapper">
                            <i class="fa fa-lock auth-input-icon"></i>
                            <input name="U_PASS" id="U_PASS" placeholder="Enter password" type="password" class="auth-form-control" required autocomplete="current-password">
                        </div>
                    </div>

                    <button type="submit" id="modalLogin" name="modalLogin" class="btn-auth-submit">
                        <span class="glyphicon glyphicon-log-in" style="margin-right: 6px;"></span> Sign In
                    </button> 
                </form>
            </div>
            <!-- End Login Tab -->

            <!-- Tab Pane 2: Register/Sign Up -->
            <div class="tab-pane fade" id="profile">
                <form action="../backend/customer/controller.php?action=add" onsubmit="return personalInfo();" name="personal" method="POST" enctype="multipart/form-data" autocomplete="off">
                    <input class="proid" type="hidden" name="proid" value="">
                    
                    <!-- First Name & Last Name Grid -->
                    <div style="display: flex; gap: 15px;">
                        <div class="auth-form-group" style="flex: 1;">
                            <label class="auth-input-label">First Name</label>
                            <div class="auth-input-wrapper">
                                <i class="fa fa-user auth-input-icon"></i>
                                <input id="FNAME" name="FNAME" placeholder="First Name" type="text" class="auth-form-control" required autocomplete="off">
                            </div>
                        </div>
                        <div class="auth-form-group" style="flex: 1;">
                            <label class="auth-input-label">Last Name</label>
                            <div class="auth-input-wrapper">
                                <i class="fa fa-user auth-input-icon"></i>
                                <input id="LNAME" name="LNAME" placeholder="Last Name" type="text" class="auth-form-control" required autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <!-- Gender Radio Selection -->
                    <div class="auth-form-group">
                        <label class="auth-input-label">Gender</label>
                        <div class="gender-radio-group">
                            <label class="gender-radio-label">
                                <input id="GENDER_M" name="GENDER" type="radio" checked="true" value="Male" style="margin: 0;"> Male
                            </label>
                            <label class="gender-radio-label">
                                <input id="GENDER_F" name="GENDER" type="radio" value="Female" style="margin: 0;"> Female
                            </label>
                        </div>
                    </div>

                    <!-- Contact Number -->
                    <div class="auth-form-group">
                        <label class="auth-input-label">Contact Number</label>
                        <div class="auth-input-wrapper">
                            <i class="fa fa-phone auth-input-icon"></i>
                            <input id="PHONE" name="PHONE" placeholder="10-digit mobile number" type="number" class="auth-form-control" required autocomplete="off">
                        </div>
                    </div>

                    <!-- Municipality / City Address -->
                    <div class="auth-form-group">
                        <label class="auth-input-label">Municipality / City</label>
                        <div class="auth-input-wrapper">
                            <i class="fa fa-map-marker auth-input-icon"></i>
                            <input id="CITYADD" name="CITYADD" placeholder="Municipality / City Address" type="text" class="auth-form-control" required autocomplete="off">
                        </div>
                    </div>

                    <!-- Username -->
                    <div class="auth-form-group">
                        <label class="auth-input-label">Choose Username</label>
                        <div class="auth-input-wrapper">
                            <i class="fa fa-user-circle auth-input-icon"></i>
                            <input id="CUSUNAME" name="CUSUNAME" placeholder="Username" type="text" class="auth-form-control" required autocomplete="off">
                        </div>
                    </div> 

                    <!-- Password -->
                    <div class="auth-form-group">
                        <label class="auth-input-label">Choose Password</label>
                        <div class="auth-input-wrapper">
                            <i class="fa fa-key auth-input-icon"></i>
                            <input id="CUSPASS" name="CUSPASS" placeholder="At least 8-15 characters" type="password" class="auth-form-control" required autocomplete="new-password">
                        </div>
                        <p style="font-size: 11px; color: #94a3b8; margin: 5px 0 0 0; line-height: 1.4;">
                            Must be 8 to 15 characters containing letters and numbers.
                        </p>
                    </div>

                    <!-- Terms & Conditions Checkbox -->
                    <div class="auth-form-group" style="margin-bottom: 22px;">
                        <label class="gender-radio-label" style="font-weight: 500; font-size: 13px;">
                            <input type="checkbox" id="conditionterms" name="conditionterms" value="checkbox" required style="margin: 0; transform: scale(1.1);"> 
                            I agree to the <a href="javascript:void(0);" onclick="OpenPopupCenter('terms.php','Terms And Conditions','600','600')" style="color: #3b82f6; font-weight: 700; text-decoration: none;">Terms & Conditions</a>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" name="submit" class="btn-auth-submit">
                        <span class="glyphicon glyphicon-edit" style="margin-right: 6px;"></span> Create Account
                    </button> 
                </form>
            </div>
            <!-- End Register Tab -->
        </div>
      </div>
    </div>
  </div>
</div>
<!-- end sign up modal -->

<!-- Premium Outfit Modal CSS Inject -->
<style type="text/css">
  /* Premium Outfit Modal Styles */
  .auth-modal-content {
      font-family: 'Outfit', sans-serif !important;
      border-radius: 20px !important;
      border: 1px solid #e2e8f0 !important;
      box-shadow: 0 10px 40px rgba(0,0,0,0.08) !important;
      background-color: #ffffff !important;
      color: #1e293b !important;
      overflow: hidden;
      position: relative;
      transition: all 0.3s ease;
  }
  
  /* Tabs switcher styling */
  .auth-modal-content .nav-pills {
      display: flex;
      background-color: #f1f5f9;
      padding: 6px;
      border-radius: 12px;
      margin-bottom: 25px;
      border: 1px solid #e2e8f0;
  }
  
  .auth-modal-content .nav-pills > li {
      flex: 1;
      text-align: center;
      float: none !important;
  }
  
  .auth-modal-content .nav-pills > li > a {
      color: #64748b !important;
      font-weight: 700 !important;
      font-size: 14.5px !important;
      padding: 10px 15px !important;
      border-radius: 8px !important;
      background: transparent !important;
      border: none !important;
      transition: all 0.2s ease !important;
      display: block;
      margin: 0 !important;
  }
  
  .auth-modal-content .nav-pills > li.active > a {
      background-color: #ffffff !important;
      color: #1e3a8a !important;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05) !important;
  }
  
  /* Modern input text styling */
  .auth-form-group {
      margin-bottom: 18px;
      text-align: left;
  }
  
  .auth-input-label {
      font-size: 13.5px;
      font-weight: 700;
      color: #475569;
      margin-bottom: 6px;
      display: block;
  }
  
  .auth-input-wrapper {
      position: relative;
      width: 100%;
  }
  
  .auth-input-icon {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: #94a3b8;
      font-size: 15px;
  }
  
  .auth-form-control {
      width: 100% !important;
      background-color: #f8fafc !important;
      border: 1px solid #cbd5e1 !important;
      border-radius: 10px !important;
      padding: 10px 14px 10px 40px !important;
      font-size: 14px !important;
      font-weight: 500 !important;
      color: #1e293b !important;
      outline: none !important;
      height: auto !important;
      transition: all 0.2s ease !important;
  }
  
  .auth-form-control:focus {
      background-color: #ffffff !important;
      border-color: #1e3a8a !important;
      box-shadow: 0 0 0 4px rgba(30, 58, 138, 0.1) !important;
  }
  
  /* Gender Radio Group styling */
  .gender-radio-group {
      display: flex;
      gap: 20px;
      padding: 5px 0;
  }
  
  .gender-radio-label {
      font-size: 14px;
      font-weight: 600;
      color: #475569;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 8px;
  }
  
  /* Brand title */
  .auth-brand-logo {
      font-size: 28px;
      font-weight: 800;
      color: #1e3a8a;
      text-align: center;
      margin-bottom: 5px;
  }
  .auth-brand-tagline {
      font-size: 13px;
      color: #64748b;
      text-align: center;
      margin-bottom: 25px;
  }
  
  /* Primary buttons */
  .btn-auth-submit {
      width: 100%;
      background: linear-gradient(135deg, #1e3a8a, #3b82f6) !important;
      color: #ffffff !important;
      font-weight: 700 !important;
      font-size: 15px !important;
      padding: 12px !important;
      border-radius: 10px !important;
      border: none !important;
      box-shadow: 0 4px 15px rgba(30, 58, 138, 0.15) !important;
      transition: all 0.2s ease !important;
      cursor: pointer;
  }
  
  .btn-auth-submit:hover {
      background: linear-gradient(135deg, #1d4ed8, #2563eb) !important;
      box-shadow: 0 6px 20px rgba(30, 58, 138, 0.25) !important;
      transform: translateY(-1px);
  }
  
  /* Modal responsive wrapper */
  .auth-modal-dialog {
      max-width: 480px !important;
      margin: 50px auto !important;
      padding: 0 15px;
  }
  
  /* Dark Mode compatibility styles */
  body.dark-mode .auth-modal-content {
      background-color: #1e293b !important;
      border-color: #334155 !important;
      color: #f1f5f9 !important;
  }
  
  body.dark-mode .auth-modal-content .nav-pills {
      background-color: #0f172a !important;
      border-color: #334155 !important;
  }
  
  body.dark-mode .auth-modal-content .nav-pills > li.active > a {
      background-color: #1e293b !important;
      color: #38bdf8 !important;
      box-shadow: 0 4px 10px rgba(0,0,0,0.3) !important;
  }
  
  body.dark-mode .auth-modal-content .nav-pills > li > a {
      color: #94a3b8 !important;
  }
  
  body.dark-mode .auth-input-label {
      color: #cbd5e1 !important;
  }
  
  body.dark-mode .auth-form-control {
      background-color: #0f172a !important;
      border-color: #334155 !important;
      color: #f1f5f9 !important;
  }
  
  body.dark-mode .auth-form-control:focus {
      border-color: #38bdf8 !important;
      box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.15) !important;
  }
  
  body.dark-mode .gender-radio-label {
      color: #cbd5e1 !important;
  }
  
  body.dark-mode .auth-brand-logo {
      color: #38bdf8 !important;
  }
  
  body.dark-mode .auth-brand-tagline {
      color: #94a3b8 !important;
  }
  
  body.dark-mode .btn-auth-submit {
      background: linear-gradient(135deg, #38bdf8, #2563eb) !important;
      box-shadow: 0 4px 15px rgba(56, 189, 248, 0.15) !important;
  }
  
  body.dark-mode .btn-auth-submit:hover {
      background: linear-gradient(135deg, #0ea5e9, #3b82f6) !important;
  }
</style>

<script language="javascript" type="text/javascript">
    function OpenPopupCenter(pageURL, title, w, h) {
        var left = (screen.width - w) / 2;
        var top = (screen.height - h) / 4;
        var targetWin = window.open(pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
    } 
</script>