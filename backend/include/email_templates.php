<?php

class EmailTemplates
{
    public static function otpVerification($code, $purposeLabel, $minutesValid = 10)
    {
        $code = htmlspecialchars($code);
        $purposeLabel = htmlspecialchars($purposeLabel);
        $minutesValid = (int) $minutesValid;
        $year = date('Y');

        $digits = str_split($code);
        $digitBoxes = '';
        foreach ($digits as $d) {
            $digitBoxes .= '<td style="padding:0 4px;"><div class="digit-box" style="width:44px;height:52px;line-height:52px;text-align:center;font-size:26px;font-weight:800;color:#1e3a8a;background:#f0f9ff;border:2px solid #bfdbfe;border-radius:10px;font-family:\'Segoe UI\',Arial,sans-serif;">' . $d . '</div></td>';
        }

        return '<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="color-scheme" content="light dark">
<meta name="supported-color-schemes" content="light dark">
<title>H-Mart Verification Code</title>
<style>
@media (prefers-color-scheme: dark) {
  .email-bg { background-color: #0f172a !important; }
  .email-card { background-color: #1e293b !important; border-color: #334155 !important; }
  .email-header { background: linear-gradient(135deg, #1e3a8a 0%, #312e81 100%) !important; }
  .text-title { color: #f1f5f9 !important; }
  .text-body { color: #94a3b8 !important; }
  .digit-box { background-color: #0f172a !important; border-color: #38bdf8 !important; color: #38bdf8 !important; }
  .footer-bg { background-color: #0f172a !important; border-color: #334155 !important; }
  .footer-text { color: #64748b !important; }
}
</style>
</head>
<body class="email-bg" style="margin:0;padding:0;background-color:#f1f5f9;font-family:\'Segoe UI\',Roboto,Arial,sans-serif;">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" class="email-bg" style="background-color:#f1f5f9;padding:32px 16px;">
<tr><td align="center">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width:520px;" class="email-card">

<!-- Header -->
<tr><td class="email-header" style="background:linear-gradient(135deg,#1e3a8a 0%,#2563eb 50%,#1d4ed8 100%);border-radius:16px 16px 0 0;padding:32px 28px;text-align:center;">
  <div style="font-size:28px;font-weight:800;color:#ffffff;letter-spacing:-0.5px;">H-Mart</div>
  <div style="font-size:13px;color:rgba(255,255,255,0.85);margin-top:6px;font-weight:500;">Smart Shopping, Delivered Fresh</div>
</td></tr>

<!-- Body -->
<tr><td class="email-card" style="background-color:#ffffff;padding:36px 32px;border-left:1px solid #e2e8f0;border-right:1px solid #e2e8f0;">
  <p class="text-title" style="margin:0 0 8px;font-size:20px;font-weight:700;color:#1e293b;">Verify your email</p>
  <p class="text-body" style="margin:0 0 28px;font-size:15px;line-height:1.6;color:#64748b;">Use this one-time code to <strong style="color:#1e3a8a;">' . $purposeLabel . '</strong>. It expires in <strong>' . $minutesValid . ' minutes</strong>.</p>

  <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:0 auto 28px;">
  <tr>' . $digitBoxes . '</tr>
  </table>

  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#f8fafc;border-radius:12px;border:1px solid #e2e8f0;">
  <tr><td style="padding:16px 20px;">
    <p style="margin:0;font-size:13px;color:#64748b;line-height:1.5;">
      <strong style="color:#475569;">Security tip:</strong> H-Mart will never ask you to share this code. If you did not request it, ignore this email.
    </p>
  </td></tr>
  </table>
</td></tr>

<!-- Footer -->
<tr><td class="footer-bg" style="background-color:#f8fafc;border:1px solid #e2e8f0;border-top:none;border-radius:0 0 16px 16px;padding:20px 28px;text-align:center;">
  <p class="footer-text" style="margin:0 0 6px;font-size:12px;color:#94a3b8;">&copy; ' . $year . ' H-Mart. All rights reserved.</p>
  <p class="footer-text" style="margin:0;font-size:11px;color:#cbd5e1;">This is an automated message. Please do not reply.</p>
</td></tr>

</table>
</td></tr>
</table>
</body>
</html>';
    }

    public static function otpPlainText($code, $purposeLabel, $minutesValid = 10)
    {
        return "H-Mart Verification\n\n"
            . "Your code to {$purposeLabel}: {$code}\n\n"
            . "Valid for {$minutesValid} minutes.\n"
            . "Do not share this code with anyone.\n\n"
            . "— H-Mart Team";
    }

    public static function signupVerification($actual_link)
    {
        $actual_link = htmlspecialchars($actual_link);
        $year = date('Y');

        return '<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="color-scheme" content="light dark">
<meta name="supported-color-schemes" content="light dark">
<title>Welcome to H-Mart — Verify Your Account</title>
<style>
@media (prefers-color-scheme: dark) {
  .email-bg { background-color: #0f172a !important; }
  .email-card { background-color: #1e293b !important; border-color: #334155 !important; }
  .email-header { background: linear-gradient(135deg, #1e3a8a 0%, #312e81 100%) !important; }
  .text-title { color: #f1f5f9 !important; }
  .text-body { color: #94a3b8 !important; }
  .footer-bg { background-color: #0f172a !important; border-color: #334155 !important; }
  .footer-text { color: #64748b !important; }
}
</style>
</head>
<body class="email-bg" style="margin:0;padding:0;background-color:#f1f5f9;font-family:\'Segoe UI\',Roboto,Arial,sans-serif;">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" class="email-bg" style="background-color:#f1f5f9;padding:32px 16px;">
<tr><td align="center">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width:520px;" class="email-card">

<!-- Header -->
<tr><td class="email-header" style="background:linear-gradient(135deg,#1e3a8a 0%,#2563eb 50%,#1d4ed8 100%);border-radius:16px 16px 0 0;padding:32px 28px;text-align:center;">
  <div style="font-size:28px;font-weight:800;color:#ffffff;letter-spacing:-0.5px;">H-Mart</div>
  <div style="font-size:13px;color:rgba(255,255,255,0.85);margin-top:6px;font-weight:500;">Smart Shopping, Delivered Fresh</div>
</td></tr>

<!-- Body -->
<tr><td class="email-card" style="background-color:#ffffff;padding:36px 32px;border-left:1px solid #e2e8f0;border-right:1px solid #e2e8f0;text-align:left;">
  <p class="text-title" style="margin:0 0 8px;font-size:20px;font-weight:700;color:#1e293b;">Welcome to H-Mart!</p>
  <p class="text-body" style="margin:0 0 24px;font-size:15px;line-height:1.6;color:#64748b;">
    We are excited to have you on board! To complete your registration and activate your account, please click the button below to verify your email address:
  </p>

  <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:0 auto 28px;">
  <tr><td align="center" style="border-radius:10px;background:#0c3c78;">
    <a href="' . $actual_link . '" target="_blank" style="display:inline-block;padding:12px 30px;background:#0c3c78;color:#ffffff;text-decoration:none;border-radius:10px;font-weight:700;font-size:15px;font-family:\'Segoe UI\',Arial,sans-serif;border:1px solid #0c3c78;">Verify My Account</a>
  </td></tr>
  </table>

  <p class="text-body" style="margin:0 0 24px;font-size:14px;line-height:1.6;color:#64748b;">
    If the button above does not work, copy and paste the following link into your web browser:
    <br><br>
    <a href="' . $actual_link . '" style="color:#2563eb;word-break:break-all;">' . $actual_link . '</a>
  </p>

  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#f8fafc;border-radius:12px;border:1px solid #e2e8f0;">
  <tr><td style="padding:16px 20px;">
    <p style="margin:0;font-size:13px;color:#64748b;line-height:1.5;">
      <strong style="color:#475569;">Security tip:</strong> This link is valid for 24 hours. If you did not create an account on H-Mart, you can safely ignore this email.
    </p>
  </td></tr>
  </table>
</td></tr>

<!-- Footer -->
<tr><td class="footer-bg" style="background-color:#f8fafc;border:1px solid #e2e8f0;border-top:none;border-radius:0 0 16px 16px;padding:20px 28px;text-align:center;">
  <p class="footer-text" style="margin:0 0 6px;font-size:12px;color:#94a3b8;">&copy; ' . $year . ' H-Mart. All rights reserved.</p>
  <p class="footer-text" style="margin:0;font-size:11px;color:#cbd5e1;">This is an automated message. Please do not reply.</p>
</td></tr>

</table>
</td></tr>
</table>
</body>
</html>';
    }
}
