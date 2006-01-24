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

function renderRelatedContacts($query,$id)
{
        global $mod_strings;
        global $app_strings;

        $focus = new Contact();
	
	$button = '';

        if(isPermitted("Contacts",3,"") == 'yes')
        {
		$button .= '<input title="Change" accessKey="" tabindex="2" type="button" class="button" value="'.$app_strings['LBL_SELECT_CONTACT_BUTTON_LABEL'].'" name="Button" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Contacts&return_module=Activities&action=Popup&popuptype=detailview&form=EditView&form_submit=false&recordid='.$_REQUEST["record"].'","test","width=600,height=400,resizable=1,scrollbars=1");\'>&nbsp;';
	}
	$returnset = '&return_module=Activities&return_action=DetailView&activity_mode=Events&return_id='.$id;

	return GetRelatedList('Activities','Contacts',$focus,$query,$button,$returnset);

}

function renderRelatedProducts($query,$id)
{
	global $mod_strings;
	global $app_strings;
	
	$focus = new Product();
 
	$button = '';

        if(isPermitted("Products",3,"") == 'yes')
        { 
		$button .= '<input title="Change" accessKey="" tabindex="2" type="button" class="button" value="'.$app_strings['LBL_SELECT_PRODUCT_BUTTON_LABEL'].'" name="Button" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Products&action=Popup&return_module=Activities&popuptype=detailview&form=EditView&form_submit=false&recordid='.$_REQUEST["record"].'","test","width=600,height=400,resizable=1,scrollbars=1");\'>&nbsp;';
	}
	$returnset = '&return_module=Activities&return_action=DetailView&activity_mode=Events&return_id='.$id;

	$list = GetRelatedList('Activities','Products',$focus,$query,$button,$returnset);
	echo '</form>';
}
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

//

function renderRelatedUsers($query,$id)
{
	

  global $theme;
  $theme_path="themes/".$theme."/";
  $image_path=$theme_path."images/";
  require_once ($theme_path."layout_utils.php");
  $activity_id=$id;
  global $adb,$log;
  
  global $mod_strings;
  global $app_strings;

  $result=$adb->query($query);   


  $noofrows = $adb->num_rows($result);
  $header[] = $app_strings['LBL_LIST_NAME'];
  $header[] = $app_strings['LBL_LIST_USER_NAME'];
  $header[] = $app_strings['LBL_EMAIL'];
  $header[] = $app_strings['LBL_PHONE']; 

  // To display the dates for the Group calendar starts -Jaguar
	$recur_dates_qry='select distinct(recurringdate) from recurringevents where activityid='.$activity_id;
	$recur_result=$adb->query($recur_dates_qry);
	$noofrows_recur = $adb->num_rows($recur_result);
	if($noofrows_recur==0)
	{
		$recur_dates_qry='select activity.date_start,recurringevents.* from activity left outer join recurringevents on activity.activityid=recurringevents.activityid where recurringevents.activityid is NULL and activity.activityid='.$activity_id .' group by activity.activityid';
		$recur_result=$adb->query($recur_dates_qry);
		$noofrows_recur = $adb->num_rows($recur_result);

	}

	
	$recur_table="<table border=0 cellspacing=0 cellpadding=2>
		     <tr><td colspan=".$noofrows_recur." align=center>".$app_strings['LBL_AVAILABLE']."</td></tr>";
	if($noofrows_recur !=0)
	{
		while($row_recur = $adb->fetch_array($recur_result))
                {
			global $current_user;
			$dat_fmt = $current_user->date_format;
			if($dat_fmt == 'yyyy-mm-dd' || $dat_fmt == 'mm-dd-yyyy')
			{
				$date_display="m/d";
			}
			else if($dat_fmt == 'dd-mm-yyyy')
			{
				$date_display="d/m";
			}

			$recur_dates=$row_recur['recurringdate'];
			if($recur_dates=="")
			{
				$recur_dates=$row_recur['date_start'];
			}

			$st=explode("-",$recur_dates);
			$date_val = date($date_display,mktime(0,0,0,date("$st[1]"),(date("$st[2]")),date("$st[0]")));
			$recur_table.="<td>$date_val</td> ";
                }
		$recur_table.="</tr>";
	}
	$recur_table.="</table>";
  while($row = $adb->fetch_array($result))
  {
	
    global $current_user;
	
    $entries = Array();	
	
    if(is_admin($current_user))
    {
    	$entries[] = $row['last_name'].' '.$row['first_name'];
    }
    else
    {
    	$entries[] = $row['last_name'].' '.$row['first_name'];
    }	

 $entries[] = $row['user_name'];

	$entries[] = $row['email1'];
	if($email == '')	$email = $row['email2'];
	if($email == '')	$email = $row['yahoo_id'];


	$entries[] = $row['phone_home'];
	if($phone == '')	$phone = $row['phone_work'];
        if($phone == '')        $phone = $row['phone_other'];
        if($phone == '')	$phone = $row['phone_fax'];

	$act_date_start= getDBInsertDateValue($row['date_start']); //getting the Date format - Jaguar
	$act_due_date= getDBInsertDateValue($row['due_date']);

	$act_time_start=$row['time_start'];
	$act_mins_dur=$row['duration_minutes'];

	$activity_start_time=time_to_number($act_time_start);	
	$activity_end_time=get_duration($act_time_start,$act_hour_dur,$act_mins_dur);	

	$activity_owner_qry='select users.user_name,users.id  userid from users,crmentity where users.id=crmentity.smownerid and crmentity.crmid='.$id;
	$result_owner=$adb->query($activity_owner_qry);

        while($row_owner = $adb->fetch_array($result_owner))
        {
		$owner=$row_owner['userid'];
	}
	
	$recur_dates_qry='select recurringdate from recurringevents where activityid ='.$activity_id;
	$recur_result=$adb->query($recur_dates_qry);
	$noofrows_recur = $adb->num_rows($recur_result);
	$userid=$row['id'];
	if($noofrows_recur !=0)
	{
		$avail_table="<table border=0 cellspacing=0 cellpadding=0 width='100%'>";
		$avail_table.="<tr>";
		while($row_recur = $adb->fetch_array($recur_result))
		{
			$recur_dates=getDBInsertDateValue($row_recur['recurringdate']);
			$availability=status_availability($owner,$userid,$activity_id,$recur_dates,$activity_start_time,$activity_end_time);	
			 $log->info("activity start time ".$activity_start_time."activity end time".$activity_end_time."Available date".$recur_dates);
			$avail_table.="<td>$availability</td>";

			
		}
	}
	else
	{
		$recur_dates=$act_date_start;
		$availability=status_availability($owner,$userid,$activity_id,$recur_dates,$activity_start_time,$activity_end_time);	
		$log->info("activity start time ".$activity_start_time."activity end time".$activity_end_time."Available  date".$recur_dates);		
		$list .= $availability;
	}
	// Group Calendar coding	
	
	$entries_list[]=$entries;
  }

	if($entries_list != '')
		$return_data = array('header'=>$header, 'entries'=>$entries_list);
		return $return_data;

}



?>
