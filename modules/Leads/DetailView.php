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
require_once('database/DatabaseConnection.php');
require_once('include/CustomFieldUtil.php');

global $mod_strings;
global $app_strings;
global $app_list_strings;

$focus = new Lead();

if(isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
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
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id());
$xtpl->assign("ID", $focus->id);
$xtpl->assign("ACCOUNT_NAME", $focus->account_name);	
$xtpl->assign("ACCOUNT_ID", $focus->account_id);	
$xtpl->assign("LEAD_SOURCE", $focus->lead_source);
$xtpl->assign("SALUTATION", $focus->salutation);
$xtpl->assign("FIRST_NAME", $focus->first_name);
$xtpl->assign("LAST_NAME", $focus->last_name);
$xtpl->assign("PHONE", $focus->phone);
$xtpl->assign("COMPANY", $focus->company);
$xtpl->assign("MOBILE", $focus->mobile);
$xtpl->assign("DESIGNATION", $focus->designation);
$xtpl->assign("FAX", $focus->fax);
$xtpl->assign("EMAIL", $focus->email);
$xtpl->assign("INDUSTRY", $focus->industry);
$xtpl->assign("WEBSITE", $focus->website);
$xtpl->assign("ANNUAL_REVENUE", $focus->annual_revenue);
$xtpl->assign("LEAD_STATUS", $focus->lead_status);
$xtpl->assign("LICENSE_KEY", $focus->license_key);
$xtpl->assign("RATING", $focus->rating);
//$xtpl->assign("ASSIGNED_TO", get_assigned_user_name($focus->assigned_user_id));
$user_group_name=get_assigned_user_or_group_name($focus->id);
$xtpl->assign("ASSIGNED_TO", $user_group_name);
$xtpl->assign("EMPLOYEES", $focus->employees);
$xtpl->assign("ADDRESS_STREET", $focus->address_street);
$xtpl->assign("ADDRESS_CITY", $focus->address_city);
$xtpl->assign("ADDRESS_STATE", $focus->address_state);
$xtpl->assign("ADDRESS_POSTALCODE", $focus->address_postalcode);
$xtpl->assign("ADDRESS_COUNTRY", $focus->address_country);
$xtpl->assign("YAHOO_ID", $focus->yahoo_id);
if (isset($focus->yahoo_id) && $focus->yahoo_id !== "") $xtpl->assign("YAHOO_MESSENGER", "<a href='http://edit.yahoo.com/config/send_webmesg?.target=".$focus->yahoo_id."'><img border=0 src='http://opi.yahoo.com/online?u=".$focus->yahoo_id."'&m=g&t=2'></a>");
$xtpl->assign("DESCRIPTION", $focus->description);

if($entityDel)
	{
		$xtpl->assign("DELETEBUTTON","<td><input title=\"$app_strings[LBL_DELETE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_DELETE_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='Leads'; this.form.return_action.value='ListView'; this.form.action.value='Delete'; return confirm('$app_strings[NTC_DELETE_CONFIRMATION]')\" type=\"submit\" name=\"Delete\" value=\" $app_strings[LBL_DELETE_BUTTON_LABEL]\"></td>");
	}

$xtpl->assign("SENDMAILBUTTON","<td><input title=\"$app_strings[LBL_SENDMAIL_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_SENDMAIL_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='Leads'; this.form.module.value='Emails';this.form.email_directing_module.value='leads';this.form.return_action.value='DetailView';this.form.action.value='EditView';\" type=\"submit\" name=\"SendMail\" value=\"$app_strings[LBL_SENDMAIL_BUTTON_LABEL]\"></td>");

//$browser = getenv("HTTP_USER_AGENT");
//$pos1 = strrpos($testString,'Windows');
//$local=explode(';',$browser);
//$test=strrpos($local[2],"Windows");
//if($test == true) 
{
	$xtpl->assign("MERGEBUTTON","<input title=\"$app_strings[LBL_MERGE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_MERGE_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.action.value='Merge';\" type=\"submit\" name=\"Merge\" value=\" $app_strings[LBL_MERGE_BUTTON_LABEL]\"></td>");
}
require_once('modules/Users/UserInfoUtil.php');
$wordTemplateResult = fetchWordTemplateList("Leads");
$tempCount = mysql_num_rows($wordTemplateResult);
$tempVal = mysql_fetch_array($wordTemplateResult);
for($templateCount=0;$templateCount<$tempCount;$templateCount++)
{
$optionString .="<option value=\"".$tempVal["filename"]."\">" .$tempVal["filename"] ."</option>";
$tempVal = mysql_fetch_array($wordTemplateResult);
}
$xtpl->assign("WORDTEMPLATEOPTIONS","<td align=right>&nbsp;&nbsp;Select template to Mail Merge:<select name=\"mergefile\">".$optionString."</select>");


//Assigning Custom Field Values
$custfld = CustomFieldDetailView($focus->id, "Leads", "leadcf", "leadid");
$xtpl->assign("CUSTOMFIELD", $custfld);
$xtpl->parse("main");
$xtpl->out("main");

echo "<BR>\n";
/*
// Now get the list of direct reports that match this one.
$focus_list = & $focus->get_direct_reports();

include('modules/Contacts/SubPanelView.php');

echo "<BR>\n";

// Now get the list of opportunities that match this one.
$focus_list = & $focus->get_opportunities();

include('modules/Opportunities/SubPanelView.php');

echo "<BR>\n";

// Now get the list of cases that match this one.
$focus_list = & $focus->get_cases();

include('modules/Cases/SubPanelView.php');

echo "<BR>\n";
*/

// Now get the list of activities that match this contact.
$focus_tasks_list = & $focus->get_tasks();
$focus_meetings_list = & $focus->get_meetings();
$focus_calls_list = & $focus->get_calls();
$focus_emails_list = & $focus->get_emails();
$focus_notes_list = & $focus->get_notes();

include('modules/Activities/SubPanelView.php');

echo "</td></tr>\n";

?>
