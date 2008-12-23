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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Activities/OpenListView.php,v 1.22 2005/04/19 17:00:30 ray Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

/**
 * Function to get Pending/Upcoming activities
 * @param integer  $mode     - number to differentiate upcoming and pending activities
 * return array    $values   - activities record in array format
 */
function getPendingActivities($mode,$view='')
{
	global $log;
        $log->debug("Entering getPendingActivities() method ...");
	require_once("data/Tracker.php");
	require_once("include/utils/utils.php");
	require_once('include/utils/CommonUtils.php'); //new
	require_once('user_privileges/default_module_view.php');
	

	global $currentModule;
	global $singlepane_view;
	global $theme;
	global $focus;
	global $action;
	global $adb;
	global $app_strings;
	global $current_language;
	global $current_user;
	$current_module_strings = return_module_language($current_language, 'Calendar');

	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	require('user_privileges/user_privileges_'.$current_user->id.'.php');
	require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
	//code added to customize upcomming and pending activities
	if($_REQUEST['activity_view']=='')
	{	
		$activity_view='today';
	}
	else
		$activity_view=$_REQUEST['activity_view'];

	$today = date("Y-m-d", time());

	if($view == 'today')
	{	
		$upcoming_condition = " AND (date_start = '$today' OR vtiger_recurringevents.recurringdate = '$today')";
		$pending_condition = " AND (due_date = '$today' OR vtiger_recurringevents.recurringdate = '$today')";
	}	
	else if($view == 'all')
	{
		$upcoming_condition = " AND (date_start >= '$today' OR vtiger_recurringevents.recurringdate >= '$today')";
		$pending_condition = " AND (due_date <= '$today' OR vtiger_recurringevents.recurringdate <= '$today')";
	}
	if($mode != 1)
	{
		//CHANGE : TO IMPROVE PERFORMANCE
		//for upcoming avtivities
		$list_query = " select vtiger_crmentity.crmid,vtiger_crmentity.smownerid,vtiger_crmentity.setype, vtiger_recurringevents.recurringdate, vtiger_activity.* from vtiger_activity inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_activity.activityid LEFT JOIN vtiger_groups ON vtiger_groups.groupid = vtiger_crmentity.smownerid left outer join vtiger_recurringevents on vtiger_recurringevents.activityid=vtiger_activity.activityid WHERE vtiger_crmentity.deleted=0 and vtiger_activity.activitytype not in ('Emails') AND ( vtiger_activity.status is NULL OR vtiger_activity.status not in ('Completed','Deferred')) and  (  vtiger_activity.eventstatus is NULL OR  vtiger_activity.eventstatus not in ('Held','Not Held') )".$upcoming_condition;
		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[16] == 3)
		{
			$sec_parameter=getListViewSecurityParameter('Calendar');
			$list_query .= $sec_parameter;
		}

	}	
	else
	{
		//CHANGE : TO IMPROVE PERFORMANCE
		//for pending activities
		$list_query = "select vtiger_crmentity.crmid,vtiger_crmentity.smownerid,vtiger_crmentity.setype, vtiger_recurringevents.recurringdate, vtiger_activity.* from vtiger_activity inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_activity.activityid LEFT JOIN vtiger_groups ON vtiger_groups.groupid = vtiger_crmentity.smownerid left outer join vtiger_recurringevents on vtiger_recurringevents.activityid=vtiger_activity.activityid WHERE vtiger_crmentity.deleted=0 and (vtiger_activity.activitytype not in ('Emails')) AND (vtiger_activity.status is NULL OR vtiger_activity.status not in ('Completed','Deferred')) and (vtiger_activity.eventstatus is NULL OR  vtiger_activity.eventstatus not in ('Held','Not Held')) ".$pending_condition;
		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[16] == 3)                   
                {
			$sec_parameter=getListViewSecurityParameter('Calendar'); 
	                $list_query .= $sec_parameter; 
	                                                                                                                                    }
	}
	
	$list_query.= " GROUP BY vtiger_activity.activityid";
	$list_query.= " ORDER BY date_start,time_start ASC";
	$res = $adb->query($list_query);
	$noofrecords = $adb->num_rows($res);
	$open_activity_list = array();
	$noofrows = $adb->num_rows($res);
	if (count($res)>0)
		for($i=0;$i<$noofrows;$i++) 
		{
			//CHANGE : TO IMPROVE PERFORMANCE
			//$parent_name=getRelatedTo("Calendar",$res,$i);
			$open_activity_list[] = Array('name' => $adb->query_result($res,$i,'subject'),
					'id' => $adb->query_result($res,$i,'activityid'),
					'type' => $adb->query_result($res,$i,'activitytype'),
					'module' => $adb->query_result($res,$i,'setype'),
					//'status' => $adb->query_result($res,$i,'status'),
					//'firstname' => $adb->query_result($res,$i,'firstname'),
					//'lastname' => $adb->query_result($res,$i,'lastname'),
					//'accountname' => $adb->query_result($res,$i,'accountname'),
					//'accountid' => $adb->query_result($res, $i, 'accountid'),
					//'contactid' => $adb->query_result($res,$i,'contactid'),
					'date_start' => getDisplayDate($adb->query_result($res,$i,'date_start')),
					'due_date' => getDisplayDate($adb->query_result($res,$i,'due_date')),
					//'recurringtype' => getDisplayDate($adb->query_result($res,$i,'recurringtype')),
					'recurringdate' => getDisplayDate($adb->query_result($res,$i,'recurringdate')),
					//'parent'=> $parent_name,
					// Fredy Klammsteiner, 4.8.2005: changes from 4.0.1 migrated to 4.2
					'priority' => $adb->query_result($res,$i,'priority'), // Armando L�scher 04.07.2005 -> �priority -> Desc: Get vtiger_priority from db
					);
		}
	
	$title=array();
	$title[]=$view;
	$title[]='myUpcoPendAct.gif';
	$title[]='home_myact';
	//$title[]=getActivityView($activity_view);
	$title[]='showActivityView';		
	$title[]='MyUpcumingFrm';
	$title[]='activity_view';

	$header=array();
	$header[] =$current_module_strings['LBL_LIST_SUBJECT'];
	$header[] ='Type';
	//CHANGE : TO IMPROVE PERFORMANCE
	/*$header[] =$current_module_strings['LBL_LIST_CLOSE'];
	$header[] =$current_module_strings['LBL_LIST_CONTACT'];
	$header[] =$current_module_strings['LBL_LIST_ACCOUNT'];
	$header[] =$current_module_strings['LBL_LIST_RELATED_TO'];
	$header[] =$current_module_strings['LBL_LIST_DATE'];
	$header[] =$current_module_strings['LBL_LIST_RECURRING_TYPE'];*/
	//activity select options

	// Stick the form header out there.

	$return_url="&return_module=$currentModule&return_action=DetailView&return_id=" . ((is_object($focus)) ? $focus->id : "");
	$oddRow = true;
	$entries=array();

	foreach($open_activity_list as $event)
	{
		$recur_date=ereg_replace('--','',$event['recurringdate']);
		if($recur_date!="")
			$event['date_start']=$event['recurringdate'];
			// Fredy Klammsteiner, 4.8.2005: changes from 4.0.1 migrated to 4.2
		// begin: Armando L�scher 04.07.2005 -> �priority
		// Desc: Set vtiger_priority colors
		$font_color_high = "color:#00DD00;";
		$font_color_medium = "color:#DD00DD;";

		switch ($event['priority'])
		{
			case 'High':
				$font_color=$font_color_high;
				break;
			case 'Medium':
				$font_color=$font_color_medium;
				break;
			default:
				$font_color='';
		}
		// end: Armando L�scher 04.07.2005 -> �priority


		$end_date=$event['due_date']; //included for getting the OverDue Activities in the Upcoming Activities
		$start_date=$event['date_start'];
		//CHANGE : TO IMPROVE PERFORMANCE
		/*switch ($event['type']) {
			case 'Call':
				$activity_fields = "<a href='index.php?return_module=Home&return_action=index&return_id=$focus->activityid&action=Save&module=Calendar&record=".$event['id']."&activity_type=".$event['type']."&change_status=true&eventstatus=Held' style='".$font_color."'>X</a>"; // Armando L�scher 05.07.2005 -> �priority -> Desc: inserted style="$P_FONT_COLOR"
				break;
			case 'Meeting':
				$activity_fields = "<a href='index.php?return_module=Home&return_action=index&return_id=$focus->activityid&action=Save&module=Calendar&record=".$event['id']."&activity_type=".$event['type']."&change_status=true&eventstatus=Held' style='".$font_color."'>X</a>"; // Armando L�scher 05.07.2005 -> �priority -> Desc: inserted style="$P_FONT_COLOR"

			case 'Task':
				$activity_fields = "<a href='index.php?return_module=Home&return_action=index&return_id=$focus->activityid&action=Save&module=Calendar&record=".$event['id']."&activity_type=".$event['type']."&change_status=true&status=Completed' style='".$font_color."'>X</a>"; // Armando L�scher 05.07.2005 -> �priority -> Desc: inserted style="$P_FONT_COLOR"
				break;
		}*/

		if($event['type'] != 'Task' && $event['type'] != 'Emails' && $event['type'] != '')
			$activity_type = 'Events';
		else
			$activity_type = 'Task';



		//Code included for showing Overdue Activities in Upcoming Activities -Jaguar
		$end_date=getDBInsertDateValue($end_date);
		if($end_date== '0000-00-00' OR $end_date =="")
		{
			$end_date=$start_date;
		}
		if($recur_date!="")
		{
			$recur_date=getDBInsertDateValue($recur_date);	
			$end=explode("-",$recur_date);
		}
		else
		{
			$end=explode("-",$end_date);
		}

		$current_date=date("Y-m-d",mktime(date("m"),date("d"),date("Y")));
		$curr=explode("-",$current_date);
		$date_diff= mktime(0,0,0,date("$curr[1]"),date("$curr[2]"),date("$curr[0]")) - mktime(0,0,0,date("$end[1]"),date("$end[2]"),date("$end[0]"));

		if($date_diff>0)
		{
			$x="pending";
		}
		else
		{
			if($oddRow)
			{
				$x="oddListRow";
			}
			else
			{
				$x="evenListRow";
			}
		}
		// Code by Jaguar Ends
		$entries[$event['id']] = array(
				'0' => '<a href="index.php?action=DetailView&module='.$event["module"].'&activity_mode='.$activity_type.'&record='.$event["id"].''.$return_url.'" style="'.$font_color.';">'.$event["name"].'</a>',
				'IMAGE' => '<IMG src="'.$image_path.$event["type"].'s.gif">',
				//CHANGE : TO IMPROVE PERFORMANCE
				/*'ACTIVITY' => $activity_fields,
				'CONTACT_NAME' => '<a href="index.php?action=DetailView&module=Contacts&record='.$event['contactid'].''.$return_url.'" style="'.$font_color.';">'.$event['firstname'].' '.$event['lastname'].'</a>',
				'ACCOUNT_NAME' => '<a href="index.php?action=DetailView&module=Accounts&record='.$event['accountid'].'" style="'.$font_color.';">'.$event['accountname'].'</a>',
				'PARENT_NAME' => $event['parent'],
				'TIME' => $event['date_start'],
				'RECURRINGTYPE' => ereg_replace('--','',$event['recurringtype']),*/
				);
	}
	$values=Array('noofactivities'=>$noofrecords,'Title'=>$title,'Header'=>$header,'Entries'=>$entries);
	$log->debug("Exiting getPendingActivities method ...");
		return $values;
}

/**
 * Function creates HTML to display ActivityView selection box
 * @param string   $activity_view                 - activity view 
 * return string   $ACTIVITY_VIEW_SELECT_OPTION   - HTML selection box
 */
function getActivityview($activity_view)	
{	
	global $log;
	$log->debug("Entering getActivityview(".$activity_view.") method ...");
	$today = date("Y-m-d", time());

	if($activity_view == 'Today')
	{	
		$selected1 = 'selected';
	}	
	else if($activity_view == 'This Week')
	{
		$selected2 = 'selected';
	}
	else if($activity_view == 'This Month')
	{	
		$selected3 = 'selected';
	}	
	else if($activity_view == 'This Year')	
	{
		$selected4 = 'selected';
	}

	//constructing the combo values for activities
	$ACTIVITY_VIEW_SELECT_OPTION = '<select class=small name="activity_view" onchange="showActivityView(this)">';
	$ACTIVITY_VIEW_SELECT_OPTION .= '<option value="Today" '.$selected1.'>';
	$ACTIVITY_VIEW_SELECT_OPTION .= 'Today';
	$ACTIVITY_VIEW_SELECT_OPTION .= '</option>';
	$ACTIVITY_VIEW_SELECT_OPTION .= '<option value="This Week" '.$selected2.'>';
	$ACTIVITY_VIEW_SELECT_OPTION .= 'This Week';
	$ACTIVITY_VIEW_SELECT_OPTION .= '</option>';
	$ACTIVITY_VIEW_SELECT_OPTION .= '<option value="This Month" '.$selected3.'>';
	$ACTIVITY_VIEW_SELECT_OPTION .= 'This Month';
	$ACTIVITY_VIEW_SELECT_OPTION .= '</option>';
	$ACTIVITY_VIEW_SELECT_OPTION .= '<option value="This Year" '.$selected4.'>';
	$ACTIVITY_VIEW_SELECT_OPTION .= 'This Year';
	$ACTIVITY_VIEW_SELECT_OPTION .= '</option>';
	$ACTIVITY_VIEW_SELECT_OPTION .= '</select>';
	
	$log->debug("Exiting getActivityview method ...");
	return $ACTIVITY_VIEW_SELECT_OPTION;
}
?>
