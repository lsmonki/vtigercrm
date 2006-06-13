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

// User is used to store customer information.
class User extends CRMEntity {
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
	var $table_name = "users";
	var $sortby_fields = Array();		  
	
    // This is the list of fields that are in the lists.
    var $list_fields_name = Array();
    var $list_link_field= '';

	var $list_mode;
	var $popup_type;

	var $search_fields = Array();
    var $search_fields_name = Array();
	
	var $module_name = "Users";

	var $object_name = "User";
	var $user_preferences;
	var $activity_view;
	var $lead_view;
	var $tagcloud;
	var $imagename;
	var $defhomeview;
	//var $sortby_fields = Array('user_name','email1','last_name','is_admin','status');	

	var $encodeFields = Array("first_name", "last_name", "description");

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('reports_to_name');		
	
	// This is the list of fields that are in the lists.
	var $list_fields = Array('id', 'first_name', 'last_name', 'user_name', 'status', 'department', 'yahoo_id', 'is_admin', 'email1', 'phone_work');
	//commented as we get issues with sugarbean
	/*
	var $list_fields = Array(
		'UserName'=>Array('users'=>'user_name'),
		'Role'=>Array(''=>''),
		'Email'=>Array('users'=>'email1'),
		'Name'=>Array('users'=>'last_name'),
		'Admin'=>Array('users'=>'is_admin'),
		'Status'=>Array('users'=>'status'),
		'Tools'=>Array(''=>''),
	);*/	
		
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
		
		
		if ($this->status != "Inactive") $this->authenticated = true;
		
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
			$query = "SELECT user_name FROM $this->table_name WHERE id='$this->id'";
			$result =$this->db->query($query, true);	
			$row = $this->db->fetchByAssoc($result);
			$this->log->debug("select old password query: $query");
			$this->log->debug("return result of $row");
	
			if($row == null)
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

//function added for the listview of vtiger_users for 5.0 beta
  function getUserListViewHeader()
  {
	  global $mod_strings;
	  $header_array=array($mod_strings['LBL_LIST_NO'],$mod_strings['LBL_LIST_TOOLS'],$mod_strings['LBL_LIST_USER_NAME_ROLE'],$mod_strings['LBL_LIST_EMAIL'],$mod_strings['LBL_LIST_PHONE'],$mod_strings['LBL_ADMIN'],$mod_strings['LBL_STATUS']);
	  return $header_array;
  }

  function getUserListViewEntries($navigation_array,$sorder='',$orderby='')
  {
	  global $theme;
	  global $adb, $current_user;
	  $theme_path="themes/".$theme."/";
	  $image_path=$theme_path."images/";
	  if($sorder != '' && $orderby !='')
	  $list_query = ' SELECT * from users where deleted=0 order by '.$orderby.' '.$sorder;
	  else
	  $list_query = "SELECT * from users where deleted=0 order by ".$this->default_order_by." ".$this->default_sort_order;
	  $result =$adb->query($list_query);
	  $entries_list = array();
	  $roleinfo = getAllRoleDetails();

	  for($i = $navigation_array['start'];$i <= $navigation_array['end_val']; $i++)
	  {
		  $entries=array();
		  $id=$adb->query_result($result,$i-1,'id');

		  $entries[]='<a href="index.php?action=DetailView&module=Users&parenttab=Settings&record='.$id.'">'.$this->db->query_result($result,$i-1,'user_name').'</a>';

		  $rolecode= fetchUserRole($adb->query_result($result,$i-1,'id'));
		  $entries[]='<a href="index.php?action=RoleDetailView&module=Users&parenttab=Settings&roleid='.$rolecode.'">'.$roleinfo[$rolecode][0];
		  $entries[]='<a href="mailto:'.$adb->query_result($result,$i-1,'email1').'">'.$adb->query_result($result,$i-1,'email1').' </a>';

		  $entries[]='<a href="index.php?action=DetailView&module=Users&parenttab=Settings&record='.$id.'">'. $this->db->query_result($result,$i-1,'last_name').' '.$adb->query_result($result,$i-1,'first_name').'</a>';

		  $entries[]=$adb->query_result($result,$i-1,'is_admin');
		  $entries[]=$adb->query_result($result,$i-1,'status');
		  $entries[]=$adb->query_result($result,$i-1,'phone_work');
		  if($adb->query_result($result,$i-1,'user_name') == 'admin' || $adb->query_result($result,$i-1,'user_name') == 'standarduser' )
		  {
			  $entries[]='<a href="index.php?action=EditView&return_action=ListView&return_module=Users&module=Users&parenttab=Settings&record='.$id.'"><img src="'.$image_path.'editfield.gif" border="0" alt="Edit" title="Edit"/></a>&nbsp;&nbsp;';
			  }
			  elseif($adb->query_result($result,$i-1,'id') == $current_user->id)
			  {
				  $entries[]='<a href="index.php?action=EditView&return_action=ListView&return_module=Users&module=Users&parenttab=Settings&record='.$id.'"><img src="'.$image_path.'editfield.gif" border="0" alt="Edit" title="Edit"/></a>&nbsp;&nbsp;';
			  }
			  else

			  $entries[]='<a href="index.php?action=EditView&return_action=ListView&return_module=Users&module=Users&parenttab=Settings&record='.$id.'"><img src="'.$image_path.'editfield.gif" border="0" alt="Edit" title="Edit"/></a>&nbsp;&nbsp;<img src="'.$image_path.'delete.gif" onclick="deleteUser('.$id.')" border="0"  alt="Delete" title="Delete"/></a>';

			  $entries_list[]=$entries;

			  }
			  return $entries_list;
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
                        }
                }
		$this->id = $userid;
		return $this;
		
	}

	
}

	


?>
