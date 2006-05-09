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
	var $db;
	var $start_time;
	var $end_time;
	var $subject;
	var $participant = Array();
	var $participant_state = Array();
	var $contact_name;
	var $account_id;
	var $account_name;
	var $eventstatus;
	var $activity_type;
	var $description = "";
	var $record;

	function Appointment()
	{
		$this->db = new PearDatabase();
	}	
	function read_appointment($userid, &$from_datetime, &$to_datetime)
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


		/*$r = $adb->query($q);
                $n = $adb->getRowCount($r);
                $a = 0;

                while ( $a < $n )
                {
                        $o = &new Appointment();
                        $result = $adb->fetchByAssoc($r);
			echo '<pre>';print_r($result);echo '</pre>';
                        //echo '<pre>' print_r($result);echo '</pre>';
                        //print_r($result);
                        //$o->read_result($r,$a);
                        $o->read_result($result);
                        $a++;
                        if ( $o->see_ok() ) {
                                //Get all participants 
                                //$o->read_participants();
                                //print("GS --> see");
                                $obj->callist[$o->record_id] = &$o;
                        }
                        unset($o);
                }*/
		return;
	}
	
	
}
?>
