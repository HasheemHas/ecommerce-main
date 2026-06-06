<?php
/**
 * Company Information & About Us page.
 */
require_once("../backend/include/initialize.php");
?>

<style type="text/css">
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');

    .about-wrapper {
        font-family: 'Outfit', sans-serif;
        color: #1e293b;
        padding: 40px 0 80px;
    }
    .about-header {
        text-align: center;
        margin-bottom: 60px;
    }
    .about-header h1 {
        font-size: 38px;
        font-weight: 800;
        letter-spacing: -0.5px;
        margin-bottom: 15px;
        color: #1e3a8a;
    }
    .about-header p {
        font-size: 17px;
        color: #64748b;
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
    }
    
    /* Grid layout */
    .about-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        margin-bottom: 60px;
        align-items: center;
    }
    .about-image-card {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        border-radius: 24px;
        padding: 60px 40px;
        color: white;
        text-align: center;
        box-shadow: 0 20px 40px rgba(30, 58, 138, 0.1);
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-height: 380px;
    }
    .about-image-card h2 {
        font-size: 48px;
        font-weight: 800;
        margin-bottom: 10px;
    }
    .about-image-card p {
        font-size: 16px;
        opacity: 0.9;
        max-width: 320px;
        margin: 0 auto;
    }

    .about-content-card {
        text-align: left;
    }
    .about-subtitle {
        font-size: 12px;
        font-weight: 800;
        color: #3b82f6;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 10px;
    }
    .about-content-card h3 {
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 20px;
        color: #1e293b;
    }
    .about-content-card p {
        font-size: 15.5px;
        line-height: 1.7;
        color: #475569;
        margin-bottom: 20px;
    }

    /* Core Values */
    .values-section {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.01);
        margin-bottom: 60px;
    }
    .values-title {
        text-align: center;
        font-size: 26px;
        font-weight: 800;
        margin-bottom: 40px;
    }
    .values-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 25px;
    }
    .value-card {
        padding: 25px;
        background: #f8fafc;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        text-align: left;
    }
    .value-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.03);
    }
    .value-icon {
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
    .value-card h4 {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 10px;
        color: #1e293b;
    }
    .value-card p {
        font-size: 14px;
        color: #64748b;
        line-height: 1.6;
        margin: 0;
    }

    /* Dark Mode Overrides */
    body.dark-mode .about-wrapper {
        color: #f1f5f9;
    }
    body.dark-mode .about-header h1 {
        color: #38bdf8;
    }
    body.dark-mode .about-header p {
        color: #cbd5e1;
    }
    body.dark-mode .about-content-card h3 {
        color: #f1f5f9;
    }
    body.dark-mode .about-content-card p {
        color: #cbd5e1;
    }
    body.dark-mode .values-section {
        background: #1e293b;
        border-color: #334155;
    }
    body.dark-mode .values-title {
        color: #f1f5f9;
    }
    body.dark-mode .value-card {
        background: #0f172a;
        border-color: #334155;
    }
    body.dark-mode .value-icon {
        background: #1e293b;
        color: #38bdf8;
    }
    body.dark-mode .value-card h4 {
        color: #f1f5f9;
    }
    body.dark-mode .value-card p {
        color: #94a3b8;
    }
    
    @media (max-width: 768px) {
        .about-grid {
            grid-template-columns: 1fr;
            gap: 30px;
        }
        .values-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="about-wrapper">
    <div class="container">
        <!-- Header -->
        <div class="about-header">
            <h1>About H-Mart</h1>
            <p>Empowering millions of smart shoppers with AI-assisted product selection, top-tier brands, and seamless order logistics.</p>
        </div>

        <!-- Grid: Image/Card & Content -->
        <div class="about-grid">
            <div class="about-image-card">
                <h2>10M+</h2>
                <p>Happy Customers Worldwide</p>
                <div style="margin-top: 30px; border-top: 1px solid rgba(255,255,255,0.2); padding-top: 20px;">
                    <h4 style="font-weight: 700; margin: 0 0 5px 0;">Established 2026</h4>
                    <p style="font-size: 13px; opacity: 0.8;">Pioneering Smart E-Commerce Solutions</p>
                </div>
            </div>
            
            <div class="about-content-card">
                <div class="about-subtitle">Our Journey</div>
                <h3>Building the Future of Retail</h3>
                <p>H-Mart began with a simple vision: to bridge the gap between AI personalization and high-quality commerce. Today, we are proud to offer thousands of curated products in shoes, apparel, electronics, and home interiors, all tailored specifically to customer preferences.</p>
                <p>By leveraging secure payment structures, robust delivery systems, and real-time support channels, we continue to deliver premium value directly to your doorstep. We are driven by customer satisfaction and technical innovation, ensuring that every purchase is secure, fast, and exactly what you wanted.</p>
            </div>
        </div>

        <!-- Core Values Section -->
        <div class="values-section">
            <h3 class="values-title">Our Core Values</h3>
            <div class="values-grid">
                <!-- Value 1 -->
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fa fa-shield"></i>
                    </div>
                    <h4>Trust & Security</h4>
                    <p>Every transaction, payment method, and personal detail is secured with state-of-the-art encryption protocols.</p>
                </div>

                <!-- Value 2 -->
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fa fa-bolt"></i>
                    </div>
                    <h4>Lightning Delivery</h4>
                    <p>Our intelligent shipping routes and warehouse logistics ensure orders are packaged and delivered in record time.</p>
                </div>

                <!-- Value 3 -->
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fa fa-comments"></i>
                    </div>
                    <h4>Support Everywhere</h4>
                    <p>From dynamic live help desks to immediate FAQ responses, we are always here to resolve any issues.</p>
                </div>
            </div>
        </div>
    </div>
</div>