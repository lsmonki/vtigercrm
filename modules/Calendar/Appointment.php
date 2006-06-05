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
require_once('include/utils/CommonUtils.php');
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
	var $creatorid;
	var $creator;
	var $owner;
	var $ownerid;
	var $assignedto;
	var $eventstatus;
	var $activity_type;
	var $description;
	var $record;
	var $image_name;
	var $formatted_datetime;
	var $duration_min;
	var $duration_hour;

	function Appointment()
	{
		$this->participant = Array();
		$this->participant_state = Array();
		$this->description = "";
	}
	
	/** To get the events of the specified user and shared events
	  * @param $userid -- The user Id:: Type integer
          * @param $from_datetime -- The start date Obj :: Type Array
          * @param $to_datetime -- The end date Obj :: Type Array
          * @param $view -- The calendar view :: Type String
	  * @returns $list :: Type Array
	 */
	
	function readAppointment($userid, &$from_datetime, &$to_datetime, $view)
	{
		global $current_user,$adb;
		$shared_ids = getSharedCalendarId($current_user->id);		
		if(empty($shared_ids))
			$shared_ids = $current_user->id;
                $q= "select activity.*, crmentity.*, account.accountname,account.accountid,activitygrouprelation.groupname FROM activity inner join crmentity on activity.activityid = crmentity.crmid left join recurringevents on activity.activityid=recurringevents.activityid left outer join activitygrouprelation on activitygrouprelation.activityid=activity.activityid left join cntactivityrel on activity.activityid = cntactivityrel.activityid left join contactdetails on cntactivityrel.contactid = contactdetails.contactid left join account  on contactdetails.accountid = account.accountid inner join salesmanactivityrel on salesmanactivityrel.activityid=activity.activityid WHERE activity.activitytype in ('Call','Meeting') AND ";

                if(!is_admin($current_user))
                {
                        $q .= " ( ";
                }

                $q.=" ((activity.date_start < '". $to_datetime->get_formatted_date() ."' AND activity.date_start >= '". $from_datetime->get_formatted_date()."')";
                if(!is_admin($current_user))
                {
                        $q .= "  ) AND ((crmentity.smownerid ='".$current_user->id."' and salesmanactivityrel.smid = '".$current_user->id."') or (crmentity.smownerid in (".$shared_ids.") and salesmanactivityrel.smid in (".$shared_ids.")))";
                }
                $q .= " AND crmentity.deleted = 0) AND recurringevents.activityid is NULL ";
                $q .= " ORDER by activity.date_start,activity.time_start";
		$r = $adb->query($q);
                $n = $adb->getRowCount($r);
                $a = 0;
		$list = Array();
                while ( $a < $n )
                {
                        $obj = &new Appointment();
                        $result = $adb->fetchByAssoc($r);
                        $obj->readResult($result, $view);
                        $a++;
			$list[] = $obj;
                        unset($obj);
                }
		//Get Recurring events
		$q = "SELECT activity.activityid, activity.subject, activity.activitytype, crmentity.description, activity.time_start, activity.duration_hours, activity.duration_minutes, activity.priority, activity.location,activity.eventstatus, crmentity.*, recurringevents.recurringid, recurringevents.recurringdate as date_start ,recurringevents.recurringtype,account.accountname,account.accountid,activitygrouprelation.groupname from activity inner join crmentity on activity.activityid = crmentity.crmid inner join recurringevents on activity.activityid=recurringevents.activityid left outer join activitygrouprelation on activitygrouprelation.activityid=activity.activityid left join cntactivityrel on activity.activityid = cntactivityrel.activityid left join contactdetails on cntactivityrel.contactid = contactdetails.contactid left join account  on contactdetails.accountid = account.accountid inner join salesmanactivityrel on salesmanactivityrel.activityid=activity.activityid";

                $q.=" where ( activity.activitytype in ('Call','Meeting') AND ";
                if(!is_admin($current_user))
                {
                        $q .= " ( ";
                }
                $q .= "  (recurringdate < '".$to_datetime->get_formatted_date()."' AND recurringdate >= '".$from_datetime->get_formatted_date(). "') ";
                if(!is_admin($current_user))
                {
			$q .= " ) AND ((crmentity.smownerid ='".$current_user->id."' and salesmanactivityrel.smid = '".$current_user->id."' ) or (crmentity.smownerid in (".$shared_ids.") and salesmanactivityrel.smid in (".$shared_ids.")))";
                }

                $q .= " AND crmentity.deleted = 0 )" ;
                $q .= " ORDER by recurringid";
                $r = $adb->query($q);
                $n = $adb->getRowCount($r);
                $a = 0;
		while ( $a < $n )
                {
			$obj = &new Appointment();
                        $result = $adb->fetchByAssoc($r);
                        $obj->readResult($result,$view);
                        $a++;
			$list[] = $obj;
                        unset($obj);
                }


		usort($list,'compare');
		return $list;
	}


	/** To read and set the events value in Appointment Obj
          * @param $act_array -- The activity array :: Type Array
          * @param $view -- The calendar view :: Type String
         */
	function readResult($act_array, $view)
	{
		global $adb;
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
		$this->duration_hour     = $act_array["duration_hours"];
		$this->duration_minute   = $act_array["duration_minutes"];
		$this->creatorid 	 = $act_array["smcreatorid"];
		$this->creator           = getUserName($act_array["smcreatorid"]);
		if($act_array["smownerid"]==0)
                {
                        $this->assignedto ="group";
                        $this->owner = $act_array["groupname"];
                }
		else
		{
			$this->assignedto ="user";
			$this->ownerid = $act_array["smownerid"];
			$this->owner   = getUserName($act_array["smownerid"]);
                        $query="SELECT cal_color FROM users where id = ".$this->ownerid;
                        $result=$adb->query($query);
                        if($adb->getRowCount($result)!=0)
			{
                        	$res = $adb->fetchByAssoc($result, -1, false);
                                $this->color = $res['cal_color'];
                        }
		}
		
		if($act_array["activitytype"] == 'Call')
		{
			$this->image_name = 'Calls.gif';
		}
		if($act_array["activitytype"] == 'Meeting')
		{
			$this->image_name = 'Meetings.gif';
		}
                $this->record            = $act_array["activityid"];
		if($view == 'day' || $view == 'week')
		{
			if($st_hour <= 9 && strlen(trim($st_hour)) < 2)
                	{
	                        $st_hour= '0'.$st_hour;
        	        }
			$this->formatted_datetime= $act_array["date_start"].":".$st_hour;
		}
		elseif($view == 'year')
		{
			list($year,$month,$date) = explode("-",$act_array["date_start"]);
			$this->formatted_datetime = $month;
		}
		else
		{
			$this->formatted_datetime= $act_array["date_start"];
		}
		return;
	}
	
	
}

/** To two array values
  * @param $a -- The activity array :: Type Array
  * @param $b -- The activity array :: Type Array
  * @returns value 0 or 1 or -1 depends on comparision result
 */
function compare($a,$b)
{
	if ($a->start_time->ts == $b->start_time->ts)
	{
		return 0;
   	}
	return ($a->start_time->ts < $b->start_time->ts) ? -1 : 1;
}
?>
