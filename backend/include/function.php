<?php
	function strip_zeros_from_date($marked_string="") {
		//first remove the marked zeros
		$no_zeros = str_replace('*0','',$marked_string);
		$cleaned_string = str_replace('*0','',$no_zeros);
		return $cleaned_string;
	}
	function redirect_to($location = NULL) {
		if($location != NULL){
			header("Location: {$location}");
			exit;
		}
	}
	function redirect($location=Null){
		if($location!=Null){
			// Global fix: Redirects to root index.php?q=... from backend lose parameters during routing.
			// Rewrite to frontend/index.php?q=... to preserve the correct routing location.
			if (strpos($location, 'index.php?') !== false && strpos($location, 'frontend/') === false && strpos($location, 'admin/') === false) {
				$location = str_replace('index.php?', 'frontend/index.php?', $location);
			}
			echo "<script>
					window.location='{$location}'
				</script>";	
		}else{
			echo 'error location';
		}
		 
	}
	function output_message($message="") {
	
		if(!empty($message)){
		return "<p class=\"message\">{$message}</p>";
		}else{
			return "";
		}
	}
	function date_toText($datetime=""){
		$nicetime = strtotime($datetime);
		return date("F d, Y \\a\\t h:i A", $nicetime);	
					
	}
	spl_autoload_register(function ($class_name) {
		$class_name = strtolower($class_name);
		$path = LIB_PATH.DS."{$class_name}.php";
		if(file_exists($path)){
			require_once($path);
		}else{
			die("The file {$class_name}.php could not be found.");
		}
	});

	function currentpage_public(){
		$this_page = $_SERVER['SCRIPT_NAME']; // will return /path/to/file.php
	    $bits = explode('/',$this_page);
	    $this_page = $bits[count($bits)-1]; // will return file.php, with parameters if case, like file.php?id=2
	    $this_script = $bits[0]; // will return file.php, no parameters*/
		 return $bits[2];
	  
	}

	function currentpage_admin(){
		$this_page = $_SERVER['SCRIPT_NAME']; // will return /path/to/file.php
	    $bits = explode('/',$this_page);
	    $this_page = $bits[count($bits)-1]; // will return file.php, with parameters if case, like file.php?id=2
	    $this_script = $bits[0]; // will return file.php, no parameters*/
		 return $bits[4];
	  
	}
  // echo "string " .currentpage_admin()."<br/>";

	function curPageName() {
 return substr($_SERVER['REQUEST_URI'], 21, strrpos($_SERVER['REQUEST_URI'], '/')-24);
}

  // echo "The current page name is ".curPageName();
	 
	function msgBox($msg=""){
		?>
		<script type="text/javascript">
			 alert(<?php echo $msg; ?>)
		</script>
		<?php
	}

	function log_audit_action($action, $table, $old_values = null, $new_values = null) {
		global $mydb;
		if (!isset($mydb)) {
			$mydb = new Database();
		}
		$adminId = isset($_SESSION['USERID']) ? (int)$_SESSION['USERID'] : 0;
		$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
		$oldValEsc = $old_values ? "'" . $mydb->escape_value(json_encode($old_values)) . "'" : "NULL";
		$newValEsc = $new_values ? "'" . $mydb->escape_value(json_encode($new_values)) . "'" : "NULL";
		$actionEsc = $mydb->escape_value($action);
		$tableEsc = $mydb->escape_value($table);
		
		$query = "INSERT INTO `audit_logs` (`admin_id`, `action`, `target_table`, `old_values`, `new_values`, `ip_address`, `timestamp`)
				  VALUES ({$adminId}, '{$actionEsc}', '{$tableEsc}', {$oldValEsc}, {$newValEsc}, '{$ip}', NOW())";
		$mydb->setQuery($query);
		$mydb->executeQuery();
	}
	
	// Multi-Language Helper
	function t($text_key, $default_text = "") {
		global $mydb;
		if (!isset($mydb)) {
			$mydb = new Database();
		}
		
		$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';
		if ($lang === 'en' && empty($default_text)) {
			$default_text = str_replace('_', ' ', $text_key);
		}
		if (empty($default_text)) {
			$default_text = $text_key;
		}
		
		// Static Cache
		static $translations_cache = [];
		$cache_key = $lang . '_' . $text_key;
		if (isset($translations_cache[$cache_key])) {
			return $translations_cache[$cache_key];
		}
		
		$text_key_esc = $mydb->escape_value($text_key);
		$lang_esc = $mydb->escape_value($lang);
		
		$query = "SELECT `translated_text` FROM `translations_cache` WHERE `lang_code` = '{$lang_esc}' AND `text_key` = '{$text_key_esc}' LIMIT 1";
		$mydb->setQuery($query);
		$row = $mydb->loadSingleResult();
		
		if ($row) {
			$translations_cache[$cache_key] = $row->translated_text;
			return $row->translated_text;
		}
		
		// Fallback to base translation if not found in Spanish/Arabic
		if ($lang !== 'en') {
			$query = "SELECT `translated_text` FROM `translations_cache` WHERE `lang_code` = 'en' AND `text_key` = '{$text_key_esc}' LIMIT 1";
			$mydb->setQuery($query);
			$en_row = $mydb->loadSingleResult();
			if ($en_row) {
				$translations_cache[$cache_key] = $en_row->translated_text;
				return $en_row->translated_text;
			}
		}
		
		// Register default string in cache if not exists
		$def_esc = $mydb->escape_value($default_text);
		$mydb->setQuery("INSERT IGNORE INTO `translations_cache` (`lang_code`, `text_key`, `translated_text`) VALUES ('{$lang_esc}', '{$text_key_esc}', '{$def_esc}')");
		$mydb->executeQuery();
		
		$translations_cache[$cache_key] = $default_text;
		return $default_text;
	}
	
	// Multi-Currency Converter Helper
	function convert_price($price) {
		global $mydb;
		if (!isset($mydb)) {
			$mydb = new Database();
		}
		
		$currency = isset($_SESSION['currency']) ? $_SESSION['currency'] : 'PHP';
		
		static $currencies_cache = [];
		if (empty($currencies_cache)) {
			$mydb->setQuery("SELECT * FROM `currencies` WHERE `status` = 'Active'");
			$res = $mydb->loadResultList();
			if ($res) {
				foreach ($res as $c) {
					$currencies_cache[$c->currency_code] = $c;
				}
			}
		}
		
		if (isset($currencies_cache[$currency])) {
			$curr_obj = $currencies_cache[$currency];
			$converted = $price * $curr_obj->exchange_rate;
			return $curr_obj->currency_symbol . ' ' . number_format($converted, 2);
		}
		
		return '₱ ' . number_format($price, 2);
	}
		
?>