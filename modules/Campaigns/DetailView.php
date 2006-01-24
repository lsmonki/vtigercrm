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
require_once('Smarty_setup.php');
require_once('include/utils/utils.php');
require_once('modules/Campaigns/Campaign.php');
require_once('include/utils/utils.php');

$focus = new Campaign();

if(isset($_REQUEST['record']) && isset($_REQUEST['record'])) 
{
    $focus->retrieve_entity_info($_REQUEST['record'],"Campaign");
    $focus->name=$focus->column_fields['ticket_title'];
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') 
{
        $focus->id = "";
}

//Added code for Error display in sending mail to assigned to user when ticket is created or updated.
if($_REQUEST['mail_error'] != '')
{
        require_once("modules/Emails/mail.php");
        echo parseEmailErrorString($_REQUEST['mail_error']);
}

global $app_strings;
global $mod_strings;

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$smarty = new vtigerCRM_Smarty;
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);

if (isset($focus->name)) $smarty->assign("NAME", $focus->name);
else $smarty->assign("NAME", "");
$smarty->assign("BLOCKS", getBlocks("Campaign","detail_view",'',$focus->column_fields));
$smarty->assign("TICKETID", $_REQUEST['record']);

$smarty->assign("CUSTOMFIELD", $cust_fld);
$smarty->assign("SINGLE_MOD","Campaign");
$category = getParentTab();
$smarty->assign("CATEGORY",$category);

$permissionData = $_SESSION['action_permission_set'];
if(isPermitted("Campaign",1,$_REQUEST['record']) == 'yes')
{
	$smarty->assign("EDITBUTTON","<input title=\"$app_strings[LBL_EDIT_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_EDIT_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='Campaign'; this.form.return_action.value='DetailView'; this.form.return_id.value='".$_REQUEST['record']."'; this.form.action.value='EditView'\" type=\"submit\" name=\"Edit\" value=\"$app_strings[LBL_EDIT_BUTTON_LABEL]\">&nbsp;");


	$smarty->assign("DUPLICATEBUTTON","<input title=\"$app_strings[LBL_DUPLICATE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_DUPLICATE_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='Campaign'; this.form.return_action.value='DetailView'; this.form.isDuplicate.value='true'; this.form.action.value='EditView'\" type=\"submit\" name=\"Duplicate\" value=\"$app_strings[LBL_DUPLICATE_BUTTON_LABEL]\">&nbsp;");
}


if(isPermitted("Campaign",2,$_REQUEST['record']) == 'yes')
{
	$smarty->assign("DELETEBUTTON","<input title=\"$app_strings[LBL_DELETE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_DELETE_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='Campaign'; this.form.return_action.value='ListView'; this.form.action.value='Delete'; return confirm('$app_strings[NTC_DELETE_CONFIRMATION]')\" type=\"submit\" name=\"Delete\" value=\"$app_strings[LBL_DELETE_BUTTON_LABEL]\">&nbsp;");
}

//Added button for Convert the ticket to FAQ
$smarty->assign("CONVERTASFAQ","<input title=\"$mod_strings[LBL_CONVERT_AS_FAQ_BUTTON_TITLE]\" accessKey=\"$mod_strings[LBL_CONVERT_AS_FAQ_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='Faq'; this.form.return_action.value='DetailView'; this.form.action.value='ConvertAsFAQ'; \" type=\"submit\" name=\"ConvertAsFAQ\" value=\"$mod_strings[LBL_CONVERT_AS_FAQ_BUTTON_LABEL]\">&nbsp;");

$smarty->assign("IMAGE_PATH", $image_path);
$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$smarty->assign("ID", $_REQUEST['record']);
if(isPermitted("Campaign",8,'') == 'yes')
{
	$smarty->assign("MERGEBUTTON","<input title=\"$app_strings[LBL_MERGE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_MERGE_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.action.value='Merge';\" type=\"submit\" name=\"Merge\" value=\" $app_strings[LBL_MERGE_BUTTON_LABEL]\">&nbsp;");

        require_once('include/utils/UserInfoUtil.php');
        $wordTemplateResult = fetchWordTemplateList("Campaign");
        $tempCount = $adb->num_rows($wordTemplateResult);
        $tempVal = $adb->fetch_array($wordTemplateResult);
        for($templateCount=0;$templateCount<$tempCount;$templateCount++)
        {
                $optionString []=$tempVal["filename"];
                $tempVal = $adb->fetch_array($wordTemplateResult);
        }
	$smarty->assign("WORDTEMPLATEOPTIONS",$app_strings['LBL_SELECT_TEMPLATE_TO_MAIL_MERGE']);
        $smarty->assign("TOPTIONS",$optionString);
}


$smarty->assign("MODULE","Campaign");
$smarty->display("DetailView.tpl");
//Security check for related list
global $profile_id;
$tab_per_Data = getAllTabsPermission($profile_id);
$permissionData = $_SESSION['action_permission_set'];
$focus->id = $_REQUEST['record'];


?>
