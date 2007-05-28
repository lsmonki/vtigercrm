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
require_once('include/utils/UserInfoUtil.php');
require_once('include/database/PearDatabase.php');
require_once('modules/Calendar/Activity.php');
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
	var $priority;
	var $activity_type;
	var $description;
	var $record;
	var $image_name;
	var $formatted_datetime;
	var $duration_min;
	var $duration_hour;
	var $shared = false;

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
		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
		$shared_ids = getSharedCalendarId($current_user->id);
                $q= "select vtiger_activity.*, vtiger_crmentity.*, vtiger_activitygrouprelation.groupname FROM vtiger_activity inner join vtiger_crmentity on vtiger_activity.activityid = vtiger_crmentity.crmid left join vtiger_recurringevents on vtiger_activity.activityid=vtiger_recurringevents.activityid left outer join vtiger_activitygrouprelation on vtiger_activitygrouprelation.activityid=vtiger_activity.activityid left join vtiger_groups on vtiger_groups.groupname = vtiger_activitygrouprelation.groupname WHERE vtiger_crmentity.deleted = 0 and vtiger_activity.activitytype in ('Call','Meeting') AND (vtiger_activity.date_start < '". $to_datetime->get_formatted_date() ."' AND vtiger_activity.date_start >= '". $from_datetime->get_formatted_date()."') ";
		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[16] == 3)
		{
			$sec_parameter=getListViewSecurityParameter('Calendar');
			$q .= $sec_parameter;
		}
									
                $q .= " AND vtiger_recurringevents.activityid is NULL ";
                $q .= " group by vtiger_activity.activityid ORDER by vtiger_activity.date_start,vtiger_activity.time_start";
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
		$q = "SELECT vtiger_activity.activityid, vtiger_activity.subject, vtiger_activity.activitytype, vtiger_crmentity.description, vtiger_activity.time_start,vtiger_activity.time_end, vtiger_activity.duration_hours, vtiger_activity.duration_minutes,vtiger_activity.due_date, vtiger_activity.priority, vtiger_activity.location,vtiger_activity.eventstatus, vtiger_crmentity.*, vtiger_recurringevents.recurringid, vtiger_recurringevents.recurringdate as date_start ,vtiger_recurringevents.recurringtype,vtiger_activitygrouprelation.groupname from vtiger_activity inner join vtiger_crmentity on vtiger_activity.activityid = vtiger_crmentity.crmid inner join vtiger_recurringevents on vtiger_activity.activityid=vtiger_recurringevents.activityid left outer join vtiger_activitygrouprelation on vtiger_activitygrouprelation.activityid=vtiger_activity.activityid left join vtiger_groups on vtiger_groups.groupname = vtiger_activitygrouprelation.groupname ";

                $q.=" where vtiger_crmentity.deleted = 0 and vtiger_activity.activitytype in ('Call','Meeting') AND (recurringdate < '".$to_datetime->get_formatted_date()."' AND recurringdate >= '".$from_datetime->get_formatted_date(). "') ";

		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[16] == 3)
		{
			$sec_parameter=getListViewSecurityParameter('Calendar');
			$q .= $sec_parameter;
		}
													
                $q .= " ORDER by vtiger_recurringevents.recurringid";
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
          * @param $act_array -- The vtiger_activity array :: Type Array
          * @param $view -- The calendar view :: Type String
         */
	function readResult($act_array, $view)
	{
		global $adb,$current_user;
		$format_sthour='';
                $format_stmin='';
		$this->description       = $act_array["description"];
		//$this->account_name      = $act_array["accountname"];
		//$this->account_id        = $act_array["accountid"];
		$this->eventstatus       = $act_array["eventstatus"];
		$this->priority		 = $act_array["priority"];
		$this->subject           = $act_array["subject"];
		$this->activity_type     = $act_array["activitytype"];
		$this->duration_hour     = $act_array["duration_hours"];
		$this->duration_minute   = $act_array["duration_minutes"];
		$this->creatorid         = $act_array["smcreatorid"];
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
			if(!is_admin($current_user))
			{
				if($act_array["smownerid"] != $current_user->id)
					$this->shared = true;
			}
			$this->owner   = getUserName($act_array["smownerid"]);
			$query="SELECT cal_color FROM vtiger_users where id = ".$this->ownerid;
			$result=$adb->query($query);
			if($adb->getRowCount($result)!=0)
			{
				$res = $adb->fetchByAssoc($result, -1, false);
				$this->color = $res['cal_color'];
			}
		}
		if($act_array["activitytype"] == 'Call')
		{
			$this->image_name = 'Call.gif';
		}
		if($act_array["activitytype"] == 'Meeting')
		{
			$this->image_name = 'Meeting.gif';
		}
		$this->record            = $act_array["activityid"];
		list($styear,$stmonth,$stday) = explode("-",$act_array["date_start"]);
		if($act_array["time_start"] != null)
		{
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
			$st_hour= $format_sthour;
		}
		else
		{
			$st_hour = 'notime';
			$format_stmin = '00';
			$format_sthour= '00';
		}
		list($eyear,$emonth,$eday) = explode("-",$act_array["due_date"]);
		if($act_array["time_end"] != '')
		{
			list($end_hour,$end_min,$end_sec) = split(":",$act_array["time_end"]);
			if($end_hour <= 9 && strlen(trim($end_hour)) < 2)
			{
				$format_endhour= '0'.$end_hour;
			}
			else
			{
				$format_endhour= $end_hour;
			}
			if($end_min <= 9 && strlen(trim($end_min)) < 2)
			{
				$format_endmin= '0'.$end_min;
			}
			else
			{
				$format_endmin = $end_min;
			}
			$end_hour= $format_endhour;
		}
		else
		{
			$end_min = '50';
			$end_hour= '23';
		}

		$start_date_arr = Array(
			'min'   => $format_stmin,
			'hour'  => $format_sthour,
			'day'   => $stday,
			'month' => $stmonth,
			'year'  => $styear
		);
		$end_date_arr = Array(
			'min'   => $end_min,
			'hour'  => $end_hour,
			'day'   => $eday,
			'month' => $emonth,
			'year'  => $eyear
		);
                $this->start_time        = new DateTime($start_date_arr,true);
                $this->end_time          = new DateTime($end_date_arr,true);
		if($view == 'day' || $view == 'week')
		{
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
  * @param $a -- The vtiger_activity array :: Type Array
  * @param $b -- The vtiger_activity array :: Type Array
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
