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
 * $Header:  vtiger_crm/sugarcrm/modules/Contacts/DetailView.php,v 1.21 2005/01/11 14:24:56 jack Exp $
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
global $mod_strings;
global $app_strings;
global $app_list_strings;

$focus = new Contact();

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

$log->info("Contact detail view");

$xtpl=new XTemplate ('modules/Contacts/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("ACCOUNT_NAME", $focus->account_name);
$xtpl->assign("ACCOUNT_ID", $focus->account_id);
$xtpl->assign("LEAD_SOURCE", $focus->lead_source);
$xtpl->assign("SALUTATION", $focus->salutation);
$xtpl->assign("FIRST_NAME", $focus->first_name);
$xtpl->assign("LAST_NAME", $focus->last_name);
$xtpl->assign("TITLE", $focus->title);
$xtpl->assign("DEPARTMENT", $focus->department);
if ($focus->birthdate == '0000-00-00') $xtpl->assign("BIRTHDATE", '');
else $xtpl->assign("BIRTHDATE", $focus->birthdate);
if ($focus->do_not_call == 'on') $xtpl->assign("DO_NOT_CALL", "checked");
$xtpl->assign("ASSIGNED_TO", $focus->assigned_user_name);
$xtpl->assign("REPORTS_TO_ID", $focus->reports_to_id);
$xtpl->assign("REPORTS_TO_NAME", $focus->reports_to_name);
$xtpl->assign("PHONE_HOME", $focus->phone_home);
$xtpl->assign("PHONE_MOBILE", $focus->phone_mobile);
$xtpl->assign("PHONE_WORK", $focus->phone_work);
$xtpl->assign("PHONE_OTHER", $focus->phone_other);
$xtpl->assign("PHONE_FAX", $focus->phone_fax);
$xtpl->assign("EMAIL1", $focus->email1);
$xtpl->assign("EMAIL2", $focus->email2);
$xtpl->assign("YAHOO_ID", $focus->yahoo_id);
if (isset($focus->yahoo_id) && $focus->yahoo_id !== "") $xtpl->assign("YAHOO_MESSENGER", "<a href='http://edit.yahoo.com/config/send_webmesg?.target=".$focus->yahoo_id."'><img border=0 src='http://opi.yahoo.com/online?u=".$focus->yahoo_id."'&m=g&t=2'></a>");
$xtpl->assign("ASSISTANT", $focus->assistant);
$xtpl->assign("ASSISTANT_PHONE", $focus->assistant_phone);
if ($focus->email_opt_out == 'on') $xtpl->assign("EMAIL_OPT_OUT", "checked");
$xtpl->assign("PRIMARY_ADDRESS_STREET", $focus->primary_address_street);
$xtpl->assign("PRIMARY_ADDRESS_CITY", $focus->primary_address_city);
$xtpl->assign("PRIMARY_ADDRESS_STATE", $focus->primary_address_state);
$xtpl->assign("PRIMARY_ADDRESS_POSTALCODE", $focus->primary_address_postalcode);
$xtpl->assign("PRIMARY_ADDRESS_COUNTRY", $focus->primary_address_country);
$xtpl->assign("ALT_ADDRESS_STREET", $focus->alt_address_street);
$xtpl->assign("ALT_ADDRESS_CITY", $focus->alt_address_city);
$xtpl->assign("ALT_ADDRESS_STATE", $focus->alt_address_state);
$xtpl->assign("ALT_ADDRESS_POSTALCODE", $focus->alt_address_postalcode);
$xtpl->assign("ALT_ADDRESS_COUNTRY", $focus->alt_address_country);
$xtpl->assign("DESCRIPTION", nl2br($focus->description));
$xtpl->assign("DATE_MODIFIED", substr($focus->date_modified,0,16));
$xtpl->assign("DATE_ENTERED", substr($focus->date_entered,0,16));
//Assigning Custom Field Values
$custfld = CustomFieldDetailView($focus->id, "Contacts", "contactcf", "contactid");
$xtpl->assign("CUSTOMFIELD", $custfld);

if($entityDel)
	{
		$xtpl->assign("DELETEBUTTON","<td><input title=\"$app_strings[LBL_DELETE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_DELETE_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='Contacts'; this.form.return_action.value='ListView'; this.form.action.value='Delete'; return confirm('$app_strings[NTC_DELETE_CONFIRMATION]')\" type=\"submit\" name=\"Delete\" value=\"$app_strings[LBL_DELETE_BUTTON_LABEL]\"></td>");
	}

$xtpl->assign("SENDMAILBUTTON","<td><input title=\"$app_strings[LBL_SENDMAIL_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_SENDMAIL_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.return_module.value='Emails'; this.form.module.value='Emails';this.form.email_directing_module.value='contacts';this.form.return_action.value='DetailView';this.form.action.value='EditView';\" type=\"submit\" name=\"SendMail\" value=\"$app_strings[LBL_SENDMAIL_BUTTON_LABEL]\"></td>");

//$browser = getenv("HTTP_USER_AGENT");
//$pos1 = strrpos($testString,'Windows');
//$local=explode(';',$browser);
//$test=strrpos($local[2],"Windows");
//if($test == true)
{
	$xtpl->assign("MERGEBUTTON","<input title=\"$app_strings[LBL_MERGE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_MERGE_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.action.value='Merge';\" type=\"submit\" name=\"Merge\" value=\" $app_strings[LBL_MERGE_BUTTON_LABEL]\"></td>");
}
require_once('modules/Users/UserInfoUtil.php');
$wordTemplateResult = fetchWordTemplateList("Contacts");
$tempCount = mysql_num_rows($wordTemplateResult);
$tempVal = mysql_fetch_array($wordTemplateResult);
for($templateCount=0;$templateCount<$tempCount;$templateCount++)
{
$optionString .="<option value=\"".$tempVal["filename"]."\">" .$tempVal["filename"] ."</option>";
$tempVal = mysql_fetch_array($wordTemplateResult);
}
$xtpl->assign("WORDTEMPLATEOPTIONS","<td align=right>&nbsp;&nbsp;Select template to Mail Merge:<select name=\"mergefile\">".$optionString."</select>");

$xtpl->parse("main");
$xtpl->out("main");

echo "<BR>\n";

// Now get the list of direct reports that match this one.
$focus_list = & $focus->get_direct_reports();

include('modules/Contacts/SubPanelViewContactsAndUsers.php');
$SubPanel = new SubPanelViewContactsAndUsers();
$SubPanel->setFocus($focus);
$SubPanel->setContactsList($focus_list);
$SubPanel->setHideUsers(true);
$SubPanel->ProcessSubPanelListView( 'modules/Contacts/SubPanelViewDirectReport.html',$mod_strings, $action);

echo "<BR>\n";

// Now get the list of opportunities that match this one.
$focus_list = & $focus->get_opportunities();

include('modules/Opportunities/SubPanelView.php');

echo "<BR>\n";

// Now get the list of cases that match this one.
//$focus_list = & $focus->get_cases();

//include('modules/Cases/SubPanelView.php');

//echo "<BR>\n";

// Now get the list of activities that match this contact.
$focus_tasks_list = & $focus->get_tasks();
$focus_meetings_list = & $focus->get_meetings();
$focus_calls_list = & $focus->get_calls();
$focus_emails_list = & $focus->get_emails();
$focus_notes_list = & $focus->get_notes();

include('modules/Activities/SubPanelView.php');

//echo "</td></tr>\n";
echo "<BR>\n";
echo "<BR>\n";

require_once('include/RelatedTicketListUtil.php');
$list = getTicketList($focus->id, "Contacts", $image_path,$theme);
echo $list;

?>
