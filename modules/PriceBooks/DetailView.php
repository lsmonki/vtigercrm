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
//require_once('XTemplate/xtpl.php');
require_once('Smarty_setup.php');
require_once('include/utils/utils.php');
require_once('modules/PriceBooks/PriceBook.php');
require_once('include/utils/utils.php');

$focus = new PriceBook();

if(isset($_REQUEST['record']) && isset($_REQUEST['record'])) 
{
	$focus->retrieve_entity_info($_REQUEST['record'],"PriceBooks");
	$focus->id = $_REQUEST['record'];
	$pricebookname = getPriceBookName($focus->id);
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') 
{
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

$smarty = new vtigerCRM_Smarty;
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
if(isset($_REQUEST['category']) && $_REQUEST['category'] !='')
{
            $category = $_REQUEST['category'];
}
else
{
            $category = getParentTabFromModule($currentModule);
}
$smarty->assign("CATEGORY",$category);

$smarty->assign("BLOCKS", getBlocks("PriceBooks","detail_view",'',$focus->column_fields));
$category = getParentTab();
$smarty->assign("CATEGORY",$category);

$smarty->assign("CUSTOMFIELD", $cust_fld);

if(isPermitted("PriceBooks",1,$_REQUEST['record']) == 'yes')
{
	$smarty->assign("EDITBUTTON","<input title=\"$app_strings[LBL_EDIT_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_EDIT_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='PriceBooks'; this.form.return_action.value='DetailView'; this.form.return_id.value='".$_REQUEST['record']."'; this.form.action.value='EditView'\" type=\"submit\" name=\"Edit\" value=\"$app_strings[LBL_EDIT_BUTTON_LABEL]\">");


	$smarty->assign("DUPLICATEBUTTON","<input title=\"$app_strings[LBL_DUPLICATE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_DUPLICATE_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='PriceBooks'; this.form.return_action.value='DetailView'; this.form.isDuplicate.value='true'; this.form.action.value='EditView'\" type=\"submit\" name=\"Duplicate\" value=\"$app_strings[LBL_DUPLICATE_BUTTON_LABEL]\">");
}

if(isPermitted("PriceBooks",2,$_REQUEST['record']) == 'yes')
{
	$smarty->assign("DELETEBUTTON","<input title=\"$app_strings[LBL_DELETE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_DELETE_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='PriceBooks'; this.form.return_action.value='index'; this.form.action.value='Delete'; return confirm('$app_strings[NTC_DELETE_CONFIRMATION]')\" type=\"submit\" name=\"Delete\" value=\"$app_strings[LBL_DELETE_BUTTON_LABEL]\">");
}


/*
$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("PRICEBOOKNAME", $pricebookname);
$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$xtpl->assign("ID", $_REQUEST['record']);
$xtpl->parse("main");
$xtpl->out("main");
*/
$smarty->assign("IMAGE_PATH", $image_path);
$smarty->assign("PRICEBOOKNAME", $pricebookname);
$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$smarty->assign("ID", $_REQUEST['record']);
//Security check for related list
global $profile_id;
$tab_per_Data = getAllTabsPermission($profile_id);
$permissionData = $_SESSION['action_permission_set'];
//getRelatedLists("PriceBooks",$focus);
$smarty->assign("MODULE", $module);
$smarty->display("DetailView.tpl");
?>
