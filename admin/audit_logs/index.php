<?php
require_once("../../backend/include/initialize.php");
if (!isset($_SESSION['USERID'])){
    redirect(web_root."admin/index.php");
}
$view = (isset($_GET['view']) && $_GET['view'] != '') ? $_GET['view'] : 'list';
$title = "Audit Logs & Security Trail";

if ($view === 'export') {
    // Audit Action Export to CSV
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=hmart_audit_trail_' . date('Ymd_His') . '.csv');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Log ID', 'Admin ID', 'Admin User', 'Action', 'Target Table', 'Old Values', 'New Values', 'IP Address', 'Timestamp']);
    
    global $mydb;
    $mydb->setQuery("
        SELECT a.*, u.U_NAME 
        FROM `audit_logs` a
        LEFT JOIN `tbluseraccount` u ON a.admin_id = u.USERID
        ORDER BY a.log_id DESC
    ");
    $rows = $mydb->loadResultList();
    if ($rows) {
        foreach ($rows as $row) {
            fputcsv($output, [
                $row->log_id,
                $row->admin_id,
                $row->U_NAME ?? 'System/Unknown',
                $row->action,
                $row->target_table,
                $row->old_values,
                $row->new_values,
                $row->ip_address,
                $row->timestamp
            ]);
        }
    }
    fclose($output);
    exit;
}

$content = 'list.php';
require_once("../theme/templates.php");
?>
