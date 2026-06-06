<?php
/**
 * Interactive Careers page.
 */
require_once("../backend/include/initialize.php");

// Handle Careers application submission
if (isset($_POST['apply_job'])) {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $role = isset($_POST['role']) ? trim($_POST['role']) : '';
    $experience = isset($_POST['experience']) ? trim($_POST['experience']) : '';
    $message_text = isset($_POST['message']) ? trim($_POST['message']) : '';

    if (!empty($name) && !empty($email) && !empty($phone) && !empty($role) && !empty($experience)) {
        global $mydb;
        
        // 1. Create table if not exists
        $mydb->setQuery("
            CREATE TABLE IF NOT EXISTS `career_applications` (
              `id` INT AUTO_INCREMENT PRIMARY KEY,
              `name` VARCHAR(100) NOT NULL,
              `email` VARCHAR(100) NOT NULL,
              `phone` VARCHAR(30) NOT NULL,
              `role` VARCHAR(100) NOT NULL,
              `experience` VARCHAR(100) NOT NULL,
              `message` TEXT NOT NULL,
              `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
        $mydb->executeQuery();
        
        // 2. Insert application
        $mydb->setQuery("
            INSERT INTO `career_applications` (`name`, `email`, `phone`, `role`, `experience`, `message`)
            VALUES (
                '".$mydb->escape_value($name)."',
                '".$mydb->escape_value($email)."',
                '".$mydb->escape_value($phone)."',
                '".$mydb->escape_value($role)."',
                '".$mydb->escape_value($experience)."',
                '".$mydb->escape_value($message_text)."'
            )
        ");
        
        if ($mydb->executeQuery()) {
            message("Congratulations! Your application for the " . htmlspecialchars($role) . " position has been successfully submitted.", "success");
        } else {
            message("Failed to submit application. Please try again later.", "error");
        }
    } else {
        message("All mandatory form fields must be filled.", "error");
    }
    redirect("index.php?q=careers");
}
?>

<style type="text/css">
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');

    .careers-wrapper {
        font-family: 'Outfit', sans-serif;
        color: #1e293b;
        padding: 40px 0 80px;
    }
    .careers-header {
        text-align: center;
        margin-bottom: 50px;
    }
    .careers-header h1 {
        font-size: 38px;
        font-weight: 800;
        letter-spacing: -0.5px;
        margin-bottom: 12px;
        color: #1e3a8a;
    }
    .careers-header p {
        font-size: 16px;
        color: #64748b;
        max-width: 600px;
        margin: 0 auto;
    }
    
    /* Open Positions Styling */
    .job-list {
        margin-bottom: 40px;
    }
    .job-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 20px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01);
        text-align: left;
        transition: transform 0.2s ease, border-color 0.2s ease;
    }
    .job-card:hover {
        transform: translateY(-2px);
        border-color: #cbd5e1;
    }
    .job-title-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    .job-title {
        font-size: 19px;
        font-weight: 800;
        color: #1e293b;
        margin: 0;
    }
    .job-badge {
        font-size: 10.5px;
        font-weight: 800;
        text-transform: uppercase;
        background: #dbeafe;
        color: #1e40af;
        padding: 4px 10px;
        border-radius: 20px;
        letter-spacing: 0.5px;
    }
    .job-meta {
        display: flex;
        gap: 15px;
        font-size: 13.5px;
        color: #64748b;
        margin-bottom: 15px;
    }
    .job-meta i {
        margin-right: 4px;
    }
    .job-desc {
        font-size: 14.5px;
        line-height: 1.6;
        color: #475569;
        margin: 0;
    }

    /* Application Card */
    .apply-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 35px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
        text-align: left;
    }
    .apply-card h3 {
        font-size: 22px;
        font-weight: 800;
        margin-bottom: 8px;
    }
    .apply-card p {
        font-size: 13.5px;
        color: #64748b;
        margin-bottom: 25px;
    }
    .careers-form-group {
        margin-bottom: 16px;
    }
    .careers-form-group label {
        font-size: 12.5px;
        font-weight: 700;
        color: #475569;
        margin-bottom: 6px;
        display: block;
    }
    .careers-input {
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
    .careers-input:focus {
        border-color: #2563eb;
    }
    .btn-apply-submit {
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
    .btn-apply-submit:hover {
        background: #1d4ed8;
    }

    /* Dark Mode Overrides */
    body.dark-mode .careers-wrapper {
        color: #f1f5f9;
    }
    body.dark-mode .careers-header h1 {
        color: #38bdf8;
    }
    body.dark-mode .careers-header p {
        color: #cbd5e1;
    }
    body.dark-mode .job-card {
        background: #1e293b;
        border-color: #334155;
    }
    body.dark-mode .job-title {
        color: #f1f5f9;
    }
    body.dark-mode .job-badge {
        background: #1e293b;
        color: #38bdf8;
        border: 1px solid #38bdf8;
    }
    body.dark-mode .job-meta {
        color: #cbd5e1;
    }
    body.dark-mode .job-desc {
        color: #cbd5e1;
    }
    body.dark-mode .apply-card {
        background: #1e293b;
        border-color: #334155;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    body.dark-mode .apply-card p {
        color: #cbd5e1;
    }
    body.dark-mode .careers-form-group label {
        color: #cbd5e1;
    }
    body.dark-mode .careers-input {
        background: #0f172a;
        border-color: #334155;
        color: #f1f5f9;
    }
    body.dark-mode .careers-input:focus {
        border-color: #38bdf8;
    }
    body.dark-mode .btn-apply-submit {
        background: #38bdf8;
        color: #0f172a;
    }
    body.dark-mode .btn-apply-submit:hover {
        background: #0ea5e9;
    }
</style>

<div class="careers-wrapper">
    <div class="container">
        <!-- Header -->
        <div class="careers-header">
            <h1>Careers at H-Mart</h1>
            <p>Help us shape the future of smart commerce. We are looking for talented, passionate individuals to join our growing global team.</p>
        </div>

        <!-- Alert messages -->
        <?php check_message(); ?>

        <div class="row" style="margin-top: 30px;">
            <!-- Left Column: Open Roles -->
            <div class="col-md-7">
                <h3 style="font-weight: 800; margin-bottom: 25px; text-align: left;">Open Positions</h3>
                
                <div class="job-list">
                    <!-- Job 1 -->
                    <div class="job-card">
                        <div class="job-title-row">
                            <h4 class="job-title">Full Stack PHP Developer</h4>
                            <span class="job-badge">Full-time</span>
                        </div>
                        <div class="job-meta">
                            <span><i class="fa fa-map-marker"></i> Remote / Manila</span>
                            <span><i class="fa fa-briefcase"></i> 3+ Years Exp</span>
                        </div>
                        <p class="job-desc">Join our core platform group to develop new features, refine search models, build API integrations, and expand our database infrastructure for global scalability.</p>
                    </div>

                    <!-- Job 2 -->
                    <div class="job-card">
                        <div class="job-title-row">
                            <h4 class="job-title">Customer Support Representative</h4>
                            <span class="job-badge">Full-time</span>
                        </div>
                        <div class="job-meta">
                            <span><i class="fa fa-map-marker"></i> Bacolod Office</span>
                            <span><i class="fa fa-briefcase"></i> 1+ Year Exp</span>
                        </div>
                        <p class="job-desc">Help manage client order inquiries, handle refund requests, answer delivery queries, and provide premium ticketing assistance to H-Mart members.</p>
                    </div>

                    <!-- Job 3 -->
                    <div class="job-card">
                        <div class="job-title-row">
                            <h4 class="job-title">Logistics & Supply Chain Specialist</h4>
                            <span class="job-badge">Contract</span>
                        </div>
                        <div class="job-meta">
                            <span><i class="fa fa-map-marker"></i> Cebu Warehouse</span>
                            <span><i class="fa fa-briefcase"></i> 2+ Years Exp</span>
                        </div>
                        <p class="job-desc">Orchestrate fulfillment operations, manage real-time inventory counts, and coordinate with courier partners to maintain premium shipping timelines.</p>
                    </div>
                </div>
            </div>

            <!-- Right Column: Application Form -->
            <div class="col-md-5">
                <div class="apply-card">
                    <h3>Join H-Mart</h3>
                    <p>Submit your application and our talent acquisition team will review your profile.</p>

                    <form action="index.php?q=careers" method="POST">
                        <div class="careers-form-group">
                            <label for="name">Your Name</label>
                            <input type="text" name="name" id="name" required class="careers-input" placeholder="e.g. Hasheem M" value="<?php echo isset($_SESSION['CUSNAME']) ? $_SESSION['CUSNAME'] : ''; ?>">
                        </div>

                        <div class="careers-form-group">
                            <label for="email">Email Address</label>
                            <input type="email" name="email" id="email" required class="careers-input" placeholder="e.g. candidate@example.com">
                        </div>

                        <div class="careers-form-group">
                            <label for="phone">Phone Number</label>
                            <input type="text" name="phone" id="phone" required class="careers-input" placeholder="e.g. +91 9876543210">
                        </div>

                        <div class="careers-form-group">
                            <label for="role">Desired Position</label>
                            <select name="role" id="role" required class="careers-input">
                                <option value="">-- Select Position --</option>
                                <option value="Full Stack PHP Developer">Full Stack PHP Developer</option>
                                <option value="Customer Support Representative">Customer Support Representative</option>
                                <option value="Logistics & Supply Chain Specialist">Logistics & Supply Chain Specialist</option>
                            </select>
                        </div>

                        <div class="careers-form-group">
                            <label for="experience">Total Experience</label>
                            <select name="experience" id="experience" required class="careers-input">
                                <option value="">-- Select Experience --</option>
                                <option value="Fresh Graduate">Fresh Graduate</option>
                                <option value="1-2 Years">1-2 Years</option>
                                <option value="3-5 Years">3-5 Years</option>
                                <option value="5+ Years">5+ Years</option>
                            </select>
                        </div>

                        <div class="careers-form-group">
                            <label for="message">Cover Letter / Note</label>
                            <textarea name="message" id="message" class="careers-input" rows="4" placeholder="Briefly describe why you are a good fit..."></textarea>
                        </div>

                        <button type="submit" name="apply_job" class="btn-apply-submit">Submit Application</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
