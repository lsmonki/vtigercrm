<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/
require_once('include/database/PearDatabase.php');
//require_once('HelpDeskUtil.php');
require_once('XTemplate/xtpl.php');
require_once('include/utils.php');
require_once('modules/Products/PriceBook.php');
require_once('include/uifromdbutil.php');

$focus = new PriceBook();

if(isset($_REQUEST['record']) && isset($_REQUEST['record'])) {
  $focus->retrieve_entity_info($_REQUEST['record'],"PriceBook");
  $focus->id = $_REQUEST['record'];
  $pricebookname = getPriceBookName($focus->id);
  //$focus->name=$focus->column_fields['productname'];		
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
        $focus->id = "";
}

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$xtpl=new XTemplate ('modules/Products/PriceBookDetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$block_1 = getDetailBlockInformation("PriceBook",1,$focus->column_fields);
$xtpl->assign("BLOCK1", $block_1);
$block_2 = getDetailBlockInformation("PriceBook",2,$focus->column_fields);
$xtpl->assign("BLOCK2", $block_2);
$block_1_header = getBlockTableHeader("LBL_PRICEBOOK_INFORMATION");
$block_2_header = getBlockTableHeader("LBL_DESCRIPTION_INFORMATION");
$xtpl->assign("BLOCK1_HEADER", $block_1_header);
$xtpl->assign("BLOCK2_HEADER", $block_2_header);

$block_5 = getDetailBlockInformation("PriceBook",5,$focus->column_fields);
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

if(isPermitted("PriceBook",1,$_REQUEST['record']) == 'yes')
{
	$xtpl->assign("EDITBUTTON","<td><input title=\"$app_strings[LBL_EDIT_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_EDIT_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='Products'; this.form.return_action.value='PriceBookDetailView'; this.form.return_id.value='".$_REQUEST['record']."'; this.form.action.value='PriceBookEditView'\" type=\"submit\" name=\"Edit\" value=\"$app_strings[LBL_EDIT_BUTTON_LABEL]\"></td>");


	$xtpl->assign("DUPLICATEBUTTON","<td><input title=\"$app_strings[LBL_DUPLICATE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_DUPLICATE_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='Products'; this.form.return_action.value='PriceBookDetailView'; this.form.isDuplicate.value='true'; this.form.action.value='PriceBookEditView'\" type=\"submit\" name=\"Duplicate\" value=\"$app_strings[LBL_DUPLICATE_BUTTON_LABEL]\"></td>");
}

if(isPermitted("PriceBook",2,$_REQUEST['record']) == 'yes')
{
	$xtpl->assign("DELETEBUTTON","<td><input title=\"$app_strings[LBL_DELETE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_DELETE_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='Products'; this.form.return_action.value='index'; this.form.action.value='DeletePriceBook'; return confirm('$app_strings[NTC_DELETE_CONFIRMATION]')\" type=\"submit\" name=\"Delete\" value=\"$app_strings[LBL_DELETE_BUTTON_LABEL]\"></td>");
}



$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("PRICEBOOKNAME", $pricebookname);
$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$xtpl->assign("ID", $_REQUEST['record']);
$xtpl->parse("main");
$xtpl->out("main");

//Security check for related list
global $profile_id;
$tab_per_Data = getAllTabsPermission($profile_id);
$permissionData = $_SESSION['action_permission_set'];
getRelatedLists("PriceBook",$focus);

?>
