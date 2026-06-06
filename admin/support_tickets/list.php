<?php
global $mydb;

// Auto-seed sample support tickets if table is empty
$mydb->setQuery("SELECT count(*) as total FROM `support_tickets`");
$count_res = $mydb->loadSingleResult();
if ($count_res->total == 0) {
    // We need a customer ID to bind to. Let's find a valid customer ID or fallback to 1.
    $mydb->setQuery("SELECT `CUSTOMERID` FROM `tblcustomer` LIMIT 1");
    $cust = $mydb->loadSingleResult();
    $cust_id = $cust ? $cust->CUSTOMERID : 1;
    
    // Insert sample tickets
    $tickets = [
        [$cust_id, 10001, 'Request for refund: Damaged Item', 'Refund', 'Open', 'High'],
        [$cust_id, NULL, 'Size exchange inquiry for jacket', 'Product Inquiry', 'Assigned', 'Medium'],
        [$cust_id, 10002, 'Double charge on my credit card', 'Payment Issue', 'Open', 'High'],
        [$cust_id, NULL, 'How can I change my shipping address?', 'Other', 'Resolved', 'Low']
    ];
    
    foreach ($tickets as $t) {
        $order_val = $t[1] !== NULL ? $t[1] : 'NULL';
        $mydb->setQuery("INSERT INTO `support_tickets` (`customer_id`, `order_number`, `subject`, `category`, `status`, `priority`) 
                         VALUES ({$t[0]}, {$order_val}, '{$t[2]}', '{$t[3]}', '{$t[4]}', '{$t[5]}')");
        $mydb->executeQuery();
        $ticket_id = $mydb->insert_id();
        
        // Add default message
        $msg = "Hello, I would like to query about my support request: {$t[2]}. Please resolve as soon as possible.";
        $mydb->setQuery("INSERT INTO `ticket_replies` (`ticket_id`, `sender_type`, `sender_id`, `message_body`) 
                         VALUES ({$ticket_id}, 'Customer', {$cust_id}, '{$msg}')");
        $mydb->executeQuery();
        
        if ($t[4] === 'Assigned') {
            // Assign to admin user
            $mydb->setQuery("SELECT `USERID` FROM `tbluseraccount` LIMIT 1");
            $admin = $mydb->loadSingleResult();
            $admin_id = $admin ? $admin->USERID : 1;
            
            $mydb->setQuery("INSERT INTO `ticket_assignments` (`ticket_id`, `agent_id`) VALUES ({$ticket_id}, {$admin_id})");
            $mydb->executeQuery();
            
            // Add admin reply
            $admin_msg = "Hello! We are looking into your request and will update you shortly.";
            $mydb->setQuery("INSERT INTO `ticket_replies` (`ticket_id`, `sender_type`, `sender_id`, `message_body`) 
                             VALUES ({$ticket_id}, 'Admin', {$admin_id}, '{$admin_msg}')");
            $mydb->executeQuery();
        }
    }
}

// Fetch all support tickets
$mydb->setQuery("
    SELECT t.*, c.FNAME, c.LNAME, a.agent_id, u.U_NAME as agent_name
    FROM `support_tickets` t
    LEFT JOIN `tblcustomer` c ON t.customer_id = c.CUSTOMERID
    LEFT JOIN `ticket_assignments` a ON t.ticket_id = a.ticket_id
    LEFT JOIN `tbluseraccount` u ON a.agent_id = u.USERID
    ORDER BY FIELD(t.priority, 'High', 'Medium', 'Low'), t.created_at DESC
");
$tickets = $mydb->loadResultList();
?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default" style="border-radius: 12px; box-shadow: var(--shadow); border: 1px solid var(--border-color); background: var(--card-bg);">
            <div class="panel-heading" style="background: var(--card-header-bg); border-bottom: 1px solid var(--border-color); padding: 20px; border-top-left-radius: 12px; border-top-right-radius: 12px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
                <div>
                    <h3 style="margin: 0; font-weight: 800; font-size: 18px; color: var(--primary-light);"><i class="fa fa-ticket"></i> Customer Support Tickets</h3>
                    <p style="margin: 4px 0 0 0; font-size: 12px; color: var(--text-muted);">Manage customer refund requests, product inquiries, and check-in threads.</p>
                </div>
            </div>
            
            <div class="panel-body" style="padding: 20px;">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dash-table">
                        <thead>
                            <tr style="background: var(--table-header-bg);">
                                <th style="font-weight: 700; font-size: 12px;">Ticket ID</th>
                                <th style="font-weight: 700; font-size: 12px;">Customer</th>
                                <th style="font-weight: 700; font-size: 12px;">Subject / Issue</th>
                                <th style="font-weight: 700; font-size: 12px;">Category</th>
                                <th style="font-weight: 700; font-size: 12px;">Priority</th>
                                <th style="font-weight: 700; font-size: 12px;">Status</th>
                                <th style="font-weight: 700; font-size: 12px;">Assigned Agent</th>
                                <th style="font-weight: 700; font-size: 12px;">Updated At</th>
                                <th style="font-weight: 700; font-size: 12px; text-align: center; width: 120px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tickets as $row) { 
                                $priorityClass = "label-default";
                                if ($row->priority === "High") {
                                    $priorityClass = "label-danger";
                                } elseif ($row->priority === "Medium") {
                                    $priorityClass = "label-warning";
                                }
                                
                                $statusClass = "label-info";
                                if ($row->status === "Resolved") {
                                    $statusClass = "label-success";
                                } elseif ($row->status === "Closed") {
                                    $statusClass = "label-default";
                                } elseif ($row->status === "Open") {
                                    $statusClass = "label-danger";
                                }
                            ?>
                                <tr>
                                    <td>#<?php echo $row->ticket_id; ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars(($row->FNAME ?? 'Guest') . ' ' . ($row->LNAME ?? '')); ?></strong>
                                        <br><small class="text-muted">Cus ID: <?php echo $row->customer_id; ?></small>
                                    </td>
                                    <td>
                                        <div style="font-weight: 600; font-size: 13px;"><?php echo htmlspecialchars($row->subject); ?></div>
                                        <?php if ($row->order_number) { ?>
                                            <small class="text-muted"><i class="fa fa-shopping-bag"></i> Order #<?php echo $row->order_number; ?></small>
                                        <?php } ?>
                                    </td>
                                    <td><code><?php echo htmlspecialchars($row->category); ?></code></td>
                                    <td><span class="label <?php echo $priorityClass; ?>" style="font-size: 10px; font-weight: 700; text-transform: uppercase;"><?php echo htmlspecialchars($row->priority); ?></span></td>
                                    <td><span class="label <?php echo $statusClass; ?>" style="font-size: 10px; font-weight: 700; text-transform: uppercase;"><?php echo htmlspecialchars($row->status); ?></span></td>
                                    <td>
                                        <?php if ($row->agent_name) { ?>
                                            <strong><i class="fa fa-user-circle"></i> <?php echo htmlspecialchars($row->agent_name); ?></strong>
                                        <?php } else { ?>
                                            <span class="text-danger" style="font-style: italic; font-size: 12px;"><i class="fa fa-warning"></i> Unassigned</span>
                                        <?php } ?>
                                    </td>
                                    <td><small class="text-muted"><?php echo date_toText($row->updated_at); ?></small></td>
                                    <td style="text-align: center;">
                                        <a href="index.php?view=detail&id=<?php echo $row->ticket_id; ?>" class="btn btn-xs btn-primary" style="border-radius: 4px; font-weight: 700; padding: 4px 10px;">
                                            <i class="fa fa-eye"></i> Manage
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
