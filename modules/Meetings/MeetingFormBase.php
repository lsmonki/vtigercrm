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
 * $Header: /cvsroot/vtigercrm/vtiger_crm/modules/Meetings/MeetingFormBase.php,v 1.10 2005/01/17 05:11:30 saraj Exp $
 * Description:  Base Form For Meetings
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
 
 
class MeetingFormBase{
	function getFormBody($prefix, $mod=''){
			if(!empty($mod)){
	global $current_language;
	$mod_strings = return_module_language($current_language, $mod);
}else global $mod_strings;
			global $app_strings;
			global $app_list_strings;
			global $current_user;
			global $theme;
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
			$user_id = $current_user->id;
			$default_status = $mod_strings['LBL_DEFAULT_STATUS'];
			$default_parent_type= $app_list_strings['record_type_default_key'];
			$default_date_start = date('Y-m-d');
			$default_time_start = date('H:i');
			// Unimplemented until jscalendar language files are fixed
			// $cal_lang = (empty($cal_codes[$current_language])) ? $cal_codes[$default_language] : $cal_codes[$current_language];
			$cal_lang = "en";
			$cal_dateformat = parse_calendardate($app_strings['NTC_DATE_FORMAT']);
			$form = <<<EOF
					<link rel="stylesheet" type="text/css" media="all" href="jscalendar/calendar-win2k-cold-1.css">
					<script type="text/javascript" src="jscalendar/calendar.js"></script>
					<script type="text/javascript" src="jscalendar/lang/calendar-{$cal_lang}.js"></script>
					<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
					<input type="hidden" name="${prefix}record" value="">
					<input type="hidden" name="${prefix}status" value="${default_status}">
					<input type="hidden" name="${prefix}parent_type" value="${default_parent_type}">
					<input type="hidden" name="${prefix}assigned_user_id" value='${user_id}'>
					<input type="hidden" name="${prefix}duration_hours" value="1">
					<input type="hidden" name="${prefix}duration_minutes" value="00">
					<FONT class="required">$lbl_required_symbol</FONT>$lbl_subject<br>
					<input name='${prefix}name' size='30' maxlength='255' type="text"><br>
					<FONT class="required">$lbl_required_symbol</FONT>$lbl_date&nbsp;<font size="1"><em>$ntc_date_format</em></font><br>
					<input name='${prefix}date_start' id='jscal_field' type="text" maxlength="10" value="${default_date_start}"> <img src="themes/$theme/images/calendar.gif" id="jscal_trigger"><br>
					<FONT class="required">$lbl_required_symbol</FONT>$lbl_time&nbsp;<font size="1"><em>$ntc_time_format</em></font><br>
					<input name='${prefix}time_start' type="text" maxlength='5' value="${default_time_start}"><br><br>
					<script type="text/javascript">
					Calendar.setup ({
						inputField : "jscal_field", ifFormat : "$cal_dateformat", showsTime : false, button : "jscal_trigger", singleClick : true, step : 1
					});
					</script>
EOF;
return $form;
		}
	function getForm($prefix, $mod=''){
		if(!empty($mod)){
	global $current_language;
	$mod_strings = return_module_language($current_language, $mod);
}else global $mod_strings;
		global $app_strings;
		global $app_list_strings;
		$lbl_save_button_title = $app_strings['LBL_SAVE_BUTTON_TITLE'];
		$lbl_save_button_key = $app_strings['LBL_SAVE_BUTTON_KEY'];
		$lbl_save_button_label = $app_strings['LBL_SAVE_BUTTON_LABEL'];


$the_form = get_left_form_header($mod_strings['LBL_NEW_FORM_TITLE']);
$the_form .= <<<EOQ

		
		<form name="${prefix}MeetingSave" onSubmit="return verify_data(${prefix}MeetingSave)" method="POST" action="index.php">
			<input type="hidden" name="${prefix}module" value="Meetings">
			
			<input type="hidden" name="${prefix}action" value="Save">
		
EOQ;
$the_form	.= $this->getFormBody($prefix);
$the_form .= <<<EOQ
		<input title="$lbl_save_button_title" accessKey="$lbl_save_button_key" class="button" type="submit" name="button" value="  $lbl_save_button_label  " >
		</form>
EOQ;

$the_form .= get_left_form_footer();
$the_form .= get_validate_record_js();

return $the_form;	
}


function handleSave($prefix,$redirect=true, $useRequired=false){
	require_once('modules/Meetings/Meeting.php');
	require_once('include/logging.php');
	require_once('include/formbase.php');
	
	$local_log =& LoggerManager::getLogger('MeetingSaveForm');
	$focus = new Meeting();
	if($useRequired &&  !checkRequired($prefix, array_keys($focus->required_fields))){
		return null;
	}
	$focus = populateFromPost($prefix, $focus);
	$focus->save();
	$return_id = $focus->id;
	$local_log->debug("Saved record with id of ".$return_id);
	if($redirect){
		$this->handleRedirect($return_id);
	}else{
		return $focus;	
	}
}

function handleRedirect($return_id){
	if(isset($_POST['return_module']) && $_POST['return_module'] != "") $return_module = $_POST['return_module'];
	else $return_module = "Meetings";
	if(isset($_POST['return_action']) && $_POST['return_action'] != "") $return_action = $_POST['return_action'];
	else $return_action = "DetailView";
	if(isset($_POST['return_id']) && $_POST['return_id'] != "") $return_id = $_POST['return_id'];
	header("Location: index.php?action=$return_action&module=$return_module&record=$return_id");
}

	


}

?>