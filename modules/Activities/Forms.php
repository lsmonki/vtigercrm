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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Activities/Forms.php,v 1.7 2005/04/19 16:49:29 ray Exp $
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
require_once('include/ComboUtil.php');
require_once('include/utils.php');
function get_validate_record_js () {
global $mod_strings;
global $app_strings;

$lbl_subject = $mod_strings['LBL_LIST_SUBJECT'];
$lbl_date = $mod_strings['LBL_LIST_DUE_DATE'];
$lbl_time = $mod_strings['LBL_LIST_DUE_TIME'];
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

var fieldname,fieldlabel,fielddatatype;	
function verify_data(form) 
{
	var form_name=form.name;
	if(form_name=='ActivitySave')
        {
                form.due_date.value=form.date_start.value;
                fieldname = new Array('task_subject','jscal_field_date_start');
                fieldlabel = new Array('Subject','Start Date & Time');
                fielddatatype = new Array('V~M','DT~M~task_time_start');
        }
        else
        {
                form.due_date.value=form.date_start.value;
		fieldname = new Array('event_subject','jscal_field_event_date_start','duration_hours')
                fieldlabel = new Array('Subject','Start Date & Time','Duration')
                fielddatatype = new Array('V~M','DT~M~event_time_start','I~M')
        }

	var ret = formValidate();
	return ret;
}
// end hiding contents from old browsers  -->
</script>

EOQ;

return $the_script;
}

/* Commented for RC
/**
 * Create HTML form to enter a new record with the minimum necessary fields.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_new_record_form () {
global $app_strings, $mod_strings, $app_list_strings;
global $current_user;
global $theme;
// Unimplemented until jscalendar language files are fixed
// global $current_language;
// global $default_language;
// global $cal_codes;

$user_id = $current_user->id;
// Unimplemented until jscalendar language files are fixed
// $cal_lang = (empty($cal_codes[$current_language])) ? $cal_codes[$default_language] : $cal_codes[$current_language];
$cal_lang = "en";
$cal_dateformat = parse_calendardate($app_strings['NTC_DATE_FORMAT']);
$value=date('Y-m-d');
$dis_value=getNewDisplayDate();
$curr_time = date('H:i');

$the_form = get_left_form_header($app_strings['LBL_NEW_TASK']);
$the_form .= <<<EOQ

		<link rel="stylesheet" type="text/css" media="all" href="jscalendar/calendar-win2k-cold-1.css">
		<script type="text/javascript" src="jscalendar/calendar.js"></script>
		<script type="text/javascript" src="jscalendar/lang/calendar-{$cal_lang}.js"></script>
		<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
		<form name="ActivitySave" onsubmit="return verify_data(this)" method="POST" action="index.php">
			<input type="hidden" name="module" value="Activities">
			<input type="hidden" name="record" value="">
			<input type="hidden" name="activity_mode" value="Task">
			<input type="hidden" name="assigned_user_id" value="${user_id}">
			<input type="hidden" name="action" value="Save">

			<input type="hidden" name="due_date" value="">

		<FONT class="required">${app_strings['LBL_REQUIRED_SYMBOL']}</FONT>Subject<br>
		<input name='subject' id='task_subject' type="text" value=""><br>
		<FONT class="required">${app_strings['LBL_REQUIRED_SYMBOL']}</FONT>Start Date & Time&nbsp;<br>
		<input name="date_start" id="jscal_field_date_start" type="text" tabindex="2" size="11" maxlength="10" value="{$dis_value}"> <img src="themes/{$theme}/images/calendar.gif" id="jscal_trigger_date_start">&nbsp; 
		<input name="time_start" id='task_time_start' tabindex="1" size="5" maxlength="5" type="text" value="{$curr_time}"><br>
		<font size=1><em old="(yyyy-mm-dd 24:00)">($current_user->date_format 24:00)</em></font><br><br>
		<input title="${app_strings['LBL_SAVE_BUTTON_TITLE']}" accessKey="${app_strings['LBL_SAVE_BUTTON_KEY']}" class="button" type="submit" name="button" value="${app_strings['LBL_SAVE_BUTTON_LABEL']}" >
		</form>
		<script type="text/javascript">
		Calendar.setup ({
			inputField : "jscal_field_date_start", ifFormat : "$cal_dateformat", showsTime : false, button : "jscal_trigger_date_start", singleClick : true, step : 1
		});
		
		</script>
EOQ;

$the_form .= get_left_form_footer();
$the_form .= '<br>';
$comboFieldNames = Array('activitytype'=>'activitytype_dom',
			 'duration_minutes'=>'duration_minutes_dom');
$comboFieldArray = getComboArray($comboFieldNames);
$the_form .= get_left_form_header($app_strings['LBL_NEW_EVENT']);
$the_form .= <<<EOQ

		<form name="EventSave" method="POST" action="index.php" onSubmit="return verify_data(this)">
			<input type="hidden" name="module" value="Activities">
			<input type="hidden" name="record" value="">
			<input type="hidden" name="activity_mode" value="Events">
			<input type="hidden" name="assigned_user_id" value="${user_id}">
			<input type="hidden" name="action" value="Save">

			<input type="hidden" name="due_date" value="">

			
		<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
		<FONT class="required">${app_strings['LBL_REQUIRED_SYMBOL']}</FONT>Subject<br>
		<input name='subject' id='event_subject' type="text" value=""><br>
		<FONT class="required">${app_strings['LBL_REQUIRED_SYMBOL']}</FONT>Start Date & Time&nbsp;<br>
		<input name="date_start" id="jscal_field_event_date_start" type="text" tabindex="2" size="11" maxlength="10" value="{$dis_value}"> <img src="themes/{$theme}/images/calendar.gif" id="jscal_trigger_event_date_start">&nbsp; 
		<input name="time_start" id='event_time_start' tabindex="1" size="5" maxlength="5" type="text" value="{$curr_time}"><br>
		<font size=1><em old="(yyyy-mm-dd 24:00)">($current_user->date_format 24:00)</em></font><br>
		Activity Type<br>
		<select name='activitytype'>
EOQ;

$the_form .= get_select_options_with_id($comboFieldArray['activitytype_dom'], "");
$the_form .= <<<EOQ
                </select><br>
		Duration<br>
		<input name="duration_hours" type="text" size="2" value="1">&nbsp;
		<select name='duration_minutes'>
EOQ;

$the_form .= get_select_options_with_id($comboFieldArray['duration_minutes_dom'], "");
$the_form .= <<<EOQ
                </select>(hours/minutes)<br>	
		<br>
		<input title="${app_strings['LBL_SAVE_BUTTON_TITLE']}" accessKey="${app_strings['LBL_SAVE_BUTTON_KEY']}" class="button" type="submit" name="button" value="${app_strings['LBL_SAVE_BUTTON_LABEL']}" >
		</form>
		<script type="text/javascript">
		Calendar.setup ({
			inputField : "jscal_field_event_date_start", ifFormat : "$cal_dateformat", showsTime : false, button : "jscal_trigger_event_date_start", singleClick : true, step : 1
		});

		
		</script>
EOQ;
$the_form .= get_left_form_footer();
$the_form .= get_validate_record_js();


return $the_form;
}
?>
