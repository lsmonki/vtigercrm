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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Emails/DetailView.php,v 1.22 2005/03/24 19:09:21 rank Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('Smarty_setup.php');
require_once('data/Tracker.php');
require_once('modules/Emails/Email.php');
require_once('modules/Emails/Forms.php');
require_once('include/upload_file.php');
require_once('include/database/PearDatabase.php');
require_once('include/utils/utils.php');

global $log;
global $app_strings;
global $mod_strings;

$focus = new Email();

if(isset($_REQUEST['record'])) 
{
	$focus->retrieve_entity_info($_REQUEST['record'],"Emails");
	 $log->info("Entity info successfully retrieved for DetailView.");
	$focus->id = $_REQUEST['record'];
	if($focus->column_fields['name'] != '')
	        $focus->name = $focus->column_fields['name'];		
	else
		$focus->name = $focus->column_fields['subject'];
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') 
{
	$focus->id = "";
} 

//needed when creating a new email with default values passed in 
if (isset($_REQUEST['contact_name']) && is_null($focus->contact_name)) 
{
	$focus->contact_name = $_REQUEST['contact_name'];
}
if (isset($_REQUEST['contact_id']) && is_null($focus->contact_id)) 
{
	$focus->contact_id = $_REQUEST['contact_id'];
}
if (isset($_REQUEST['opportunity_name']) && is_null($focus->parent_name)) 
{
	$focus->parent_name = $_REQUEST['opportunity_name'];
}
if (isset($_REQUEST['opportunity_id']) && is_null($focus->parent_id)) 
{
	$focus->parent_id = $_REQUEST['opportunity_id'];
}
if (isset($_REQUEST['account_name']) && is_null($focus->parent_name)) 
{
	$focus->parent_name = $_REQUEST['account_name'];
}
if (isset($_REQUEST['account_id']) && is_null($focus->parent_id)) 
{
	$focus->parent_id = $_REQUEST['account_id'];
}
if (isset($_REQUEST['parent_name'])) 
{
        $focus->parent_name = $_REQUEST['parent_name'];
}
if (isset($_REQUEST['parent_id'])) 
{
        $focus->parent_id = $_REQUEST['parent_id'];
}
if (isset($_REQUEST['parent_type'])) 
{
        $focus->parent_type = $_REQUEST['parent_type'];
}
if (isset($_REQUEST['filename']) && is_null($focus->filename)) 
{
        $focus->filename = $_REQUEST['filename'];
}
elseif (is_null($focus->parent_type)) 
{
        $focus->parent_type = $app_list_strings['record_type_default_key'];
}

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("Email detail view");

$submenu = array('LBL_EMAILS_TITLE'=>'index.php?module=Emails&action=index','LBL_WEBMAILS_TITLE'=>'index.php?module=squirrelmail-1.4.4&action=redirect');
$sec_arr = array('index.php?module=Emails&action=index'=>'Emails','index.php?module=squirrelmail-1.4.4&action=redirect'=>'Emails'); 
echo '<br>';

$smarty = new vtigerCRM_Smarty;
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);

$smarty->assign("UPDATEINFO",updateInfo($focus->id));
if (isset($_REQUEST['return_module'])) $smarty->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $smarty->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $smarty->assign("RETURN_ID", $_REQUEST['return_id']);
$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);
$category = getParentTab();
$smarty->assign("CATEGORY",$category);

if (isset($focus->name)) $smarty->assign("NAME", $focus->name);
else $smarty->assign("NAME", "");

$smarty->assign("BLOCKS", getBlocks("Emails","detail_view",'',$focus->column_fields));

$smarty->assign("SINGLE_MOD","Email");

$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$smarty->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js());

$smarty->assign("ID", $_REQUEST['record']);

$permissionData = $_SESSION['action_permission_set'];
if(isPermitted("Emails",1,$_REQUEST['record']) == 'yes')
	$smarty->assign("EDIT_DUPLICATE","permitted");

if(isPermitted("Emails",2,$_REQUEST['record']) == 'yes')
	$smarty->assign("DELETE","permitted");
//Security check for related list
global $profile_id;
$tab_per_Data = getAllTabsPermission($profile_id);
$permissionData = $_SESSION['action_permission_set'];

//Constructing the Related Lists from here
$smarty->assign("MODULE","Emails");
$smarty->display("DetailView.tpl");
?>
