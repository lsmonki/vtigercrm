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
/*********************************************************************************
 * $Header:  vtiger_crm/modules/Users/User.php,v 1.1 2004/08/17 15:06:40 gjk Exp $
 * Description: TODO:  To be written.
 ********************************************************************************/

require_once('include/logging.php');
require_once('database/DatabaseConnection.php');
require_once('data/SugarBean.php');

// User is used to store customer information.
class User extends SugarBean {
	var $log;

	// Stored fields
	var $id;
	var $user_name;
	var $user_password;
	var $first_name;
	var $last_name;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $description;
	var $phone_home;
	var $phone_mobile;
	var $phone_work;
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
	var $theme;
	var $status;
	var $title;
	var $department;
	var $authenticated = false;
	var $error_string;
	var $is_admin;
	var $language;
	
	var $reports_to_name;
	var $reports_to_id;
	
	var $table_name = "users";

	var $object_name = "User";

	var $column_fields = Array("id"
		,"user_name"
		,"user_password"
		,"first_name"
		,"last_name"
		,"description"
		,"date_entered"
		,"date_modified"
		,"modified_user_id"
		,"title"
		,"department"
		,"is_admin"
		,"phone_home"
		,"phone_mobile"
		,"phone_work"
		,"phone_other"
		,"phone_fax"
		,"email1"
		,"email2"
		,"yahoo_id"
		,"address_street"
		,"address_city"
		,"address_state"
		,"address_postalcode"
		,"address_country"
		,"reports_to_id"
		,"theme"
		,"status"
		,"language"
		);

	var $encodeFields = Array("first_name", "last_name", "description");

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('reports_to_name');		
	
	// This is the list of fields that are in the lists.
	var $list_fields = Array('id', 'first_name', 'last_name', 'user_name', 'status', 'department', 'yahoo_id', 'is_admin', 'email1', 'phone_work');	
		
	var $default_order_by = "user_name";

	var $new_schema = true;

	function User() {
		$this->log = LoggerManager::getLogger('user');
	}

	function create_tables () {
		$query = 'CREATE TABLE '.$this->table_name.' ( ';
		$query .= 'id char(36) NOT NULL';
		$query .= ', user_name varchar(20)';
		$query .= ', user_password varchar(30)';
		$query .= ', first_name varchar(30)';
		$query .= ', last_name varchar(30)';
		$query .= ', reports_to_id char(36)';
		$query .= ', is_admin char(3) default 0';
		$query .= ', description text';
		$query .= ', date_entered datetime NOT NULL';
		$query .= ', date_modified datetime NOT NULL';
		$query .= ', modified_user_id char(36) NOT NULL';
		$query .= ', title varchar(50)';
		$query .= ', department varchar(50)';
		$query .= ', phone_home varchar(50)';
		$query .= ', phone_mobile varchar(50)';
		$query .= ', phone_work varchar(50)';
		$query .= ', phone_other varchar(50)';
		$query .= ', phone_fax varchar(50)';
		$query .= ', email1 varchar(100)';
		$query .= ', email2 varchar(100)';
		$query .= ', yahoo_id varchar(100)';
		$query .= ', status varchar(25)';
		$query .= ', address_street varchar(150)';
		$query .= ', address_city varchar(100)';
		$query .= ', address_state varchar(100)';
		$query .= ', address_country varchar(25)';
		$query .= ', address_postalcode varchar(9)';
		$query .= ', theme varchar(50)';
		$query .= ', language varchar(20)';
		$query .= ', deleted bool NOT NULL default 0';
		$query .= ', PRIMARY KEY ( ID )';
		$query .= ', KEY ( user_name )';
		$query .= ', KEY ( user_password ))';
	
		$this->log->info($query);
		
		mysql_query($query) or die("failed to create table: ".mysql_error()."<BR><BR>query: $query");

	//TODO Clint 4/27 - add exception handling logic here if the table can't be created.
	
	}

	function drop_tables () {
		$query = 'DROP TABLE IF EXISTS '.$this->table_name;

		$this->log->info($query);
			
		mysql_query($query);

	//TODO Clint 4/27 - add exception handling logic here if the table can't be dropped.

	}
	
	function get_summary_text()
	{
		return "$this->first_name $this->last_name";
	}

	/**
	* @return string encrypted password for storage in DB and comparison against DB password.
	* @param string $user_name - Must be non null and at least 2 characters
	* @param string $user_password - Must be non null and at least 1 character.
	* @desc Take an unencrypted username and password and return the encrypted password
	*/
	function encrypt_password($user_password)
	{
		// encrypt the password.
		$salt = substr($this->user_name, 0, 2);
		$encrypted_password = crypt($user_password, $salt);	

		return $encrypted_password;
	}
	
	/** 
	 * Load a user based on the user_name in $this
	 * @return -- this if load was successul and null if load failed.
	 */
	function load_user($user_password)
	{
		$this->log->debug("Starting user load for $this->user_name");
		
		if( !isset($this->user_name) || $this->user_name == "" || !isset($user_password) || $user_password == "")
			return null;

		$encrypted_password = $this->encrypt_password($user_password);
			
		$query = "SELECT * from $this->table_name where user_name='$this->user_name' AND user_password='$encrypted_password'";
		$this->log->info($query);
		$result = mysql_query($query) or die("Error retrieving user: ".$query);
		
		if(mysql_num_rows($result) != 1)
		{
			$this->log->warn("User authentication for $this->user_name failed");
			return null;
		}
		
		// get the id
		$row = mysql_fetch_assoc($result);
		
		// now fill in the fields.
		foreach($this->column_fields as $field)
		{
			if(isset($row[$field]))
			{
				$this->$field = $row[$field];
			}
		}

		$this->fill_in_additional_detail_fields();
		if ($this->status != "Inactive") $this->authenticated = true;
		
		return $this;
	}		

	
	/**
	* @param string $user name - Must be non null and at least 1 character.
	* @param string $user_password - Must be non null and at least 1 character.
	* @param string $new_password - Must be non null and at least 1 character.
	* @return boolean - If passwords pass verification and query succeeds, return true, else return false.
	* @desc Verify that the current password is correct and write the new password to the DB.
	*/
	function change_password($user_password, $new_password)
	{
		global $mod_strings;
		global $current_user;
		$this->log->debug("Starting password change for $this->user_name");
		
		if( !isset($new_password) || $new_password == "") {
			$this->error_string = $mod_strings['ERR_PASSWORD_CHANGE_FAILED_1'].$user_name.$mod_strings['ERR_PASSWORD_CHANGE_FAILED_2'];
			return false;
		}
		
		$encrypted_password = $this->encrypt_password($user_password);
		$encrypted_new_password = $this->encrypt_password($new_password);

		if (!is_admin($current_user)) {
			//check old password first
			$query = "SELECT user_name FROM $this->table_name WHERE user_password='$encrypted_password' AND id='$this->id'";
			$result = mysql_query($query) or die("Error selecting old password for $this->user_name: $query ".mysql_error());
			
			$row = mysql_fetch_assoc($result);
			$this->log->debug("select old password query: $query");
			$this->log->debug("return result of $row");
	
			if($row == null)
			{
				$this->log->warn("Incorrect old password for $this->user_name");
				$this->error_string = $mod_strings['ERR_PASSWORD_INCORRECT_OLD'];
				return false;
			}
		}		

		//set new password
		$query = "UPDATE $this->table_name SET user_password='$encrypted_new_password' where id='$this->id'";
		$result = mysql_query($query) or die("Error setting new password for $this->user_name: ".mysql_error());
		return true;
	}
	
	function is_authenticated()
	{
		return $this->authenticated;
	}
	
	function fill_in_additional_list_fields()
	{
		$this->fill_in_additional_detail_fields();	
	}
	
	function fill_in_additional_detail_fields()
	{
		$query = "SELECT u1.first_name, u1.last_name from users as u1, users as u2 where u1.id = u2.reports_to_id AND u2.id = '$this->id' and u1.deleted=0";
		$result = mysql_query($query) or die("Error filling in additional detail fields: ".mysql_error());
		
		$row = mysql_fetch_assoc($result);
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

	function retrieve_user_id($user_name)
	{
		$query = "SELECT id from users where user_name='$user_name' AND deleted=0";
		$result = mysql_query($query) or $this->log->fatal("Error retrieving user ID: ".mysql_error()); //die("Error retrieving user ID: ".mysql_error());}
		
		$row = mysql_fetch_assoc($result);
		return $row['id'];
	}
	
	/** 
	 * @return -- returns a list of all users in the system.
	 */
	function verify_data()
	{
		global $mod_strings;
		
		$query = "SELECT user_name from users where user_name='$this->user_name' AND id<>'$this->id' AND deleted=0";
		$result = mysql_query($query) or die("Error selecting possible duplicate users: ".mysql_error());
		$dup_users = mysql_fetch_assoc($result);
		
		$query = "SELECT user_name from users where is_admin = 'on' AND deleted=0";
		$result = mysql_query($query) or die("Error selecting possible duplicate users: ".mysql_error());
		$last_admin = mysql_fetch_assoc($result);

		$this->log->debug("last admin length: ".count($last_admin));
		$this->log->debug($last_admin['user_name']." == ".$this->user_name);

		$verified = true;
		if($dup_users != null)
		{
			$this->error_string .= $mod_strings['ERR_USER_NAME_EXISTS_1'].$this->user_name.$mod_strings['ERR_USER_NAME_EXISTS_2'];
			$verified = false;
		}
		if(!isset($_REQUEST['is_admin']) &&
			count($last_admin) == 1 && 
			$last_admin['user_name'] == $this->user_name) {
			$this->log->debug("last admin length: ".count($last_admin));

			$this->error_string .= $mod_strings['ERR_LAST_ADMIN_1'].$this->user_name.$mod_strings['ERR_LAST_ADMIN_2'];
			$verified = false;
		}
		return $verified;
	}
}

?>
