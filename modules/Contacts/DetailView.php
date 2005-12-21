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

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Contacts/Forms.php');
require_once('include/CustomFieldUtil.php');
require_once('include/database/PearDatabase.php');
require_once('include/uifromdbutil.php');

global $vtlog;
global $mod_strings;
global $app_strings;
global $app_list_strings;

$focus = new Contact();

if(isset($_REQUEST['record']) && $_REQUEST['record']!='') {

        $focus->id=$_REQUEST['record'];
        $focus->retrieve_entity_info($_REQUEST['record'],'Contacts');
	$vtlog->logthis("Entity info successfully retrieved for Contact DetailView.",'info');
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

$xtpl=new XTemplate ('modules/Contacts/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string'
]);

if (isset($focus->firstname)) $xtpl->assign("FIRST_NAME", $focus->firstname);
else $xtpl->assign("FIRST_NAME", "");
$xtpl->assign("LAST_NAME", $focus->lastname);


//get Block 1 Information
$block_1 = getDetailBlockInformation("Contacts",1,$focus->column_fields);
$xtpl->assign("BLOCK1", $block_1);

//get Address Information
$block_2 = getDetailBlockInformation("Contacts",2,$focus->column_fields);
$xtpl->assign("BLOCK2", $block_2);

//get Description Information
$block_3 = getDetailBlockInformation("Contacts",3,$focus->column_fields);
$xtpl->assign("BLOCK3", $block_3);

//get CustomerPortal Information
$block_4 = getDetailBlockInformation("Contacts",4,$focus->column_fields);
$xtpl->assign("BLOCK4", $block_4);

$block_1_header = getBlockTableHeader("LBL_CONTACT_INFORMATION");
$block_2_header = getBlockTableHeader("LBL_ADDRESS_INFORMATION");
$block_3_header = getBlockTableHeader("LBL_DESCRIPTION_INFORMATION");
$block_4_header = getBlockTableHeader("LBL_CUSTOMER_PORTAL_INFORMATION");
$xtpl->assign("BLOCK1_HEADER", $block_1_header);
$xtpl->assign("BLOCK2_HEADER", $block_2_header);
$xtpl->assign("BLOCK3_HEADER", $block_3_header);
$xtpl->assign("BLOCK4_HEADER", $block_4_header);

$block_5 = getDetailBlockInformation("Contacts",5,$focus->column_fields);
$vtlog->logthis("Detail Block Informations successfully retrieved.",'info');
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
if(isPermitted("Contacts",1,$_REQUEST['record']) == 'yes')
{
	$xtpl->assign("EDITBUTTON","<td><input title=\"$app_strings[LBL_EDIT_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_EDIT_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='Contacts'; this.form.return_action.value='DetailView'; this.form.return_id.value='".$_REQUEST['record']."'; this.form.action.value='EditView'\" type=\"submit\" name=\"Edit\" value=\"$app_strings[LBL_EDIT_BUTTON_LABEL]\"></td>");


	$xtpl->assign("DUPLICATEBUTTON","<td><input title=\"$app_strings[LBL_DUPLICATE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_DUPLICATE_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='Contacts'; this.form.return_action.value='DetailView'; this.form.isDuplicate.value='true'; this.form.action.value='EditView'\" type=\"submit\" name=\"Duplicate\" value=\"$app_strings[LBL_DUPLICATE_BUTTON_LABEL]\"></td>");
}


if(isPermitted("Contacts",2,$_REQUEST['record']) == 'yes')
{
	$xtpl->assign("DELETEBUTTON","<td><input title=\"$app_strings[LBL_DELETE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_DELETE_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='Contacts'; this.form.return_action.value='ListView'; this.form.action.value='Delete'; return confirm('$app_strings[NTC_DELETE_CONFIRMATION]')\" type=\"submit\" name=\"Delete\" value=\"$app_strings[LBL_DELETE_BUTTON_LABEL]\"></td>");
}
if(isPermitted("Emails",1,'') == 'yes')
{
	$xtpl->assign("SENDMAILBUTTON","<td><input title=\"$app_strings[LBL_SENDMAIL_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_SENDMAIL_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='Contacts'; this.form.module.value='Emails';this.form.email_directing_module.value='contacts';this.form.return_action.value='DetailView';this.form.action.value='EditView';\" type=\"submit\" name=\"SendMail\" value=\"$app_strings[LBL_SENDMAIL_BUTTON_LABEL]\"></td>");
}

//$browser = getenv("HTTP_USER_AGENT");
//$pos1 = strrpos($testString,'Windows');
//$local=explode(';',$browser);
//$test=strrpos($local[2],"Windows");
//if($test == true)
if(isPermitted("Contacts",8,'') == 'yes')
{
	$xtpl->assign("MERGEBUTTON","<input title=\"$app_strings[LBL_MERGE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_MERGE_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.action.value='Merge';\" type=\"submit\" name=\"Merge\" value=\" $app_strings[LBL_MERGE_BUTTON_LABEL]\"></td>");

	require_once('modules/Users/UserInfoUtil.php');
	$wordTemplateResult = fetchWordTemplateList("Contacts");
	$tempCount = $adb->num_rows($wordTemplateResult);
	$tempVal = $adb->fetch_array($wordTemplateResult);
	for($templateCount=0;$templateCount<$tempCount;$templateCount++)
	{
		$optionString .="<option value=\"".$tempVal["templateid"]."\">" .$tempVal["filename"] ."</option>";
		$tempVal = $adb->fetch_array($wordTemplateResult);
	}
	$xtpl->assign("WORDTEMPLATEOPTIONS","<td align=right>&nbsp;&nbsp;".$app_strings['LBL_SELECT_TEMPLATE_TO_MAIL_MERGE']."<select name=\"mergefile\">".$optionString."</select>");
}
$xtpl->parse("main");
$xtpl->out("main");

echo "<BR>\n";

/*
// Now get the list of direct reports that match this one.
$focus_list = & $focus->get_direct_reports();

*/


//Security check for related list
global $profile_id;
$tab_per_Data = getAllTabsPermission($profile_id);
$permissionData = $_SESSION['action_permission_set'];

getRelatedLists("Contacts",$focus);

/*
//Constructing the Related Lists from here
include('modules/Contacts/RenderRelatedListUI.php');

// Now get the list of opportunities that match this one.

if($tab_per_Data[2] == 0)
{
        if($permissionData[2][3] == 0)
        {
		$focus_list = & $focus->get_opportunities($focus->id);
	}
}

if($tab_per_Data[9] == 0)
{
        if($permissionData[9][3] == 0)
        {
 		$focus_activities_list = & $focus->get_activities($focus->id);
	}
}

if($tab_per_Data[10] == 0)
{
        if($permissionData[10][3] == 0)
        {
 		$focus_emails_list = & $focus->get_emails($focus->id);
	}
}

$focus_tickets_list = & $focus->get_tickets($focus->id);
$focus_history_list =  $focus->get_history($focus->id);

if($tab_per_Data[8] == 0)
{
        if($permissionData[8][3] == 0)
        {
 		$focus_attachments_list = & $focus->get_attachments($focus->id);
	}
}

*/
?>

