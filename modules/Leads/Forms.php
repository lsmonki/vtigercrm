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
/**
 * Create javascript to validate the data entered into a record.
 */
function get_validate_record_js () {
global $mod_strings;
global $app_strings;

$lbl_last_name = $mod_strings['LBL_LIST_LAST_NAME'];
$lbl_company = $mod_strings['LBL_LIST_COMPANY'];
$err_missing_required_fields = $app_strings['ERR_MISSING_REQUIRED_FIELDS'];
$err_invalid_email_address = $app_strings['ERR_INVALID_EMAIL_ADDRESS'];

$the_script  = <<<EOQ

<script type="text/javascript" language="Javascript">
<!--  to hide script contents from old browsers

function verify_data(form) {
	var isError = false;
	var errorMessage = "";
	if (form.lastname.value == "") {
		isError = true;
		errorMessage += "\\n$lbl_last_name";
	}
	if (form.company.value == "") {
		isError = true;
		errorMessage += "\\n$lbl_company";
	}

	if (isError == true) {
		alert("$err_missing_required_fields" + errorMessage);
		return false;
	}
	if (form.email.value != "" && !/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(form.email1.value)) {
		alert('"' + form.email.value + '" $err_invalid_email_address');
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
 */

function get_new_record_form () {
global $mod_strings;
global $app_strings;
global $current_user;

$lbl_required_symbol = $app_strings['LBL_REQUIRED_SYMBOL'];
$lbl_first_name = $mod_strings['LBL_FIRST_NAME'];
$lbl_last_name = $mod_strings['LBL_LAST_NAME'];
$lbl_company = $mod_strings['LBL_COMPANY'];
$lbl_phone = $mod_strings['LBL_PHONE'];
$lbl_email = $mod_strings['LBL_EMAIL'];
$lbl_save_button_title = $app_strings['LBL_SAVE_BUTTON_TITLE'];
$lbl_save_button_key = $app_strings['LBL_SAVE_BUTTON_KEY'];
$lbl_save_button_label = $app_strings['LBL_SAVE_BUTTON_LABEL'];
$user_id = $current_user->id;

$the_form = get_left_form_header($mod_strings['LBL_NEW_FORM_TITLE']);
$the_form .= <<<EOQ

		<form name="LeadSave" onSubmit="return verify_data(LeadSave)" method="POST" action="index.php">
			<input type="hidden" name="module" value="Leads">
			<input type="hidden" name="record" value="">			
			<input type="hidden" name="assigned_user_id" value='${user_id}'>
			<input type="hidden" name="email2" value="">			
			<input type="hidden" name="action" value="Save">
		$lbl_first_name<br>
		<input name='firstname' type="text" value=""><br>
		<FONT class="required">$lbl_required_symbol</FONT>$lbl_last_name<br>
		<input name='lastname' type="text" value=""><br>
		<FONT class="required">$lbl_required_symbol</FONT>$lbl_company<br>
		<input name='company' type="text" value=""><br>
		$lbl_phone<br>
		<input name='phone' type="text" value=""><br>
		$lbl_email<br>
		<input name='email' type="text" value=""><br><br>
		<input title="$lbl_save_button_title" accessKey="$lbl_save_button_key" class="button" type="submit" name="button" value="  $lbl_save_button_label  " >
		</form>
		
EOQ;
$the_form .= get_left_form_footer();
$the_form .= get_validate_record_js();

return $the_form;
}


?>
