<?php
global $mydb;

// Fetch lists
$mydb->setQuery("SELECT * FROM `email_lists`");
$lists = $mydb->loadResultList();
if (empty($lists)) {
    // Seed list if empty
    $mydb->setQuery("INSERT INTO `email_lists` (`list_name`, `description`) VALUES ('All Verified Customers', 'All active customer accounts registered on storefront')");
    $mydb->executeQuery();
    $mydb->setQuery("SELECT * FROM `email_lists`");
    $lists = $mydb->loadResultList();
}

// Fetch campaigns
$mydb->setQuery("
    SELECT c.*, l.list_name,
    (SELECT COUNT(*) FROM `email_queue` q WHERE q.campaign_id = c.campaign_id) as total_emails,
    (SELECT COUNT(*) FROM `email_queue` q WHERE q.campaign_id = c.campaign_id AND q.status = 'Sent') as sent_emails
    FROM `email_campaigns` c
    LEFT JOIN `email_lists` l ON c.list_id = l.list_id
    ORDER BY c.campaign_id DESC
");
$campaigns = $mydb->loadResultList();
?>

<div class="row">
    <!-- Left Column: Create Campaign Form -->
    <div class="col-md-4">
        <div class="panel panel-default" style="border-radius: 12px; box-shadow: var(--shadow); border: 1px solid var(--border-color); background: var(--card-bg);">
            <div class="panel-heading" style="background: var(--card-header-bg); border-bottom: 1px solid var(--border-color); padding: 15px; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <h4 style="margin: 0; font-weight: 800; font-size: 15px; color: var(--primary-light);"><i class="fa fa-pencil-square-o"></i> Create Campaign</h4>
            </div>
            <div class="panel-body" style="padding: 15px;">
                <form action="index.php" method="POST">
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 12px; color: var(--text-main);">Campaign Title:</label>
                        <input type="text" name="campaign_title" required class="form-control" placeholder="e.g. June Summer Sale Clearance" style="border-radius: 6px; border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-main);">
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 12px; color: var(--text-main);">Subject Line:</label>
                        <input type="text" name="subject_line" required class="form-control" placeholder="e.g. Save 25% off all summer items this weekend!" style="border-radius: 6px; border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-main);">
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 12px; color: var(--text-main);">Target Email List:</label>
                        <select name="list_id" required class="form-control" style="border-radius: 6px; border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-main);">
                            <?php foreach ($lists as $lst) { ?>
                                <option value="<?php echo $lst->list_id; ?>"><?php echo htmlspecialchars($lst->list_name); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 12px; color: var(--text-main);">HTML Content:</label>
                        <textarea name="content_html" required class="form-control" rows="8" placeholder="<h2>HTML Campaign template</h2><p>Check out our products...</p>" style="border-radius: 6px; border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-main); font-family: monospace;"></textarea>
                    </div>
                    <button type="submit" name="btn_create_campaign" class="btn btn-primary btn-block" style="background: var(--primary-light); border: none; border-radius: 6px; font-weight: 700; padding: 10px;"><i class="fa fa-paper-plane-o"></i> Save Draft Campaign</button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Right Column: Campaign List & Delivery Stats -->
    <div class="col-md-8">
        <div class="panel panel-default" style="border-radius: 12px; box-shadow: var(--shadow); border: 1px solid var(--border-color); background: var(--card-bg);">
            <div class="panel-heading" style="background: var(--card-header-bg); border-bottom: 1px solid var(--border-color); padding: 15px; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <h4 style="margin: 0; font-weight: 800; font-size: 15px; color: var(--primary-light);"><i class="fa fa-envelope-o"></i> Marketing Campaigns History</h4>
            </div>
            <div class="panel-body" style="padding: 15px;">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr style="background: var(--table-header-bg);">
                                <th style="font-weight:700; font-size:12px;">Campaign</th>
                                <th style="font-weight:700; font-size:12px;">Target List</th>
                                <th style="font-weight:700; font-size:12px;">Status</th>
                                <th style="font-weight:700; font-size:12px;">Emails Enqueued</th>
                                <th style="font-weight:700; font-size:12px; text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($campaigns) > 0) { 
                                foreach ($campaigns as $camp) {
                                    $lblClass = ($camp->status === 'Sent') ? 'label-success' : 'label-warning';
                            ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($camp->campaign_title); ?></strong><br>
                                        <small class="text-muted">Sub: <?php echo htmlspecialchars($camp->subject_line); ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($camp->list_name); ?></td>
                                    <td><span class="label <?php echo $lblClass; ?>" style="font-size:10px; font-weight:700; text-transform:uppercase;"><?php echo $camp->status; ?></span></td>
                                    <td><?php echo $camp->total_emails; ?> emails</td>
                                    <td style="text-align: center; vertical-align: middle;">
                                        <?php if ($camp->status === 'Draft') { ?>
                                            <a href="index.php?view=send&id=<?php echo $camp->campaign_id; ?>" class="btn btn-xs btn-success" style="border-radius: 4px; font-weight: 700; padding: 4px 8px;"><i class="fa fa-send"></i> Send Campaign</a>
                                        <?php } else { ?>
                                            <button disabled class="btn btn-xs btn-default" style="border-radius: 4px; font-weight:700; cursor: not-allowed;"><i class="fa fa-check"></i> Sent</button>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } 
                            } else { ?>
                                <tr>
                                    <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 30px 0;">No email campaigns built yet.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
