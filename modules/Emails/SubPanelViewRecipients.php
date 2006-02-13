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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Emails/SubPanelViewRecipients.php,v 1.2 2004/10/06 09:02:05 jack Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");

global $currentModule;

global $mod_strings;
global $app_strings;

global $theme;
global $focus;
global $action;

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

// focus_list is the means of passing data to a SubPanelView.
global $focus_list;

$button  = "<table cellspacing='0' cellpadding='1' border='0'><form border='0' action='index.php' method='post' name='form' id='form'>\n";
$button .= "<input type='hidden' name='module' value='Contacts'>\n";
if ($currentModule == 'Accounts') $button .= "<input type='hidden' name='account_id' value='$focus->id'>\n<input type='hidden' name='account_name' value='$focus->name'>\n";
$button .= "<input type='hidden' name='return_module' value='".$currentModule."'>\n";
$button .= "<input type='hidden' name='return_action' value='".$action."'>\n";
$button .= "<input type='hidden' name='return_id' value='".$focus->id."'>\n";
$button .= "<input type='hidden' name='action'>\n";
$button .= "<tr><td>&nbsp;</td>";
if ($focus->parent_type == "Accounts") $button .= "<td><input title='".$app_strings['LBL_SELECT_CONTACT_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_SELECT_CONTACT_BUTTON_KEY']."' type='button' class='button' value='".$app_strings['LBL_SELECT_CONTACT_BUTTON_LABEL']."' name='button' LANGUAGE=javascript onclick='window.open(\"index.php?module=Contacts&action=Popup&html=Popup_picker&form=DetailView&form_submit=true&query=true&account_id=$focus->parent_id&account_name=".urlencode($focus->parent_name)."\",\"new\",\"width=600,height=400,resizable=1,scrollbars=1\");'></td>\n";
else $button .= "<td><input title='".$app_strings['LBL_SELECT_CONTACT_BUTTON_TITLE']."' accessyKey='".$app_strings['LBL_SELECT_CONTACT_BUTTON_KEY']."' type='button' class='button' value='".$app_strings['LBL_SELECT_CONTACT_BUTTON_LABEL']."' name='button' LANGUAGE=javascript onclick='window.open(\"index.php?module=Contacts&action=Popup&html=Popup_picker&form=DetailView&form_submit=true\",\"new\",\"width=600,height=400,resizable=1,scrollbars=1\");'></td>\n";
$button .= "<td><input title='".$app_strings['LBL_SELECT_USER_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_SELECT_USER_BUTTON_KEY']."' type='button' class='button' value='".$app_strings['LBL_SELECT_USER_BUTTON_LABEL']."' name='button' LANGUAGE=javascript onclick='window.open(\"index.php?module=Users&action=Popup&html=Popup_picker&form=DetailView&form_submit=true\",\"new\",\"width=600,height=400,resizable=1,scrollbars=1\");'></td>\n";
$button .= "</tr></form></table>\n";

// Stick the form header out there.
echo get_form_header($mod_strings['LBL_INVITEE'], $button, false);
$xtpl=new XTemplate ('modules/Emails/SubPanelViewRecipients.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("RETURN_URL", "&return_module=$currentModule&return_action=DetailView&return_id=$focus->id");
$xtpl->assign("EMAIL_ID", $focus->id);

$oddRow = true;
foreach($focus_users_list as $user)
{
	$user_fields = array(
		'USER_NAME' => $user->user_name,
		'FULL_NAME' => $user->first_name." ".$user->last_name,
		'ID' => $user->id,
		'EMAIL' => $user->email1,
		'PHONE_WORK' => $user->phone_work
	);
	
	$xtpl->assign("USER", $user_fields);
	
	if($oddRow)
    {
        //todo move to themes
		$xtpl->assign("ROW_COLOR", 'oddListRow');
    }
    else
    {
        //todo move to themes
		$xtpl->assign("ROW_COLOR", 'evenListRow');
    }
    $oddRow = !$oddRow;

	$xtpl->parse("users.row");
// Put the rows in.
}

$xtpl->parse("users");
$xtpl->out("users");

$oddRow = true;
foreach($focus_contacts_list as $contact)
{
	$contact_fields = array(
		'YAHOO_ID' => $contact->yahoo_id,
		'FIRST_NAME' => $contact->first_name,
		'LAST_NAME' => $contact->last_name,
		'ACCOUNT_NAME' => $contact->account_name,
		'ID' => $contact->id,
		'EMAIL' => $contact->email1,
		'PHONE_WORK' => $contact->phone_work
	);
	
	$xtpl->assign("CONTACT", $contact_fields);
	
	if($oddRow)
    {
        //todo move to themes
		$xtpl->assign("ROW_COLOR", 'oddListRow');
    }
    else
    {
        //todo move to themes
		$xtpl->assign("ROW_COLOR", 'evenListRow');
    }
    $oddRow = !$oddRow;

	// If there is a YMId, parse that row
	if(isset($contact->yahoo_id) && $contact->yahoo_id != '')
		$xtpl->parse("contacts.row.yahoo_id");
	else
		$xtpl->parse("contacts.row.no_yahoo_id");

	$xtpl->parse("contacts.row");
// Put the rows in.
}

$xtpl->parse("contacts");
$xtpl->out("contacts");

// Stick on the form footer
echo get_form_footer();
 
?>
