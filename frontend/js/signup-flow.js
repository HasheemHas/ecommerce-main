(function () {
    const webRoot = typeof OTP_WEB_ROOT !== 'undefined' ? OTP_WEB_ROOT : '/ecommerce/';

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('signup-details-form');
        const btnContinue = document.getElementById('btn-signup-continue');
        const btnVerify = document.getElementById('btn-signup-verify');
        const btnResend = document.getElementById('btn-signup-resend');
        const otpStep = document.getElementById('signup-otp-step');
        const otpCode = document.getElementById('signup-otp-code');
        const msgEl = document.getElementById('signup-form-msg');
        const stepLabel = document.getElementById('signup-step-label');
        const verifiedInput = document.getElementById('signup_verified');

        if (!btnContinue) return;

        function showMsg(text, ok) {
            msgEl.className = 'otp-msg ' + (ok ? 'ok' : 'err');
            msgEl.textContent = text;
        }

        function splitName() {
            const full = document.getElementById('fullname').value.trim();
            const parts = full.split(/\s+/);
            document.getElementById('FNAME').value = parts[0] || 'Customer';
            document.getElementById('LNAME').value = parts.slice(1).join(' ') || 'User';
        }

        function validateStep1() {
            const formEl = document.getElementById('signup-details-form');
            if (!formEl.checkValidity()) {
                formEl.reportValidity();
                return false;
            }
            splitName();
            return true;
        }

        function sendOtp() {
            const email = document.getElementById('CUSUNAME').value.trim();
            const fd = new FormData();
            fd.append('email', email);
            fd.append('purpose', 'signup');
            return fetch(webRoot.replace('frontend/', 'backend/') + 'api/otp_send.php', { method: 'POST', body: fd }).then(function (r) { return r.json(); });
        }

        btnContinue.addEventListener('click', function () {
            if (!validateStep1()) return;
            btnContinue.disabled = true;
            btnContinue.textContent = 'Sending code…';
            sendOtp().then(function (res) {
                btnContinue.disabled = false;
                btnContinue.textContent = 'Continue to email verification';
                if (!res.ok) {
                    showMsg(res.message, false);
                    return;
                }
                showMsg(res.message, true);
                document.getElementById('otp-email-display').textContent = document.getElementById('CUSUNAME').value.trim();
                otpStep.style.display = 'block';
                btnContinue.style.display = 'none';
                stepLabel.textContent = 'Step 2 of 2 — Verify email';
                ['fullname', 'CUSUNAME', 'PHONE', 'CUSPASS'].forEach(function (id) {
                    var el = document.getElementById(id);
                    if (el) el.readOnly = true;
                });
                otpCode.focus();
                otpCode.value = '';
            }).catch(function () {
                btnContinue.disabled = false;
                showMsg('Network error. Try again.', false);
            });
        });

        function verifyAndSubmit() {
            const email = document.getElementById('CUSUNAME').value.trim();
            const code = otpCode.value.trim();
            if (code.length !== 6) {
                showMsg('Enter the 6-digit code from your email.', false);
                return;
            }
            btnVerify.disabled = true;
            btnVerify.textContent = 'Verifying…';
            const fd = new FormData();
            fd.append('email', email);
            fd.append('otp', code);
            fd.append('purpose', 'signup');
            fetch(webRoot.replace('frontend/', 'backend/') + 'api/otp_verify.php', { method: 'POST', body: fd })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    if (!res.ok) {
                        btnVerify.disabled = false;
                        btnVerify.textContent = 'Verify & create account';
                        showMsg(res.message, false);
                        return;
                    }
                    verifiedInput.value = '1';
                    showMsg('Email verified! Creating your account…', true);
                    form.submit();
                })
                .catch(function () {
                    btnVerify.disabled = false;
                    btnVerify.textContent = 'Verify & create account';
                    showMsg('Network error.', false);
                });
        }

        btnVerify.addEventListener('click', verifyAndSubmit);
        otpCode.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 6);
        });

        if (btnResend) {
            btnResend.addEventListener('click', function () {
                btnResend.disabled = true;
                sendOtp().then(function (res) {
                    btnResend.disabled = false;
                    showMsg(res.message, res.ok);
                    otpCode.value = '';
                });
            });
        }
    });
})();
