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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Users/Save.php,v 1.4.2.1 2004/12/22 17:07:09 jack Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/Users/User.php');
require_once('include/logging.php');
require_once('modules/Users/UserInfoUtil.php');
$log =& LoggerManager::getLogger('index');


if (isset($_POST['record']) && !is_admin($current_user) && $_POST['record'] != $current_user->id) echo ("Unauthorized access to user administration.");
elseif (!isset($_POST['record']) && !is_admin($current_user)) echo ("Unauthorized access to user administration.");

$focus = new User();
$focus->retrieve($_POST['record']);
if(strtolower($current_user->is_admin) == 'off'  && $current_user->id != $focus->id){
		$log->fatal("SECURITY:Non-Admin ". $current_user->id . " attempted to change settings for user:". $focus->id);
		header("Location: index.php?module=Users&action=Logout");
		exit;
	}
if(strtolower($current_user->is_admin) == 'off'  && isset($_POST['is_admin']) && strtolower($_POST['is_admin']) == 'on'){
		$log->fatal("SECURITY:Non-Admin ". $current_user->id . " attempted to change is_admin settings for user:". $focus->id);
		header("Location: index.php?module=Users&action=Logout");
		exit;
	}
	
if (isset($_POST['user_name']) && isset($_POST['old_password']) && isset($_POST['new_password'])) {
		
		//changing fourm password	
		define('IN_PHPBB', 1);
		
		$phpbb_root_path = "modules/MessageBoard/";
		require($phpbb_root_path . 'extension.inc');
		include($phpbb_root_path . 'common.php');
		
		$new_pass = $_POST['new_password'];
		$new_pass = md5($new_pass);
		$uname = $_POST['user_name'];
		$sql = "UPDATE " . USERS_TABLE . " SET user_password = '$new_pass' WHERE username = '$uname'";
		if (!($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not update user password', '', __LINE__, __FILE__, $sql);
		}
		//
		//
                $temp_user = $current_user;
                //
                // Changing Calendar Password
                require_once("modules/Calendar/user_ins.php");
                $q1 = $u->update_password($new_passwd,$uname);
                $focus->User();
                $focus->db->query($q1, true, "Error setting new password for $this->user_name: ");
                //
                $current_user = $temp_user;

		if (!$focus->change_password($_POST['old_password'], $_POST['new_password'])) {
		
			header("Location: index.php?action=Error&module=Users&error_string=".urlencode($focus->error_string));
		exit;
	}
}  
else {
	foreach($focus->column_fields as $field)
	{
		if(isset($_POST[$field]))
		{
			$value = $_POST[$field];
			$focus->$field = $value;
			
		}
	}
	
	foreach($focus->additional_column_fields as $field)
	{
		if(isset($_POST[$field]))
		{
			$value = $_POST[$field];
			$focus->$field = $value;
			
		}
	}
	
	if (!isset($_POST['is_admin'])) $focus->is_admin = 'off';
	
	
	if (!$focus->verify_data()) {
		header("Location: index.php?action=Error&module=Users&error_string=".urlencode($focus->error_string));
		exit;
	}
	else {	
		$focus->save();
		include('modules/Calendar/user_ins.php');
		include("modules/Users/forum_register.php");	
		$return_id = $focus->id;
	}
}

if(isset($_POST['user_role']))
{
   updateUser2RoleMapping($_POST['user_role'],$focus->id);
}


if(isset($_POST['return_module']) && $_POST['return_module'] != "") $return_module = $_POST['return_module'];
else $return_module = "Users";
if(isset($_POST['return_action']) && $_POST['return_action'] != "") $return_action = $_POST['return_action'];
else $return_action = "DetailView";
if(isset($_POST['return_id']) && $_POST['return_id'] != "") $return_id = $_POST['return_id'];

$log->debug("Saved record with id of ".$return_id);
header("Location: index.php?action=$return_action&module=$return_module&record=$return_id");
?>
