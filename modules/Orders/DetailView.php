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
 * $Header: /cvsroot/vtigercrm/vtiger_crm/modules/Orders/DetailView.php,v 1.10 2005/07/16 07:12:13 mickie Exp $
 * Description:  TODO To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Orders/Order.php');
require_once('include/CustomFieldUtil.php');
require_once('include/database/PearDatabase.php');
require_once('include/uifromdbutil.php');
global $mod_strings;
global $app_strings;
global $app_list_strings;

$focus = new Order();
//$focus->set_strings();
//var_dump($focus);

if(isset($_REQUEST['record']) && isset($_REQUEST['record'])) {
    $focus->retrieve_entity_info($_REQUEST['record'],"Orders");
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

$log->info("Order detail view");

$xtpl=new XTemplate ('modules/Orders/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
if (isset($focus->name)) $xtpl->assign("NAME", $focus->name);
else $xtpl->assign("NAME", "");
//get Block 1 Information
$block_1_header = getBlockTableHeader("LBL_PO_INFORMATION");
$block_1 = getDetailBlockInformation("Orders",1,$focus->column_fields);
$xtpl->assign("BLOCK1_HEADER", $block_1_header);
$xtpl->assign("BLOCK1", $block_1);

//get Address Information
$block_2_header = getBlockTableHeader("LBL_ADDRESS_INFORMATION");
$block_2 = getDetailBlockInformation("Orders",2,$focus->column_fields);
$xtpl->assign("BLOCK2_HEADER", $block_2_header);
$xtpl->assign("BLOCK2", $block_2);

//get Description Information
$block_3_header = getBlockTableHeader("LBL_DESCRIPTION_INFORMATION");
$block_3 = getDetailBlockInformation("Orders",3,$focus->column_fields);
$xtpl->assign("BLOCK3_HEADER", $block_3_header);
$xtpl->assign("BLOCK3", $block_3);

$block_6_header = getBlockTableHeader("LBL_TERMS_INFORMATION");
$block_6 = getDetailBlockInformation("Orders",6,$focus->column_fields);
$xtpl->assign("BLOCK6_HEADER", $block_6_header);
$xtpl->assign("BLOCK6", $block_6);


//$block_4_header = getBlockTableHeader("LBL_RELATED_PRODUCTS");
$block_4 = getDetailAssociatedProducts('Orders',$focus);
//$xtpl->assign("BLOCK4_HEADER", $block_4_header);
$xtpl->assign("BLOCK4", $block_4);

$block_5 = getDetailBlockInformation("Orders",5,$focus->column_fields);
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

$xtpl->assign("ID", $_REQUEST['record']);


$permissionData = $_SESSION['action_permission_set'];
if(isPermitted("Orders",1,$_REQUEST['record']) == 'yes')
{
	$xtpl->assign("EDITBUTTON","<td><input title=\"$app_strings[LBL_EDIT_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_EDIT_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='Orders'; this.form.return_action.value='DetailView'; this.form.return_id.value='".$_REQUEST['record']."'; this.form.action.value='EditView'\" type=\"submit\" name=\"Edit\" value=\"$app_strings[LBL_EDIT_BUTTON_LABEL]\"></td>");


	$xtpl->assign("DUPLICATEBUTTON","<td><input title=\"$app_strings[LBL_DUPLICATE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_DUPLICATE_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='Orders'; this.form.return_action.value='DetailView'; this.form.isDuplicate.value='true'; this.form.action.value='EditView'\" type=\"submit\" name=\"Duplicate\" value=\"$app_strings[LBL_DUPLICATE_BUTTON_LABEL]\"></td>");

}

	$xtpl->assign("CREATEPDF","<td><input title=\"Export To PDF\" accessKey=\"Alt+e\" class=\"button\" onclick=\"this.form.return_module.value='Orders'; this.form.return_action.value='DetailView'; this.form.return_id.value='".$_REQUEST['record']."'; this.form.action.value='CreatePDF'\" type=\"submit\" name=\"Export To PDF\" value=\"$app_strings[LBL_EXPORT_TO_PDF]\"></td>");
	
if(isPermitted("Orders",2,$_REQUEST['record']) == 'yes')
{
	$xtpl->assign("DELETEBUTTON","<td><input title=\"$app_strings[LBL_DELETE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_DELETE_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='Orders'; this.form.return_action.value='index'; this.form.action.value='Delete'; return confirm('$app_strings[NTC_DELETE_CONFIRMATION]')\" type=\"submit\" name=\"Delete\" value=\"$app_strings[LBL_DELETE_BUTTON_LABEL]\"></td>");
}

$xtpl->parse("main");
$xtpl->out("main");

//Security check for related list
global $profile_id;
$tab_per_Data = getAllTabsPermission($profile_id);
$permissionData = $_SESSION['action_permission_set'];
getRelatedLists("Orders",$focus);


echo "<BR>\n";


?>
