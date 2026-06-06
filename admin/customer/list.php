<?php
    if (!isset($_SESSION['USERID'])){
        redirect(web_root."admin/index.php");
    }
?>

<style>
.cus-table-card {
    background: var(--card-bg, #fff);
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    transition: background 0.25s, border-color 0.25s;
}
.cus-table-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px;
    border-bottom: 1px solid var(--border-color, #f1f5f9);
}
.cus-table-header h4 {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
    color: var(--text-main, #0f172a);
}
.customers-table { width: 100%; border-collapse: collapse; }
.customers-table thead th {
    background: var(--table-header-bg, #f8fafc);
    padding: 12px 20px;
    text-align: left;
    font-size: 11px;
    font-weight: 700;
    color: var(--text-muted, #64748b);
    text-transform: uppercase;
    letter-spacing: 0.6px;
    border-bottom: 1px solid var(--border-color, #e2e8f0);
}
.customers-table tbody tr {
    border-bottom: 1px solid var(--border-color, #f1f5f9);
    transition: background 0.15s;
}
.customers-table tbody tr:hover { background: var(--hover-bg, #f8fafc); }
.customers-table tbody td {
    padding: 16px 20px;
    font-size: 14px;
    color: var(--text-main, #334155);
    vertical-align: middle;
}

.cus-avatar {
    width: 36px; height: 36px;
    border-radius: 50%;
    background: #1e3a8a;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 14px;
    font-weight: 700;
    margin-right: 10px;
    vertical-align: middle;
    overflow: hidden;
}
.cus-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.cus-name { font-weight: 600; color: var(--text-main, #0f172a); vertical-align: middle; }

.status-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
}
.status-verified {
    background: #ecfdf5;
    color: #10b981;
}
.status-unverified {
    background: #fef2f2;
    color: #ef4444;
}

.action-btns { display: flex; gap: 8px; justify-content: center; }
.btn-edit-cus {
    width: 34px; height: 34px;
    border-radius: 8px;
    background: #1e3a8a;
    color: #fff;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-size: 14px;
    transition: background 0.2s;
    cursor: pointer;
}
.btn-edit-cus:hover { background: #1e40af; color: #fff; text-decoration: none; }
.btn-delete-cus {
    width: 34px; height: 34px;
    border-radius: 8px;
    background: #fee2e2;
    color: #dc2626;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-size: 14px;
    transition: all 0.2s;
    cursor: pointer;
}
.btn-delete-cus:hover { background: #dc2626; color: #fff; text-decoration: none; }
</style>

<!-- Title Row -->
<div class="page-title-row" style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:25px;">
    <div>
        <h1 style="font-weight:800; color:var(--primary); font-size:28px; margin:0 0 5px 0;">Customers Management</h1>
        <p style="color:var(--text-muted); margin:0; font-size:14px;">View, edit details, and delete registered customer accounts.</p>
    </div>
</div>

<div class="cus-table-card">
    <div class="cus-table-header">
        <h4><i class="fa fa-users" style="margin-right:8px; color:#1e3a8a;"></i>List of Customers</h4>
    </div>

    <table class="customers-table">
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Email / Username</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Status</th>
                <th style="text-align:center;">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
            $mydb->setQuery("SELECT * FROM `tblcustomer` ORDER BY CUSTOMERID DESC");
            $cur = $mydb->loadResultList();

            foreach ($cur as $result) {
                $initials = strtoupper(substr($result->FNAME, 0, 1) . substr($result->LNAME, 0, 1));
                $fullName = htmlspecialchars($result->FNAME . ' ' . $result->LNAME);
                
                $avatar_html = '<span class="cus-avatar">' . $initials . '</span>';
                if (!empty($result->CUSPHOTO)) {
                    $avatar_path = web_root . $result->CUSPHOTO;
                    $avatar_html = '<span class="cus-avatar"><img src="' . $avatar_path . '" alt="avatar" onerror="this.parentElement.innerHTML=\'' . $initials . '\'"></span>';
                }

                $status_class = ($result->TERMS == 1) ? 'status-verified' : 'status-unverified';
                $status_text = ($result->TERMS == 1) ? 'Verified' : 'Unverified';

                echo '<tr>';
                
                // Name
                echo '<td>
                    ' . $avatar_html . '
                    <span class="cus-name">' . $fullName . '</span>
                </td>';

                // Email
                echo '<td style="color:#475569;">' . htmlspecialchars($result->CUSUNAME) . '</td>';

                // Phone
                echo '<td>' . htmlspecialchars($result->PHONE) . '</td>';

                // Address
                $address = htmlspecialchars(trim($result->STREETADD . ' ' . $result->BRGYADD . ' ' . $result->CITYADD));
                if (empty($address)) {
                    $address = '<span style="color:#94a3b8; font-style:italic;">No Address Saved</span>';
                }
                echo '<td style="color:#475569;">' . $address . '</td>';

                // Status badge
                echo '<td><span class="status-badge ' . $status_class . '">' . $status_text . '</span></td>';

                // Actions
                echo '<td><div class="action-btns">';
                
                echo '<a href="index.php?view=edit&id=' . $result->CUSTOMERID . '" class="btn-edit-cus" title="Edit Customer">
                        <i class="fa fa-pencil"></i>
                      </a>';

                echo '<a href="controller.php?action=delete&id=' . $result->CUSTOMERID . '"
                         class="btn-delete-cus"
                         title="Delete Customer"
                         onclick="return confirmDelete(\'' . htmlspecialchars($fullName, ENT_QUOTES) . '\')">
                        <i class="fa fa-trash-o"></i>
                      </a>';

                echo '</div></td>';
                echo '</tr>';
            }
        ?>
        </tbody>
    </table>
</div>

<script>
function confirmDelete(name) {
    return confirm('Are you sure you want to delete customer "' + name + '"?\nThis will remove their profile and all related order history. This action cannot be undone.');
}
</script>
