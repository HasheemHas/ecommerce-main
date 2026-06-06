<?php
/**
 * Privacy Policy page.
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
            <h1>Privacy Policy</h1>
            <p>Last Updated: June 2, 2026</p>
        </div>

        <div class="policy-card">
            <h2>1. Information We Collect</h2>
            <p>We collect essential account details, transaction data, and platform usage metrics to provide a secure and customized experience, including:</p>
            <ul>
                <li><strong>Personal Data:</strong> Name, physical address, email, telephone number, and login credentials.</li>
                <li><strong>Transaction Records:</strong> Orders placed, billing details, and specific payment options selected.</li>
                <li><strong>Interactive Logs:</strong> Dialogue queries exchanged with our conversational AI Personal Shopper to optimize recommendation algorithms.</li>
            </ul>

            <h2>2. How We Use Your Data</h2>
            <p>We process customer information strictly to fulfill purchase requests and platform features, such as:</p>
            <ul>
                <li>Fulfilling order logistics, packaging, and dispatch tracking.</li>
                <li>Sending system alerts, password recovery links, and ticket follow-ups.</li>
                <li>Providing customized search outputs based on preferred tags (shoes, apparel, electronics).</li>
            </ul>

            <h2>3. Information Sharing & Third Parties</h2>
            <p>H-Mart does not sell or lease customer records. Data is shared with external partners only for service execution, such as:</p>
            <ul>
                <li>Delivery and courier networks for physical shipping.</li>
                <li>Payment verification and processing services.</li>
                <li>Database servers securing backup data files.</li>
            </ul>

            <h2>4. Security Measures</h2>
            <p>We employ robust security controls including cryptographic hashing (SHA-1/bcrypt) for password protection, TLS connection protocols, and strict access controls on the database console to prevent unauthorized breaches.</p>
        </div>
    </div>
</div>
