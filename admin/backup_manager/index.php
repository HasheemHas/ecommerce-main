<?php
require_once("../../backend/include/initialize.php");
if (!isset($_SESSION['USERID'])){
    redirect(web_root."admin/index.php");
}

$view = (isset($_GET['view']) && $_GET['view'] != '') ? $_GET['view'] : 'list';
$title = "System Backup & Restore Manager";

$backup_dir = __DIR__ . '/../../backups/';
if (!is_dir($backup_dir)) {
    mkdir($backup_dir, 0777, true);
}

if ($view === 'create') {
    global $mydb;
    
    // Connect database
    $conn = new mysqli(server, user, pass, database_name);
    if ($conn->connect_error) {
        // Log failure
        $mydb->setQuery("INSERT INTO `backup_logs` (`file_name`, `file_size_bytes`, `storage_location`, `status`, `error_details`) 
                         VALUES ('', 0, 'Local', 'Failed', 'Database connection error: " . $conn->connect_error . "')");
        $mydb->executeQuery();
        
        $_SESSION['message'] = "Backup failed to connect to database.";
        $_SESSION['msgtype'] = "danger";
        redirect("index.php");
    }
    
    $conn->set_charset("utf8");
    
    $tables = [];
    $result = $conn->query("SHOW TABLES");
    while ($row = $result->fetch_row()) {
        $tables[] = $row[0];
    }
    
    $sql_dump = "-- H-Mart SQL Database Backup\n";
    $sql_dump .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
    $sql_dump .= "SET FOREIGN_KEY_CHECKS=0;\n\n";
    
    foreach ($tables as $table) {
        // Table structure
        $res = $conn->query("SHOW CREATE TABLE `{$table}`");
        $row = $res->fetch_row();
        $sql_dump .= "\n\n" . $row[1] . ";\n\n";
        
        // Table data
        $res = $conn->query("SELECT * FROM `{$table}`");
        $num_fields = $res->field_count;
        
        while ($row = $res->fetch_row()) {
            $sql_dump .= "INSERT INTO `{$table}` VALUES(";
            for ($j = 0; $j < $num_fields; $j++) {
                if (isset($row[$j])) {
                    // Escape special characters
                    $val = addslashes($row[$j]);
                    $val = str_replace("\n", "\\n", $val);
                    $sql_dump .= '"' . $val . '"';
                } else {
                    $sql_dump .= 'NULL';
                }
                if ($j < ($num_fields - 1)) {
                    $sql_dump .= ',';
                }
            }
            $sql_dump .= ");\n";
        }
    }
    
    $sql_dump .= "\nSET FOREIGN_KEY_CHECKS=1;\n";
    
    $filename = 'backup_db_' . date('Ymd_His') . '_' . time() . '.sql';
    $filepath = $backup_dir . $filename;
    
    if (file_put_contents($filepath, $sql_dump) !== false) {
        $filesize = filesize($filepath);
        
        $mydb->setQuery("INSERT INTO `backup_logs` (`file_name`, `file_size_bytes`, `storage_location`, `status`) 
                         VALUES ('{$filename}', {$filesize}, 'Local', 'Success')");
        $mydb->executeQuery();
        
        log_audit_action("create", "backup_logs", "Generated database backup file: {$filename}", null, json_encode(['file' => $filename, 'size' => $filesize]));
        
        $_SESSION['message'] = "Database SQL backup generated successfully.";
        $_SESSION['msgtype'] = "success";
    } else {
        $mydb->setQuery("INSERT INTO `backup_logs` (`file_name`, `file_size_bytes`, `storage_location`, `status`, `error_details`) 
                         VALUES ('{$filename}', 0, 'Local', 'Failed', 'Write file error')");
        $mydb->executeQuery();
        
        $_SESSION['message'] = "Failed to write backup SQL file.";
        $_SESSION['msgtype'] = "danger";
    }
    
    $conn->close();
    redirect("index.php");
}

if ($view === 'download') {
    $filename = basename($_GET['file']);
    $filepath = $backup_dir . $filename;
    
    if (file_exists($filepath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
        exit;
    } else {
        $_SESSION['message'] = "Backup file not found on disk.";
        $_SESSION['msgtype'] = "danger";
        redirect("index.php");
    }
}

if ($view === 'restore') {
    $filename = basename($_GET['file']);
    $filepath = $backup_dir . $filename;
    
    if (file_exists($filepath)) {
        $sql = file_get_contents($filepath);
        
        // Connect and execute restore queries
        $conn = new mysqli(server, user, pass, database_name);
        if ($conn->connect_error) {
            $_SESSION['message'] = "Restore failed to connect database: " . $conn->connect_error;
            $_SESSION['msgtype'] = "danger";
            redirect("index.php");
        }
        
        // Execute multi-query
        if ($conn->multi_query($sql)) {
            do {
                if ($result = $conn->store_result()) {
                    $result->free();
                }
            } while ($conn->more_results() && $conn->next_result());
            
            log_audit_action("update", "backup_logs", "Restored database from file: {$filename}", null, json_encode(['file' => $filename]));
            
            $_SESSION['message'] = "Database restored successfully from " . $filename . "!";
            $_SESSION['msgtype'] = "success";
        } else {
            $_SESSION['message'] = "Error restoring database: " . $conn->error;
            $_SESSION['msgtype'] = "danger";
        }
        $conn->close();
    } else {
        $_SESSION['message'] = "Restore file not found on disk.";
        $_SESSION['msgtype'] = "danger";
    }
    redirect("index.php");
}

if ($view === 'delete') {
    $id = intval($_GET['id']);
    global $mydb;
    
    $mydb->setQuery("SELECT * FROM `backup_logs` WHERE `backup_id` = {$id}");
    $row = $mydb->loadSingleResult();
    
    if ($row) {
        $filepath = $backup_dir . $row->file_name;
        if (file_exists($filepath) && !empty($row->file_name)) {
            unlink($filepath);
        }
        
        $mydb->setQuery("DELETE FROM `backup_logs` WHERE `backup_id` = {$id}");
        $mydb->executeQuery();
        
        log_audit_action("delete", "backup_logs", "Removed database backup: " . $row->file_name, json_encode($row));
        
        $_SESSION['message'] = "Backup file removed from index and disk.";
        $_SESSION['msgtype'] = "success";
    }
    redirect("index.php");
}

$content = 'list.php';
require_once("../theme/templates.php");
?>
