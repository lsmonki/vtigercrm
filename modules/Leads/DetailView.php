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
require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Leads/Lead.php');
require_once('modules/Leads/Forms.php');
require_once('include/database/PearDatabase.php');
require_once('include/CustomFieldUtil.php');
require_once('include/uifromdbutil.php');
require_once('modules/Users/UserInfoUtil.php');

global $mod_strings;
global $app_strings;
global $app_list_strings;

    global $vtlog;
$focus = new Lead();

if(isset($_REQUEST['record']))
{
    $focus->id = $_REQUEST['record'];	

    $focus->retrieve_entity_info($_REQUEST['record'],"Leads");
    $focus->id = $_REQUEST['record'];
    $vtlog->logthis("id is ".$focus->id ,'debug'); 
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

$log->info("Lead detail view");

$xtpl=new XTemplate ('modules/Leads/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$xtpl->assign("ID", $focus->id);
 
if (isset($focus->firstname)) $xtpl->assign("FIRST_NAME", $focus->firstname);
else $xtpl->assign("FIRST_NAME", "");
$xtpl->assign("LAST_NAME", $focus->lastname);

//get Block 1 Information
$block_1_header = getBlockTableHeader("LBL_LEAD_INFORMATION");
$block_1 = getDetailBlockInformation("Leads",1,$focus->column_fields);
$xtpl->assign("BLOCK1", $block_1);

//get Address Information
$block_2_header = getBlockTableHeader("LBL_ADDRESS_INFORMATION");
$block_2 = getDetailBlockInformation("Leads",2,$focus->column_fields);
$xtpl->assign("BLOCK2", $block_2);
//get Description Information
$block_3_header = getBlockTableHeader("LBL_DESCRIPTION_INFORMATION");
$block_3 = getDetailBlockInformation("Leads",3,$focus->column_fields);
$xtpl->assign("BLOCK3", $block_3);

$xtpl->assign("BLOCK1_HEADER", $block_1_header);
$xtpl->assign("BLOCK2_HEADER", $block_2_header);
$xtpl->assign("BLOCK3_HEADER", $block_3_header);


$block_5 = getDetailBlockInformation("Leads",5,$focus->column_fields);
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

}

$xtpl->assign("CUSTOMFIELD", $cust_fld);

$val = isPermitted("Leads",1,$_REQUEST['record']);

$permissionData = $_SESSION['action_permission_set'];
if(isPermitted("Leads",1,$_REQUEST['record']) == 'yes')
{
	$xtpl->assign("EDITBUTTON","<td><input title=\"$app_strings[LBL_EDIT_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_EDIT_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='Leads'; this.form.return_action.value='DetailView'; this.form.return_id.value='".$_REQUEST['record']."'; this.form.action.value='EditView'\" type=\"submit\" name=\"Edit\" value=\"$app_strings[LBL_EDIT_BUTTON_LABEL]\"></td>");


	$xtpl->assign("DUPLICATEBUTTON","<td><input title=\"$app_strings[LBL_DUPLICATE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_DUPLICATE_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='Leads'; this.form.return_action.value='DetailView'; this.form.isDuplicate.value='true'; this.form.action.value='EditView'\" type=\"submit\" name=\"Duplicate\" value=\"$app_strings[LBL_DUPLICATE_BUTTON_LABEL]\"></td>");

	
}

//Security check for Convert Lead Button
global $profile_id;
$tab_per_Data = getAllTabsPermission($profile_id);
$permissionData = $_SESSION['action_permission_set'];

if(isPermitted("Leads",1,$_REQUEST['record']) == 'yes' && $tab_per_Data[getTabid("Accounts")] == 0 && $tab_per_Data[getTabid("Contacts")] == 0 && $permissionData[getTabid("Accounts")][1] == 0 && $permissionData[getTabid("Contacts")][1] ==0)
{
	$xtpl->assign("CONVERTLEAD","<td><input title=\"$app_strings[LBL_CONVERT_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_CONVERT_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='Leads'; this.form.action.value='ConvertLead'\" type=\"submit\" name=\"Convert\" value=\"$app_strings[LBL_CONVERT_BUTTON_LABEL]\"></td>");
}


if(isPermitted("Leads",2,$_REQUEST['record']) == 'yes')
{
	$xtpl->assign("DELETEBUTTON","<td><input title=\"$app_strings[LBL_DELETE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_DELETE_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='Leads'; this.form.return_action.value='ListView'; this.form.action.value='Delete'; return confirm('$app_strings[NTC_DELETE_CONFIRMATION]')\" type=\"submit\" name=\"Delete\" value=\"$app_strings[LBL_DELETE_BUTTON_LABEL]\"></td>");
}

if(isPermitted("Emails",1,'') == 'yes')
{
	$xtpl->assign("SENDMAILBUTTON","<td><input title=\"$app_strings[LBL_SENDMAIL_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_SENDMAIL_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='Leads'; this.form.module.value='Emails';this.form.email_directing_module.value='leads';this.form.return_action.value='DetailView';this.form.action.value='EditView';\" type=\"submit\" name=\"SendMail\" value=\"$app_strings[LBL_SENDMAIL_BUTTON_LABEL]\"></td>");
}

if(isPermitted("Leads",8,'') == 'yes') 
{
	$xtpl->assign("MERGEBUTTON","<input title=\"$app_strings[LBL_MERGE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_MERGE_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.action.value='Merge';\" type=\"submit\" name=\"Merge\" value=\" $app_strings[LBL_MERGE_BUTTON_LABEL]\"></td>");
	$wordTemplateResult = fetchWordTemplateList("Leads");
	$tempCount = $adb->num_rows($wordTemplateResult);
	$tempVal = $adb->fetch_array($wordTemplateResult);
	for($templateCount=0;$templateCount<$tempCount;$templateCount++)
	{
		$optionString .="<option value=\"".$tempVal["templateid"]."\">" .$tempVal["filename"] ."</option>";
		$tempVal = $adb->fetch_array($wordTemplateResult);
	}
	$xtpl->assign("WORDTEMPLATEOPTIONS","<td align=right>&nbsp;&nbsp;".$mod_strings['LBL_SELECT_TEMPLATE_TO_MAIL_MERGE']."<select name=\"mergefile\">".$optionString."</select>");
}


$xtpl->parse("main");
$xtpl->out("main");

echo "<BR>\n";
getRelatedLists("Leads",$focus);

// Now get the list of activities that match this contact.
// $focus_tasks_list = & $focus->get_tasks($focus->id);
//$focus_meetings_list = & $focus->get_meetings($focus->id);
// $focus_activities_list = & $focus->get_activities($focus->id);
// $focus_emails_list = & $focus->get_emails($focus->id);
// $focus_notes_list = & $focus->get_notes($focus->id);
// $focus_tickets_list = & $focus->get_tickets($focus->id);
// $focus_history_list = & $focus->get_history($focus->id);
// $focus_attachments_list = & $focus->get_attachments($focus->id);

?>
