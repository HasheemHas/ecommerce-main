<?php
/**
 * Store Location page.
 */
require_once("../backend/include/initialize.php");
?>

<style type="text/css">
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');

    .location-wrapper {
        font-family: 'Outfit', sans-serif;
        color: #1e293b;
        padding: 40px 0 80px;
    }
    .location-header {
        text-align: center;
        margin-bottom: 50px;
    }
    .location-header h1 {
        font-size: 38px;
        font-weight: 800;
        letter-spacing: -0.5px;
        margin-bottom: 12px;
        color: #1e3a8a;
    }
    .location-header p {
        font-size: 16px;
        color: #64748b;
    }
    
    /* Layout Cards */
    .location-grid {
        display: grid;
        grid-template-columns: 1.2fr 0.8fr;
        gap: 30px;
    }
    .map-mockup-card {
        background: #e2e8f0;
        border-radius: 20px;
        border: 1px solid #cbd5e1;
        min-height: 400px;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
    }
    .map-placeholder-content {
        text-align: center;
        padding: 40px;
    }
    .map-placeholder-content i {
        font-size: 64px;
        color: #ef4444;
        margin-bottom: 20px;
    }
    .map-placeholder-content h3 {
        font-size: 22px;
        font-weight: 800;
        margin-bottom: 8px;
        color: #1e293b;
    }
    .map-placeholder-content p {
        font-size: 14.5px;
        color: #64748b;
        max-width: 320px;
        margin: 0 auto;
    }

    .info-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 35px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
        text-align: left;
    }
    .info-section {
        margin-bottom: 25px;
    }
    .info-section:last-child {
        margin-bottom: 0;
    }
    .info-section h3 {
        font-size: 17px;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .info-item {
        display: flex;
        gap: 12px;
        font-size: 14.5px;
        color: #475569;
        margin-bottom: 10px;
        line-height: 1.6;
    }
    .info-item i {
        color: #2563eb;
        margin-top: 4px;
    }

    /* Dark Mode Overrides */
    body.dark-mode .location-wrapper {
        color: #f1f5f9;
    }
    body.dark-mode .location-header h1 {
        color: #38bdf8;
    }
    body.dark-mode .location-header p {
        color: #cbd5e1;
    }
    body.dark-mode .map-mockup-card {
        background: #1e293b;
        border-color: #334155;
    }
    body.dark-mode .map-placeholder-content h3 {
        color: #f1f5f9;
    }
    body.dark-mode .map-placeholder-content p {
        color: #cbd5e1;
    }
    body.dark-mode .info-card {
        background: #1e293b;
        border-color: #334155;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    body.dark-mode .info-section h3 {
        color: #f1f5f9;
    }
    body.dark-mode .info-item {
        color: #cbd5e1;
    }
    body.dark-mode .info-item i {
        color: #38bdf8;
    }

    @media (max-width: 768px) {
        .location-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="location-wrapper">
    <div class="container">
        <!-- Header -->
        <div class="location-header">
            <h1>Store Location</h1>
            <p>Visit our headquarters or check dispatch details for local shipping networks.</p>
        </div>

        <div class="location-grid">
            <!-- Left: Interactive Map Mockup -->
            <div class="map-mockup-card">
                <div class="map-placeholder-content">
                    <i class="fa fa-map-marker"></i>
                    <h3>H-Mart Headquarters</h3>
                    <p>Bacolod City, Philippines. Interactive mapping coordinates loaded successfully.</p>
                </div>
            </div>

            <!-- Right: Details Card -->
            <div class="info-card">
                <!-- Section 1 -->
                <div class="info-section">
                    <h3>Address & Contact</h3>
                    <div class="info-item">
                        <i class="fa fa-home"></i>
                        <span>Hmart HQ Building, Lacson Street, Bacolod City, Philippines</span>
                    </div>
                    <div class="info-item">
                        <i class="fa fa-phone"></i>
                        <span>+91-9988776655</span>
                    </div>
                    <div class="info-item">
                        <i class="fa fa-envelope"></i>
                        <span>support@hmart.com</span>
                    </div>
                </div>

                <!-- Section 2 -->
                <div class="info-section" style="border-top: 1px solid #f1f5f9; padding-top: 20px; margin-top: 20px;">
                    <h3>Store Hours</h3>
                    <div class="info-item">
                        <i class="fa fa-clock-o"></i>
                        <span>Monday - Friday: 9:00 AM - 8:00 PM</span>
                    </div>
                    <div class="info-item">
                        <i class="fa fa-clock-o"></i>
                        <span>Saturday: 10:00 AM - 6:00 PM</span>
                    </div>
                    <div class="info-item">
                        <i class="fa fa-ban"></i>
                        <span>Sunday: Closed (Online orders open 24/7)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    if(document.body.classList.contains('dark-mode')) {
        var topRow = document.querySelector('.info-section');
        if(topRow && topRow.nextElementSibling) {
            topRow.nextElementSibling.style.borderTopColor = '#334155';
        }
    }
</script>
