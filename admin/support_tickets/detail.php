<?php
global $mydb;

$ticket_id = intval($_GET['id']);

// Fetch ticket details
$mydb->setQuery("
    SELECT t.*, c.FNAME, c.LNAME, c.EMAIL, c.PHONE
    FROM `support_tickets` t
    LEFT JOIN `tblcustomer` c ON t.customer_id = c.CUSTOMERID
    WHERE t.ticket_id = {$ticket_id}
");
$ticket = $mydb->loadSingleResult();

if (!$ticket) {
    $_SESSION['message'] = "Ticket not found.";
    $_SESSION['msgtype'] = "danger";
    redirect("index.php");
}

// Fetch replies
$mydb->setQuery("
    SELECT r.*, u.U_NAME as admin_name, c.FNAME as cust_name
    FROM `ticket_replies` r
    LEFT JOIN `tbluseraccount` u ON r.sender_type = 'Admin' AND r.sender_id = u.USERID
    LEFT JOIN `tblcustomer` c ON r.sender_type = 'Customer' AND r.sender_id = c.CUSTOMERID
    WHERE r.ticket_id = {$ticket_id}
    ORDER BY r.created_at ASC
");
$replies = $mydb->loadResultList();

// Fetch current assignment
$mydb->setQuery("SELECT * FROM `ticket_assignments` WHERE `ticket_id` = {$ticket_id}");
$assignment = $mydb->loadSingleResult();

// Fetch list of agents
$mydb->setQuery("SELECT * FROM `tbluseraccount` WHERE `U_ROLE` IN ('Administrator', 'Staff')");
$agents = $mydb->loadResultList();

// Determine canned responses based on ticket category
$canned_responses = [];
if ($ticket->category === 'Refund') {
    $canned_responses = [
        "Refund Approved" => "Hello, after reviewing your ticket regarding order #{$ticket->order_number}, we have approved your refund request. The amount will credit back to your account in 3-5 business days.",
        "Refund Rejected" => "Hello, we regret to inform you that order #{$ticket->order_number} does not qualify for a refund under our return terms. Please refer to our support guidelines for more details.",
        "Request Images" => "Hello, we received your return request. Could you please upload or send photos of the damaged item so we can verify the issue and proceed with the refund?"
    ];
} elseif ($ticket->category === 'Payment Issue') {
    $canned_responses = [
        "Double Charge Fix" => "Hello, we noticed a double transaction occurred on your payment method. We have reversed the duplicate charge, which should reflect on your statement shortly.",
        "Payment Failed Inquiry" => "Hello, your payment failed because of banking security rules. We recommend contacting your card issuer or attempting transaction checkout again with alternative methods."
    ];
} else {
    $canned_responses = [
        "Polite Greeting" => "Hello, thank you for reaching out to H-Mart Support. We are investigating your query and will get back to you with updates shortly.",
        "Resolve Confirmation" => "Hello, we have marked this ticket as resolved. Please let us know if there is anything else we can assist you with!"
    ];
}
?>

<div class="row">
    <!-- Ticket Meta Info & Assignments -->
    <div class="col-md-4">
        <div class="panel panel-default" style="border-radius: 12px; box-shadow: var(--shadow); border: 1px solid var(--border-color); background: var(--card-bg);">
            <div class="panel-heading" style="background: var(--card-header-bg); border-bottom: 1px solid var(--border-color); padding: 20px; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <h3 style="margin: 0; font-weight: 800; font-size: 16px; color: var(--primary-light);"><i class="fa fa-info-circle"></i> Ticket Context</h3>
            </div>
            <div class="panel-body" style="padding: 20px;">
                <div style="margin-bottom: 15px;">
                    <span class="text-muted" style="font-size: 11px; text-transform: uppercase; font-weight: 600;">Status</span>
                    <div style="margin-top: 5px;">
                        <span class="label label-primary" style="font-size: 12px; font-weight: 700; text-transform: uppercase; padding: 5px 10px;"><?php echo htmlspecialchars($ticket->status); ?></span>
                    </div>
                </div>
                
                <div style="margin-bottom: 15px;">
                    <span class="text-muted" style="font-size: 11px; text-transform: uppercase; font-weight: 600;">Category & Priority</span>
                    <div style="margin-top: 5px; font-size: 13px;">
                        <code><?php echo htmlspecialchars($ticket->category); ?></code> &bull; <span class="text-danger" style="font-weight: 700;"><?php echo htmlspecialchars($ticket->priority); ?> Priority</span>
                    </div>
                </div>

                <div style="margin-bottom: 15px; border-top: 1px solid var(--border-color); padding-top: 15px;">
                    <span class="text-muted" style="font-size: 11px; text-transform: uppercase; font-weight: 600;">Customer Details</span>
                    <div style="margin-top: 5px; font-size: 13px;">
                        <strong><?php echo htmlspecialchars($ticket->FNAME . ' ' . $ticket->LNAME); ?></strong>
                        <div style="color: var(--text-muted); margin-top: 2px;"><i class="fa fa-envelope"></i> <?php echo htmlspecialchars($ticket->EMAIL); ?></div>
                        <div style="color: var(--text-muted); margin-top: 2px;"><i class="fa fa-phone"></i> <?php echo htmlspecialchars($ticket->PHONE); ?></div>
                    </div>
                </div>

                <!-- Update Status Buttons -->
                <div style="border-top: 1px solid var(--border-color); padding-top: 15px; margin-bottom: 15px;">
                    <span class="text-muted" style="font-size: 11px; text-transform: uppercase; font-weight: 600;">Update Status</span>
                    <div style="margin-top: 8px; display: flex; gap: 8px; flex-wrap: wrap;">
                        <a href="index.php?view=update_status&id=<?php echo $ticket->ticket_id; ?>&status=Resolved" class="btn btn-xs btn-success" style="border-radius: 4px; font-weight: 700;">Mark Resolved</a>
                        <a href="index.php?view=update_status&id=<?php echo $ticket->ticket_id; ?>&status=Closed" class="btn btn-xs btn-default" style="border-radius: 4px; font-weight: 700; border: 1px solid var(--border-color);">Close Ticket</a>
                        <a href="index.php?view=update_status&id=<?php echo $ticket->ticket_id; ?>&status=Open" class="btn btn-xs btn-danger" style="border-radius: 4px; font-weight: 700;">Reopen</a>
                    </div>
                </div>

                <!-- Agent Assignment Form -->
                <form action="index.php?view=assign" method="post" style="border-top: 1px solid var(--border-color); padding-top: 15px;">
                    <input type="hidden" name="ticket_id" value="<?php echo $ticket->ticket_id; ?>">
                    <div class="form-group">
                        <label style="font-weight: 600; font-size: 12px; color: var(--text-muted); text-transform: uppercase;">Assign Staff Agent</label>
                        <select name="agent_id" class="form-control input-sm" required style="border-radius: 6px; margin-top: 5px;">
                            <option value="">Select Agent...</option>
                            <?php foreach ($agents as $ag) { ?>
                                <option value="<?php echo $ag->USERID; ?>" <?php echo ($assignment && $assignment->agent_id == $ag->USERID) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($ag->U_NAME); ?> (<?php echo htmlspecialchars($ag->U_ROLE); ?>)
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <button type="submit" name="assign_agent" class="btn btn-xs btn-primary btn-block" style="background: var(--primary-light); border: none; font-weight: 700; border-radius: 4px; padding: 6px;">
                        Update Assignment
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Ticket Conversation Thread -->
    <div class="col-md-8">
        <div class="panel panel-default" style="border-radius: 12px; box-shadow: var(--shadow); border: 1px solid var(--border-color); background: var(--card-bg);">
            <div class="panel-heading" style="background: var(--card-header-bg); border-bottom: 1px solid var(--border-color); padding: 20px; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <h3 style="margin: 0; font-weight: 800; font-size: 18px; color: var(--primary-light);"><i class="fa fa-comments"></i> Ticket ID: #<?php echo $ticket->ticket_id; ?> &mdash; <?php echo htmlspecialchars($ticket->subject); ?></h3>
                <p style="margin: 4px 0 0 0; font-size: 12px; color: var(--text-muted);">Thread started on <?php echo date_toText($ticket->created_at); ?></p>
            </div>
            
            <div class="panel-body" style="padding: 20px;">
                <!-- Discussion Thread list -->
                <div style="max-height: 400px; overflow-y: auto; padding-right: 10px; margin-bottom: 20px; display: flex; flex-direction: column; gap: 15px;">
                    <?php foreach ($replies as $rep) { 
                        $isAdmin = ($rep->sender_type === 'Admin');
                        $align = $isAdmin ? 'align-self-end' : 'align-self-start';
                        $bg = $isAdmin ? 'background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd;' : 'background: var(--bg-color); color: var(--text-main); border: 1px solid var(--border-color);';
                        
                        // Dark mode adjustments
                        if ($isAdmin) {
                            $bg = 'background: #0369a1; color: #f0f9ff; border: 1px solid #0284c7;';
                        }
                    ?>
                        <div class="<?php echo $align; ?>" style="max-width: 80%; border-radius: 12px; padding: 15px; <?php echo $bg; ?>">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; gap: 15px;">
                                <strong style="font-size: 12px;">
                                    <?php echo $isAdmin ? '<i class="fa fa-user-circle"></i> ' . htmlspecialchars($rep->admin_name) . ' (Support)' : htmlspecialchars($rep->cust_name) . ' (Customer)'; ?>
                                </strong>
                                <small style="font-size: 10px; opacity: 0.8;"><?php echo date('H:i d M Y', strtotime($rep->created_at)); ?></small>
                            </div>
                            <div style="font-size: 13px; line-height: 1.5; white-space: pre-line;"><?php echo htmlspecialchars($rep->message_body); ?></div>
                        </div>
                    <?php } ?>
                </div>

                <!-- Canned Responses / AI Auto-replies -->
                <div style="border-top: 1px solid var(--border-color); padding-top: 15px; margin-bottom: 15px;">
                    <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.5px;"><i class="fa fa-magic"></i> AI Suggested Responses</span>
                    <div style="display: flex; gap: 8px; margin-top: 8px; flex-wrap: wrap;">
                        <?php foreach ($canned_responses as $label => $text) { ?>
                            <button type="button" class="btn btn-xs btn-default" onclick="useCanned(<?php echo htmlspecialchars(json_encode($text)); ?>)" style="border-radius: 6px; font-weight: 600; border: 1px solid var(--border-color); background: var(--bg-color); font-size: 11px; padding: 4px 10px;">
                                <?php echo htmlspecialchars($label); ?>
                            </button>
                        <?php } ?>
                    </div>
                </div>

                <!-- Reply Composer Form -->
                <form action="index.php?view=reply" method="post">
                    <input type="hidden" name="ticket_id" value="<?php echo $ticket->ticket_id; ?>">
                    <div class="form-group">
                        <textarea name="message_body" id="reply_message" class="form-control" rows="5" placeholder="Compose your response to the customer..." required style="border-radius: 8px; font-size: 13px; resize: none;"></textarea>
                    </div>
                    <div style="display: flex; justify-content: flex-end;">
                        <button type="submit" name="send_reply" class="btn btn-primary" style="background: var(--primary-light); border: none; font-weight: 700; border-radius: 6px; padding: 10px 24px;">
                            <i class="fa fa-reply"></i> Send Response
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function useCanned(text) {
    var txtarea = document.getElementById('reply_message');
    if (txtarea) {
        txtarea.value = text;
    }
}
</script>
