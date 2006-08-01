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
 require_once('include/database/PearDatabase.php');
 global $current_user,$adb;
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

 //To set recurring details
 $query = 'select vtiger_recurringevents.recurringfreq,vtiger_recurringevents.recurringinfo from vtiger_recurringevents where vtiger_recurringevents.activityid = '.$focus->id;
 $res = $adb->query($query);
 $rows = $adb->num_rows($res);
 if($rows != 0)
 {
	 $data['recurringcheck'] = 'on';
	 $data['repeat_frequency'] = $adb->query_result($res,0,'recurringfreq');
	 $recurringinfo =  explode("::",$adb->query_result($res,0,'recurringinfo'));
	 $data['recurringtype'] = $recurringinfo[0];
	 if($recurringinfo[0] == 'Weekly')
	 {
		 
	 }
	 elseif($recurringinfo[0] == 'Monthly')
	 {
		 $data['repeatMonth'] = $recurringinfo[1];
		 if($recurringinfo[1] == 'date')
		 {
			 $data['repeatMonth_date'] = $recurringinfo[2];
		 }
		 else
		 {
			 $data['repeatMonth_daytype'] = $recurringinfo[2];
			 $data['repeatMonth_day'] = $recurringinfo[3];
		 }
	 }
	 
 }


 //To set user selected hour format
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
	 $data['priority'] = $focus->column_fields['taskpriority'];
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
	 $data['priority'] = $focus->column_fields['taskpriority'];
	 $data['sendnotification'] = $focus->column_fields['sendnotification'];
	 $data['activitytype'] = $focus->column_fields['activitytype'];
	 //$time_arr = getaddEventPopupTime($format);
	 
 }
 //To get value for Related To field
 if(isset($focus->column_fields['parent_id']) && $focus->column_fields['parent_id'] != null)
 {
	 $value = $focus->column_fields['parent_id'];
	 $data['parent_id'] = $value;
	 $parent_module = getSalesEntityType($value);
	 if($parent_module == "Leads")
	 {
		 $sql = "select * from vtiger_leaddetails where leadid=".$value;
		 $result = $adb->query($sql);
		 $first_name = $adb->query_result($result,0,"firstname");
		 $last_name = $adb->query_result($result,0,"lastname");
		 $parent_name = $last_name.' '.$first_name;
	 }
	 elseif($parent_module == "Accounts")
	 {
		 $sql = "select * from  vtiger_account where accountid=".$value;
		 $result = $adb->query($sql);
		 $parent_name = $adb->query_result($result,0,"accountname");
	 }
	 elseif($parent_module == "Potentials")
	 {
		 $sql = "select * from  vtiger_potential where potentialid=".$value;
		 $result = $adb->query($sql);
		 $parent_name = $adb->query_result($result,0,"potentialname");
	 }
	 $data['parent_type'] = $parent_module;
	 $data['parent_name'] = $parent_name;
	 
 
 }

 //To get Contact info
 $conquery = 'select vtiger_contactdetails.contactid, vtiger_contactdetails.firstname,vtiger_contactdetails.lastname from vtiger_contactdetails inner join vtiger_cntactivityrel on vtiger_cntactivityrel.contactid=vtiger_contactdetails.contactid inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_contactdetails.contactid where vtiger_cntactivityrel.activityid='.$focus->id.' and vtiger_crmentity.deleted=0';
 $con_res = $adb->query($conquery);
 $cntslist ='';

 while($row = $adb->fetch_array($con_res))
 {
	 $cntslist .= $row['lastname'].' '.$row['firstname'];
	 $cntslist .= '\n';
 }
 $data['contactlist'] = $cntslist;
 $js_arr = "<SCRIPT id='activity_cont'> var data = new Array(\"".join($data,'","')."\");
 var key = new Array(\"".join(array_keys($data),'","')."\");
 var activity_type = '".$activity_mode."';
 </SCRIPT>";
 echo $js_arr;
 

?>
