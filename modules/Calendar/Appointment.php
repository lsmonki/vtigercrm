<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/
require_once('modules/Calendar/CalendarCommon.php');
require_once('include/database/PearDatabase.php');
require_once('modules/Activities/Activity.php');
class Appointment
{
	var $start_time;
	var $end_time;
	var $subject;
	var $participant;
	var $participant_state;
	var $contact_name;
	var $account_id;
	var $account_name;
	var $eventstatus;
	var $activity_type;
	var $description;
	var $record;
	var $image_name;
	var $formatted_datetime;

	function Appointment()
	{
		$this->participant = Array();
		$this->participant_state = Array();
		$this->description = "";
	}	
	function readAppointment($userid, &$from_datetime, &$to_datetime)
	{
		global $current_user,$adb;
		$shared_ids = getSharedCalendarId($current_user->id,'shared');		
                $q= "select activity.*,crmentity.*,account.accountname,account.accountid,activitygrouprelation.groupname FROM activity inner join crmentity on activity.activityid = crmentity.crmid left outer join activitygrouprelation on activitygrouprelation.activityid=activity.activityid left join cntactivityrel on activity.activityid = cntactivityrel.activityid left join contactdetails on cntactivityrel.contactid = contactdetails.contactid left join account  on contactdetails.accountid = account.accountid inner join salesmanactivityrel on salesmanactivityrel.activityid=activity.activityid WHERE activity.activitytype in ('Call','Meeting') AND ";

                if(!is_admin($current_user))
                {
                        $q .= " ( ";
                }

                $q.=" ((activity.date_start < '". $to_datetime->get_formatted_date() ."' AND activity.date_start >= '". $from_datetime->get_formatted_date()."')";
                $q.=" and (activity.date_start like (activity.due_date) or (activity.date_start != '0000-00-00' ))";

                if(!is_admin($current_user))
                {
                        $q .= "  ) AND ((crmentity.smownerid ='".$current_user->id."' and salesmanactivityrel.smid = '".$current_user->id."') or (crmentity.smownerid in ($shared_ids) and salesmanactivityrel.smid in ($shared_ids) and activity.visibility='Public'))";
                }
                $q .= " AND crmentity.deleted = 0)";
                $q .= " ORDER by activity.date_start,activity.time_start";


		$r = $adb->query($q);
                $n = $adb->getRowCount($r);
                $a = 0;
		$list = Array();
                while ( $a < $n )
                {
                        $obj = &new Appointment();
                        $result = $adb->fetchByAssoc($r);
                        //echo '<pre>' print_r($result);echo '</pre>';
                        $obj->readResult($result);
			//$list_arr[$obj->record] = $obj;
                        $a++;
			$list[] = $obj;
                        unset($obj);
                }
		usort($list,'compare');
		//echo '<pre>';print_r($list);echo '</pre>';
		return $list;
	}

	function readResult($act_array)
	{
		$format_sthour='';
                $format_stmin='';
                list($st_hour,$st_min,$st_sec) = split(":",$act_array["time_start"]);
                if($st_hour <= 9 && strlen(trim($st_hour)) < 2)
                {
                        $format_sthour= '0'.$st_hour;
                }
                else
                {
                        $format_sthour= $st_hour;
                }
                if($st_min <= 9 && strlen(trim($st_min)) < 2)
                {
                        $format_stmin= '0'.$st_min;
                }
                else
                {
                        $format_stmin = $st_min;
                }
		list($styear,$stmonth,$stday) = explode("-",$act_array["date_start"]);
                $startdate = $act_array["date_start"] .' ' . $format_sthour .":" . $format_stmin .":00";
		$start_date_arr = Array(
					'min'   => $format_stmin,
					'hour'  => $format_sthour,
					'day'   => $stday,
					'month' => $stmonth,
					'year'  => $styear
				       );
                //end time calculation
                $end_hour = 0;
                $end_min = $st_min + $act_array["duration_minutes"];
                if($end_min <= 9) $end_min= '0'.$end_min;
		if($end_min >= 60)
                {
                        $end_min = $end_min%60;
                        if($end_min <= 9) $end_min= '0'.$end_min;
                        $end_hour++;
                }
                $end_hour = $end_hour + $st_hour + $act_array["duration_hours"];
                if($end_hour <= 9) $end_hour= '0'.$end_hour;
                if ($end_hour > 23) $end_hour = 23;
		list($eyear,$emonth,$eday) = explode("-",$act_array["due_date"]);
		$enddate = $act_array["date_start"] .' ' . $end_hour .":" . $end_min .":00";
		$end_date_arr = Array(
                                        'min'   => $end_min,
                                        'hour'  => $end_hour,
                                        'day'   => $eday,
                                        'month' => $emonth,
                                        'year'  => $eyear
                                       );

                $this->description       = $act_array["description"];
                $this->start_time        = new DateTime($start_date_arr,true);
                $this->account_name      = $act_array["accountname"];    
                $this->account_id        = $act_array["accountid"];      
                $this->eventstatus       = $act_array["eventstatus"];    
                $this->end_time          = new DateTime($end_date_arr,true);
                $this->subject           = $act_array["subject"];
                $this->activity_type     = $act_array["activitytype"];
		if($act_array["activitytype"] == 'Call')
		{
			$this->image_name = 'Calls.gif';
		}
		if($act_array["activitytype"] == 'Meeting')
		{
			$this->image_name = 'Meetings.gif';
		}
                $this->record            = $act_array["activityid"];
		$this->formatted_datetime= $act_array["date_start"].":".$st_hour;
		return;
	}
	
	
}

function compare($a,$b)
{
	if ($a->start_time->ts == $b->start_time->ts)
	{
		return 0;
   	}
	return ($a->start_time->ts < $b->start_time->ts) ? -1 : 1;
}
?>
