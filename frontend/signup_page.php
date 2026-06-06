<!-- Google Fonts & Custom CSS -->
<style type="text/css">
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');

    .auth-page-wrapper {
        font-family: 'Outfit', sans-serif;
        background-color: #f8fafc;
        min-height: 85vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        margin-top: -20px;
    }
    .auth-top-header { font-size: 26px; font-weight: 800; color: #1e3a8a; margin-bottom: 25px; }
    .hmart-auth-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
        display: flex;
        width: 100%;
        max-width: 720px;
        min-height: 560px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }
    .auth-left-vegetable-panel {
        width: 32%;
        background: url('https://images.unsplash.com/photo-1597362925123-77861d3fbac7?w=500') center/cover;
        min-height: 200px;
    }
    .auth-right-panel { width: 68%; padding: 40px; background: white; }
    .auth-right-title { font-size: 28px; font-weight: 800; color: #1e293b; margin: 0 0 6px; }
    .auth-right-subtitle { font-size: 14px; color: #64748b; margin-bottom: 24px; }
    .input-label { font-size: 12px; font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block; }
    .input-field-group { margin-bottom: 16px; }
    .auth-input {
        width: 100%;
        padding: 12px 14px;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        font-size: 14px;
        box-sizing: border-box;
    }
    .auth-input:focus { border-color: #1e3a8a; outline: none; box-shadow: 0 0 0 3px rgba(30,58,138,.1); }
    .auth-submit-btn {
        width: 100%;
        background: #0c3c78;
        color: white;
        font-weight: 700;
        padding: 13px;
        border-radius: 8px;
        border: none;
        font-size: 14px;
        cursor: pointer;
        margin-top: 8px;
    }
    .auth-submit-btn:hover { background: #092e5c; }
    .auth-submit-btn:disabled { opacity: 0.6; cursor: not-allowed; }
    .signup-step-badge { font-size: 12px; font-weight: 700; color: #1e3a8a; background: #eff6ff; padding: 6px 12px; border-radius: 20px; display: inline-block; margin-bottom: 16px; }
    #signup-otp-step { display: none; margin-top: 20px; padding-top: 20px; border-top: 1px solid #e2e8f0; }
    .otp-msg { display: none; padding: 12px; border-radius: 8px; font-size: 14px; margin-bottom: 12px; }
    .otp-msg.ok { display: block; background: #dcfce7; color: #166534; }
    .otp-msg.err { display: block; background: #fee2e2; color: #991b1b; }
    .otp-code-input { letter-spacing: 8px; text-align: center; font-size: 20px; font-weight: 700; }
    .checkbox-row { display: flex; gap: 10px; font-size: 13px; color: #475569; margin-bottom: 12px; align-items: flex-start; }
    .auth-redirect-text { text-align: center; margin-top: 20px; font-size: 14px; }
    .auth-redirect-link { color: #1e3a8a; font-weight: 800; text-decoration: none; }

    /* Dark mode — full page + inputs (fixes autofill mismatch) */
    body.dark-mode .auth-page-wrapper { background-color: #0f172a !important; }
    body.dark-mode .auth-top-header { color: #38bdf8 !important; }
    body.dark-mode .hmart-auth-card {
        background: #1e293b !important;
        border-color: #334155 !important;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.35) !important;
    }
    body.dark-mode .auth-right-panel { background: #1e293b !important; }
    body.dark-mode .auth-right-title { color: #f1f5f9 !important; }
    body.dark-mode .auth-right-subtitle { color: #94a3b8 !important; }
    body.dark-mode .input-label { color: #cbd5e1 !important; }
    body.dark-mode .auth-input {
        background-color: #0f172a !important;
        border-color: #475569 !important;
        color: #f1f5f9 !important;
        -webkit-text-fill-color: #f1f5f9 !important;
    }
    body.dark-mode .auth-input:focus {
        border-color: #38bdf8 !important;
        box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.2) !important;
    }
    body.dark-mode .auth-input:-webkit-autofill,
    body.dark-mode .auth-input:-webkit-autofill:hover,
    body.dark-mode .auth-input:-webkit-autofill:focus {
        -webkit-box-shadow: 0 0 0 1000px #0f172a inset !important;
        -webkit-text-fill-color: #f1f5f9 !important;
        caret-color: #f1f5f9 !important;
    }
    body.dark-mode .auth-input[readonly] {
        background-color: #1e293b !important;
        color: #94a3b8 !important;
        -webkit-text-fill-color: #94a3b8 !important;
    }
    body.dark-mode .signup-step-badge {
        background: #1e3a8a !important;
        color: #93c5fd !important;
    }
    body.dark-mode #signup-otp-step { border-top-color: #334155 !important; }
    body.dark-mode .otp-msg.ok { background: #14532d !important; color: #86efac !important; }
    body.dark-mode .otp-msg.err { background: #7f1d1d !important; color: #fecaca !important; }
    body.dark-mode .checkbox-row { color: #cbd5e1 !important; }
    body.dark-mode .auth-submit-btn {
        background-color: #38bdf8 !important;
        color: #0f172a !important;
    }
    body.dark-mode .auth-submit-btn:hover { background-color: #0ea5e9 !important; }
    body.dark-mode .auth-redirect-text { color: #94a3b8 !important; }
    body.dark-mode .auth-redirect-link { color: #38bdf8 !important; }

    @media (max-width: 767px) {
        .hmart-auth-card { flex-direction: column; }
        .auth-left-vegetable-panel, .auth-right-panel { width: 100%; }
    }
</style>

<div class="auth-page-wrapper">
    <div class="auth-top-header">H-Mart</div>
    <div class="hmart-auth-card">
        <div class="auth-left-vegetable-panel"></div>
        <div class="auth-right-panel">
            <h2 class="auth-right-title">Create your account</h2>
            <p class="auth-right-subtitle">Fill in your details below to register.</p>

            <div id="signup-form-msg" class="otp-msg"></div>

            <form id="signup-details-form" onsubmit="return handleSignupFormSubmit();" action="../backend/customer/controller.php?action=add" method="POST" autocomplete="off">
                <input type="hidden" name="FNAME" id="FNAME">
                <input type="hidden" name="LNAME" id="LNAME">
                <input type="hidden" name="CITYADD" value="Not specified">
                <input type="hidden" name="GENDER" value="Male">
                <input type="hidden" name="proid" value="">

                <label class="input-label" for="fullname">Full name</label>
                <div class="input-field-group">
                    <input type="text" id="fullname" class="auth-input" placeholder="Your full name" required autocomplete="off">
                </div>

                <label class="input-label" for="CUSUNAME">Email address</label>
                <div class="input-field-group">
                    <input type="email" name="CUSUNAME" id="CUSUNAME" class="auth-input" placeholder="you@email.com" required autocomplete="off">
                </div>

                <label class="input-label" for="PHONE">Phone number</label>
                <div class="input-field-group">
                    <input type="tel" name="PHONE" id="PHONE" class="auth-input" placeholder="10-digit mobile" required pattern="[0-9]{10,15}" autocomplete="off">
                </div>

                <label class="input-label" for="CUSPASS">Password</label>
                <div class="input-field-group">
                    <input type="password" name="CUSPASS" id="CUSPASS" class="auth-input" placeholder="Create a password" required minlength="6" autocomplete="new-password">
                </div>

                <label class="checkbox-row">
                    <input type="checkbox" id="conditionterms" name="conditionterms" value="checkbox" required>
                    <span>I agree to the Terms of Sale and Privacy Policy.</span>
                </label>

                <button type="submit" name="submit" id="btn-signup-submit" class="auth-submit-btn">Register Account</button>
            </form>

            <div class="auth-redirect-text">
                Already have an account? <a href="index.php?q=login" class="auth-redirect-link">Log in</a>
            </div>
        </div>
    </div>
</div>

<script>
    function handleSignupFormSubmit() {
        const full = document.getElementById('fullname').value.trim();
        if (full === '') {
            alert('Please enter your full name.');
            return false;
        }
        const parts = full.split(/\s+/);
        document.getElementById('FNAME').value = parts[0] || 'Customer';
        document.getElementById('LNAME').value = parts.slice(1).join(' ') || 'User';
        return true;
    }
</script>
