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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Potentials/EditView.php,v 1.16 2005/03/24 16:18:38 samk Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('Smarty_setup.php');
require_once('data/Tracker.php');
require_once('modules/Potentials/Opportunity.php');
require_once('modules/Potentials/Forms.php');
require_once('include/CustomFieldUtil.php');
require_once('include/ComboUtil.php');
require_once('include/utils/utils.php');
require_once('include/FormValidationUtil.php');
global $app_strings;
global $mod_strings;

$focus = new Potential();
$smarty = new vtigerCRM_Smarty();

if(isset($_REQUEST['record']) && $_REQUEST['record'] != '') 
{
    $focus->id = $_REQUEST['record'];
    $focus->mode = 'edit'; 	
    $focus->retrieve_entity_info($_REQUEST['record'],"Potentials");
    $focus->name=$focus->column_fields['potentialname'];	
}
if(isset($_REQUEST['account_id']))
{
        $focus->column_fields['account_id'] = $_REQUEST['account_id'];
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
    	$focus->mode = ''; 	
}

$disp_view = getView($focus->mode);
if($disp_view == 'edit_view')
	$smarty->assign("BLOCKS",getBlocks("Potentials",$disp_view,$mode,$focus->column_fields));
else
{
	$smarty->assign("BASBLOCKS",getBlocks("Potentials",$disp_view,$mode,$focus->column_fields,'BAS'));
	$smarty->assign("ADVBLOCKS",getBlocks("Potentials",$disp_view,$mode,$focus->column_fields,'ADV'));
}
$smarty->assign("OP_MODE",$disp_view);
$category = getParentTab();
$smarty->assign("CATEGORY",$category);

//needed when creating a new opportunity with a default account value passed in
if (isset($_REQUEST['accountname']) && is_null($focus->accountname)) {
	$focus->accountname = $_REQUEST['accountname'];
	
}
if (isset($_REQUEST['accountid']) && is_null($focus->accountid)) {
	$focus->accountid = $_REQUEST['accountid'];
}
if (isset($_REQUEST['contactid']) && is_null($focus->contactid)) {
	$focus->contactid = $_REQUEST['contactid'];
}

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
//retreiving the combo values array
$comboFieldNames = Array('leadsource'=>'leadsource_dom'
                      ,'opportunity_type'=>'opportunity_type_dom'
                      ,'sales_stage'=>'sales_stage_dom');
$comboFieldArray = getComboArray($comboFieldNames);
require_once($theme_path.'layout_utils.php');

$log->info("Potential detail view");
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);

if (isset($focus->name)) 
$smarty->assign("NAME", $focus->name);
else 
$smarty->assign("NAME", "");

if(isset($cust_fld))
{
        $smarty->assign("CUSTOMFIELD", $cust_fld);
}
if($focus->mode == 'edit')
{
	$smarty->assign("UPDATEINFO",updateInfo($focus->id));
	$smarty->assign("MODE", $focus->mode);
}		



// Unimplemented until jscalendar language files are fixed
$smarty->assign("CALENDAR_LANG", $app_strings['LBL_JSCALENDAR_LANG']);
$smarty->assign("CALENDAR_DATEFORMAT", parse_calendardate($app_strings['NTC_DATE_FORMAT']));

if (isset($_REQUEST['return_module'])) 
$smarty->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) 
$smarty->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) 
$smarty->assign("RETURN_ID", $_REQUEST['return_id']);
if (isset($_REQUEST['return_viewname'])) 
$smarty->assign("RETURN_VIEWNAME", $_REQUEST['return_viewname']);
$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$smarty->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js());
$smarty->assign("ID", $focus->id);
$smarty->assign("MODULE",$currentModule);
$smarty->assign("SINGLE_MOD","Potential");


 $potential_tables = Array('potential','crmentity','potentialscf'); 
 $tabid = getTabid("Potentials");
 $validationData = getDBValidationData($potential_tables,$tabid);
 $data = split_validationdataArray($validationData);

 $smarty->assign("VALIDATION_DATA_FIELDNAME",$data['fieldname']);
 $smarty->assign("VALIDATION_DATA_FIELDDATATYPE",$data['datatype']);
 $smarty->assign("VALIDATION_DATA_FIELDLABEL",$data['fieldlabel']);
if($focus->mode == 'edit')
$smarty->display("salesEditView.tpl");
else
$smarty->display("CreateView.tpl");

?>
