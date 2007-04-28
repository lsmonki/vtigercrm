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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Users/Save.php,v 1.14 2005/03/17 06:37:39 rank Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/Users/Users.php');
require_once('include/logging.php');
require_once('include/utils/UserInfoUtil.php');
$log =& LoggerManager::getLogger('index');


global $adb;
$user_name = $_REQUEST['userName'];
if(isset($_REQUEST['dup_check']) && $_REQUEST['dup_check'] != '')
{
        $query = "SELECT user_name FROM vtiger_users WHERE user_name ='".$user_name."'";
        $result = $adb->query($query);
        if($adb->num_rows($result) > 0)
        {
		echo 'User Name Already Exists!';
		die;
	}else
	{
	        echo 'SUCCESS';
	        die;
	}
}
																		
if (isset($_POST['record']) && !is_admin($current_user) && $_POST['record'] != $current_user->id) echo ("Unauthorized access to user administration.");
elseif (!isset($_POST['record']) && !is_admin($current_user)) echo ("Unauthorized access to user administration.");

$focus = new Users();
if(isset($_REQUEST["record"]) && $_REQUEST["record"] != '')
{
    $focus->mode='edit';
	$focus->id = $_REQUEST["record"];
}
else
{
    $focus->mode='';
}    


if($_REQUEST['changepassword'] == 'true')
{
	$focus->retrieve_entity_info($_REQUEST['record'],'Users');
	$focus->id = $_REQUEST['record'];
if (isset($_POST['new_password'])) {
		$new_pass = $_POST['new_password'];
		$new_passwd = $_POST['new_password'];
		$new_pass = md5($new_pass);
		$old_pass = $_POST['old_password'];
		$uname = $_POST['user_name'];
		if (!$focus->change_password($_POST['old_password'], $_POST['new_password'])) {
		
			header("Location: index.php?action=Error&module=Users&error_string=".urlencode($focus->error_string));
		exit;
}
}
	
}	

    
//save user Image
if(! $_REQUEST['changepassword'] == 'true')
{
	if(strtolower($current_user->is_admin) == 'off'  && $current_user->id != $focus->id)
	{
		$log->fatal("SECURITY:Non-Admin ". $current_user->id . " attempted to change settings for user:". $focus->id);
		header("Location: index.php?module=Users&action=Logout");
		exit;
	}
	if(strtolower($current_user->is_admin) == 'off'  && isset($_POST['is_admin']) && strtolower($_POST['is_admin']) == 'on')
	{
		$log->fatal("SECURITY:Non-Admin ". $current_user->id . " attempted to change is_admin settings for user:". $focus->id);
		header("Location: index.php?module=Users&action=Logout");
		exit;
	}
	
	if (!isset($_POST['is_admin'])) $_REQUEST["is_admin"] = 'off';
	//Code contributed by mike crowe for rearrange the home page and tab
	if (!isset($_POST['deleted'])) $_REQUEST["deleted"] = '0';
	if (!isset($_POST['homeorder']) || $_POST['homeorder'] == "" ) $_REQUEST["homeorder"] = 'ILTI,QLTQ,ALVT,PLVT,CVLVT,HLT,OLV,GRT,OLTSO';

	setObjectValuesFromRequest($focus);
	$focus->saveentity("Users");
	//$focus->imagename = $image_upload_array['imagename'];
	$focus->saveHomeOrder($focus->id);
	$return_id = $focus->id;

if (isset($_POST['user_name']) && isset($_POST['new_password'])) {
		$new_pass = $_POST['new_password'];
		$new_passwd = $_POST['new_password'];
		$new_pass = md5($new_pass);
		$uname = $_POST['user_name'];
		if (!$focus->change_password($_POST['confirm_new_password'], $_POST['new_password'])) {
		
			header("Location: index.php?action=Error&module=Users&error_string=".urlencode($focus->error_string));
		exit;
}
}  

if(isset($focus->id) && $focus->id != '')
{

  if(isset($_POST['user_role']))
  {
    updateUser2RoleMapping($_POST['user_role'],$focus->id);
  }
  if(isset($_POST['group_name']) && $_POST['group_name'] != '')
  {
    updateUsers2GroupMapping($_POST['group_name'],$focus->id);
  }
}
else
{
  if(isset($_POST['user_role']))
  {
    insertUser2RoleMapping($_POST['user_role'],$focus->id);
  }
  if(isset($_POST['group_name']))
  {
    insertUsers2GroupMapping($_POST['group_name'],$focus->id);
  }
}

//Creating the Privileges Flat File
require_once('modules/Users/CreateUserPrivilegeFile.php');
createUserPrivilegesfile($focus->id);
createUserSharingPrivilegesfile($focus->id);

}
if(isset($_POST['return_module']) && $_POST['return_module'] != "") $return_module = $_POST['return_module'];
else $return_module = "Users";
if(isset($_POST['return_action']) && $_POST['return_action'] != "") $return_action = $_POST['return_action'];
else $return_action = "DetailView";
if(isset($_POST['return_id']) && $_POST['return_id'] != "") $return_id = $_POST['return_id'];
if(isset($_REQUEST['activity_mode']))   $activitymode = '&activity_mode='.$_REQUEST['activity_mode'];
if(isset($_POST['parenttab'])) $parenttab = $_POST['parenttab'];

$log->debug("Saved record with id of ".$return_id);



if($_REQUEST['modechk'] == 'prefview')
	header("Location: index.php?action=$return_action&module=$return_module&record=$return_id");
else
	header("Location: index.php?action=$return_action&module=$return_module&record=$return_id&parenttab=$parenttab");


?>
