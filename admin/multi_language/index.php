<?php
require_once("../../backend/include/initialize.php");
if (!isset($_SESSION['USERID'])){
    redirect(web_root."admin/index.php");
}

$view = (isset($_GET['view']) && $_GET['view'] != '') ? $_GET['view'] : 'list';
$title = "Multi-Language Dictionary Manager";

if ($view === 'add') {
    if (isset($_POST['save'])) {
        global $mydb;
        $lang_code = $_POST['lang_code'];
        $text_key = $_POST['text_key'];
        $translated_text = $_POST['translated_text'];
        
        $mydb->setQuery("INSERT INTO `translations_cache` (`lang_code`, `text_key`, `translated_text`) 
                         VALUES ('{$lang_code}', '{$text_key}', '" . $mydb->escape_value($translated_text) . "')");
        $mydb->executeQuery();
        
        log_audit_action("create", "translations_cache", "New translation string", json_encode($_POST));
        
        $_SESSION['message'] = "New translation text added successfully.";
        $_SESSION['msgtype'] = "success";
    }
    redirect("index.php");
}

if ($view === 'edit') {
    if (isset($_POST['save_edit'])) {
        global $mydb;
        $id = $_POST['translation_id'];
        $translated_text = $_POST['translated_text'];
        
        // Fetch old values for audit
        $mydb->setQuery("SELECT * FROM `translations_cache` WHERE `translation_id` = {$id}");
        $old_row = $mydb->loadSingleResult();
        
        $mydb->setQuery("UPDATE `translations_cache` SET `translated_text` = '" . $mydb->escape_value($translated_text) . "' WHERE `translation_id` = {$id}");
        $mydb->executeQuery();
        
        log_audit_action("update", "translations_cache", "Updated translation string ID: {$id}", json_encode($old_row), json_encode($_POST));
        
        $_SESSION['message'] = "Translation updated successfully.";
        $_SESSION['msgtype'] = "success";
    }
    redirect("index.php");
}

if ($view === 'delete') {
    $id = $_GET['id'];
    global $mydb;
    $mydb->setQuery("SELECT * FROM `translations_cache` WHERE `translation_id` = {$id}");
    $old_row = $mydb->loadSingleResult();
    
    $mydb->setQuery("DELETE FROM `translations_cache` WHERE `translation_id` = {$id}");
    $mydb->executeQuery();
    
    log_audit_action("delete", "translations_cache", "Deleted translation key: " . ($old_row->text_key ?? $id), json_encode($old_row));
    
    $_SESSION['message'] = "Translation key deleted.";
    $_SESSION['msgtype'] = "success";
    redirect("index.php");
}

$content = 'list.php';
require_once("../theme/templates.php");
?>
