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
				$site_root = preg_replace('#frontend/?$#i', '', web_root);
				$query = substr($location, strpos($location, 'index.php?') + strlen('index.php?'));
				$location = rtrim($site_root, '/') . '/frontend/index.php?' . $query;
			}
			if (!headers_sent()) {
				header('Location: ' . $location, true, 302);
				exit;
			}
			$location_json = json_encode($location, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
			echo '<script>window.location.assign(' . $location_json . ');</script>';
			exit;
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
		$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';
		if ($lang === 'en' && empty($default_text)) {
			$default_text = str_replace('_', ' ', $text_key);
		}
		if (empty($default_text)) {
			$default_text = $text_key;
		}

		// English is the source language. Returning it directly avoids a database
		// query (and previously an INSERT) for every label rendered on every page.
		if ($lang === 'en') {
			return $default_text;
		}

		if (!isset($mydb)) {
			$mydb = new Database();
		}
		
		// Load a language once per request instead of issuing one query per label.
		static $translations_by_language = [];
		if (!array_key_exists($lang, $translations_by_language)) {
			$translations_by_language[$lang] = [];
			$lang_esc = $mydb->escape_value($lang);
			$mydb->setQuery("SELECT `text_key`, `translated_text` FROM `translations_cache` WHERE `lang_code` = '{$lang_esc}'");
			foreach ($mydb->loadResultList() as $translation) {
				$translations_by_language[$lang][$translation->text_key] = $translation->translated_text;
			}
		}

		return isset($translations_by_language[$lang][$text_key])
			? $translations_by_language[$lang][$text_key]
			: $default_text;
	}

	/**
	 * Build a product image URL from the path stored in tblproduct.IMAGES.
	 * Real uploaded files are served directly. A cacheable generated SVG is used
	 * when a migration references a file that is absent on an ephemeral server.
	 */
	function product_image_url($image_path, $product_name = '') {
		$site_root = preg_replace('#frontend/?$#i', '', web_root);
		$site_root = rtrim($site_root, '/') . '/';
		$image_path = trim(str_replace('\\', '/', (string) $image_path));

		if (preg_match('#^(?:https?:)?//#i', $image_path) || strpos($image_path, 'data:') === 0) {
			return $image_path;
		}

		$image_path = ltrim($image_path, '/');
		if (strpos($image_path, 'admin/products/') === 0) {
			$relative_path = $image_path;
		} elseif (strpos($image_path, 'uploaded_photos/') === 0) {
			$relative_path = 'admin/products/' . $image_path;
		} else {
			$relative_path = 'admin/products/uploaded_photos/' . basename($image_path);
		}

		$disk_path = rtrim(server_root, '/\\') . DIRECTORY_SEPARATOR
			. str_replace('/', DIRECTORY_SEPARATOR, $relative_path);
		if ($image_path !== '' && is_file($disk_path)) {
			return $site_root . implode('/', array_map('rawurlencode', explode('/', $relative_path)));
		}

		$params = ['image' => basename($image_path ?: 'product.jpg')];
		if ($product_name !== '') {
			$params['name'] = $product_name;
		}
		return $site_root . 'frontend/product-image.php?' . http_build_query($params);
	}
	
	// Multi-Currency Converter Helper
	function convert_price($price) {
		global $mydb;
		if (!isset($mydb)) {
			$mydb = new Database();
		}
		
		$currency = isset($_SESSION['currency']) ? $_SESSION['currency'] : 'INR';
		
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
		
		return '₹ ' . number_format($price, 2);
	}
		
?>
