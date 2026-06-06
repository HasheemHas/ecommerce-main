<?php
/**
 * Refund Policy page.
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
            <h1>Refund & Returns Policy</h1>
            <p>Last Updated: June 2, 2026</p>
        </div>

        <div class="policy-card">
            <h2>1. Cancellation Window</h2>
            <p>Customers can request cancellation for any order immediately after placement, as long as the order status is currently labeled as **Pending** in the customer profile portal. Once an order is processed, packaged, or marked as **Shipped**, standard cancellation rules will not apply.</p>

            <h2>2. Eligibility for Returns & Refunds</h2>
            <p>You can request returns or refunds within <strong>7 calendar days</strong> from the delivery timestamp. To qualify for a refund, items must satisfy the following conditions:</p>
            <ul>
                <li>The item must be in its original packaging with all security tags and brand tags intact.</li>
                <li>The product must remain unused, unwashed, and in the same state it was delivered.</li>
                <li>Items bought during clearances or labeled non-refundable are excluded.</li>
            </ul>

            <h2>3. Return Process</h2>
            <p>Filing a return request is simple:</p>
            <ul>
                <li>Log in to your account and navigate to <strong>Profile -> Order History</strong>.</li>
                <li>Click <strong>Details</strong> next to the target order card.</li>
                <li>If the purchase is marked as **Delivered** and falls within the 7-day window, click on <strong>Request Return</strong>.</li>
                <li>State the specific reasons (e.g., incorrect sizing, damaged product, defective parts) and click submit.</li>
                <li>The support desk will contact you via email to schedule return pickup.</li>
            </ul>

            <h2>4. Refund Timeframe</h2>
            <p>Once a returned item is received at our logistics hub and passes quality inspection, we will approve the refund. Approved refunds are credited within <strong>5-7 business days</strong> to the original payment channel (UPI, Card, Bank account, or local store pickup wallet).</p>
        </div>
    </div>
</div>
