<?php
/**
 * Delivery Information page.
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
            <h1>Delivery & Shipping Information</h1>
            <p>Last Updated: June 2, 2026</p>
        </div>

        <div class="policy-card">
            <h2>1. Ship Zones & Localities</h2>
            <p>H-Mart ships across all major regional hubs, states, and cities. We work directly with leading carrier channels to assure that local deliveries reach both metropolitan centers and outer suburbs efficiently.</p>

            <h2>2. Dispatch & Handling Timeline</h2>
            <p>Orders verified before 3:00 PM are cataloged, packaged, and dispatched from our local fulfillment depots on the same day. All orders placed during evenings or holidays will transition to handling status on the subsequent business day.</p>

            <h2>3. Shipping Fees & Delivery Timelines</h2>
            <ul>
                <li><strong>Standard Express Shipping:</strong> Delivery in 3-5 business days. Free for all orders above ₹999; otherwise, a flat fee of ₹99 applies.</li>
                <li><strong>Local Premium Delivery:</strong> Delivery in 1-2 business days (available in selected cities). A premium service charge of ₹199 applies.</li>
            </ul>

            <h2>4. Tracking & Delivery Checks</h2>
            <p>Once a package leaves our distribution facility, an alert containing your unique tracking link is updated under <strong>Account -> Track Order</strong>. You can view step-by-step updates from dispatch to final delivery on the tracking dashboard.</p>
        </div>
    </div>
</div>
