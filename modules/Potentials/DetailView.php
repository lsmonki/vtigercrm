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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Potentials/DetailView.php,v 1.28 2005/04/18 10:37:49 samk Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Potentials/Opportunity.php');
require_once('modules/Potentials/Forms.php');
require_once('include/CustomFieldUtil.php');
require_once('include/uifromdbutil.php');

global $mod_strings;
global $app_strings;
global $app_list_strings;

$focus = new Potential();

if(isset($_REQUEST['record'])  && $_REQUEST['record']!='') {
    $focus->retrieve_entity_info($_REQUEST['record'],"Potentials");
    $focus->id = $_REQUEST['record'];	
    $focus->name=$focus->column_fields['potentialname'];
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
} 

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("Potential detail view");

$xtpl=new XTemplate ('modules/Potentials/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$xtpl->assign("ID", $focus->id);

$xtpl->assign("ACCOUNTID",$focus->column_fields['account_id']);

if (isset($focus->name)) $xtpl->assign("NAME", $focus->name);
else $xtpl->assign("NAME", "");

//get Block 1 Information

$block_1 = getDetailBlockInformation("Potentials",1,$focus->column_fields);
$xtpl->assign("BLOCK1", $block_1);

//get Address Information

$block_2 = getDetailBlockInformation("Potentials",2,$focus->column_fields);
$xtpl->assign("BLOCK2", $block_2);
//get Description Information

$block_3 = getDetailBlockInformation("Potentials",3,$focus->column_fields);
$xtpl->assign("BLOCK3", $block_3);

$block_1_header = getBlockTableHeader("LBL_OPPORTUNITY_INFORMATION");
$block_2_header = getBlockTableHeader("LBL_DESCRIPTION_INFORMATION");
$xtpl->assign("BLOCK1_HEADER", $block_1_header);
$xtpl->assign("BLOCK2_HEADER", $block_2_header);

$block_5 = getDetailBlockInformation("Potentials",5,$focus->column_fields);
if(trim($block_5) != '')
{
        $cust_fld = '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formOuterBorder">';
        $cust_fld .=  '<tr><td>';
	$block_5_header = getBlockTableHeader("LBL_CUSTOM_INFORMATION");
        $cust_fld .= $block_5_header;
        $cust_fld .= '<table width="100%" border="0" cellspacing="1" cellpadding="0">';
        $cust_fld .= $block_5;
        $cust_fld .= '</table>';
        $cust_fld .= '</td></tr></table>';
        $cust_fld .= '<BR>';

}

$xtpl->assign("CUSTOMFIELD", $cust_fld);

$permissionData = $_SESSION['action_permission_set'];

if(isPermitted("Potentials",1,$_REQUEST['record']) == 'yes')
{
	$xtpl->assign("EDITBUTTON","<td><input title=\"$app_strings[LBL_EDIT_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_EDIT_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='Potentials'; this.form.return_action.value='DetailView'; this.form.module.value='Potentials'; this.form.return_id.value='".$_REQUEST['record']."'; this.form.action.value='EditView'\" type=\"submit\" name=\"Edit\" value=\"$app_strings[LBL_EDIT_BUTTON_LABEL]\"></td>");


	$xtpl->assign("DUPLICATEBUTTON","<td><input title=\"$app_strings[LBL_DUPLICATE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_DUPLICATE_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='Potentials'; this.form.return_action.value='DetailView'; this.form.isDuplicate.value='true'; this.form.module.value='Potentials'; this.form.action.value='EditView'\" type=\"submit\" name=\"Duplicate\" value=\"$app_strings[LBL_DUPLICATE_BUTTON_LABEL]\"></td>");
}
if(isPermitted("Invoice",1,$_REQUEST['record']) == 'yes')
{
	$xtpl->assign("CONVERTINVOICE","<td><input title=\"$app_strings[LBL_CONVERTINVOICE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_CONVERTINVOICE_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='Potentials'; this.form.return_action.value='DetailView'; this.form.return_id.value='".$_REQUEST['record']."';this.form.convertmode.value='potentoinvoice';this.form.module.value='Invoice'; this.form.action.value='EditView'\" type=\"submit\" name=\"Convert To Invoice\" value=\"$app_strings[LBL_CONVERTINVOICE_BUTTON_LABEL]\"></td>");
}
if(isPermitted("Potentials",2,$_REQUEST['record']) == 'yes')
{
	$xtpl->assign("DELETEBUTTON","<td><input title=\"$app_strings[LBL_DELETE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_DELETE_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='Potentials'; this.form.return_action.value='index'; this.form.module.value='Potentials'; this.form.action.value='Delete'; return confirm('$app_strings[NTC_DELETE_CONFIRMATION]')\" type=\"submit\" name=\"Delete\" value=\"$app_strings[LBL_DELETE_BUTTON_LABEL]\"></td>");
}

$xtpl->parse("main");
$xtpl->out("main");

//Security check for related list
global $profile_id;
$tab_per_Data = getAllTabsPermission($profile_id);
$permissionData = $_SESSION['action_permission_set'];
getRelatedLists("Potentials",$focus);

//Constructing the Related Lists from here
// Now get the list of opportunities that match this one.

/*
// Now get the list of activities that match this opportunity.
if($tab_per_Data[9] == 0)
{
        if($permissionData[9][3] == 0)
        {
		$focus_activities_list = & $focus->get_activities($focus->id);
	}
}

if($tab_per_Data[4] == 0)
{
        if($permissionData[4][3] == 0)
        {
		 $focus_contacts_list = & $focus->get_contacts($focus->id);
	}
}

if($tab_per_Data[14] == 0)
{
        if($permissionData[14][3] == 0)
        {	
		 $focus_contacts_list = & $focus->get_products($focus->id);
	}
}
$focus_history_list = & $focus->get_history($focus->id);
$focus_stage_history_list = & $focus->get_stage_history($focus->id);

if($tab_per_Data[8] == 0)
{
        if($permissionData[8][3] == 0)
        {
		$focus_attachments_list = & $focus->get_attachments($focus->id);
	}
}


*/
?>
