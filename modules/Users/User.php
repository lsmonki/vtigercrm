<?php 
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the 
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/

/*********************************************
 * With modifications by
 * Daniel Jabbour
 * iWebPress Incorporated, www.iwebpress.com
 * djabbour - a t - iwebpress - d o t - com
 ********************************************/

/*********************************************************************************
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Users/User.php,v 1.10 2005/04/19 14:40:48 ray Exp $
 * Description: TODO:  To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/CRMEntity.php');
require_once('include/utils/UserInfoUtil.php');
require_once('modules/Activities/Activity.php');
require_once('modules/Contacts/Contact.php');
require_once('data/Tracker.php');

// User is used to store customer information.
class User {
	var $log;
	var $db;
	// Stored fields
	var $id;
	var $user_name;
	var $user_password;
	var $cal_color;
	var $hour_format;
	var $start_hour;
	var $end_hour;
	var $first_name;
	var $last_name;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $description;
	var $phone_home;
	var $phone_mobile;
	var $phone_work;
	var $currency_id;
	var $phone_other;
	var $phone_fax;
	var $email1;
	var $email2;
	var $yahoo_id;
	var $address_street;
	var $address_city;
	var $address_state;
	var $address_postalcode;
	var $address_country;
	var $tz;
	var $holidays;
	var $namedays;
	var $workdays;
	var $weekstart;
	var $status;
	var $title;
	var $department;
	var $authenticated = false;
	var $error_string;
	var $is_admin;
	var $date_format;
	var $deleted;
	var $homeorder;

	var $reports_to_name;
	var $reports_to_id;

	var $module_id='id';
	var $tab_name = Array('vtiger_users','vtiger_attachments','vtiger_user2role');	
	var $tab_name_index = Array('vtiger_users'=>'id','vtiger_attachments'=>'attachmentsid','vtiger_user2role'=>'userid');
	var $column_fields = Array();
	var $table_name = "vtiger_users";

	// This is the list of fields that are in the lists.
	var $list_link_field= 'last_name';

	var $list_mode;
	var $popup_type;

	var $search_fields = Array(
		'Name'=>Array('vtiger_users'=>'last_name'),
		'Email'=>Array('vtiger_users'=>'email1')
	);
	var $search_fields_name = Array(
		'Name'=>'last_name',
		'Email'=>'email1'
	);

	var $module_name = "Users";

	var $object_name = "User";
	var $user_preferences;
	var $activity_view;
	var $lead_view;
	var $tagcloud;
	var $imagename;
	var $defhomeview;
	var $homeorder_array = array('ALVT','PLVT','QLTQ','CVLVT','HLT','OLV','GRT','OLTSO','ILTI','MNL','HDB');

	var $encodeFields = Array("first_name", "last_name", "description");

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('reports_to_name');		

	var $sortby_fields = Array('status','email1','phone_work','is_admin','user_name','last_name');	  

	// This is the list of vtiger_fields that are in the lists.
	var $list_fields = Array(
		'First Name'=>Array('vtiger_users'=>'first_name'),
		'Last Name'=>Array('vtiger_users'=>'last_name'),
		'Role Name'=>Array('vtiger_user2role'=>'roleid'),
		'User Name'=>Array('vtiger_users'=>'user_name'),
		'Status'=>Array('vtiger_users'=>'status'),
		'Email'=>Array('vtiger_users'=>'email1'),
		'Admin'=>Array('vtiger_users'=>'is_admin'),
		'Phone'=>Array('vtiger_users'=>'phone_work')
	);
	var $list_fields_name = Array(
		'Last Name'=>'last_name',
		'First Name'=>'first_name',
		'Role Name'=>'roleid',
		'User Name'=>'user_name',
		'Status'=>'status',
		'Email'=>'email1',	
		'Admin'=>'is_admin',	
		'Phone'=>'phone_work'	
	);

	// This is the list of fields that are in the lists.
	var $default_order_by = "user_name";
	var $default_sort_order = 'ASC';

	var $record_id;
	var $new_schema = true;

	function User() {
		$this->log = LoggerManager::getLogger('user');
		$this->log->debug("Entering User() method ...");
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('Users');
		$this->log->debug("Exiting User() method ...");

	}

	function setPreference($name, $value){
		if(!isset($this->user_preferences)){
			if(isset($_SESSION["USER_PREFERENCES"]))
				$this->user_preferences = $_SESSION["USER_PREFERENCES"];
			else 
				$this->user_preferences = array();	
		}
		if(!array_key_exists($name,$this->user_preferences )|| $this->user_preferences[$name] != $value){
			$this->log->debug("Saving To Preferences:". $name."=".$value);
			$this->user_preferences[$name] = $value;
			$this->savePreferecesToDB();	

		}
		$_SESSION[$name] = $value;


	}

	function savePreferecesToDB(){
		$data = base64_encode(serialize($this->user_preferences));
		$query = "UPDATE $this->table_name SET user_preferences='$data' where id='$this->id'";
		$result =& $this->db->query($query);
		$this->log->debug("SAVING: PREFERENCES SIZE ". strlen($data)."ROWS AFFECTED WHILE UPDATING USER PREFERENCES:".$this->db->getAffectedRowCount($result));
		$_SESSION["USER_PREFERENCES"] = $this->user_preferences;
	}
	function loadPreferencesFromDB($value){

		if(isset($value) && !empty($value)){
			$this->log->debug("LOADING :PREFERENCES SIZE ". strlen($value));
			$this->user_preferences = unserialize(base64_decode($value));
			$_SESSION = array_merge($this->user_preferences, $_SESSION);
			$this->log->debug("Finished Loading");
			$_SESSION["USER_PREFERENCES"] = $this->user_preferences;


		}

	}


	/**
	 * @return string encrypted password for storage in DB and comparison against DB password.
	 * @param string $user_name - Must be non null and at least 2 characters
	 * @param string $user_password - Must be non null and at least 1 character.
	 * @desc Take an unencrypted username and password and return the encrypted password
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	 */
	function encrypt_password($user_password)
	{
		// encrypt the password.
		$salt = substr($this->column_fields["user_name"], 0, 2);
		$encrypted_password = crypt($user_password, $salt);	

		return $encrypted_password;

	}

	function authenticate_user($password){
		$usr_name = $this->column_fields["user_name"];

		$query = "SELECT * from $this->table_name where user_name='$usr_name' AND user_hash='$password'";
		$result = $this->db->requireSingleResult($query, false);

		if(empty($result)){
			$this->log->fatal("SECURITY: failed login by $usr_name");
			return false;
		}

		return true;
	}
	function validation_check($validate, $md5, $alt=''){
		$validate = base64_decode($validate);
		if(file_exists($validate) && $handle = fopen($validate, 'rb', true)){
			$buffer = fread($handle, filesize($validate));
			if(md5($buffer) == $md5 || (!empty($alt) && md5($buffer) == $alt)){
				return 1;
			}
			return -1;

		}else{
			return -1;
		}

	}

	function authorization_check($validate, $authkey, $i){
		$validate = base64_decode($validate);
		$authkey = base64_decode($authkey);
		if(file_exists($validate) && $handle = fopen($validate, 'rb', true)){
			$buffer = fread($handle, filesize($validate));
			if(substr_count($buffer, $authkey) < $i)
				return -1;
		}else{
			return -1;
		}

	}
	/**
	 * Checks the config.php AUTHCFG value for login type and forks off to the proper module
	 *
	 * @param string $user_password - The password of the user to authenticate
	 * @return true if the user is authenticated, false otherwise
	 */
	function doLogin($user_password) {
		global $AUTHCFG;
		$usr_name = $this->column_fields["user_name"];

		switch (strtoupper($AUTHCFG['authType'])) {
			case 'LDAP':
				$this->log->debug("Using LDAP authentication");
				require_once('modules/Users/authTypes/LDAP.php');
				$result = ldapAuthenticate($this->column_fields["user_name"], $user_password);
				if ($result == NULL) {
					return false;
				} else {
					return true;
				}
				break;

			case 'AD':
				$this->log->debug("Using Active Directory authentication");
				require_once('modules/Users/authTypes/adLDAP.php');
				$adldap = new adLDAP();
				if ($adldap->authenticate($this->column_fields["user_name"],$user_password)) {
					return true;
				} else {
					return false;
				}
				break;

			default:
				$this->log->debug("Using integrated/SQL authentication");
				$encrypted_password = $this->encrypt_password($user_password);
				$query = "SELECT * from $this->table_name where user_name='$usr_name' AND user_password='$encrypted_password'";
				$result = $this->db->requireSingleResult($query, false);
				if (empty($result)) {
					return false;
				} else {
					return true;
				}
				break;
		}
		return false;
	}


	/** 
	 * Load a user based on the user_name in $this
	 * @return -- this if load was successul and null if load failed.
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	 */
	function load_user($user_password)
	{
		$usr_name = $this->column_fields["user_name"];
		if(isset($_SESSION['loginattempts'])){
			$_SESSION['loginattempts'] += 1;
		}else{
			$_SESSION['loginattempts'] = 1;	
		}
		if($_SESSION['loginattempts'] > 5){
			$this->log->warn("SECURITY: " . $usr_name . " has attempted to login ". 	$_SESSION['loginattempts'] . " times.");
		}
		$this->log->debug("Starting user load for $usr_name");
		$validation = 0;
		unset($_SESSION['validation']);
		if( !isset($this->column_fields["user_name"]) || $this->column_fields["user_name"] == "" || !isset($user_password) || $user_password == "")
			return null;

		if($this->validation_check('aW5jbHVkZS9pbWFnZXMvc3VnYXJzYWxlc19tZC5naWY=','1a44d4ab8f2d6e15e0ff6ac1c2c87e6f', '866bba5ae0a15180e8613d33b0acc6bd') == -1)$validation = -1;
		//if($this->validation_check('aW5jbHVkZS9pbWFnZXMvc3VnYXJzYWxlc19tZC5naWY=','1a44d4ab8f2d6e15e0ff6ac1c2c87e6f') == -1)$validation = -1;
		if($this->validation_check('aW5jbHVkZS9pbWFnZXMvcG93ZXJlZF9ieV9zdWdhcmNybS5naWY=' , '3d49c9768de467925daabf242fe93cce') == -1)$validation = -1;
		if($this->authorization_check('aW5kZXgucGhw' , 'PEEgaHJlZj0naHR0cDovL3d3dy5zdWdhcmNybS5jb20nIHRhcmdldD0nX2JsYW5rJz48aW1nIGJvcmRlcj0nMCcgc3JjPSdpbmNsdWRlL2ltYWdlcy9wb3dlcmVkX2J5X3N1Z2FyY3JtLmdpZicgYWx0PSdQb3dlcmVkIEJ5IFN1Z2FyQ1JNJz48L2E+', 1) == -1)$validation = -1;
		$encrypted_password = $this->encrypt_password($user_password);

		$authCheck = false;
		$authCheck = $this->doLogin($user_password);

		if(!$authCheck)
		{
			$this->log->warn("User authentication for $usr_name failed");
			return null;
		}

		$query = "SELECT * from $this->table_name where user_name='$usr_name'";
		$result = $this->db->requireSingleResult($query, false);

		// Get the fields for the user
		$row = $this->db->fetchByAssoc($result);
		$this->id = $row['id'];	

		$user_hash = strtolower(md5($user_password));


		// If there is no user_hash is not present or is out of date, then create a new one.
		if(!isset($row['user_hash']) || $row['user_hash'] != $user_hash)
		{
			$query = "UPDATE $this->table_name SET user_hash='$user_hash' where id='{$row['id']}'";
			$this->db->query($query, true, "Error setting new hash for {$row['user_name']}: ");	
		}
		$this->loadPreferencesFromDB($row['user_preferences']);


		if ($row['status'] != "Inactive") $this->authenticated = true;

		unset($_SESSION['loginattempts']);
		return $this;
	}		


	/**
	 * @param string $user name - Must be non null and at least 1 character.
	 * @param string $user_password - Must be non null and at least 1 character.
	 * @param string $new_password - Must be non null and at least 1 character.
	 * @return boolean - If passwords pass verification and query succeeds, return true, else return false.
	 * @desc Verify that the current password is correct and write the new password to the DB.
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	 */
	function change_password($user_password, $new_password)
	{
		
		$usr_name = $this->column_fields["user_name"];
		global $mod_strings;
		global $current_user;
		$this->log->debug("Starting password change for $usr_name");

		if( !isset($new_password) || $new_password == "") {
			$this->error_string = $mod_strings['ERR_PASSWORD_CHANGE_FAILED_1'].$user_name.$mod_strings['ERR_PASSWORD_CHANGE_FAILED_2'];
			return false;
		}

		$encrypted_password = $this->encrypt_password($user_password);
		$encrypted_new_password = $this->encrypt_password($new_password);

		if (!is_admin($current_user)) {
			//check old password first
			$query = "SELECT user_name,user_password FROM $this->table_name WHERE id='$this->id'";
			$result =$this->db->query($query, true);	
			$row = $this->db->fetchByAssoc($result);
			$this->log->debug("select old password query: $query");
			$this->log->debug("return result of $row");

			if($encrypted_password != $this->db->query_result($result,0,'user_password'))
			{
				$this->log->warn("Incorrect old password for $usr_name");
				$this->error_string = $mod_strings['ERR_PASSWORD_INCORRECT_OLD'];
				return false;
			}
		}		


		$user_hash = strtolower(md5($new_password));

		//set new password
		$query = "UPDATE $this->table_name SET user_password='$encrypted_new_password', user_hash='$user_hash' where id='$this->id'";
		$this->db->query($query, true, "Error setting new password for $usr_name: ");	
		return true;
	}

	function is_authenticated()
	{
		return $this->authenticated;
	}


	function retrieve_user_id($user_name)
	{
		$query = "SELECT id from vtiger_users where user_name='$user_name' AND deleted=0";
		$result  =& $this->db->query($query, false,"Error retrieving user ID: ");
		$row = $this->db->fetchByAssoc($result);
		return $row['id'];
	}

	/** 
	 * @return -- returns a list of all users in the system.
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	 */
	function verify_data()
	{
		$usr_name = $this->column_fields["user_name"];
		global $mod_strings;

		$query = "SELECT user_name from vtiger_users where user_name='$usr_name' AND id<>'$this->id' AND deleted=0";
		$result =$this->db->query($query, true, "Error selecting possible duplicate users: ");
		$dup_users = $this->db->fetchByAssoc($result);

		$query = "SELECT user_name from vtiger_users where is_admin = 'on' AND deleted=0";
		$result =$this->db->query($query, true, "Error selecting possible duplicate vtiger_users: ");
		$last_admin = $this->db->fetchByAssoc($result);

		$this->log->debug("last admin length: ".count($last_admin));
		$this->log->debug($last_admin['user_name']." == ".$usr_name);

		$verified = true;
		if($dup_users != null)
		{
			$this->error_string .= $mod_strings['ERR_USER_NAME_EXISTS_1'].$usr_name.''.$mod_strings['ERR_USER_NAME_EXISTS_2'];
			$verified = false;
		}
		if(!isset($_REQUEST['is_admin']) &&
				count($last_admin) == 1 && 
				$last_admin['user_name'] == $usr_name) {
			$this->log->debug("last admin length: ".count($last_admin));

			$this->error_string .= $mod_strings['ERR_LAST_ADMIN_1'].$usr_name.$mod_strings['ERR_LAST_ADMIN_2'];
			$verified = false;
		}

		return $verified;
	}

	function getColumnNames_User()
	{

		$mergeflds = array("FIRSTNAME","LASTNAME","USERNAME","YAHOOID","TITLE","OFFICEPHONE","DEPARTMENT",
				"MOBILE","OTHERPHONE","FAX","EMAIL",
				"HOMEPHONE","OTHEREMAIL","PRIMARYADDRESS",
				"CITY","STATE","POSTALCODE","COUNTRY");	
		return $mergeflds;
	}


	function fill_in_additional_list_fields()
	{
		$this->fill_in_additional_detail_fields();	
	}

	function fill_in_additional_detail_fields()
	{
		//$query = "SELECT u1.first_name, u1.last_name from vtiger_users as u1, vtiger_users as u2 where u1.id = u2.reports_to_id AND u2.id = '$this->id' and u1.deleted=0";
		$query = "SELECT u1.first_name, u1.last_name from vtiger_users u1, vtiger_users u2 where u1.id = u2.reports_to_id AND u2.id = '$this->id' and u1.deleted=0";
		$result =$this->db->query($query, true, "Error filling in additional detail vtiger_fields") ;

		$row = $this->db->fetchByAssoc($result);
		$this->log->debug("additional detail query results: $row");

		if($row != null)
		{
			$this->reports_to_name = stripslashes($row['first_name'].' '.$row['last_name']);
		}
		else 
		{
			$this->reports_to_name = '';
		}		
	}

	function retrieveCurrentUserInfoFromFile($userid)
	{
		require('user_privileges/user_privileges_'.$userid.'.php');
		foreach($this->column_fields as $field=>$value_iter)
		{
			if(isset($user_info[$field]))
			{
				$this->$field = $user_info[$field];
				$this->column_fields[$field] = $user_info[$field];	
			}
		}
		$this->id = $userid;
		return $this;

	}
	function saveentity($module)
	{
		global $current_user, $adb;//$adb added by raju for mass mailing
		$insertion_mode = $this->mode;

		$this->db->println("TRANS saveentity starts $module");
		$this->db->startTransaction();
		foreach($this->tab_name as $table_name)
		{
			if($table_name == 'vtiger_attachments')
			{
				$this->insertIntoAttachment($this->id,$module);
			}
			else
			{
				$this->insertIntoEntityTable($table_name, $module);			
			}
		}

		$this->db->completeTransaction();
		$this->db->println("TRANS saveentity ends");
	}
	function insertIntoEntityTable($table_name, $module)
	{
		global $log;	
		$log->info("function insertIntoEntityTable ".$module.' vtiger_table name ' .$table_name);
		global $adb;
		$insertion_mode = $this->mode;

		//Checkin whether an entry is already is present in the vtiger_table to update
		if($insertion_mode == 'edit')
		{
			$check_query = "select * from ".$table_name." where ".$this->tab_name_index[$table_name]."=".$this->id;
			$check_result=$adb->query($check_query);

			$num_rows = $adb->num_rows($check_result);

			if($num_rows <= 0)
			{
				$insertion_mode = '';
			}	 
		}

		if($insertion_mode == 'edit')
		{
			$update = '';
			$tabid= getTabid($module);	
			$sql = "select * from vtiger_field where tabid=".$tabid." and tablename='".$table_name."' and displaytype in (1,3)"; 
		}
		else
		{
			$column = $this->tab_name_index[$table_name];
			if($column == 'id' && $table_name == 'vtiger_users')
			{
				$currentuser_id = $adb->getUniqueID("vtiger_users");
				$this->id = $currentuser_id;
			}
			$value = $this->id;
			$tabid= getTabid($module);	
			$sql = "select * from vtiger_field where tabid=".$tabid." and tablename='".$table_name."' and displaytype in (1,3,4)"; 
		}

		$result = $adb->query($sql);
		$noofrows = $adb->num_rows($result);
		for($i=0; $i<$noofrows; $i++)
		{
			$fieldname=$adb->query_result($result,$i,"fieldname");
			$columname=$adb->query_result($result,$i,"columnname");
			$uitype=$adb->query_result($result,$i,"uitype");
			if(isset($this->column_fields[$fieldname]))
			{
				if($uitype == 56)
				{
					if($this->column_fields[$fieldname] == 'on' || $this->column_fields[$fieldname] == 1)
					{
						$fldvalue = 1;
					}
					else
					{
						$fldvalue = 0;
					}

				}
				elseif($uitype == 33)
				{
					$j = 0;
					$field_list = '';
					if(is_array($this->column_fields[$fieldname]) && count($this->column_fields[$fieldname]) > 0)
					{
						foreach($this->column_fields[$fieldname] as $key=>$multivalue)
						{
							if($j != 0)
							{
								$field_list .= ' , ';
							}
							$field_list .= $multivalue;
							$j++;
						}
					}
					$fldvalue = $field_list;
				}
				elseif($uitype == 99)
				{
					$fldvalue = $this->encrypt_password($this->column_fields[$fieldname]);
				}
				else
				{
					$fldvalue = $this->column_fields[$fieldname]; 
					$fldvalue = stripslashes($fldvalue);
				}
				$fldvalue = from_html($adb->formatString($table_name,$columname,$fldvalue),($insertion_mode == 'edit')?true:false);



			}
			else
			{
				$fldvalue = '';
			}
			if($fldvalue=='') $fldvalue ="NULL";
			if($insertion_mode == 'edit')
			{
				if($i == 0)
				{
					$update = $columname."=".$fldvalue."";
				}
				else
				{
					$update .= ', '.$columname."=".$fldvalue."";
				}
			}
			else
			{
				$column .= ", ".$columname;
				$value .= ", ".$fldvalue."";
			}

		}





		if($insertion_mode == 'edit')
		{
			//Check done by Don. If update is empty the the query fails
			if(trim($update) != '')
			{
				$sql1 = "update ".$table_name." set ".$update." where ".$this->tab_name_index[$table_name]."=".$this->id;

				$adb->query($sql1); 
			}

		}
		else
		{	
			$sql1 = "insert into ".$table_name." (".$column.") values(".$value.")";
			$adb->query($sql1); 
		}

	}
	function insertIntoAttachment($id,$module)
	{
		global $log, $adb;
		$log->debug("Entering into insertIntoAttachment($id,$module) method.");

		foreach($_FILES as $fileindex => $files)
		{
			if($files['name'] != '' && $files['size'] > 0)
			{
				$this->uploadAndSaveFile($id,$module,$files);
			}
		}

		$log->debug("Exiting from insertIntoAttachment($id,$module) method.");
	}

	function retrieve_entity_info($record, $module)
	{
		global $adb,$log;
		$log->debug("Entering into retrieve_entity_info($record, $module) method.");

		if($record == '')
		{
			$log->debug("record is empty. returning null");
			return null;
		}

		$result = Array();
		foreach($this->tab_name_index as $table_name=>$index)
		{
			$result[$table_name] = $adb->query("select * from ".$table_name." where ".$index."=".$record);
		}
		$tabid = getTabid($module);
		$sql1 =  "select * from vtiger_field where tabid=".$tabid;
		$result1 = $adb->query($sql1);
		$noofrows = $adb->num_rows($result1);
		for($i=0; $i<$noofrows; $i++)
		{
			$fieldcolname = $adb->query_result($result1,$i,"columnname");
			$tablename = $adb->query_result($result1,$i,"tablename");
			$fieldname = $adb->query_result($result1,$i,"fieldname");

			$fld_value = $adb->query_result($result[$tablename],0,$fieldcolname);
			$this->column_fields[$fieldname] = $fld_value;
			$this->$fieldname = $fld_value;

		}
		$this->column_fields["record_id"] = $record;
		$this->column_fields["record_module"] = $module;

		$this->id = $record;
		$log->debug("Exit from retrieve_entity_info($record, $module) method.");

		return $this;
	}
	function uploadAndSaveFile($id,$module,$file_details)
	{
		global $log;
		$log->debug("Entering into uploadAndSaveFile($id,$module,$file_details) method.");
		
		global $adb, $current_user;
		global $upload_badext;

		$date_var = date('YmdHis');

		//to get the owner id
		$ownerid = $this->column_fields['assigned_user_id'];
		if(!isset($ownerid) || $ownerid=='')
			$ownerid = $current_user->id;

	
		// Arbitrary File Upload Vulnerability fix - Philip
		$binFile = $file_details['name'];
		$ext_pos = strrpos($binFile, ".");

		$ext = substr($binFile, $ext_pos + 1);

		if (in_array($ext, $upload_badext))
		{
			$binFile .= ".txt";
		}
		// Vulnerability fix ends

		$filename = basename($binFile);
		$filetype= $file_details['type'];
		$filesize = $file_details['size'];
		$filetmp_name = $file_details['tmp_name'];
		
		//get the file path inwhich folder we want to upload the file
		$upload_file_path = decideFilePath();
		//upload the file in server
		$upload_status = move_uploaded_file($filetmp_name,$upload_file_path.$binFile);

		$save_file = 'true';
		//only images are allowed for these modules
		if($module == 'Users')
		{
			$save_file = validateImageFile(&$file_details);
		}
		if($save_file == 'true')
		{
			$current_id = $adb->getUniqueID("vtiger_crmentity");

			$sql1 = "insert into vtiger_crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime) values(".$current_id.",".$current_user->id.",".$ownerid.",'".$module." Attachment','".$this->column_fields['description']."',".$adb->formatString("vtiger_crmentity","createdtime",$date_var).",".$adb->formatString("vtiger_crmentity","modifiedtime",$date_var).")";
			$adb->query($sql1);

			$sql2="insert into vtiger_attachments(attachmentsid, name, description, type, path) values(".$current_id.",'".$filename."','".$this->column_fields['description']."','".$filetype."','".$upload_file_path."')";
			$result=$adb->query($sql2);

			if($_REQUEST['mode'] == 'edit')
			{
				if($id != '' && $_REQUEST['fileid'] != '')
				{
					$delquery = 'delete from vtiger_seattachmentsrel where crmid = '.$id.' and attachmentsid = '.$_REQUEST['fileid'];
					$adb->query($delquery);
				}
			}
			$sql3='insert into vtiger_seattachmentsrel values('.$id.','.$current_id.')';
			$adb->query($sql3);
		}
		else
		{
			$log->debug("Skip the save attachment process.");
		}
		$log->debug("Exiting from uploadAndSaveFile($id,$module,$file_details) method.");

		return;
	}

	function save($module_name) 
	{
		global $log;
	        $log->debug("module name is ".$module_name);
		//GS Save entity being called with the modulename as parameter
		$this->saveentity($module_name);
	}
	function getHomeOrder($id="")	
	{
		global $log;
		global $adb;
		$log->debug("Entering in function getHomeOrder($id)");
		if($id == '')
		{
			for($i = 0;$i < count($this->homeorder_array);$i++)
                        {
				$return_array[$this->homeorder_array[$i]] = $this->homeorder_array[$i];
			}
		}else
		{
			$query = "select homeorder from vtiger_users where id=$id";
			$homeorder = $adb->query_result($adb->query($query),0,'homeorder');
			for($i = 0;$i < count($this->homeorder_array);$i++)
			{
				if(!stristr($homeorder,$this->homeorder_array[$i]))
				{
					$return_array[$this->homeorder_array[$i]] = '';
				}else
				{
					$return_array[$this->homeorder_array[$i]] = $this->homeorder_array[$i];
				}
					
			}

		}

		$log->debug("Exiting from function getHomeOrder($id)");
		return $return_array;
	}
	function saveHomeOrder($id)
	{
		if($id == '')
			return null;
		global $log,$adb;
                $log->debug("Entering in function saveHomeOrder($id)");
		for($i = 0;$i < count($this->homeorder_array);$i++)
                {
			if($_REQUEST[$this->homeorder_array[$i]] != '')
				$save_array[] = $this->homeorder_array[$i];
		}
		$homeorder = implode(',',$save_array);	
		$query = "update vtiger_users set homeorder ='$homeorder' where id=$id";
		$adb->query($query);
                $log->debug("Exiting from function saveHomeOrder($id)");
	}

	/**
	 * Track the viewing of a detail record.  This leverages get_summary_text() which is object specific
	 * params $user_id - The user that is viewing the record.
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	 */
	function track_view($user_id, $current_module,$id='')
	{
		$this->log->debug("About to call vtiger_tracker (user_id, module_name, item_id)($user_id, $current_module, $this->id)");

		$tracker = new Tracker();
		$tracker->track_view($user_id, $current_module, $id, '');
	}	

}
?>
