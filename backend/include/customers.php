<?php
require_once(LIB_PATH.DS.'database.php');
#[AllowDynamicProperties]
class Customer {
	protected static  $tblname = "tblcustomer";

	function dbfields () {
		global $mydb;
		return $mydb->getfieldsononetable(self::$tblname);

	}
	function listofcustomer(){
		global $mydb;
		$mydb->setQuery("SELECT * FROM ".self::$tblname);
		return $cur;
	}
	function find_customer($id="",$name=""){
		global $mydb;
		$mydb->setQuery("SELECT * FROM ".self::$tblname." 
			WHERE CUSTOMERID = {$id} OR FNAME = '{$name}'");
		$cur = $mydb->executeQuery();
		$row_count = $mydb->num_rows($cur);
		return $row_count;
	}

 
	function find_all_customer($name=""){
		global $mydb;
		$mydb->setQuery("SELECT * FROM ".self::$tblname." 
			WHERE FNAME = '{$name}'");
		$cur = $mydb->executeQuery();
		$row_count = $mydb->num_rows($cur);
		return $row_count;
	}
	static function cusAuthentication($email, $password){
		global $mydb;
		$identifier = $mydb->escape_value(strtolower(trim($email)));
		$mydb->setQuery("SELECT * FROM ".self::$tblname." WHERE LOWER(`CUSUNAME`) = '{$identifier}' OR LOWER(`EMAILADD`) = '{$identifier}' LIMIT 1");
		$user_found = $mydb->loadSingleResult();
		if (!$user_found) {
			return false;
		}

		$stored = (string) $user_found->CUSPASS;
		$is_legacy_sha1 = preg_match('/^[a-f0-9]{40}$/i', $stored) === 1;
		$valid = $is_legacy_sha1
			? hash_equals(strtolower($stored), sha1($password))
			: password_verify($password, $stored);
		if (!$valid) {
			return false;
		}

		// Transparently upgrade old SHA-1 accounts after a successful login.
		if ($is_legacy_sha1) {
			$new_hash = $mydb->escape_value(password_hash($password, PASSWORD_BCRYPT));
			$mydb->setQuery("UPDATE ".self::$tblname." SET `CUSPASS`='{$new_hash}' WHERE `CUSTOMERID`=" . (int) $user_found->CUSTOMERID);
			$mydb->executeQuery();
		}

		if (function_exists('session_regenerate_id')) {
			session_regenerate_id(true);
		}
		$_SESSION['CUSID'] = $user_found->CUSTOMERID;
		$_SESSION['CUSNAME'] = $user_found->FNAME . ' ' . $user_found->LNAME;
		$_SESSION['CUSUNAME'] = $user_found->CUSUNAME;
		unset($_SESSION['CUSUPASS']);
		return true;
	}
	
	 
	function single_customer($id=""){
			global $mydb;
			$mydb->setQuery("SELECT * FROM ".self::$tblname." 
				Where CUSTOMERID= {$id} LIMIT 1");
			$cur = $mydb->loadSingleResult();
			return $cur;
	}
	function find_phone($phone=""){
			global $mydb;
			$mydb->setQuery("SELECT * FROM ".self::$tblname." 
				Where PHONE= {$phone} LIMIT 1");
			$cur = $mydb->loadSingleResult();
			return $cur;
	}
	/*---Instantiation of Object dynamically---*/
	static function instantiate($record) {
		$object = new self;

		foreach($record as $attribute=>$value){
		  if($object->has_attribute($attribute)) {
		    $object->$attribute = $value;
		  }
		} 
		return $object;
	}
	
	
	/*--Cleaning the raw data before submitting to Database--*/
	private function has_attribute($attribute) {
	  // We don't care about the value, we just want to know if the key exists
	  // Will return true or false
	  return array_key_exists($attribute, $this->attributes());
	}

	protected function attributes() { 
		// return an array of attribute names and their values
	  global $mydb;
	  $attributes = array();
	  foreach($this->dbfields() as $field) {
	    if(property_exists($this, $field)) {
			$attributes[$field] = $this->$field;
		}
	  }
	  return $attributes;
	}
	
	protected function sanitized_attributes() {
	  global $mydb;
	  $clean_attributes = array();
	  // sanitize the values before submitting
	  // Note: does not alter the actual value of each attribute
	  foreach($this->attributes() as $key => $value){
	    $clean_attributes[$key] = $mydb->escape_value($value);
	  }
	  return $clean_attributes;
	}
	
	
	/*--Create,Update and Delete methods--*/
	public function save() {
	  // A new record won't have an id yet.
	  return isset($this->id) ? $this->update() : $this->create();
	}
	
	public function create() {
		global $mydb;
		// Don't forget your SQL syntax and good habits:
		// - INSERT INTO table (key, key) VALUES ('value', 'value')
		// - single-quotes around all values
		// - escape all values to prevent SQL injection
		$attributes = $this->sanitized_attributes();
		$sql = "INSERT INTO ".self::$tblname." (";
		$sql .= join(", ", array_keys($attributes));
		$sql .= ") VALUES ('";
		$sql .= join("', '", array_values($attributes));
		$sql .= "')";
	echo $mydb->setQuery($sql);
	
	 if($mydb->executeQuery()) {
	    $this->id = $mydb->insert_id();
	    return true;
	  } else {
	    return false;
	  }
	}

	public function update($id=0) {
	  global $mydb;
		$attributes = $this->sanitized_attributes();
		$attribute_pairs = array();
		foreach($attributes as $key => $value) {
		  $attribute_pairs[] = "{$key}='{$value}'";
		}
		$sql = "UPDATE ".self::$tblname." SET ";
		$sql .= join(", ", $attribute_pairs);
		$sql .= " WHERE CUSTOMERID=". $id;
	  $mydb->setQuery($sql);
	 	if(!$mydb->executeQuery()) return false; 	
		
	}

	public function delete($id=0) {
		global $mydb;
		  $sql = "DELETE FROM ".self::$tblname;
		  $sql .= " WHERE CUSTOMERID=". $id;
		  $sql .= " LIMIT 1 ";
		  $mydb->setQuery($sql);
		  
			if(!$mydb->executeQuery()) return false; 	
	
	}	


}
?>
