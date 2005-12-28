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
 * $Header$
 * Description:  TODO To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('Smarty_setup.php');
require_once('data/Tracker.php');
require_once('modules/SalesOrder/SalesOrder.php');
require_once('include/CustomFieldUtil.php');
require_once('include/database/PearDatabase.php');
require_once('include/utils/utils.php');
global $mod_strings;
global $app_strings;
global $app_list_strings;

$focus = new SalesOrder();

if(isset($_REQUEST['record']) && isset($_REQUEST['record'])) {
    $focus->retrieve_entity_info($_REQUEST['record'],"SalesOrder");
    $focus->id = $_REQUEST['record'];
    $focus->name=$focus->column_fields['subject'];		
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
} 

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("SalesOrder detail view");

$smarty = new vtigerCRM_Smarty;

$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);

$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);
$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);

if (isset($focus->name)) 
$smarty->assign("NAME", $focus->name);
else 
$smarty->assign("NAME", "");
$smarty->assign("BLOCKS", getBlocks("SalesOrder","detail_view",'',$focus->column_fields));


$smarty->assign("CUSTOMFIELD", $cust_fld);
$smarty->assign("ID", $_REQUEST['record']);
$smarty->assign("SINGLE_MOD", "SalesOrder");

$permissionData = $_SESSION['action_permission_set'];

if(isPermitted("SalesOrder",1,$_REQUEST['record']) == 'yes')
{
	$smarty->assign("EDITBUTTON","<input title=\"$app_strings[LBL_EDIT_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_EDIT_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='SalesOrder'; this.form.return_action.value='DetailView';this.form.module.value='SalesOrder'; this.form.return_id.value='".$_REQUEST['record']."'; this.form.action.value='EditView'\" type=\"submit\" name=\"Edit\" value=\"$app_strings[LBL_EDIT_BUTTON_LABEL]\">");


	$smarty->assign("DUPLICATEBUTTON","<input title=\"$app_strings[LBL_DUPLICATE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_DUPLICATE_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='SalesOrder'; this.form.return_action.value='DetailView'; this.form.module.value='SalesOrder';this.form.isDuplicate.value='true'; this.form.action.value='EditView'\" type=\"submit\" name=\"Duplicate\" value=\"$app_strings[LBL_DUPLICATE_BUTTON_LABEL]\">");
}

	$smarty->assign("CREATEPDF","<input title=\"Export To PDF\" accessKey=\"Alt+e\" class=\"button\" onclick=\"this.form.return_module.value='SalesOrder'; this.form.return_action.value='DetailView'; this.form.return_id.value='".$_REQUEST['record']."'; this.form.module.value='SalesOrder';this.form.action.value='CreateSOPDF'\" type=\"submit\" name=\"$app_strings[LBL_EXPORT_TO_PDF]\" value=\"Export To PDF\">");


if(isPermitted("Invoice",1,$_REQUEST['record']) == 'yes')
{
	$smarty->assign("CONVERTINVOICE","<input title=\"$app_strings[LBL_CONVERTINVOICE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_CONVERTINVOICE_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='Invoice'; this.form.return_action.value='DetailView'; this.form.convertmode.value='sotoinvoice';this.form.module.value='Invoice'; this.form.action.value='EditView'\" type=\"submit\" name=\"Convert To Invoice\" value=\"$app_strings[LBL_CONVERTINVOICE_BUTTON_LABEL]\">");
}


if(isPermitted("SalesOrder",2,$_REQUEST['record']) == 'yes')
{
	$smarty->assign("DELETEBUTTON","<input title=\"$app_strings[LBL_DELETE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_DELETE_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='SalesOrder'; this.form.return_action.value='index';this.form.module.value='SalesOrder'; this.form.action.value='Delete'; return confirm('$app_strings[NTC_DELETE_CONFIRMATION]')\" type=\"submit\" name=\"Delete\" value=\"$app_strings[LBL_DELETE_BUTTON_LABEL]\">");
}

$smarty->assign("MODULE", $module);
$smarty->display("DetailView.tpl");


echo "<BR>\n";
//Security check for related list
global $profile_id;
$tab_per_Data = getAllTabsPermission($profile_id);
$permissionData = $_SESSION['action_permission_set'];
//getRelatedLists("SalesOrder",$focus);


?>
