(function () {
    const webRoot = typeof OTP_WEB_ROOT !== 'undefined' ? OTP_WEB_ROOT : '/ecommerce/';

    function $(id) {
        return document.getElementById(id);
    }

    function showMsg(el, text, type) {
        if (!el) return;
        el.style.display = 'block';
        el.textContent = text;
        el.className = 'otp-alert-' + (type || 'info');
    }

    function hideMsg(el) {
        if (el) el.style.display = 'none';
    }

    function startResendTimer(btn, seconds) {
        let left = seconds;
        btn.disabled = true;
        const original = btn.getAttribute('data-label') || btn.textContent;
        btn.setAttribute('data-label', original);
        const tick = function () {
            if (left <= 0) {
                btn.disabled = false;
                btn.textContent = original;
                return;
            }
            btn.textContent = 'Resend in ' + left + 's';
            left--;
            setTimeout(tick, 1000);
        };
        tick();
    }

    function postForm(url, data) {
        return fetch(url, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: data,
        }).then(function (r) {
            return r.json();
        });
    }

    window.initOtpLogin = function () {
        const emailInput = $('otp-login-email');
        const codeInput = $('otp-login-code');
        const sendBtn = $('otp-send-btn');
        const verifyBtn = $('otp-verify-btn');
        const resendBtn = $('otp-resend-btn');
        const msgEl = $('otp-login-message');
        const stepCode = $('otp-step-code');
        const sentToEl = $('otp-sent-to');

        if (!emailInput || !sendBtn) return;

        sendBtn.addEventListener('click', function () {
            hideMsg(msgEl);
            const email = emailInput.value.trim();
            if (!email) {
                showMsg(msgEl, 'Enter your email address.', 'error');
                return;
            }

            sendBtn.disabled = true;
            sendBtn.textContent = 'Sending…';

            const fd = new FormData();
            fd.append('email', email);
            fd.append('purpose', 'login');

            postForm(webRoot.replace('frontend/', 'backend/') + 'api/otp_send.php', fd)
                .then(function (res) {
                    sendBtn.disabled = false;
                    sendBtn.textContent = 'Send code';
                    if (!res.ok) {
                        showMsg(msgEl, res.message, 'error');
                        return;
                    }
                    showMsg(msgEl, res.message, 'success');
                    if (stepCode) stepCode.style.display = 'block';
                    if (sentToEl) sentToEl.textContent = email;
                    emailInput.readOnly = true;
                    if (codeInput) {
                        codeInput.focus();
                        codeInput.value = '';
                    }
                    if (resendBtn) {
                        const cooldown = typeof OTP_RESEND_SECONDS !== 'undefined' ? OTP_RESEND_SECONDS : 60;
                        startResendTimer(resendBtn, cooldown);
                    }
                })
                .catch(function () {
                    sendBtn.disabled = false;
                    sendBtn.textContent = 'Send code';
                    showMsg(msgEl, 'Network error. Please try again.', 'error');
                });
        });

        function verifyCode() {
            hideMsg(msgEl);
            const email = emailInput.value.trim();
            const code = codeInput ? codeInput.value.trim() : '';
            if (!email || code.length < 6) {
                showMsg(msgEl, 'Enter the 6-digit code from your email.', 'error');
                return;
            }

            verifyBtn.disabled = true;
            verifyBtn.textContent = 'Verifying…';

            const fd = new FormData();
            fd.append('email', email);
            fd.append('otp', code);
            fd.append('purpose', 'login');

            postForm(webRoot.replace('frontend/', 'backend/') + 'api/otp_verify.php', fd)
                .then(function (res) {
                    verifyBtn.disabled = false;
                    verifyBtn.textContent = 'Verify & sign in';
                    if (!res.ok) {
                        showMsg(msgEl, res.message, 'error');
                        return;
                    }
                    showMsg(msgEl, 'Success! Redirecting…', 'success');
                    window.location.href = res.redirect || webRoot + 'index.php?q=profile';
                })
                .catch(function () {
                    verifyBtn.disabled = false;
                    verifyBtn.textContent = 'Verify & sign in';
                    showMsg(msgEl, 'Network error. Please try again.', 'error');
                });
        }

        if (verifyBtn) {
            verifyBtn.addEventListener('click', verifyCode);
        }

        if (codeInput) {
            codeInput.addEventListener('input', function () {
                this.value = this.value.replace(/\D/g, '').slice(0, 6);
            });
        }

        if (resendBtn) {
            resendBtn.addEventListener('click', function () {
                emailInput.readOnly = false;
                sendBtn.click();
            });
        }
    };

    window.initOtpSignup = function () {
        const emailInput = $('otp-signup-email');
        const codeInput = $('otp-signup-code');
        const sendBtn = $('otp-signup-send');
        const verifyBtn = $('otp-signup-verify');
        const msgEl = $('otp-signup-message');

        if (!sendBtn) return;

        sendBtn.addEventListener('click', function () {
            hideMsg(msgEl);
            const email = (emailInput && emailInput.value.trim()) || '';
            if (!email) {
                showMsg(msgEl, 'Enter your email.', 'error');
                return;
            }
            sendBtn.disabled = true;
            const fd = new FormData();
            fd.append('email', email);
            fd.append('purpose', 'signup');
            postForm(webRoot.replace('frontend/', 'backend/') + 'api/otp_send.php', fd).then(function (res) {
                sendBtn.disabled = false;
                showMsg(msgEl, res.message, res.ok ? 'success' : 'error');
                if (res.ok && codeInput) codeInput.focus();
            });
        });

        if (verifyBtn && codeInput) {
            verifyBtn.addEventListener('click', function () {
                const fd = new FormData();
                fd.append('email', emailInput.value.trim());
                fd.append('otp', codeInput.value.trim());
                fd.append('purpose', 'signup');
                verifyBtn.disabled = true;
                postForm(webRoot.replace('frontend/', 'backend/') + 'api/otp_verify.php', fd).then(function (res) {
                    verifyBtn.disabled = false;
                    showMsg(msgEl, res.message, res.ok ? 'success' : 'error');
                    if (res.ok && res.redirect) {
                        setTimeout(function () { window.location.href = res.redirect; }, 600);
                    }
                });
            });
            codeInput.addEventListener('input', function () {
                this.value = this.value.replace(/\D/g, '').slice(0, 6);
            });
        }
    };
})();
