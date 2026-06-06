<?php
/**
 * Interactive Help Center & FAQ page.
 */
require_once("../backend/include/initialize.php");

// Handle ticket/message submission
if (isset($_POST['submit_ticket'])) {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    
    if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
        global $mydb;
        
        // 1. Create table if not exists
        $mydb->setQuery("
            CREATE TABLE IF NOT EXISTS `contact_messages` (
              `id` INT AUTO_INCREMENT PRIMARY KEY,
              `name` VARCHAR(100) NOT NULL,
              `email` VARCHAR(100) NOT NULL,
              `subject` VARCHAR(255) NOT NULL,
              `message` TEXT NOT NULL,
              `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
        $mydb->executeQuery();
        
        // 2. Insert ticket
        $mydb->setQuery("
            INSERT INTO `contact_messages` (`name`, `email`, `subject`, `message`)
            VALUES (
                '".$mydb->escape_value($name)."',
                '".$mydb->escape_value($email)."',
                '".$mydb->escape_value($subject)."',
                '".$mydb->escape_value($message)."'
            )
        ");
        
        if ($mydb->executeQuery()) {
            message("Thank you! Your help request has been received. Our support team will respond shortly.", "success");
        } else {
            message("Failed to submit support ticket. Please try again.", "error");
        }
    } else {
        message("All form fields are required.", "error");
    }
    redirect("index.php?q=contact");
}
?>

<style type="text/css">
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');

    .help-center-wrapper {
        font-family: 'Outfit', sans-serif;
        color: #1e293b;
        padding: 40px 0 80px;
    }
    .help-header {
        text-align: center;
        margin-bottom: 50px;
    }
    .help-header h1 {
        font-size: 36px;
        font-weight: 800;
        letter-spacing: -0.5px;
        margin-bottom: 10px;
    }
    .help-header p {
        font-size: 16px;
        color: #64748b;
    }
    
    /* FAQ Accordion Styling */
    .faq-container {
        margin-bottom: 30px;
    }
    .faq-item {
        background: white;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        margin-bottom: 15px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
        transition: all 0.2s ease;
    }
    .faq-item:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
    }
    .faq-trigger {
        width: 100%;
        text-align: left;
        background: none;
        border: none;
        padding: 20px 25px;
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        outline: none;
    }
    .faq-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s cubic-bezier(0, 1, 0, 1);
        background: #f8fafc;
        border-top: 0 solid #e2e8f0;
    }
    .faq-content-inner {
        padding: 20px 25px;
        font-size: 14.5px;
        line-height: 1.6;
        color: #475569;
    }
    
    .faq-item.active .faq-trigger {
        color: #2563eb;
    }
    .faq-item.active .faq-content {
        max-height: 1000px;
        border-top-width: 1px;
        transition: max-height 0.3s ease-in-out;
    }
    .faq-icon {
        font-size: 14px;
        transition: transform 0.25s ease;
    }
    .faq-item.active .faq-icon {
        transform: rotate(180deg);
        color: #2563eb;
    }

    /* Help Form Styling */
    .support-form-card {
        background: white;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        padding: 35px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.02);
    }
    .form-title {
        font-size: 20px;
        font-weight: 800;
        margin-bottom: 8px;
    }
    .form-desc {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 25px;
    }
    .form-group label {
        font-size: 12.5px;
        font-weight: 700;
        color: #475569;
        margin-bottom: 6px;
        display: block;
    }
    .help-input {
        width: 100%;
        padding: 12px 16px;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        font-size: 14px;
        font-weight: 500;
        background: white;
        color: #1e293b;
        outline: none;
        transition: border-color 0.2s ease;
    }
    .help-input:focus {
        border-color: #2563eb;
    }
    .btn-submit-help {
        background: #2563eb;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 12px 25px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: background-color 0.2s ease;
        width: 100%;
    }
    .btn-submit-help:hover {
        background: #1d4ed8;
    }

    /* Contact Details */
    .contact-details-box {
        background: #f1f5f9;
        border-radius: 16px;
        padding: 25px;
        border: 1px solid #e2e8f0;
        margin-top: 30px;
    }
    .details-title {
        font-size: 15px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #475569;
        margin-bottom: 15px;
    }
    .detail-item {
        display: flex;
        gap: 12px;
        margin-bottom: 12px;
        font-size: 14px;
    }
    .detail-item i {
        color: #2563eb;
        margin-top: 4px;
    }

    /* Dark Mode Overrides */
    body.dark-mode .help-center-wrapper {
        color: #f1f5f9;
    }
    body.dark-mode .help-header p {
        color: #cbd5e1;
    }
    body.dark-mode .faq-item {
        background: #1e293b;
        border-color: #334155;
    }
    body.dark-mode .faq-trigger {
        color: #f1f5f9;
    }
    body.dark-mode .faq-item.active .faq-trigger {
        color: #38bdf8;
    }
    body.dark-mode .faq-content {
        background: #0f172a;
        border-top-color: #334155;
    }
    body.dark-mode .faq-content-inner {
        color: #cbd5e1;
    }
    body.dark-mode .faq-item.active .faq-icon {
        color: #38bdf8;
    }
    body.dark-mode .support-form-card {
        background: #1e293b;
        border-color: #334155;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }
    body.dark-mode .form-desc {
        color: #cbd5e1;
    }
    body.dark-mode .form-group label {
        color: #cbd5e1;
    }
    body.dark-mode .help-input {
        background: #0f172a;
        border-color: #334155;
        color: #f1f5f9;
    }
    body.dark-mode .help-input:focus {
        border-color: #38bdf8;
    }
    body.dark-mode .btn-submit-help {
        background: #38bdf8;
        color: #0f172a;
    }
    body.dark-mode .btn-submit-help:hover {
        background: #0ea5e9;
    }
    body.dark-mode .contact-details-box {
        background: #0f172a;
        border-color: #334155;
    }
    body.dark-mode .details-title {
        color: #cbd5e1;
    }
    body.dark-mode .detail-item i {
        color: #38bdf8;
    }
</style>

<div class="help-center-wrapper">
    <div class="container">
        <div class="help-header">
            <h1>Help Center & Support</h1>
            <p>Find answers to common issues or submit a support request directly to our desk.</p>
        </div>

        <!-- Alert messages -->
        <?php check_message(); ?>

        <div class="row" style="margin-top: 30px;">
            <!-- FAQ Accordion column (Left) -->
            <div class="col-md-7">
                <h3 style="font-weight: 800; margin-bottom: 25px; text-align: left;">Frequently Asked Questions</h3>
                
                <div class="faq-container">
                    <!-- FAQ 1 -->
                    <div class="faq-item">
                        <button class="faq-trigger" onclick="toggleFaq(this)">
                            How do I track my order?
                            <i class="fa fa-chevron-down faq-icon"></i>
                        </button>
                        <div class="faq-content">
                            <div class="faq-content-inner">
                                To track an order, click on <strong>Account</strong> in the top navigation bar, select <strong>Order History</strong> from the profile panel, and click on the <strong>Details</strong> button next to the order. This will open an interactive status tracker showing whether your order is Pending, Confirmed, Shipped, or Delivered.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 2 -->
                    <div class="faq-item">
                        <button class="faq-trigger" onclick="toggleFaq(this)">
                            How do I cancel my order?
                            <i class="fa fa-chevron-down faq-icon"></i>
                        </button>
                        <div class="faq-content">
                            <div class="faq-content-inner">
                                You can cancel any order that is currently in <strong>Pending</strong> status. Simply click on <strong>Account -> Order History -> Details</strong>, click the <strong>Cancel Order</strong> button, enter your cancellation reason, and confirm. The items will be returned to store stock, and the order will update immediately.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 3 -->
                    <div class="faq-item">
                        <button class="faq-trigger" onclick="toggleFaq(this)">
                            How do I request a Return / Refund?
                            <i class="fa fa-chevron-down faq-icon"></i>
                        </button>
                        <div class="faq-content">
                            <div class="faq-content-inner">
                                For orders that are already **Confirmed** or **Delivered**, you can file a return request within 7 days. Open the order <strong>Details</strong> card from your Order History tab, click <strong>Request Return</strong>, fill in the explanation of the issue (e.g. damaged goods, wrong product), and submit. The shop administrator will review and process your refund.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 4 -->
                    <div class="faq-item">
                        <button class="faq-trigger" onclick="toggleFaq(this)">
                            What are the accepted payment methods?
                            <i class="fa fa-chevron-down faq-icon"></i>
                        </button>
                        <div class="faq-content">
                            <div class="faq-content-inner">
                                H-Mart accepts multiple payment options including **Cash on Delivery (COD)**, **Cash on Pickup**, secure **UPI Transfers** (via QR scan verification), and **Debit/Credit Card Payments**. Transaction records are automatically logged on your customer profile.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ticket Submission Form column (Right) -->
            <div class="col-md-5">
                <div class="support-form-card">
                    <h3 class="form-title">Submit Support Ticket</h3>
                    <p class="form-desc">Having trouble with an order? Contact our support staff.</p>

                    <form action="index.php?q=contact" method="POST">
                        <div class="form-group" style="margin-bottom: 15px;">
                            <label for="name">Your Name</label>
                            <input type="text" name="name" id="name" required class="help-input" placeholder="e.g. Hasheem M" value="<?php echo isset($_SESSION['CUSNAME']) ? $_SESSION['CUSNAME'] : ''; ?>">
                        </div>

                        <div class="form-group" style="margin-bottom: 15px;">
                            <label for="email">Email Address</label>
                            <input type="email" name="email" id="email" required class="help-input" placeholder="e.g. customer@example.com">
                        </div>

                        <div class="form-group" style="margin-bottom: 15px;">
                            <label for="subject">Subject</label>
                            <input type="text" name="subject" id="subject" required class="help-input" placeholder="e.g. Refund delay on Order #HM-103">
                        </div>

                        <div class="form-group" style="margin-bottom: 25px;">
                            <label for="message">Detailed Message</label>
                            <textarea name="message" id="message" required class="help-input" rows="4" placeholder="Describe your issue or question in detail..."></textarea>
                        </div>

                        <button type="submit" name="submit_ticket" class="btn-submit-help">Send Message</button>
                    </form>
                </div>

                <div class="contact-details-box">
                    <h4 class="details-title">Direct Contact Info</h4>
                    
                    <div class="detail-item">
                        <i class="fa fa-envelope-o"></i>
                        <span>support@hmart.com (Average response: < 2 hours)</span>
                    </div>
                    <div class="detail-item">
                        <i class="fa fa-phone"></i>
                        <span>+91-9988776655 (Call toll-free: 9am - 6pm)</span>
                    </div>
                    <div class="detail-item">
                        <i class="fa fa-map-marker"></i>
                        <span>Hmart HQ, Bacolod City, Philippines</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function toggleFaq(button) {
        var item = button.parentNode;
        var isActive = item.classList.contains('active');
        
        // Close all FAQ items
        var allItems = document.querySelectorAll('.faq-item');
        allItems.forEach(function(i) {
            i.classList.remove('active');
        });
        
        // If it wasn't active, open it
        if (!isActive) {
            item.classList.add('active');
        }
    }
</script>