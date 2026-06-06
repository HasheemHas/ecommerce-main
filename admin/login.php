<?php
require_once("../backend/include/initialize.php");
// login confirmation
if(isset($_SESSION['USERID'])){
  redirect(web_root."admin/index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>H-Mart Admin Control Center</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/svg+xml" href="<?php echo web_root; ?>favicon.svg?v=4">
    <link rel="shortcut icon" type="image/svg+xml" href="<?php echo web_root; ?>favicon.svg?v=4">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="<?php echo web_root; ?>admin/font/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        /* ── Theme Variables ── */
        :root {
            --body-bg-from: #e0f2fe;
            --body-bg-to:   #f0f9ff;
            --body-bg-mid:  #bae6fd;
            --card-bg:      rgba(255,255,255,0.65);
            --card-border:  rgba(255,255,255,0.85);
            --card-shadow:  0 20px 40px rgba(0,0,0,0.05);
            --text-h1:      #0f172a;
            --text-sub:     #475569;
            --text-label:   #334155;
            --text-muted:   #64748b;
            --input-bg:     rgba(255,255,255,0.85);
            --input-border: #cbd5e1;
            --input-focus-bg: #fff;
            --input-focus-border: #38bdf8;
            --input-focus-shadow: rgba(56,189,248,0.2);
            --input-color:  #1e293b;
            --icon-color:   #94a3b8;
            --btn-bg:       #0f172a;
            --btn-hover:    #1e293b;
            --badge-bg:     rgba(226,232,240,0.6);
            --badge-color:  #475569;
            --divider:      #e2e8f0;
            --link-color:   #0284c7;
            --remember-color: #475569;
            --footer-color: #64748b;
            --bottom-color: #94a3b8;
            --toggle-bg:    rgba(255,255,255,0.5);
            --toggle-color: #0f172a;
            --toggle-border: rgba(255,255,255,0.8);
        }
        .dark-mode {
            --body-bg-from: #020817;
            --body-bg-to:   #0b0f19;
            --body-bg-mid:  #0f172a;
            --card-bg:      rgba(15,23,42,0.85);
            --card-border:  rgba(30,41,59,0.9);
            --card-shadow:  0 20px 60px rgba(0,0,0,0.5);
            --text-h1:      #f1f5f9;
            --text-sub:     #94a3b8;
            --text-label:   #cbd5e1;
            --text-muted:   #64748b;
            --input-bg:     rgba(30,41,59,0.8);
            --input-border: #1e293b;
            --input-focus-bg: #0f172a;
            --input-focus-border: #60a5fa;
            --input-focus-shadow: rgba(96,165,250,0.2);
            --input-color:  #e2e8f0;
            --icon-color:   #475569;
            --btn-bg:       #1e3a8a;
            --btn-hover:    #1e40af;
            --badge-bg:     rgba(30,41,59,0.7);
            --badge-color:  #94a3b8;
            --divider:      #1e293b;
            --link-color:   #60a5fa;
            --remember-color: #94a3b8;
            --footer-color: #475569;
            --bottom-color: #334155;
            --toggle-bg:    rgba(30,41,59,0.7);
            --toggle-color: #f1f5f9;
            --toggle-border: rgba(30,41,59,0.9);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--body-bg-from) 0%, var(--body-bg-mid) 50%, var(--body-bg-to) 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: background 0.4s ease;
        }

        /* ── Theme Toggle Button ── */
        .theme-toggle {
            position: fixed;
            top: 20px;
            right: 24px;
            z-index: 999;
            background: var(--toggle-bg);
            border: 1px solid var(--toggle-border);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 50px;
            padding: 8px 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            color: var(--toggle-color);
            font-size: 13px;
            font-weight: 600;
            user-select: none;
        }
        .theme-toggle:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }
        .theme-toggle i {
            font-size: 15px;
            transition: transform 0.4s ease;
        }
        .theme-toggle:hover i {
            transform: rotate(20deg);
        }
        .dark-mode .theme-toggle i.fa-sun-o  { display: inline-block; }
        .dark-mode .theme-toggle i.fa-moon-o { display: none; }
        .theme-toggle i.fa-sun-o  { display: none; }
        .theme-toggle i.fa-moon-o { display: inline-block; }

        .admin-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .admin-header h1 {
            color: var(--text-h1);
            font-size: 32px;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin-bottom: 5px;
            transition: color 0.3s;
        }
        .admin-header p {
            color: var(--text-sub);
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            transition: color 0.3s;
        }
        .login-card {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--card-border);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: var(--card-shadow);
            text-align: center;
            transition: background 0.3s, border-color 0.3s, box-shadow 0.3s;
        }
        .login-card h2 {
            font-size: 20px;
            color: var(--text-h1);
            margin-bottom: 5px;
            font-weight: 600;
            transition: color 0.3s;
        }
        .login-card p.subtitle {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 30px;
            transition: color 0.3s;
        }
        .form-group {
            text-align: left;
            margin-bottom: 20px;
            position: relative;
        }
        .form-group label {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: var(--text-label);
            font-weight: 500;
            margin-bottom: 8px;
            transition: color 0.3s;
        }
        .form-group label a {
            color: var(--link-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }
        .input-icon-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        .input-icon-wrapper .input-left-icon {
            position: absolute;
            left: 14px;
            color: var(--icon-color);
            font-size: 14px;
            pointer-events: none;
            transition: color 0.3s;
            z-index: 1;
        }
        .input-icon-wrapper input {
            width: 100%;
            padding: 12px 42px 12px 42px;
            border-radius: 10px;
            border: 1px solid var(--input-border);
            background: var(--input-bg);
            font-size: 14px;
            color: var(--input-color);
            outline: none;
            transition: all 0.25s;
            font-family: 'Inter', sans-serif;
        }
        .input-icon-wrapper input::placeholder {
            color: var(--icon-color);
        }
        .input-icon-wrapper input:focus {
            border-color: var(--input-focus-border);
            box-shadow: 0 0 0 3px var(--input-focus-shadow);
            background: var(--input-focus-bg);
        }
        .input-icon-wrapper input:focus + .input-left-icon,
        .input-icon-wrapper:focus-within .input-left-icon {
            color: var(--input-focus-border);
        }
        .input-icon-wrapper .eye-icon {
            position: absolute;
            right: 14px;
            left: auto;
            cursor: pointer;
            color: var(--icon-color);
            font-size: 14px;
            transition: color 0.2s;
        }
        .input-icon-wrapper .eye-icon:hover {
            color: var(--text-label);
        }
        .remember-wrapper {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            text-align: left;
        }
        .remember-wrapper input {
            margin-right: 8px;
            cursor: pointer;
            accent-color: #1e3a8a;
        }
        .remember-wrapper label {
            font-size: 13px;
            color: var(--remember-color);
            cursor: pointer;
            transition: color 0.3s;
        }
        .submit-btn {
            width: 100%;
            padding: 14px;
            background: var(--btn-bg);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            transition: background 0.2s, transform 0.15s;
            font-family: 'Inter', sans-serif;
        }
        .submit-btn:hover {
            background: var(--btn-hover);
            transform: translateY(-1px);
        }
        .submit-btn:active {
            transform: translateY(0);
        }
        .badges-container {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 30px;
            border-top: 1px solid var(--divider);
            padding-top: 25px;
            transition: border-color 0.3s;
        }
        .badge {
            background: var(--badge-bg);
            color: var(--badge-color);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
            letter-spacing: 0.5px;
            transition: background 0.3s, color 0.3s;
        }
        .footer-text {
            margin-top: 30px;
            font-size: 12px;
            color: var(--footer-color);
            transition: color 0.3s;
        }
        .footer-text a {
            color: var(--text-h1);
            text-decoration: none;
            font-weight: 600;
        }
        .bottom-links {
            position: absolute;
            bottom: 30px;
            font-size: 11px;
            color: var(--bottom-color);
            display: flex;
            gap: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            transition: color 0.3s;
        }
        .error-message {
            background: #fee2e2;
            color: #b91c1c;
            padding: 10px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <!-- Dark/Light Mode Toggle -->
    <button class="theme-toggle" id="login-theme-toggle" onclick="toggleLoginTheme()" title="Toggle dark/light mode">
        <i class="fa fa-moon-o"></i>
        <i class="fa fa-sun-o"></i>
        <span id="theme-label">Dark Mode</span>
    </button>

    <div class="admin-header">
        <h1>H-Mart</h1>
        <p>Admin Control Center</p>
    </div>

    <div class="login-card">
        <h2>Secure Gateway</h2>
        <p class="subtitle">Authorized personnel only</p>
        
        <?php echo check_message(); ?>

        <form method="post" action="">
            <div class="form-group">
                <label>Identity</label>
                <div class="input-icon-wrapper">
                    <i class="fa fa-envelope-o input-left-icon"></i>
                    <input type="text" name="user_email" placeholder="name@hmart.com" required>
                </div>
            </div>

            <div class="form-group">
                <label>Credentials <a href="#">Recovery</a></label>
                <div class="input-icon-wrapper">
                    <i class="fa fa-lock input-left-icon"></i>
                    <input type="password" name="user_pass" placeholder="••••••••" required>
                    <i class="fa fa-eye eye-icon" onclick="togglePassword(this)" title="Show/Hide password"></i>
                </div>
            </div>

            <div class="remember-wrapper">
                <input type="checkbox" id="remember">
                <label for="remember">Maintain persistent session</label>
            </div>

            <button type="submit" name="btnLogin" class="submit-btn">
                Access Dashboard <i class="fa fa-chevron-right"></i>
            </button>
        </form>

        <div class="badges-container">
            <div class="badge"><i class="fa fa-shield"></i> SSL SHIELDED</div>
            <div class="badge"><i class="fa fa-lock"></i> AES-256</div>
        </div>
    </div>

    <div class="footer-text">
        Technical difficulties? <a href="#">Global Support</a>
    </div>

    <div class="bottom-links">
        <span>Privacy</span>
        <span>Legal</span>
        <span>Compliance</span>
        <span>Contact</span>
    </div>

    <script>
    // ── Apply saved theme immediately on load ──
    (function() {
        const saved = localStorage.getItem('admin-theme') || 'light';
        if (saved === 'dark') {
            document.documentElement.classList.add('dark-mode');
            document.getElementById('theme-label').textContent = 'Light Mode';
        }
    })();

    function toggleLoginTheme() {
        const isDark = document.documentElement.classList.toggle('dark-mode');
        const label  = document.getElementById('theme-label');
        if (isDark) {
            localStorage.setItem('admin-theme', 'dark');
            label.textContent = 'Light Mode';
        } else {
            localStorage.setItem('admin-theme', 'light');
            label.textContent = 'Dark Mode';
        }
    }

    function togglePassword(icon) {
        const input = icon.previousElementSibling;
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fa fa-eye-slash eye-icon';
        } else {
            input.type = 'password';
            icon.className = 'fa fa-eye eye-icon';
        }
    }
    </script>
</body>
</html>

<?php 
if(isset($_POST['btnLogin'])){
  $email = trim($_POST['user_email']);
  $upass  = trim($_POST['user_pass']);
  $h_upass = sha1($upass);
  
   if ($email == '' OR $upass == '') {
      message("<div class='error-message'>Invalid Username and Password!</div>", "error");
      redirect("login.php");
    } else {  
    $user = new User();
    $res = $user::userAuthentication($email, $h_upass);
    if ($res==true) { 
      if ($_SESSION['U_ROLE']=='Administrator'){
         redirect(web_root."admin/index.php");
      }else{
           redirect(web_root."admin/login.php");
      }
    }else{
      message("<div class='error-message'>Account does not exist! Please contact Administrator.</div>", "error");
       redirect(web_root."admin/login.php"); 
    }
 }
 } 
 ?>