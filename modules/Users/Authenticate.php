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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Users/Authenticate.php,v 1.10 2005/02/28 05:25:22 jack Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/Users/User.php');
require_once('include/logging.php');
//require_once('modules/Users/AccessControl.php');

global $mod_strings;

$local_log =& LoggerManager::getLogger('authenticate');

$focus = new User();

// Add in defensive code here.
$focus->user_name = $_REQUEST['user_name'];
$user_password = $_REQUEST['user_password'];

$focus->load_user($user_password);

if($focus->is_authenticated())
{
	// Recording the login info
        $usip=$_SERVER['REMOTE_ADDR'];
        $intime=date("Y/m/d H:i:s");
        require_once('modules/Users/LoginHistory.php');
        $loghistory=new LoginHistory();
        $Signin = $loghistory->user_login($focus->user_name,$usip,$intime);

	//Authentication for tutos
        //include('modules/Calendar/Authenticate.php');

	// save the user information into the session
	// go to the home screen
	//Security related entries start
	require_once('modules/Users/UserInfoUtil.php');
	//$rolename = fetchUserRole($focus->id);
	//$profilename = fetchUserProfile($focus->id);
	$profileid = fetchUserProfileId($focus->id);	
	//setting the role into the session
	//$_SESSION['authenticated_user_roleid'] = $profilename;

	//Setting the Object in Session
	/*
	$accessObj = new AccessControl();
	$accessObj->authenticated_user_profileid = $profileid;
	$accessObj->tab_permission_set = setPermittedTabs2Session($profileid);
	
	$accessObj->action_permission_set = setPermittedActions2Session($profileid);
	$_SESSION['access_privileges'] = $accessObj; 
	*/

	
	$_SESSION['authenticated_user_profileid'] = $profileid;
        setPermittedTabs2Session($profileid);
	setPermittedActions2Session($profileid);
	setPermittedDefaultSharingAction2Session($profileid);
	
	
	//Security related entries end
	header("Location: index.php?action=index&module=Home");
	session_unregister('login_password');
	session_unregister('login_error');
	session_unregister('login_user_name');

	$_SESSION['authenticated_user_id'] = $focus->id;

	// store the user's theme in the session
	if (isset($_REQUEST['login_theme'])) {
		$authenticated_user_theme = $_REQUEST['login_theme'];
	}
	elseif (isset($_REQUEST['ck_login_theme']))  {
		$authenticated_user_theme = $_REQUEST['ck_login_theme'];
	}
	else {
		$authenticated_user_theme = $default_theme;
	}
	
	// store the user's language in the session
	if (isset($_REQUEST['login_language'])) {
		$authenticated_user_language = $_REQUEST['login_language'];
	}
	elseif (isset($_REQUEST['ck_login_language']))  {
		$authenticated_user_language = $_REQUEST['ck_login_language'];
	}
	else {
		$authenticated_user_language = $default_language;
	}

	// If this is the default user and the default user theme is set to reset, reset it to the default theme value on each login
	if($reset_theme_on_default_user && $focus->user_name == $default_user_name)
	{
		$authenticated_user_theme = $default_theme;
	}
	if(isset($reset_language_on_default_user) && $reset_language_on_default_user && $focus->user_name == $default_user_name)
	{
		$authenticated_user_language = $default_language;	
	}

	$_SESSION['authenticated_user_theme'] = $authenticated_user_theme;
	$_SESSION['authenticated_user_language'] = $authenticated_user_language;
	
	$log->debug("authenticated_user_theme is $authenticated_user_theme");
	$log->debug("authenticated_user_language is $authenticated_user_language");
	
// Clear all uploaded import files for this user if it exists

	global $import_dir;

	$tmp_file_name = $import_dir. "IMPORT_".$focus->id;

	if (file_exists($tmp_file_name))
	{
		unlink($tmp_file_name);
	}
}
else
{
	$_SESSION['login_user_name'] = $focus->user_name;
	$_SESSION['login_password'] = $user_password;
	$_SESSION['login_error'] = $mod_strings['ERR_INVALID_PASSWORD'];
	
	// go back to the login screen.	
	// create an error message for the user.
	header("Location: index.php");
}

?>
