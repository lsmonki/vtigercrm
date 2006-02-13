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
 * $Header: /cvsroot/vtigercrm/vtiger_crm/modules/Orders/Forms.php,v 1.4 2005/07/16 09:21:02 crouchingtiger Exp $
 * Description:  Contains a variety of utility functions specific to this module.
 ********************************************************************************/

/**
 * Create javascript to validate the data entered into a record.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_validate_record_js () {
global $mod_strings;
global $app_strings;

$lbl_account_name = $mod_strings['LBL_LIST_ACCOUNT_NAME'];
$err_missing_required_fields = $app_strings['ERR_MISSING_REQUIRED_FIELDS'];
$err_invalid_email_address = $app_strings['ERR_INVALID_EMAIL_ADDRESS'];
$err_invalid_date_format = $app_strings['ERR_INVALID_DATE_FORMAT'];
$err_invalid_month = $app_strings['ERR_INVALID_MONTH'];
$err_invalid_day = $app_strings['ERR_INVALID_DAY'];
$err_invalid_year = $app_strings['ERR_INVALID_YEAR'];
$err_invalid_date = $app_strings['ERR_INVALID_DATE'];

$the_script  = <<<EOQ

<script type="text/javascript" language="Javascript">
<!--  to hide script contents from old browsers
/**
 * DHTML date validation script. Courtesy of SmartWebby.com (http://www.smartwebby.com/dhtml/)
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
// Declaring valid date character, minimum year and maximum year
var dtCh= "-";
var minYear=1900;
var maxYear=2100;

function isInteger(s){
	var i;
    for (i = 0; i < s.length; i++){
        // Check that current character is number.
        var c = s.charAt(i);
        if (((c < "0") || (c > "9"))) return false;
    }
    // All characters are numbers.
    return true;
}

function stripCharsInBag(s, bag){
	var i;
    var returnString = "";
    // Search through string's characters one by one.
    // If character is not in bag, append to returnString.
    for (i = 0; i < s.length; i++){
        var c = s.charAt(i);
        if (bag.indexOf(c) == -1) returnString += c;
    }
    return returnString;
}

function daysInFebruary (year){
	// February has 29 days in any year evenly divisible by four,
    // EXCEPT for centurial years which are not also divisible by 400.
    return (((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0))) ? 29 : 28 );
}
function DaysArray(n) {
	for (var i = 1; i <= n; i++) {
		this[i] = 31
		if (i==4 || i==6 || i==9 || i==11) {this[i] = 30}
		if (i==2) {this[i] = 29}
   }
   return this
}

function isDate(dtStr){
	var daysInMonth = DaysArray(12)
	var pos1=dtStr.indexOf(dtCh)
	var pos2=dtStr.indexOf(dtCh,pos1+1)
	var strYear=dtStr.substring(0,pos1)
	var strMonth=dtStr.substring(pos1+1,pos2)
	var strDay=dtStr.substring(pos2+1)
	strYr=strYear
	if (strDay.charAt(0)=="0" && strDay.length>1) strDay=strDay.substring(1)
	if (strMonth.charAt(0)=="0" && strMonth.length>1) strMonth=strMonth.substring(1)
	for (var i = 1; i <= 3; i++) {
		if (strYr.charAt(0)=="0" && strYr.length>1) strYr=strYr.substring(1)
	}
	month=parseInt(strMonth)
	day=parseInt(strDay)
	year=parseInt(strYr)
	if (pos1==-1 || pos2==-1){
		alert("$err_invalid_date_format")
		return false
	}
	if (strMonth.length<1 || month<1 || month>12){
		alert("$err_invalid_month")
		return false
	}
	if (strDay.length<1 || day<1 || day>31 || (month==2 && day>daysInFebruary(year)) || day > daysInMonth[month]){
		alert("$err_invalid_day")
		return false
	}
	if (strYear.length != 4 || year==0 || year<minYear || year>maxYear){
		alert("$err_invalid_year")
		return false
	}
	if (dtStr.indexOf(dtCh,pos2+1)!=-1 || isInteger(stripCharsInBag(dtStr, dtCh))==false){
		alert("$err_invalid_date")
		return false
	}
return true
}

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
	if (trim(form.accountname.value) == "") {
		isError = true;
		errorMessage += "\\n$lbl_account_name";
	}
	// Here we decide whether to submit the form.
	if (isError == true) {
		alert("$err_missing_required_fields" + errorMessage);
		return false;
	}
	if (trim(form.email1.value) != "" && !/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(form.email1.value)) {
		alert('"' + form.email1.value + '" $err_invalid_email_address');
		return false;
	}
	if (trim(form.email2.value) != "" && !/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(form.email2.value)) {
		alert('"' + form.email2.value + '" $err_invalid_email_address');
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
/*
function get_new_record_form () {
global $mod_strings;
global $app_strings;
global $current_user;

$lbl_required_symbol = $app_strings['LBL_REQUIRED_SYMBOL'];
$lbl_quote_subject = $mod_strings['Subject'];
$lbl_quote_name = $mod_strings['Quote Name'];
$lbl_vendor_name = $mod_strings['Vendor Name'];
$lbl_acct_name = $mod_strings['Account Name'];
$lbl_po_name = $mod_strings['Purchase Order'];
$lbl_prod_name = $mod_strings['Product Name'];
$lbl_type = $mod_strings['Type'];
$lbl_save_button_title = $app_strings['LBL_SAVE_BUTTON_TITLE'];
$lbl_save_button_key = $app_strings['LBL_SAVE_BUTTON_KEY'];
$lbl_save_button_label = $app_strings['LBL_SAVE_BUTTON_LABEL'];
$lbl_change_button_label = $app_strings['LBL_CHANGE_BUTTON_LABEL'];
$user_id = $current_user->id;

$the_form = get_left_form_header($mod_strings['LBL_NEW_FORM_TITLE']);
$the_form .= <<<EOQ
		<form name="EditView" onSubmit="return verify_data(EditView)" method="POST" action="index.php">
			<input type="hidden" name="module" value="Orders">
			<input type="hidden" name="assigned_user_id" value='${user_id}'>
			<input type="hidden" name="action" value="Save">
		<FONT class="required">$lbl_required_symbol</FONT>$lbl_quote_subject<br>
		<input name='subject' type="text" value=""><br>
		$lbl_vendor_name<br>
		<input name="vendor_name" readonly type="text"><input name="vendor_id" type="hidden">&nbsp;<input title="Change" accessKey="" type="button" class="button" value="$lbl_change_button_label" name="Button" LANGUAGE=javascript onclick='return window.open("index.php?module=Products&action=VendorPopup&html=Popup_picker&popuptype=specific&form=EditView","test","width=600,height=400,resizable=1,scrollbars=1");'><br>
                $lbl_acct_name<br>
		<input name="account_id" type="hidden"><input name="account_name" readonly type="text"> <input title="Change [Alt+G]" accessKey="G" type="button" class="button" value="$lbl_change_button_label" name="Button" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&html=Popup_picker&form=HelpDeskEditView&popuptype=specific","test","width=600,height=400,resizable=1,scrollbars=1,top=150,left=200");'><br><br>
		
		<input title="$lbl_save_button_title" accessKey="$lbl_save_button_key" class="button" type="submit" name="button" value="  $lbl_save_button_label  " >
		</form>

EOQ;
$the_form .= get_left_form_footer();

$the_form .= get_left_form_header($mod_strings['LBL_NEW_FORM_SO_TITLE']);
$the_form .= <<<EOQ
		<form name="EditView1" onSubmit="return verify_data(EditView1)" method="POST" action="index.php">
			<input type="hidden" name="module" value="Orders">
			<input type="hidden" name="assigned_user_id" value='${user_id}'>
			<input type="hidden" name="action" value="SaveSalesOrder">
		<FONT class="required">$lbl_required_symbol</FONT>$lbl_quote_subject<br>
		<input name='subject' type="text" value=""><br>
		$lbl_quote_name<br>
		<input name="quote_name" readonly type="text"><input name="quote_id" type="hidden">&nbsp;<input title="Change" accessKey="" type="button" class="button" value="$lbl_change_button_label" name="Button" LANGUAGE=javascript onclick='return window.open("index.php?module=Quotes&action=Popup&html=Popup_picker&popuptype=formname_specific&form=EditView1","test","width=600,height=400,resizable=1,scrollbars=1");'><br>
                $lbl_acct_name<br>
		<input name="account_id" type="hidden"><input name="account_name" readonly type="text"> <input title="Change [Alt+G]" accessKey="G" type="button" class="button" value="$lbl_change_button_label" name="Button" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&html=Popup_picker&form=EditView1&popuptype=formname_specific","test","width=600,height=400,resizable=1,scrollbars=1,top=150,left=200");'><br><br>
		
		<input title="$lbl_save_button_title" accessKey="$lbl_save_button_key" class="button" type="submit" name="button" value="  $lbl_save_button_label  " >
		</form>

EOQ;
$the_form .= get_left_form_footer();

$the_form .= get_validate_record_js();

return $the_form;
}
*/

?>
