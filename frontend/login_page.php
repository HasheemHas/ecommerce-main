<!-- Google Fonts & Custom CSS -->
<style type="text/css">
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');

    .hmart-auth-page-container {
        font-family: 'Outfit', sans-serif;
        background-color: #f8fafc;
        min-height: 85vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        margin-top: -20px; /* seamless navbar alignment */
        transition: all 0.3s ease;
    }

    /* Split Card Wrapper */
    .hmart-auth-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
        display: flex;
        width: 100%;
        max-width: 950px;
        min-height: 580px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    /* Left Image Panel */
    .auth-left-panel {
        flex: 1.1;
        background: linear-gradient(rgba(30, 58, 138, 0.72), rgba(30, 58, 138, 0.72)), url('https://images.unsplash.com/photo-1542838132-92c53300491e?w=800');
        background-size: cover;
        background-position: center;
        color: white;
        padding: 55px 45px;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        text-align: left;
        position: relative;
    }
    
    .auth-left-title {
        font-size: 38px;
        font-weight: 800;
        line-height: 1.15;
        margin-bottom: 15px;
        letter-spacing: -0.5px;
        color: #ffffff;
    }
    
    .auth-left-desc {
        font-size: 15px;
        color: rgba(255, 255, 255, 0.88);
        line-height: 1.6;
        margin-bottom: 35px;
    }
    
    /* Profile Badge Avatars */
    .member-badge-strip {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .avatar-stack {
        display: flex;
    }
    
    .avatar-img {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: 2px solid #ffffff;
        object-fit: cover;
        margin-right: -10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    .avatar-text {
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.5px;
        color: rgba(255, 255, 255, 0.95);
        text-transform: uppercase;
        margin-left: 12px;
    }

    /* Right Form Panel */
    .auth-right-panel {
        flex: 0.9;
        padding: 50px 45px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        text-align: left;
        background: white;
    }
    
    .auth-right-brand {
        font-size: 28px;
        font-weight: 800;
        color: #0c3c78;
        margin-bottom: 4px;
        letter-spacing: -0.5px;
    }
    
    .auth-right-subtitle {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 30px;
    }

    /* Form Fields */
    .input-label {
        font-size: 12px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 6px;
        display: block;
    }
    
    .input-field-group {
        position: relative;
        margin-bottom: 20px;
    }
    
    .input-field-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 15px;
    }
    
    .input-field-eye {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 15px;
        cursor: pointer;
        transition: color 0.2s ease;
    }
    
    .input-field-eye:hover {
        color: #1e3a8a;
    }
    
    .auth-input {
        width: 100%;
        padding: 12px 16px 12px 42px;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        font-size: 14px;
        outline: none;
        transition: all 0.2s ease;
        background-color: white;
        color: #1e293b;
    }
    
    .auth-input:focus {
        border-color: #1e3a8a;
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    /* Checkbox & Forgot Link Row */
    .form-options-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13px;
        margin-bottom: 25px;
    }
    
    .remember-me-label {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #475569;
        font-weight: 600;
        cursor: pointer;
    }
    
    .remember-me-label input {
        width: 16px;
        height: 16px;
        cursor: pointer;
        margin: 0;
    }
    
    .forgot-link {
        color: #0c3c78;
        font-weight: 700;
        text-decoration: none !important;
        transition: color 0.2s ease;
    }
    
    .forgot-link:hover {
        color: #2563eb;
    }

    /* Main Submit Button */
    .auth-submit-btn {
        width: 100%;
        background-color: #0c3c78;
        color: white;
        font-weight: 700;
        padding: 13px;
        border-radius: 8px;
        border: none;
        font-size: 14px;
        cursor: pointer;
        transition: background-color 0.2s ease;
        margin-bottom: 25px;
    }
    
    .auth-submit-btn:hover {
        background-color: #092e5c;
    }

    /* Separator Strip */
    .auth-separator {
        display: flex;
        align-items: center;
        text-align: center;
        color: #94a3b8;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.5px;
        margin-bottom: 20px;
    }
    
    .auth-separator::before, .auth-separator::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .auth-separator:not(:empty)::before {
        margin-right: 1em;
    }
    
    .auth-separator:not(:empty)::after {
        margin-left: 1em;
    }

    /* Social Login Buttons */
    .social-buttons-row {
        display: flex;
        gap: 15px;
        margin-bottom: 30px;
    }
    
    .social-btn {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        background: white;
        font-size: 13px;
        font-weight: 700;
        color: #475569;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .social-btn:hover {
        background: #f8fafc;
        border-color: #94a3b8;
    }

    /* Redirect Link */
    .auth-redirect-text {
        font-size: 13.5px;
        color: #64748b;
        text-align: center;
    }
    
    .auth-redirect-link {
        color: #b45309;
        font-weight: 800;
        text-decoration: none !important;
        transition: color 0.2s ease;
    }
    
    .auth-redirect-link:hover {
        color: #d97706;
    }

    /* Dark Mode compatibility styles */
    body.dark-mode .hmart-auth-page-container {
        background-color: #0f172a;
    }
    
    body.dark-mode .hmart-auth-card {
        background: #1e293b;
        border-color: #334155;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    }
    
    body.dark-mode .auth-right-panel {
        background: #1e293b;
    }
    
    body.dark-mode .auth-right-brand {
        color: #38bdf8;
    }
    
    body.dark-mode .auth-right-subtitle {
        color: #94a3b8;
    }
    
    body.dark-mode .input-label {
        color: #cbd5e1;
    }
    
    body.dark-mode .auth-input {
        background-color: #0f172a;
        border-color: #334155;
        color: #f1f5f9;
    }
    
    body.dark-mode .auth-input:focus {
        border-color: #38bdf8;
        box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.15);
    }
    body.dark-mode .auth-input:-webkit-autofill,
    body.dark-mode .auth-input:-webkit-autofill:hover,
    body.dark-mode .auth-input:-webkit-autofill:focus {
        -webkit-box-shadow: 0 0 0 1000px #0f172a inset !important;
        -webkit-text-fill-color: #f1f5f9 !important;
        caret-color: #f1f5f9 !important;
    }
    body.dark-mode .otp-alert-success { background: #14532d !important; color: #86efac !important; }
    body.dark-mode .otp-alert-error { background: #7f1d1d !important; color: #fecaca !important; }
    body.dark-mode .login-tab-link { color: #94a3b8 !important; }
    body.dark-mode .login-tab-link a { color: #38bdf8 !important; }
    
    body.dark-mode .remember-me-label {
        color: #cbd5e1;
    }
    
    body.dark-mode .forgot-link {
        color: #38bdf8;
    }
    
    body.dark-mode .auth-submit-btn {
        background-color: #38bdf8;
        color: #0f172a;
    }
    
    body.dark-mode .auth-submit-btn:hover {
        background-color: #0ea5e9;
    }
    
    body.dark-mode .auth-separator {
        color: #64748b;
    }
    
    body.dark-mode .auth-separator::before, body.dark-mode .auth-separator::after {
        border-bottom-color: #334155;
    }
    
    body.dark-mode .social-btn {
        background: #0f172a;
        border-color: #334155;
        color: #cbd5e1;
    }
    
    body.dark-mode .social-btn:hover {
        background: #1e293b;
        border-color: #475569;
    }
    
    body.dark-mode .auth-redirect-text {
        color: #94a3b8;
    }
    
    body.dark-mode .auth-redirect-link {
        color: #fbbf24;
    }

    .otp-alert { display: none; padding: 12px 14px; border-radius: 8px; font-size: 14px; margin-bottom: 16px; }
    .otp-alert-success { background: #dcfce7; color: #166534; }
    .otp-alert-error { background: #fee2e2; color: #991b1b; }
    .otp-alert-info { background: #eff6ff; color: #1e40af; }
    .otp-code-input { letter-spacing: 10px; text-align: center; font-size: 22px; font-weight: 700; }
    .login-tab-link { font-size: 14px; color: #64748b; text-align: center; margin-top: 16px; }
    .login-tab-link a { color: #1e3a8a; font-weight: 600; }

    @media (max-width: 767px) {
        .hmart-auth-card {
            flex-direction: column;
            min-height: auto;
        }
        .auth-left-panel {
            min-height: 250px;
            padding: 30px;
        }
        .auth-right-panel {
            padding: 30px 20px;
        }
    }
</style>

<div class="hmart-auth-page-container">
    <div class="hmart-auth-card">
        <!-- 1. Left Graphic Panel -->
        <div class="auth-left-panel">
            <h2 class="auth-left-title">Freshness delivered<br>to your door.</h2>
            <p class="auth-left-desc">Experience the quality and variety of H-Mart from the comfort of your home. Sign in to access your rewards and personalized deals.</p>
            
            <div class="member-badge-strip">
                <div class="avatar-stack">
                    <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=80" class="avatar-img" alt="avatar" />
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=80" class="avatar-img" alt="avatar" />
                    <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=80" class="avatar-img" alt="avatar" />
                </div>
                <span class="avatar-text">Join 1M+ Members</span>
            </div>
        </div>

        <!-- 2. Right Form Panel -->
        <div class="auth-right-panel">
            <h2 class="auth-right-brand">H-Mart</h2>
            <p class="auth-right-subtitle">Sign in with your email or username and password.</p>

            <div id="otp-login-message" class="otp-alert"></div>

            <!-- OTP Login (optional when email delivery is configured) -->
            <div id="loginOtpPanel" style="display:none;">
                <label class="input-label" for="otp-login-email">Email address</label>
                <div class="input-field-group">
                    <i class="fa fa-envelope-o input-field-icon"></i>
                    <input type="email" id="otp-login-email" class="auth-input" placeholder="you@email.com" required autocomplete="email" value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
                </div>
                <button type="button" id="otp-send-btn" class="auth-submit-btn">Send code</button>

                <div id="otp-step-code" style="display:none; margin-top: 24px;">
                    <p style="font-size: 14px; color: #64748b; margin-bottom: 12px;">Code sent to <strong id="otp-sent-to"></strong></p>
                    <label class="input-label" for="otp-login-code">6-digit code</label>
                    <div class="input-field-group">
                        <i class="fa fa-key input-field-icon"></i>
                        <input type="text" id="otp-login-code" class="auth-input otp-code-input" placeholder="000000" maxlength="6" inputmode="numeric" autocomplete="off">
                    </div>
                    <button type="button" id="otp-verify-btn" class="auth-submit-btn">Verify &amp; sign in</button>
                    <p class="login-tab-link" style="margin-top: 12px;">
                        <button type="button" id="otp-resend-btn" class="auth-submit-btn" style="background: transparent; color: #1e3a8a; border: 1px solid #cbd5e1; margin-top: 8px; width: 100%;">Resend code</button>
                    </p>
                </div>
            </div>

            <!-- Password login (default) -->
            <div id="loginPasswordPanel">
            <form action="<?php echo web_root; ?>login.php" method="POST" autocomplete="on">
                <label class="input-label" for="U_USERNAME">Email Address / Username</label>
                <div class="input-field-group">
                    <i class="fa fa-user-o input-field-icon"></i>
                    <input type="text" name="U_USERNAME" id="U_USERNAME" class="auth-input" placeholder="name@company.com" required autocomplete="username">
                </div>
                <label class="input-label" for="U_PASS">Password</label>
                <div class="input-field-group">
                    <i class="fa fa-lock input-field-icon"></i>
                    <input type="password" name="U_PASS" id="U_PASS" class="auth-input" placeholder="••••••••" required autocomplete="current-password">
                </div>
                <div class="form-options-row">
                    <a href="index.php?q=recoverpassword" class="forgot-link">Forgot password?</a>
                </div>
                <button type="submit" name="sidebarLogin" class="auth-submit-btn">Sign In with password</button>
            </form>
            </div>
            <p class="login-tab-link">
                <a href="javascript:void(0);" id="toggle-password-login">Use email code instead</a>
            </p>

            <!-- Redirection Link -->
            <div class="auth-redirect-text">
                Don't have an account? <a href="index.php?q=signup" class="auth-redirect-link">Start fresh. Join H-Mart</a>
            </div>
        </div>
    </div>
</div>

<?php
if (!empty($_GET['redirect'])) {
    $_SESSION['login_redirect'] = $_GET['redirect'];
}
?>
<script>
    var OTP_WEB_ROOT = <?php echo json_encode(web_root); ?>;
    var OTP_RESEND_SECONDS = <?php echo (int)ML_OTP_RESEND_SECONDS; ?>;
</script>
<script src="<?php echo web_root; ?>js/otp-auth.js?v=<?=time()?>"></script>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof initOtpLogin === 'function') initOtpLogin();
        var toggle = document.getElementById('toggle-password-login');
        if (toggle) {
            toggle.addEventListener('click', function () {
                var otp = document.getElementById('loginOtpPanel');
                var pwd = document.getElementById('loginPasswordPanel');
                var onPwd = pwd.style.display !== 'none';
                pwd.style.display = onPwd ? 'none' : 'block';
                otp.style.display = onPwd ? 'block' : 'none';
                toggle.textContent = onPwd ? 'Use password instead' : 'Use email code instead';
            });
        }
    });
</script>
