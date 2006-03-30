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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Activities/EditView.php,v 1.11 2005/03/24 16:18:38 samk Exp $
 * Description: TODO:  To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('Smarty_setup.php');
require_once('data/Tracker.php');
require_once('modules/Activities/Activity.php');
require_once('modules/Activities/Forms.php');
require_once('include/database/PearDatabase.php');
require_once('include/CustomFieldUtil.php');
require_once('include/ComboUtil.php');
require_once('include/utils/utils.php');
require_once('include/FormValidationUtil.php');
global $app_strings;
global $mod_strings;
// Unimplemented until jscalendar language files are fixed
$activity_lbl='';
$activity_mode = $_REQUEST['activity_mode'];
if($activity_mode == 'Task')
{
	$tab_type = 'Activities';
	$activity_lbl = $mod_strings['LBL_TASK_INFORMATION'];
}
elseif($activity_mode == 'Events')
{
	$tab_type = 'Events';
	$activity_lbl = $mod_strings['LBL_EVENT_INFORMATION'];
}

$focus = new Activity();
$smarty =  new vtigerCRM_Smarty();
if(isset($_REQUEST['record']) && $_REQUEST['record']!='') {
    $focus->id = $_REQUEST['record'];
    $focus->mode = 'edit';
    $focus->retrieve_entity_info($_REQUEST['record'],$tab_type);		
    $focus->name=$focus->column_fields['subject'];		
    $smarty->assign("UPDATEINFO",updateInfo($focus->id));
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
    	$focus->mode = ''; 	
}


$disp_view = getView($focus->mode);
if($disp_view == 'edit_view')
	$smarty->assign("BLOCKS",getBlocks($tab_type,$disp_view,$mode,$focus->column_fields));
else	
{
	$smarty->assign("BASBLOCKS",getBlocks($tab_type,$disp_view,$mode,$focus->column_fields,'BAS'));
}
$smarty->assign("OP_MODE",$disp_view);
$smarty->assign("ACTIVITY_MODE",$activity_mode);

$smarty->assign("MODULE",$currentModule);
$smarty->assign("SINGLE_MOD",'Activity');
$smarty->assign("NEW_EVENT",$app_strings['LNK_NEW_EVENT']);
$smarty->assign("NEW_TASK",$app_strings['LNK_NEW_TASK']);

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("Activity detail view");

$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);

if (isset($focus->name))
$smarty->assign("NAME", $focus->name);
else
$smarty->assign("NAME", "");

if($focus->mode == 'edit')
{
        $smarty->assign("MODE", $focus->mode);
}

$category = getParentTab();
$smarty->assign("CATEGORY",$category);

// Unimplemented until jscalendar language files are fixed
$smarty->assign("CALENDAR_LANG", $app_strings['LBL_JSCALENDAR_LANG']);
$smarty->assign("CALENDAR_DATEFORMAT", parse_calendardate($app_strings['NTC_DATE_FORMAT']));

if (isset($_REQUEST['return_module']))
$smarty->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action']))
$smarty->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id']))
$smarty->assign("RETURN_ID", $_REQUEST['return_id']);
if (isset($_REQUEST['ticket_id']))
$smarty->assign("TICKETID", $_REQUEST['ticket_id']);
if (isset($_REQUEST['product_id']))
$smarty->assign("PRODUCTID", $_REQUEST['product_id']);
if (isset($_REQUEST['return_viewname']))
$smarty->assign("RETURN_VIEWNAME", $_REQUEST['return_viewname']);
$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);
$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$smarty->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js());
$smarty->assign("ID", $focus->id);

 $activities_tables = Array('activity','crmentity'); 
 $tabid = getTabid($tab_type);
 $validationData = getDBValidationData($activities_tables,$tabid);
 $data = split_validationdataArray($validationData);

 $smarty->assign("VALIDATION_DATA_FIELDNAME",$data['fieldname']);
 $smarty->assign("VALIDATION_DATA_FIELDDATATYPE",$data['datatype']);
 $smarty->assign("VALIDATION_DATA_FIELDLABEL",$data['fieldlabel']);

if($focus->mode == 'edit')
	$smarty->display("salesEditView.tpl");
else
	$smarty->display("CreateView.tpl");

?>
