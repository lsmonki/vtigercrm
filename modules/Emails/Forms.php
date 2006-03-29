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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Emails/Forms.php,v 1.4 2005/04/19 16:48:31 ray Exp $
 * Description:  Contains a variety of utility functions used to display UI
 * components such as form headers and footers.  Intended to be modified on a per
 * theme basis.
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

require_once('include/utils/utils.php'); //new

function get_validate_record_js () 
{
	global $mod_strings;
	global $app_strings;

	$lbl_subject = $mod_strings['LBL_LIST_SUBJECT'];
	$lbl_date = $mod_strings['LBL_LIST_DATE'];
	$lbl_time = $mod_strings['LBL_LIST_TIME'];
	$err_missing_required_fields = $app_strings['ERR_MISSING_REQUIRED_FIELDS'];
	$err_invalid_email_address = $app_strings['ERR_INVALID_EMAIL_ADDRESS'];
	$err_invalid_date_format = $app_strings['ERR_INVALID_DATE_FORMAT'];
	$err_invalid_month = $app_strings['ERR_INVALID_MONTH'];
	$err_invalid_day = $app_strings['ERR_INVALID_DAY'];
	$err_invalid_year = $app_strings['ERR_INVALID_YEAR'];
	$err_invalid_date = $app_strings['ERR_INVALID_DATE'];
	$err_invalid_time = $app_strings['ERR_INVALID_TIME'];

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

		function isInteger(s)
		{
			var i;
			for (i = 0; i < s.length; i++)
			{
				// Check that current character is number.
				var c = s.charAt(i);
				if (((c < "0") || (c > "9"))) return false;
			}
			// All characters are numbers.
			return true;
		}

		function stripCharsInBag(s, bag)
		{
			var i;
			var returnString = "";
			// Search through string's characters one by one.
			// If character is not in bag, append to returnString.
			for (i = 0; i < s.length; i++)
			{
				var c = s.charAt(i);
				if (bag.indexOf(c) == -1) returnString += c;
			}
			return returnString;
		}

		function daysInFebruary (year)
		{
			// February has 29 days in any year evenly divisible by four,
			// EXCEPT for centurial years which are not also divisible by 400.
			return (((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0))) ? 29 : 28 );
		}
		function DaysArray(n) 
		{
			for (var i = 1; i <= n; i++) 
			{
				this[i] = 31
				if (i==4 || i==6 || i==9 || i==11) {this[i] = 30}
				if (i==2) {this[i] = 29}
			}
			return this
		}

		function isDate(dtStr)
		{
			var daysInMonth = DaysArray(12)
			var pos1=dtStr.indexOf(dtCh)
			var pos2=dtStr.indexOf(dtCh,pos1+1)
			var strYear=dtStr.substring(0,pos1)
			var strMonth=dtStr.substring(pos1+1,pos2)
			var strDay=dtStr.substring(pos2+1)
			strYr=strYear
			if (strDay.charAt(0)=="0" && strDay.length>1) strDay=strDay.substring(1)
			if (strMonth.charAt(0)=="0" && strMonth.length>1) strMonth=strMonth.substring(1)
			for (var i = 1; i <= 3; i++) 
			{
				if (strYr.charAt(0)=="0" && strYr.length>1) strYr=strYr.substring(1)
			}
			month=parseInt(strMonth)
			day=parseInt(strDay)
			year=parseInt(strYr)
			if (pos1==-1 || pos2==-1)
			{
				alert("$err_invalid_date_format")
					return false
			}
			if (strMonth.length<1 || month<1 || month>12)
			{
				alert("$err_invalid_month")
				return false
			}
			if (strDay.length<1 || day<1 || day>31 || (month==2 && day>daysInFebruary(year)) || day > daysInMonth[month])
			{
				alert("$err_invalid_day")
				return false
			}
			if (strYear.length != 4 || year==0 || year<minYear || year>maxYear)
			{
				alert("$err_invalid_year")
				return false
			}
			if (dtStr.indexOf(dtCh,pos2+1)!=-1 || isInteger(stripCharsInBag(dtStr, dtCh))==false)
			{
				alert("$err_invalid_date")
				return false
			}
			return true
		}

		function isTime(timeStr)
		{
			//time must be in the 24:00 format
			if (timeStr.length != 5) { thetimeStr = '0'+timeStr }
			else { thetimeStr = timeStr }
			var strHour=thetimeStr.substring(0,2)
			var strMin=thetimeStr.substring(3,5)
			var strTime=strHour+strMin
			var delimiter=thetimeStr.substring(2,3)
			if (strHour>24 || strMin>60  || delimiter!=':' || strTime>2400)
			{
				alert("$err_invalid_time")
				return false
			}

			return true
		}

		function verify_data(form) 
		{
			var isError = false;
			var errorMessage = "";
			if (trim(form.subject.value) == "") 
			{
				isError = true;
				errorMessage += "\\n$lbl_subject";
			}
			if (form.date_start.value==false) 
			{
				isError = true;
				errorMessage += "\\n$lbl_date";
			}
			else if (isDate(form.date_start.value) == false) 
			{
				return false;
			}
			if (isTime(form.time_start.value)==false) 
			{
				isError = true;
				errorMessage += "\\n$lbl_time";
			}
			// Here we decide whether to submit the form.
			if (isError == true) 
			{
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
function get_new_record_form () 
{
	global $mod_strings;
	global $app_strings;
	global $app_list_strings;
	global $current_user;
	global $theme;
	global $adb;//for dynamic quickcreateform construction


	// Unimplemented until jscalendar language files are fixed
	// global $current_language;
	// global $default_language;
	// global $cal_codes;

	$lbl_required_symbol = $app_strings['LBL_REQUIRED_SYMBOL'];
	$lbl_subject = $mod_strings['LBL_SUBJECT'];
	$lbl_date = $mod_strings['LBL_DATE'];
	$lbl_time = $mod_strings['LBL_TIME'];
	$ntc_date_format = $app_strings['NTC_DATE_FORMAT'];
	$ntc_time_format = $app_strings['NTC_TIME_FORMAT'];
	$lbl_save_button_title = $app_strings['LBL_SAVE_BUTTON_TITLE'];
	$lbl_save_button_key = $app_strings['LBL_SAVE_BUTTON_KEY'];
	$lbl_save_button_label = $app_strings['LBL_SAVE_BUTTON_LABEL'];
	$default_parent_type= $app_list_strings['record_type_default_key'];
	$default_date_start = date('Y-m-d');
	$default_time_start = date('H:i');
	$user_id = $current_user->id;
	// Unimplemented until jscalendar language files are fixed
	// $cal_lang = (empty($cal_codes[$current_language])) ? $cal_codes[$default_language] : $cal_codes[$current_language];
	$cal_lang = "en";
	$cal_dateformat = parse_calendardate($app_strings['NTC_DATE_FORMAT']);

	$qcreate_form = get_left_form_header($mod_strings['LBL_NEW_FORM_TITLE']);


	$qcreate_get_field="select * from field where tabid=10 and quickcreate=0 order by quickcreatesequence";
	$qcreate_get_result=$adb->query($qcreate_get_field);
	$qcreate_get_noofrows=$adb->num_rows($qcreate_get_result);

	$fieldName_array = Array();//for validation 

	$qcreate_form.='<link rel="stylesheet" type="text/css" media="all" href="jscalendar/calendar-win2k-cold-1.css">';
	$qcreate_form.='<script type="text/javascript" src="jscalendar/calendar.js"></script>';
	$qcreate_form.='<script type="text/javascript" src="jscalendar/lang/calendar-'.$cal_lang.'.js"></script>';
	$qcreate_form.='<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>';
	$qcreate_form.='<form name="EmailSave" onSubmit="return formValidate()" method="POST" action="index.php">';
	$qcreate_form.='<input type="hidden" name="module" value="Emails">';
	$qcreate_form.='<input type="hidden" name="record" value="">';
	$qcreate_form.='<input type="hidden" name="action" value="Save">';
	$qcreate_form.='<input type="hidden" name="parent_type" value="'.$default_parent_type.'">';
	$qcreate_form.='<input type="hidden" name="assigned_user_id" value="'.$user_id.'">';


	$qcreate_form.='<table>';

	for($j=0;$j<$qcreate_get_noofrows;$j++)
	{
		$qcreate_form.='<tr>';
		$fieldlabel=$adb->query_result($qcreate_get_result,$j,'fieldlabel');
		$uitype=$adb->query_result($qcreate_get_result,$j,'uitype');
		$tabid=$adb->query_result($qcreate_get_result,$j,'tabid');

		$fieldname=$adb->query_result($qcreate_get_result,$j,'fieldname');//for validation
		$typeofdata=$adb->query_result($qcreate_get_result,$j,'typeofdata');//for validation
		$qcreate_form .= get_quickcreate_form($fieldlabel,$uitype,$fieldname,$tabid);


		//to get validationdata
		//start
		$fldLabel_array = Array();
		$fldLabel_array[$fieldlabel] = $typeofdata;
		$fieldName_array['QCK_'.$fieldname] = $fldLabel_array;

		//end

		$qcreate_form.='</tr>';

	}

	//for validation
	$validationData = $fieldName_array;
	$fieldName = '';
	$fieldLabel = '';
	$fldDataType = '';

	$rows = count($validationData);
	foreach($validationData as $fldName => $fldLabel_array)
	{
		if($fieldName == '')
		{
			$fieldName="'".$fldName."'";
		}
		else
		{
			$fieldName .= ",'".$fldName ."'";
		}
		foreach($fldLabel_array as $fldLabel => $datatype)
		{
			if($fieldLabel == '')
			{

				$fieldLabel = "'".$fldLabel ."'";
			}		
			else
			{
				$fieldLabel .= ",'".$fldLabel ."'";
			}
			if($fldDataType == '')
			{
				$fldDataType = "'".$datatype ."'";
			}
			else
			{
				$fldDataType .= ",'".$datatype ."'";
			}
		}
	}

	$qcreate_form.='</table>';

	$qcreate_form.='<input title="'.$lbl_save_button_title.'" accessKey="'.$lbl_save_button_key.'" class="button" type="submit" name="button" value="'.$lbl_save_button_label.'" >';
	$qcreate_form.='</form>';
	$qcreate_form.='
	<script type="text/javascript">
		Calendar.setup ({
inputField : "QCK_date_start", ifFormat : "'.$cal_dateformat.'", showsTime : false, button : "jscal_trigger", singleClick : true, step : 1
});

		var fieldname = new Array('.$fieldName.')
		var fieldlabel = new Array('.$fieldLabel.')
		var fielddatatype = new Array('.$fldDataType.')

	</script>';

	$qcreate_form .= get_left_form_footer();
	return $qcreate_form;

}

?>
