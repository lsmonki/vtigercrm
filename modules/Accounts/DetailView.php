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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Accounts/DetailView.php,v 1.30 2005/03/02 18:18:28 jack Exp $
 * Description:  TODO To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Accounts/Account.php');
require_once('include/CustomFieldUtil.php');
require_once('include/database/PearDatabase.php');
require_once('include/uifromdbutil.php');
global $mod_strings;
global $app_strings;
global $app_list_strings;

$focus = new Account();
//$focus->set_strings();
//var_dump($focus);

if(isset($_REQUEST['record']) && isset($_REQUEST['record'])) {
    $focus->retrieve_entity_info($_REQUEST['record'],"Accounts");
    $focus->id = $_REQUEST['record'];	
    $focus->name=$focus->column_fields['accountname'];
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
} 

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("Account detail view");

$xtpl=new XTemplate ('modules/Accounts/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
 
if (isset($focus->name)) $xtpl->assign("NAME", $focus->name);
else $xtpl->assign("NAME", "");

//get Block 1 Information

$block_1 = getDetailBlockInformation("Accounts",1,$focus->column_fields);
$xtpl->assign("BLOCK1", $block_1);

//get Address Information

$block_2 = getDetailBlockInformation("Accounts",2,$focus->column_fields);
$xtpl->assign("BLOCK2", $block_2);
//get Description Information

$block_3 = getDetailBlockInformation("Accounts",3,$focus->column_fields);
$xtpl->assign("BLOCK3", $block_3);

$block_5 = getDetailBlockInformation("Accounts",5,$focus->column_fields);
if(trim($block_5) != '')
{
        $cust_fld = '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formOuterBorder">';
        $cust_fld .=  '<tr><td>';
        $cust_fld .= '<table width="100%" border="0" cellspacing="1" cellpadding="0">';
        $cust_fld .= '<tr><th align="left" class="formSecHeader" colspan="2">Custom Information</th></tr>';
        $cust_fld .= $block_5;
        $cust_fld .= '</table>';
        $cust_fld .= '</td></tr></table>';
	$cust_fld .= '<BR>';

}

$xtpl->assign("CUSTOMFIELD", $cust_fld);

$xtpl->assign("ID", $_REQUEST['record']);
/*
if ($focus->annual_revenue != '') $xtpl->assign("ANNUAL_REVENUE", $language['LBL_CURRENCY_SYMBOL'].$focus->annual_revenue);
$xtpl->assign("DATE_MODIFIED", substr($focus->date_modified,0,16));
$xtpl->assign("DATE_ENTERED", substr($focus->date_entered,0,16));
//Assigning Custom Field Values
$custfld = CustomFieldDetailView($focus->id, "Accounts", "accountcf", "accountid");
$xtpl->assign("CUSTOMFIELD", $custfld);
*/

$permissionData = $_SESSION['action_permission_set'];
if($permissionData[$tabid]['1'] == 0)
{
	$xtpl->assign("EDITBUTTON","<td><input title=\"$app_strings[LBL_EDIT_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_EDIT_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='Accounts'; this.form.return_action.value='DetailView'; this.form.return_id.value='".$_REQUEST['record']."'; this.form.action.value='EditView'\" type=\"submit\" name=\"Edit\" value=\"$app_strings[LBL_EDIT_BUTTON_LABEL]\"></td>");


	$xtpl->assign("DUPLICATEBUTTON","<td><input title=\"$app_strings[LBL_DUPLICATE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_DUPLICATE_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='Accounts'; this.form.return_action.value='DetailView'; this.form.isDuplicate.value='true'; this.form.action.value='EditView'\" type=\"submit\" name=\"Duplicate\" value=\"$app_strings[LBL_DUPLICATE_BUTTON_LABEL]\"></td>");
}


if($permissionData[$tabid]['2'] == 0)
{
	$xtpl->assign("DELETEBUTTON","<td><input title=\"$app_strings[LBL_DELETE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_DELETE_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='Accounts'; this.form.return_action.value='ListView'; this.form.action.value='Delete'; return confirm('$app_strings[NTC_DELETE_CONFIRMATION]')\" type=\"submit\" name=\"Delete\" value=\"$app_strings[LBL_DELETE_BUTTON_LABEL]\"></td>");
}

//$browser = getenv("HTTP_USER_AGENT");
//$pos1 = strrpos($testString,'Windows');
//$local=explode(';',$browser);
//$test=strrpos($local[2],"Windows");
//if($test == true)
{
	$xtpl->assign("MERGEBUTTON","<input title=\"$app_strings[LBL_MERGE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_MERGE_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.action.value='Merge';\" type=\"submit\" name=\"Merge\" value=\" $app_strings[LBL_MERGE_BUTTON_LABEL]\"></td>");
}


require_once('modules/Users/UserInfoUtil.php');
$wordTemplateResult = fetchWordTemplateList("Accounts");
//$tempCount = mysql_num_rows($wordTemplateResult);
//$tempVal = mysql_fetch_array($wordTemplateResult);
$tempCount = $adb->num_rows($wordTemplateResult);
$tempVal = $adb->fetch_array($wordTemplateResult);
for($templateCount=0;$templateCount<$tempCount;$templateCount++)
{
$optionString .="<option value=\"".$tempVal["filename"]."\">" .$tempVal["filename"] ."</option>";
$tempVal = $adb->fetch_array($wordTemplateResult);
//$tempVal = mysql_fetch_array($wordTemplateResult);
}
$xtpl->assign("WORDTEMPLATEOPTIONS","<td align=right>&nbsp;&nbsp;Select template to Mail Merge:<select name=\"mergefile\">".$optionString."</select>");

/*if($_ENV['HOME'] == '')
{
	$xtpl->assign("MERGEBUTTON","<input title=\"$app_strings[LBL_MERGE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_MERGE_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.action.value='Merge';\" type=\"submit\" name=\"Merge\" value=\" $app_strings[LBL_MERGE_BUTTON_LABEL]\"></td>");
}
*/
$xtpl->parse("main");
$xtpl->out("main");

echo "<BR>\n";


//Security check for related list
global $profile_id;
$tab_per_Data = getAllTabsPermission($profile_id);
$permissionData = $_SESSION['action_permission_set'];


//Constructing the Related Lists from here
include('modules/Accounts/RenderRelatedListUI.php');
// Now get the list of opportunities that match this one.

if($tab_per_Data[2] == 0)
{
	if($permissionData[2][3] == 0)
	{
		$focus_list = & $focus->get_opportunities($focus->id);
	}
}

if($tab_per_Data[4] == 0)
{
	if($permissionData[4][3] == 0)
	{
		$focus_list = & $focus->get_contacts($focus->id);
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

if($tab_per_Data[13] == 0)
{
	if($permissionData[13][3] == 0)
	{
		$focus_tickets_list = & $focus->get_tickets($focus->id);
	}
}

if($tab_per_Data[8] == 0)
{
	if($permissionData[8][3] == 0)
	{
		$focus_attachments_list = & $focus->get_attachments($focus->id);
	}
}
//$focus_notes_list = & $focus->get_notes($focus->id);

/*
require_once('include/RelatedTicketListUtil.php');
$list = getTicketList($focus->id, "Accounts", $image_path,$theme);
echo $list;
*/



?>
