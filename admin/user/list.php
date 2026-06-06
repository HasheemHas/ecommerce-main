<?php
    if (!isset($_SESSION['USERID'])){
        redirect(web_root."admin/index.php");
    }
?>

<style>
.user-table-card {
    background: var(--card-bg, #fff);
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    transition: background 0.25s, border-color 0.25s;
}
.user-table-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px;
    border-bottom: 1px solid var(--border-color, #f1f5f9);
}
.user-table-header h4 {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
    color: var(--text-main, #0f172a);
}
.btn-new-user {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 9px 18px;
    background: #1e3a8a;
    color: #fff;
    border-radius: 8px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    transition: background 0.2s;
}
.btn-new-user:hover { background: #1e40af; color: #fff; text-decoration: none; }

.users-table { width: 100%; border-collapse: collapse; }
.users-table thead th {
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
.users-table tbody tr {
    border-bottom: 1px solid var(--border-color, #f1f5f9);
    transition: background 0.15s;
}
.users-table tbody tr:hover { background: var(--hover-bg, #f8fafc); }
.users-table tbody td {
    padding: 16px 20px;
    font-size: 14px;
    color: var(--text-main, #334155);
    vertical-align: middle;
}

.user-avatar {
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
}
.user-name { font-weight: 600; color: var(--text-main, #0f172a); vertical-align: middle; }

.role-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    background: #eff6ff;
    color: #1e3a8a;
}

.action-btns { display: flex; gap: 8px; justify-content: center; }
.btn-edit-user {
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
.btn-edit-user:hover { background: #1e40af; color: #fff; text-decoration: none; }
.btn-delete-user {
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
.btn-delete-user:hover { background: #dc2626; color: #fff; text-decoration: none; }
.btn-delete-user.disabled-btn {
    background: #f1f5f9;
    color: #cbd5e1;
    cursor: not-allowed;
    pointer-events: none;
}
</style>
<!-- Title Row -->
<div class="page-title-row" style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:25px;">
    <div>
        <h1 style="font-weight:800; color:var(--primary); font-size:28px; margin:0 0 5px 0;">Users Management</h1>
        <p style="color:var(--text-muted); margin:0; font-size:14px;">Manage administrator and assistant accounts and permissions.</p>
    </div>
</div>

<div class="user-table-card">
    <div class="user-table-header">
        <h4><i class="fa fa-user" style="margin-right:8px; color:#1e3a8a;"></i>List of Users</h4>
        <a href="index.php?view=add" class="btn-new-user">
            <i class="fa fa-plus"></i> New User
        </a>
    </div>

    <table class="users-table">
        <thead>
            <tr>
                <th>Account Name</th>
                <th>Username / Email</th>
                <th>Role</th>
                <th style="text-align:center;">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
            $mydb->setQuery("SELECT * FROM `tbluseraccount` ORDER BY USERID ASC");
            $cur = $mydb->loadResultList();

            foreach ($cur as $result) {
                $initials = strtoupper(substr($result->U_NAME, 0, 1));
                $isSelf   = ($result->USERID == $_SESSION['USERID']);

                echo '<tr>';

                // Name with avatar
                echo '<td>
                    <span class="user-avatar">' . $initials . '</span>
                    <span class="user-name">' . htmlspecialchars($result->U_NAME) . '</span>
                    ' . ($isSelf ? '<span style="font-size:10px; color:#94a3b8; margin-left:6px;">(You)</span>' : '') . '
                </td>';

                echo '<td style="color:#475569;">' . htmlspecialchars($result->U_USERNAME) . '</td>';

                echo '<td><span class="role-badge">' . htmlspecialchars($result->U_ROLE) . '</span></td>';

                // Action buttons
                echo '<td><div class="action-btns">';

                // Edit button — always available
                echo '<a href="index.php?view=edit&id=' . $result->USERID . '" class="btn-edit-user" title="Edit User">
                        <i class="fa fa-pencil"></i>
                      </a>';

                // Delete button — disabled only for own account
                if ($isSelf) {
                    echo '<span class="btn-delete-user disabled-btn" title="Cannot delete your own account">
                            <i class="fa fa-trash-o"></i>
                          </span>';
                } else {
                    echo '<a href="controller.php?action=delete&id=' . $result->USERID . '"
                             class="btn-delete-user"
                             title="Delete User"
                             onclick="return confirmDelete(\'' . htmlspecialchars($result->U_NAME, ENT_QUOTES) . '\')">
                            <i class="fa fa-trash-o"></i>
                          </a>';
                }

                echo '</div></td>';
                echo '</tr>';
            }
        ?>
        </tbody>
    </table>
</div>

<script>
function confirmDelete(name) {
    return confirm('Are you sure you want to delete user "' + name + '"?\nThis action cannot be undone.');
}
</script>