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

require_once('include/RelatedListView.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Products/Product.php');
require_once('include/utils/UserInfoUtil.php');

// functions added for group calendar	-Jaguar
	function get_duration($time_start,$duration_hours,$duration_minutes)
	{
		$time=explode(":",$time_start);
                $time_mins = $time[1];
                $time_hrs = $time[0];
                $mins = ($time_mins + $duration_minutes) % 60;
                $hrs_min = floor(($time_mins + $duration_minutes) / 60);
                if(!isset($hrs))
                        $hrs=0;
		$hrs = $duration_hours + $hrs_min + $time_hrs;
		if($hrs<10)
			$hrs=$hrs;
		if($mins<10)
			$mins="0".$mins;	

		$end_time = $hrs .$mins;
		return $end_time;
	}	

	function time_to_number($time_start)
	{
		$start_time_array = explode(":",$time_start);
		if(ereg("^[0]",$start_time_array[0]))
		{
			$time_start_hrs=str_replace('0',"",$start_time_array[0]);
		}
		else
		{
			$time_start_hrs=$start_time_array[0];
		}
		$start_time= $time_start_hrs .$start_time_array[1];

		return $start_time;
	}

	function status_availability($owner,$userid,$activity_id,$avail_date,$activity_start_time,$activity_end_time)	
	{
		global $adb,$image_path,$log;
		$avail_flag="false";
		$avail_date=getDBInsertDateValue($avail_date);
		if( $owner != $userid)
		{
			
			$usr_query="select activityid,activity.date_start,activity.due_date, activity.time_start,activity.duration_hours,activity.duration_minutes,crmentity.smownerid from activity,crmentity where crmentity.crmid=activity.activityid and ('".$avail_date."' like date_start) and crmentity.smownerid=".$userid." and activity.activityid !=".$activity_id."  and crmentity.deleted=0 group by crmid;";
		}
		else
		{
			$usr_query="select activityid,activity.date_start,activity.due_date, activity.time_start,activity.duration_hours,activity.duration_minutes,crmentity.smownerid from activity,crmentity where crmentity.crmid=activity.activityid and ('".$avail_date."' like date_start) and crmentity.smownerid=".$userid." and activity.activityid !=".$activity_id." and crmentity.deleted=0 group by crmid;";
		}
		$result_cal=$adb->query($usr_query);   
		$noofrows_cal = $adb->num_rows($result_cal);
		$avail_flag="false";

		if($noofrows_cal!=0)
		{
			while($row_cal = $adb->fetch_array($result_cal)) 
			{
				$usr_date_start=$row_cal['date_start'];
				$usr_due_date=$row_cal['due_date'];
				$usr_time_start=$row_cal['time_start'];
				$usr_hour_dur=$row_cal['duration_hours'];
				$usr_mins_dur=$row_cal['duration_minutes'];
				$user_start_time=time_to_number($usr_time_start);	
				$user_end_time=get_duration($usr_time_start,$usr_hour_dur,$usr_mins_dur);

				if( ( ($user_start_time > $activity_start_time) && ( $user_start_time < $activity_end_time) ) || ( ( $user_end_time > $activity_start_time) && ( $user_end_time < $activity_end_time) ) || ( ( $activity_start_time == $user_start_time ) || ($activity_end_time == $user_end_time) ) )
				{
					$availability= 'busy';
					$avail_flag="true";
	                                $log->info("user start time-- ".$user_start_time."user end time".$user_end_time);
                                        $log->info("Availability ".$availability);

				}
			}
		}
		if($avail_flag!="true")
		{
			$recur_query="SELECT activity.activityid, activity.time_start, activity.duration_hours, activity.duration_minutes , crmentity.smownerid, recurringevents.recurringid, recurringevents.recurringdate as date_start from activity inner join crmentity on activity.activityid = crmentity.crmid inner join recurringevents on activity.activityid=recurringevents.activityid where ('".$avail_date."' like recurringevents.recurringdate) and crmentity.smownerid=".$userid." and activity.activityid !=".$activity_id." and crmentity.deleted=0 group by crmid";
			
			$result_cal=$adb->query($recur_query);   
			$noofrows_cal = $adb->num_rows($result_cal);
			$avail_flag="false";

			if($noofrows_cal!=0)
			{
				while($row_cal = $adb->fetch_array($result_cal)) 
				{
					$usr_date_start=$row_cal['date_start'];
					$usr_time_start=$row_cal['time_start'];
					$usr_hour_dur=$row_cal['duration_hours'];
					$usr_mins_dur=$row_cal['duration_minutes'];
					$user_start_time=time_to_number($usr_time_start);	
					$user_end_time=get_duration($usr_time_start,$usr_hour_dur,$usr_mins_dur);

					if( ( ($user_start_time > $activity_start_time) && ( $user_start_time < $activity_end_time) ) || ( ( $user_end_time > $activity_start_time) && ( $user_end_time < $activity_end_time) ) || ( ( $activity_start_time == $user_start_time ) || ($activity_end_time == $user_end_time) ) )
					{
						$availability= 'busy';
						$avail_flag="true";
						$log->info("Recurring Events:: user start time-- ".$user_start_time."user end time".$user_end_time);
        	                                $log->info("Recurring Events:: Availability ".$availability);
					}
				}
			}

			
		}	
	 	if($avail_flag == "true")
                {
                        $availability=' <IMG SRC="'.$image_path.'/busy.gif">';
                }
                else
                {
                        $availability=' <IMG SRC="'.$image_path.'/free.gif">';
                }
		return $availability;
		
	}

?>
