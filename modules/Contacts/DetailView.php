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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Contacts/DetailView.php,v 1.38 2005/04/25 05:04:46 rank Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('Smarty_setup.php');
require_once('data/Tracker.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Contacts/Forms.php');
require_once('include/CustomFieldUtil.php');
require_once('include/database/PearDatabase.php');
require_once('include/utils/utils.php');

global $log;
global $mod_strings;
global $app_strings;

$focus = new Contact();

if(isset($_REQUEST['record']) && $_REQUEST['record']!='') {

        $focus->id=$_REQUEST['record'];
        $focus->retrieve_entity_info($_REQUEST['record'],'Contacts');
	 $log->info("Entity info successfully retrieved for Contact DetailView.");
	$focus->firstname=$focus->column_fields['firstname'];
        $focus->lastname=$focus->column_fields['lastname'];
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("Contact detail view");

$smarty = new vtigerCRM_Smarty;
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);

$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);
$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$smarty->assign("UPDATEINFO",updateInfo($focus->id));

$smarty->assign("NAME",$focus->lastname.' '.$focus->firstname);

$log->info("Detail Block Informations successfully retrieved.");
$smarty->assign("BLOCKS", getBlocks("Contacts","detail_view",'',$focus->column_fields));
$smarty->assign("CUSTOMFIELD", $cust_fld);
$smarty->assign("SINGLE_MOD","Contact");
$smarty->assign("REDIR_MOD","contacts");

$smarty->assign("ID", $_REQUEST['record']);
$permissionData = $_SESSION['action_permission_set'];
if(isPermitted("Contacts",1,$_REQUEST['record']) == 'yes')
	$smarty->assign("EDIT_DUPLICATE","permitted");

if(isPermitted("Contacts",2,$_REQUEST['record']) == 'yes')
	$smarty->assign("DELETE","permitted");
if(isPermitted("Emails",1,'') == 'yes')
{
	//Added to pass the parents list as hidden for Emails -- 09-11-2005
	$parent_email = getEmailParentsList('Contacts',$_REQUEST['record']);
	$smarty->assign("HIDDEN_PARENTS_LIST",$parent_email);
	$smarty->assign("SENDMAILBUTTON","permitted");
}

if(isPermitted("Contacts",8,'') == 'yes')
{
	$smarty->assign("MERGEBUTTON","permitted");
	require_once('include/utils/UserInfoUtil.php');
	$wordTemplateResult = fetchWordTemplateList("Contacts");
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

//Security check for related list
global $profile_id;
$tab_per_Data = getAllTabsPermission($profile_id);
$permissionData = $_SESSION['action_permission_set'];
$category = getParentTab();
$smarty->assign("CATEGORY",$category);

$smarty->assign("MODULE","Contacts");
$smarty->display("DetailView.tpl");
?>

