<?php
/**
 * Terms of Use policy page.
 */
require_once("../backend/include/initialize.php");
?>

<style type="text/css">
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');

    .policy-wrapper {
        font-family: 'Outfit', sans-serif;
        color: #1e293b;
        padding: 40px 0 80px;
        text-align: left;
    }
    .policy-header {
        text-align: center;
        margin-bottom: 50px;
    }
    .policy-header h1 {
        font-size: 36px;
        font-weight: 800;
        letter-spacing: -0.5px;
        margin-bottom: 10px;
        color: #1e3a8a;
    }
    .policy-header p {
        font-size: 15px;
        color: #64748b;
    }
    .policy-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.01);
        line-height: 1.7;
    }
    .policy-card h2 {
        font-size: 20px;
        font-weight: 800;
        color: #1e293b;
        margin-top: 30px;
        margin-bottom: 12px;
        border-left: 4px solid #2563eb;
        padding-left: 12px;
    }
    .policy-card h2:first-of-type {
        margin-top: 0;
    }
    .policy-card p {
        font-size: 15px;
        color: #475569;
        margin-bottom: 16px;
    }
    .policy-card ul {
        margin-bottom: 20px;
        padding-left: 20px;
    }
    .policy-card li {
        font-size: 14.5px;
        color: #475569;
        margin-bottom: 8px;
    }

    /* Dark Mode Overrides */
    body.dark-mode .policy-wrapper {
        color: #f1f5f9;
    }
    body.dark-mode .policy-header h1 {
        color: #38bdf8;
    }
    body.dark-mode .policy-header p {
        color: #cbd5e1;
    }
    body.dark-mode .policy-card {
        background: #1e293b;
        border-color: #334155;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    body.dark-mode .policy-card h2 {
        color: #f1f5f9;
        border-left-color: #38bdf8;
    }
    body.dark-mode .policy-card p,
    body.dark-mode .policy-card li {
        color: #cbd5e1;
    }
</style>

<div class="policy-wrapper">
    <div class="container">
        <div class="policy-header">
            <h1>Terms of Use</h1>
            <p>Last Updated: June 2, 2026</p>
        </div>

        <div class="policy-card">
            <h2>1. Acceptance of Terms</h2>
            <p>Welcome to H-Mart. By accessing or using our website, services, and AI personal shopper features, you agree to comply with and be bound by the following Terms of Use. If you do not agree, please do not access or use the platform.</p>

            <h2>2. User Accounts & Registration</h2>
            <p>To place orders and manage tracking, you must create a customer account. You are solely responsible for:</p>
            <ul>
                <li>Maintaining the confidentiality of your credentials and account password.</li>
                <li>Ensuring the accuracy of all billing, shipping, and email details supplied.</li>
                <li>Promptly notifying support if any unauthorized access is suspected.</li>
            </ul>

            <h2>3. Product Information & Pricing</h2>
            <p>H-Mart strives to ensure that all catalog details, original prices, promotional prices, and stock counts are accurate. However, typographical errors or database synchronization lags may occur. We reserve the right to correct errors or cancel orders placed with inaccurate pricing metadata.</p>

            <h2>4. AI Shopper & Automated Recommendations</h2>
            <p>Our conversational AI features generate suggestions based on natural language inputs, customer search profiles, and stock availability. These recommendations are for informational assistance only, and we do not guarantee specific item suitability for individual users.</p>

            <h2>5. Order Acceptance & Cancellations</h2>
            <p>We reserve the right, at our sole discretion, to refuse or cancel any order for reasons such as lack of stock, card payment failures, or policy violations. Orders can only be cancelled by the user while they remain in a pending state.</p>

            <h2>6. Liability Disclaimers</h2>
            <p>The platform is provided "as is" without warranties of any kind. H-Mart will not be liable for any direct, indirect, or incidental damages arising from platform downtime, delivery carrier delays, or database transmission issues.</p>
        </div>
    </div>
</div>