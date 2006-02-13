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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Contacts/SubPanelViewCase.php,v 1.2 2005/02/15 09:21:32 jack Exp $
 * Description:  TODO: To be written.
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");

global $currentModule;

global $theme;
global $focus;
global $action;
global $app_strings;
//we don't want the parent module's string file, but rather the string file specifc to this subpanel
global $current_language;
$current_module_strings = return_module_language($current_language, 'Contacts');

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

// focus_list is the means of passing data to a SubPanelView.
global $focus_list;

$button  = "<table cellspacing='0' cellpadding='1' border='0'><form border='0' action='index.php' method='post' name='subpanel' id='form'>\n";
$button .= "<input type='hidden' name='module' value='Contacts'>\n";
if ($currentModule == 'Accounts') $button .= "<input type='hidden' name='account_id' value='$focus->id'>\n<input type='hidden' name='account_name' value='$focus->name'>\n";
if ($currentModule == 'Cases') $button .= "<input type='hidden' name='case_id' value='$focus->id'>\n<input type='hidden' name='account_id' value='$focus->account_id'>\n<input type='hidden' name='account_name' value='$focus->account_name'>\n";
$button .= "<input type='hidden' name='return_module' value='".$currentModule."'>\n";
$button .= "<input type='hidden' name='return_action' value='".$action."'>\n";
$button .= "<input type='hidden' name='return_id' value='".$focus->id."'>\n";
$button .= "<input type='hidden' name='action'>\n";
$button .= "<tr><td>&nbsp;</td>";
$button .= "<td><input title='".$app_strings['LBL_NEW_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_NEW_BUTTON_KEY']."' class='button' onclick=\"this.form.action.value='EditView'\" type='submit' name='button' value='  ".$app_strings['LBL_NEW_BUTTON_LABEL']."  '></td>\n";
if ($currentModule == 'Accounts') $button .= "<td><input title=".$app_strings['LBL_SELECT_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_SELECT_BUTTON_KEY']."' type='button' class='button' value=' ".$app_strings['LBL_SELECT_BUTTON_LABEL']." ' name='button' LANGUAGE=javascript onclick='window.open(\"index.php?module=Contacts&action=Popup&html=Popup_picker&form=DetailView&form_submit=true\",\"new\",\"width=600,height=400,resizable=1,scrollbars=1\");'></td>\n";
else $button .= "<td><input title='".$app_strings['LBL_SELECT_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_SELECT_BUTTON_KEY']."' type='button' class='button' value=' ".$app_strings['LBL_SELECT_BUTTON_LABEL']." ' name='button' LANGUAGE=javascript onclick='window.open(\"index.php?module=Contacts&action=Popup&html=Popup_picker&form=DetailView&form_submit=true&query=true&account_id=$focus->account_id&account_name=".urlencode($focus->account_name)."\",\"new\",\"width=600,height=400,resizable=1,scrollbars=1\");'></td>\n";
$button .= "</tr></form></table>\n";

// Stick the form header out there.
echo get_form_header($current_module_strings['LBL_MODULE_NAME'], $button, false);

$xtpl=new XTemplate ('modules/Contacts/SubPanelViewCase.html');
$xtpl->assign("MOD", $current_module_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("RETURN_URL", "&return_module=Cases&return_action=DetailView&return_id=$focus->id");

$oddRow = true;
foreach($focus_list as $contact)
{
	$contact_fields = array(
		'YAHOO_ID' => $contact->yahoo_id,
		'FIRST_NAME' => $contact->first_name,
		'LAST_NAME' => $contact->last_name,
		'ID' => $contact->id,
		'EMAIL' => $contact->email1,
		'PHONE_WORK' => $contact->phone_work,
		'CASE_ROLE' => $contact->case_role,
		'CASE_REL_ID' => $contact->case_rel_id
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
		$xtpl->parse("main.row.yahoo_id");
	else
		$xtpl->parse("main.row.no_yahoo_id");

	$xtpl->parse("main.row");
// Put the rows in.
}

$xtpl->parse("main");
$xtpl->out("main");

// Stick on the form footer
echo get_form_footer();
 
?>