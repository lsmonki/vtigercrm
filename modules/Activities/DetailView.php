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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Activities/DetailView.php,v 1.12 2005/03/17 11:26:49 ray Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('Smarty_setup.php');
require_once('data/Tracker.php');
require_once('modules/Activities/Activity.php');
require_once('modules/Activities/Forms.php');
require_once('include/CustomFieldUtil.php');
require_once('include/database/PearDatabase.php');
require_once('include/utils/utils.php');
require_once('include/utils/utils.php');
global $mod_strings;
if( $_SESSION['mail_send_error']!="")
{
	echo '<b><font color=red>'. $mod_strings{"LBL_NOTIFICATION_ERROR"}.'</font></b><br>';
}
session_unregister('mail_send_error');

$activity_mode = $_REQUEST['activity_mode'];
if($activity_mode == 'Task')
{
        $tab_type = 'Activities';
}
elseif($activity_mode == 'Events')
{
        $tab_type = 'Events';
}
$tab_id=getTabid($tab_type);

$focus = new Activity();

if(isset($_REQUEST['record']) && isset($_REQUEST['record'])) {
    $focus->retrieve_entity_info($_REQUEST['record'],$tab_type);
    $focus->id = $_REQUEST['record'];	
    $focus->name=$focus->column_fields['subject'];		
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
} 

//needed when creating a new task with default values passed in
if (isset($_REQUEST['contactname']) && is_null($focus->contactname)) {
	$focus->contactname = $_REQUEST['contactname'];
}
if (isset($_REQUEST['contact_id']) && is_null($focus->contact_id)) {
	$focus->contact_id = $_REQUEST['contact_id'];
}
if (isset($_REQUEST['opportunity_name']) && is_null($focus->parent_name)) {
	$focus->parent_name = $_REQUEST['opportunity_name'];
}
if (isset($_REQUEST['opportunity_id']) && is_null($focus->parent_id)) {
	$focus->parent_id = $_REQUEST['opportunity_id'];
}
if (isset($_REQUEST['accountname']) && is_null($focus->parent_name)) {
	$focus->parent_name = $_REQUEST['accountname'];
}
if (isset($_REQUEST['accountid']) && is_null($focus->parent_id)) {
	$focus->parent_id = $_REQUEST['accountid'];
}

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("Activities detail view");

$smarty = new vtigerCRM_Smarty;
$category = getParentTab();
$smarty->assign("CATEGORY",$category);

$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("ACTIVITY_MODE", $activity_mode);

if (isset($focus->name)) 
$smarty->assign("NAME", $focus->name);
else 
$smarty->assign("NAME", "");
$smarty->assign("UPDATEINFO",updateInfo($focus->id));
if($activity_mode == 'Task')
{
        $tab_type = 'Activities';
}
elseif($activity_mode == 'Events')
{
        $tab_type = 'Events';
}

if (isset($_REQUEST['return_module'])) 
$smarty->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) 
$smarty->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) 
$smarty->assign("RETURN_ID", $_REQUEST['return_id']);
$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);
$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string'].'&activity_mode='.$activity_mode);
$smarty->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js());
$smarty->assign("ID", $focus->id);
$smarty->assign("NAME", $focus->name);

$smarty->assign("BLOCKS", getBlocks($tab_type,"detail_view",'',$focus->column_fields));

$smarty->assign("CUSTOMFIELD", $cust_fld);
$smarty->assign("ID", $_REQUEST['record']);
$smarty->assign("SINGLE_MOD", "Activity");

//get Description Information

$permissionData = $_SESSION['action_permission_set'];
if(isPermitted("Activities",1,$_REQUEST['record']) == 'yes')
	$smarty->assign("EDIT_DUPLICATE","permitted");

if(isPermitted("Activities",2,$_REQUEST['record']) == 'yes')
	$smarty->assign("DELETE","permitted");
  
$smarty->assign("MODULE","Activities");
$smarty->display("DetailView.tpl");

?>
