<?php
/**
 * Affiliate Program page.
 */
require_once("../backend/include/initialize.php");

// Handle Affiliate application submission
if (isset($_POST['apply_affiliate'])) {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $website = isset($_POST['website']) ? trim($_POST['website']) : '';
    $promo_method = isset($_POST['promo_method']) ? trim($_POST['promo_method']) : '';
    $message_text = isset($_POST['message']) ? trim($_POST['message']) : '';

    if (!empty($name) && !empty($email) && !empty($website) && !empty($promo_method)) {
        global $mydb;
        
        // 1. Create table if not exists
        $mydb->setQuery("
            CREATE TABLE IF NOT EXISTS `affiliate_registrations` (
              `id` INT AUTO_INCREMENT PRIMARY KEY,
              `name` VARCHAR(100) NOT NULL,
              `email` VARCHAR(100) NOT NULL,
              `website` VARCHAR(255) NOT NULL,
              `promo_method` VARCHAR(100) NOT NULL,
              `message` TEXT NOT NULL,
              `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
        $mydb->executeQuery();
        
        // 2. Insert application
        $mydb->setQuery("
            INSERT INTO `affiliate_registrations` (`name`, `email`, `website`, `promo_method`, `message`)
            VALUES (
                '".$mydb->escape_value($name)."',
                '".$mydb->escape_value($email)."',
                '".$mydb->escape_value($website)."',
                '".$mydb->escape_value($promo_method)."',
                '".$mydb->escape_value($message_text)."'
            )
        ");
        
        if ($mydb->executeQuery()) {
            message("Congratulations! Your affiliate request has been received. Our partnerships manager will contact you shortly.", "success");
        } else {
            message("Failed to submit request. Please try again later.", "error");
        }
    } else {
        message("All mandatory form fields must be filled.", "error");
    }
    redirect("index.php?q=affiliate");
}
?>

<style type="text/css">
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');

    .affiliate-wrapper {
        font-family: 'Outfit', sans-serif;
        color: #1e293b;
        padding: 40px 0 80px;
    }
    .affiliate-header {
        text-align: center;
        margin-bottom: 50px;
    }
    .affiliate-header h1 {
        font-size: 38px;
        font-weight: 800;
        letter-spacing: -0.5px;
        margin-bottom: 12px;
        color: #1e3a8a;
    }
    .affiliate-header p {
        font-size: 16px;
        color: #64748b;
        max-width: 600px;
        margin: 0 auto;
    }

    /* Benefits Section */
    .benefit-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 25px;
        margin-bottom: 50px;
    }
    .benefit-card {
        padding: 30px;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01);
        text-align: left;
        transition: transform 0.2s ease;
    }
    .benefit-card:hover {
        transform: translateY(-4px);
    }
    .benefit-icon {
        width: 48px;
        height: 48px;
        background: #dbeafe;
        color: #2563eb;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-bottom: 20px;
    }
    .benefit-card h4 {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 10px;
        color: #1e293b;
    }
    .benefit-card p {
        font-size: 14px;
        color: #64748b;
        line-height: 1.6;
        margin: 0;
    }

    /* Signup Card */
    .signup-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 35px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
        text-align: left;
        max-width: 650px;
        margin: 0 auto;
    }
    .signup-card h3 {
        font-size: 22px;
        font-weight: 800;
        margin-bottom: 8px;
        text-align: center;
    }
    .signup-card p {
        font-size: 13.5px;
        color: #64748b;
        margin-bottom: 25px;
        text-align: center;
    }
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    .affiliate-form-group {
        margin-bottom: 16px;
    }
    .affiliate-form-group label {
        font-size: 12.5px;
        font-weight: 700;
        color: #475569;
        margin-bottom: 6px;
        display: block;
    }
    .affiliate-input {
        width: 100%;
        padding: 11px 15px;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        font-size: 14px;
        font-weight: 500;
        background: white;
        color: #1e293b;
        outline: none;
        transition: border-color 0.2s ease;
    }
    .affiliate-input:focus {
        border-color: #2563eb;
    }
    .btn-affiliate-submit {
        background: #2563eb;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 12px 25px;
        font-size: 14.5px;
        font-weight: 700;
        cursor: pointer;
        transition: background-color 0.2s ease;
        width: 100%;
        margin-top: 10px;
    }
    .btn-affiliate-submit:hover {
        background: #1d4ed8;
    }

    /* Dark Mode Overrides */
    body.dark-mode .affiliate-wrapper {
        color: #f1f5f9;
    }
    body.dark-mode .affiliate-header h1 {
        color: #38bdf8;
    }
    body.dark-mode .affiliate-header p {
        color: #cbd5e1;
    }
    body.dark-mode .benefit-card {
        background: #1e293b;
        border-color: #334155;
    }
    body.dark-mode .benefit-icon {
        background: #1e293b;
        color: #38bdf8;
    }
    body.dark-mode .benefit-card h4 {
        color: #f1f5f9;
    }
    body.dark-mode .benefit-card p {
        color: #cbd5e1;
    }
    body.dark-mode .signup-card {
        background: #1e293b;
        border-color: #334155;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    body.dark-mode .signup-card p {
        color: #cbd5e1;
    }
    body.dark-mode .affiliate-form-group label {
        color: #cbd5e1;
    }
    body.dark-mode .affiliate-input {
        background: #0f172a;
        border-color: #334155;
        color: #f1f5f9;
    }
    body.dark-mode .affiliate-input:focus {
        border-color: #38bdf8;
    }
    body.dark-mode .btn-affiliate-submit {
        background: #38bdf8;
        color: #0f172a;
    }
    body.dark-mode .btn-affiliate-submit:hover {
        background: #0ea5e9;
    }

    @media (max-width: 768px) {
        .benefit-grid {
            grid-template-columns: 1fr;
        }
        .form-row {
            grid-template-columns: 1fr;
            gap: 0;
        }
    }
</style>

<div class="affiliate-wrapper">
    <div class="container">
        <!-- Header -->
        <div class="affiliate-header">
            <h1>Affiliate Partner Program</h1>
            <p>Monetize your audience traffic. Recommend H-Mart items to your readers and earn premium payouts on every completed purchase referral.</p>
        </div>

        <!-- Alert messages -->
        <?php check_message(); ?>

        <!-- Benefits Section -->
        <div class="benefit-grid">
            <!-- Benefit 1 -->
            <div class="benefit-card">
                <div class="benefit-icon">
                    <i class="fa fa-percent"></i>
                </div>
                <h4>High Commission Rates</h4>
                <p>Earn up to 10% commission on every qualifying order purchase completed through your affiliate link.</p>
            </div>

            <!-- Benefit 2 -->
            <div class="benefit-card">
                <div class="benefit-icon">
                    <i class="fa fa-clock-o"></i>
                </div>
                <h4>30-Day Cookie Window</h4>
                <p>We log and credit referrals for up to 30 full days after a potential buyer clicks your partner URL link.</p>
            </div>

            <!-- Benefit 3 -->
            <div class="benefit-card">
                <div class="benefit-icon">
                    <i class="fa fa-dashboard"></i>
                </div>
                <h4>Live Tracker Console</h4>
                <p>Monitor your link click rates, conversion details, referral balances, and payout schedules in real-time.</p>
            </div>
        </div>

        <!-- Signup Form -->
        <div class="signup-card">
            <h3>Register as an Affiliate Partner</h3>
            <p>Submit your channels below to receive affiliate link creation tools.</p>

            <form action="index.php?q=affiliate" method="POST">
                <div class="form-row">
                    <div class="affiliate-form-group">
                        <label for="name">Your Name</label>
                        <input type="text" name="name" id="name" required class="affiliate-input" placeholder="e.g. Hasheem M" value="<?php echo isset($_SESSION['CUSNAME']) ? $_SESSION['CUSNAME'] : ''; ?>">
                    </div>

                    <div class="affiliate-form-group">
                        <label for="email">Email Address</label>
                        <input type="email" name="email" id="email" required class="affiliate-input" placeholder="e.g. partner@example.com">
                    </div>
                </div>

                <div class="form-row">
                    <div class="affiliate-form-group">
                        <label for="website">Website URL / Channel Link</label>
                        <input type="url" name="website" id="website" required class="affiliate-input" placeholder="e.g. https://myblog.com">
                    </div>

                    <div class="affiliate-form-group">
                        <label for="promo_method">Primary Promotion Method</label>
                        <select name="promo_method" id="promo_method" required class="affiliate-input">
                            <option value="">-- Select Method --</option>
                            <option value="Blog Content">Blog Content / Product Reviews</option>
                            <option value="Social Media Influence">Social Media Influence</option>
                            <option value="Email Newsletters">Email Newsletters</option>
                            <option value="Price Comparison Site">Price Comparison Site</option>
                        </select>
                    </div>
                </div>

                <div class="affiliate-form-group">
                    <label for="message">About Your Platform</label>
                    <textarea name="message" id="message" class="affiliate-input" rows="4" placeholder="Briefly describe your channel audience and promotion strategy..."></textarea>
                </div>

                <button type="submit" name="apply_affiliate" class="btn-affiliate-submit">Submit Application</button>
            </form>
        </div>
    </div>
</div>
