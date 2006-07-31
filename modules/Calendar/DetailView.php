<?php
/*********************************************************************************

** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/
 require_once('modules/Activities/Activity.php');
 require_once('include/utils/utils.php'); 
 require_once('include/CustomFieldUtil.php');
 require_once('modules/Calendar/calendarLayout.php');
 global $current_user;
 $activity_mode = $_REQUEST['activity_mode'];
 if($activity_mode == 'Task')
 {
	 $tab_type = 'Activities';
 }
 elseif($activity_mode == 'Events')
 {
	 $tab_type = 'Events';
 }
 $tab_id=getTabid($tab_type);
 $focus = new Activity();
 if(isset($_REQUEST['record']) && isset($_REQUEST['record'])) {
	 $focus->retrieve_entity_info($_REQUEST['record'],$tab_type);
	 $focus->id = $_REQUEST['record'];
	 $focus->name=$focus->column_fields['subject'];
 }
 if($current_user->hour_format == '')
 	$format = 'am/pm';
 else
	$format = $current_user->hour_format;
$time = $focus->column_fields['time_start'];
$time_arr = getaddEventPopupTime($time,$time,$format);
$data['starthr'] = $time_arr['starthour'];
$data['startmin'] = $time_arr['startmin'];
$data['startfmt'] = $time_arr['startfmt'];
$data['record'] = $focus->id;
//Calculating reminder time
$rem_days = 0;
$rem_hrs = 0;
$rem_min = 0;
if($focus->column_fields['reminder_time'] != null)
{
	$data['set_reminder'] = 'Yes';
	$rem_days = floor($focus->column_fields['reminder_time']/(24*60));
	$rem_hrs = floor(($focus->column_fields['reminder_time']-$rem_days*24*60)/60);
	$rem_min = ($focus->column_fields['reminder_time']-$rem_days*24*60)%60;
	$data['remdays'] = $rem_days;
	$data['remhrs'] = $rem_hrs;
	$data['remmin'] = $rem_min;
}
else
	$data['set_reminder'] = 'No';
 if($activity_mode == 'Task')
 {
	 $data['task_subject'] = $focus->column_fields['subject'];
	 $data['task_date_start'] = $focus->column_fields['date_start'];
	 $data['assigned_user_id'] = $focus->column_fields['assigned_user_id'];
	 $data['taskstatus'] = $focus->column_fields['taskstatus'];
	 $data['taskpriority'] = $focus->column_fields['taskpriority'];
	 $data['sendnotification'] = $focus->column_fields['sendnotification'];
 }
 elseif($activity_mode == 'Events')
 {
	 $data['subject'] = $focus->column_fields['subject'];
	 $data['date_start'] = $focus->column_fields['date_start'];
	 $data['due_date'] = $focus->column_fields['due_date'];
	 $data['visibility'] = $focus->column_fields['visibility'];
	 $data['assigned_user_id'] = $focus->column_fields['assigned_user_id'];
	 $data['eventstatus'] = $focus->column_fields['eventstatus'];
	 $data['taskpriority'] = $focus->column_fields['taskpriority'];
	 $data['sendnotification'] = $focus->column_fields['sendnotification'];
	 $data['activitytype'] = $focus->column_fields['activitytype'];
	 //$time_arr = getaddEventPopupTime($format);
	 
 }

 $js_arr = "<SCRIPT id='activity_cont'> var data = new Array(\"".join($data,'","')."\");
 var key = new Array(\"".join(array_keys($data),'","')."\");
 var activity_type = '".$activity_mode."';
 </SCRIPT>";
 echo $js_arr;
 

?>
