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
 * $Header:  vtiger_crm/modules/Users/Save.php,v 1.1 2004/08/17 15:06:40 gjk Exp $
 * Description:  TODO: To be written.
 ********************************************************************************/

require_once('modules/Users/User.php');
require_once('include/logging.php');

$log =& LoggerManager::getLogger('index');


if (isset($_REQUEST['record']) && !is_admin($current_user) && $_REQUEST['record'] != $current_user->id) echo ("Unauthorized access to user administration.");
elseif (!isset($_REQUEST['record']) && !is_admin($current_user)) echo ("Unauthorized access to user administration.");

$focus = new User();
$focus->retrieve($_REQUEST['record']);

if (isset($_REQUEST['user_name']) && isset($_REQUEST['old_password']) && isset($_REQUEST['new_password'])) {
	if (!$focus->change_password($_REQUEST['old_password'], $_REQUEST['new_password'])) {
		header("Location: index.php?action=Error&module=Users&error_string=".urlencode($focus->error_string));
		exit;
	}
}  
else {
	foreach($focus->column_fields as $field)
	{
		if(isset($_REQUEST[$field]))
		{
			$value = $_REQUEST[$field];
			$focus->$field = $value;
			if(get_magic_quotes_gpc() == 1)
			{
				$focus->$field = stripslashes($focus->$field);
			}
		}
	}
	
	foreach($focus->additional_column_fields as $field)
	{
		if(isset($_REQUEST[$field]))
		{
			$value = $_REQUEST[$field];
			$focus->$field = $value;
			if(get_magic_quotes_gpc() == 1)
			{
				$focus->$field = stripslashes($focus->$field);
			}
		}
	}
	
	if (!isset($_REQUEST['is_admin'])) $focus->is_admin = 'off';
	
	if (!$focus->verify_data()) {
		header("Location: index.php?action=Error&module=Users&error_string=".urlencode($focus->error_string));
		exit;
	}
	else {	
		$focus->save();
		$return_id = $focus->id;
	}
}
if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = $_REQUEST['return_module'];
else $return_module = "Users";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = $_REQUEST['return_action'];
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = $_REQUEST['return_id'];

$log->debug("Saved record with id of ".$return_id);

if ($focus->id == $current_user->id) 
{
	$_SESSION['authenticated_user_theme'] = $focus->theme;
	$_SESSION['authenticated_user_language'] = $focus->language;
}
	
header("Location: index.php?action=$return_action&module=$return_module&record=$return_id");
?>