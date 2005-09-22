<?php
//Form for quick create should be done here

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
 * $Header: /advent/projects/wesat/vtiger_crm/vtigercrm/modules/HelpDesk/Forms.php,v 1.9 2005/03/25 10:21:31 rajeshkannan Exp $
 * Description:  Contains a variety of utility functions specific to this module.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

/**
 * Create javascript to validate the data entered into a record.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
require_once('include/ComboUtil.php');
function get_validate_record_js () {
global $mod_strings;
global $app_strings;

$lbl_subject = $mod_strings['LBL_TICKET_TITLE'];
$err_missing_required_fields = $app_strings['ERR_MISSING_REQUIRED_FIELDS'];

$the_script  = <<<EOQ

<script type="text/javascript" language="Javascript">
<!--  to hide script contents from old browsers

function trim(s) {
	while (s.substring(0,1) == " ") {
		s = s.substring(1, s.length);
	}
	while (s.substring(s.length-1, s.length) == ' ') {
		s = s.substring(0,s.length-1);
	}

	return s;
}

function verify_data(form) {
	var isError = false;
	var errorMessage = "";
	if (trim(form.ticket_title.value) == "") {
		isError = true;
		errorMessage += "\\n$lbl_subject";
	}
	// Here we decide whether to submit the form.
	if (isError == true) {
		alert("$err_missing_required_fields" + errorMessage);
		return false;
	}
	return true;
}
// end hiding contents from old browsers  -->
</script>

EOQ;

return $the_script;
}

/**
 * Create HTML form to enter a new record with the minimum necessary fields.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_new_record_form () {
global $mod_strings;
global $app_strings;
global $app_list_strings;
global $current_user;

$lbl_required_symbol = $app_strings['LBL_REQUIRED_SYMBOL'];
$lbl_ticket_title = $mod_strings['LBL_TICKET_TITLE'];
$lbl_ticket_description = $mod_strings['LBL_TICKET_DESCRIPTION'];
$lbl_ticket_category = $mod_strings['LBL_TICKET_CATEGORY'];
$lbl_ticket_priority = $mod_strings['LBL_TICKET_PRIORITY'];
$lbl_save_button_title = $app_strings['LBL_SAVE_BUTTON_TITLE'];
$lbl_save_button_key = $app_strings['LBL_SAVE_BUTTON_KEY'];
$lbl_save_button_label = $app_strings['LBL_SAVE_BUTTON_LABEL'];
$default_parent_type= $app_list_strings['record_type_default_key'];

$comboFieldNames = Array('ticketpriorities'=>'ticketpriorities_dom');
$comboFieldArray = getComboArray($comboFieldNames);
$user_id = $current_user->id;


$the_form = get_left_form_header($mod_strings['LBL_NEW_FORM_TITLE']);
$the_form .= <<<EOQ

		<form name="TicketSave" onSubmit="return verify_data(TicketSave)" method="POST" action="index.php">
			<input type="hidden" name="module" value="HelpDesk">
			<input type="hidden" name="return_module" value="HelpDesk">
			<input type="hidden" name="record" value="">
			<input type="hidden" name="parent_type" value="${default_parent_type}">
			<input type="hidden" name="assigned_user_id" value='${user_id}'>
			<input type="hidden" name="action" value="Save">
			<input type="hidden" name="return_action" value="DetailView">
		<FONT class="required">$lbl_required_symbol</FONT>
               $lbl_ticket_title<br><input name='ticket_title' maxlength='255' type="text" value=""><br>
               $lbl_ticket_description<br><input name='description' maxlength='255' type="text" value=""><br>
              $lbl_ticket_priority<br><select name='ticketpriorities'>
EOQ;


$the_form .= get_select_options_with_id($comboFieldArray['ticketpriorities_dom'], "");
$the_form .= <<<EOQ
		</select><br><br>
 <input title="$lbl_save_button_title" accessKey="$lbl_save_button_key" class="button" type="submit" name="button" value="  $lbl_save_button_label  " >
		</form>
EOQ;
$the_form .= get_left_form_footer();
$the_form .= get_validate_record_js();


return $the_form;
}
?>
