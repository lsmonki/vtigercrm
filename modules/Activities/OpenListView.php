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

function getPendingActivities()
{
	require_once('XTemplate/xtpl.php');
	require_once("data/Tracker.php");
	require_once("include/utils/utils.php");

	global $currentModule;

	global $theme;
	global $focus;
	global $action;
	global $adb;
	global $app_strings;
	global $current_language;
	global $current_user;
	$current_module_strings = return_module_language($current_language, 'Activities');

	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	require_once($theme_path.'layout_utils.php');
	//code added to customize upcomming and pending activities
	if($_REQUEST['activity_view']=='')
	{	
		$query = "select activity_view from users where id ='$current_user->id'";
		$result=$adb->query($query);
		$activity_view=$adb->query_result($result,0,'activity_view');
	}
	else
		$activity_view=$_REQUEST['activity_view'];

	$today = date("Y-m-d", time());

	if($activity_view == 'Today')
	{	
		$later = date("Y-m-d",strtotime("$today + 1 day"));
	}	
	else if($activity_view == 'This Week')
	{
		$later = date("Y-m-d", strtotime("$today + 7 days"));
	}
	else if($activity_view == 'This Month')
	{	
		$later = date("Y-m-d", strtotime("$today + 1 month"));
	}	
	else if($activity_view == 'This Year')	
	{
		$later = date("Y-m-d", strtotime("$today + 1 year"));
	}
	else if($activity_view == 'OverDue')	
	{
		$later = date("Y-m-d", strtotime("$today +1 day"));
	}

	if($activity_view != 'OverDue')
	{
		$list_query = " select crmentity.crmid,crmentity.smownerid,crmentity.setype, activity.*, contactdetails.lastname, contactdetails.firstname, contactdetails.contactid, account.accountid, account.accountname, recurringevents.recurringtype,recurringevents.recurringdate from activity inner join crmentity on crmentity.crmid=activity.activityid left join cntactivityrel on cntactivityrel.activityid= activity.activityid left join contactdetails on contactdetails.contactid= cntactivityrel.contactid left join seactivityrel on seactivityrel.activityid = activity.activityid left outer join account on account.accountid = contactdetails.accountid left outer join recurringevents on recurringevents.activityid=activity.activityid inner join salesmanactivityrel on salesmanactivityrel.activityid=activity.activityid WHERE crmentity.deleted=0 and (activity.activitytype = 'Meeting' or activity.activitytype='Call' or activity.activitytype='Task') AND ( activity.status is NULL || activity.status != 'Completed' ) and ( activity.status is NULL || activity.status != 'Deferred') and  (  activity.eventstatus is NULL ||  activity.eventstatus != 'Held') and (activity.eventstatus is NULL ||  activity.eventstatus != 'Not Held' ) AND (((date_start >= '$today' AND date_start < '$later') OR (date_start < '$today'))  OR (recurringevents.recurringdate between '$today' and '$later') ) AND crmentity.smownerid !=0 AND salesmanactivityrel.smid ='$current_user->id'";
	}	
	else
	{
		$list_query = " select crmentity.crmid,crmentity.smownerid,crmentity.setype, activity.*, contactdetails.lastname, contactdetails.firstname, contactdetails.contactid, account.accountid, account.accountname, recurringevents.recurringtype,recurringevents.recurringdate from activity inner join crmentity on crmentity.crmid=activity.activityid left join cntactivityrel on cntactivityrel.activityid= activity.activityid left join contactdetails on contactdetails.contactid= cntactivityrel.contactid left join seactivityrel on seactivityrel.activityid = activity.activityid left outer join account on account.accountid = contactdetails.accountid left outer join recurringevents on recurringevents.activityid=activity.activityid inner join salesmanactivityrel on salesmanactivityrel.activityid=activity.activityid WHERE crmentity.deleted=0 and (activity.activitytype = 'Meeting' or activity.activitytype='Call' or activity.activitytype='Task') AND ( activity.status is NULL || activity.status != 'Completed' ) and ( activity.status is NULL || activity.status != 'Deferred') and (  activity.eventstatus is NULL ||  activity.eventstatus != 'Held') and (activity.eventstatus is NULL ||  activity.eventstatus != 'Not Held' ) AND (due_date < '$today') OR (recurringevents.recurringdate < '$today') AND crmentity.smownerid !=0 AND salesmanactivityrel.smid ='$current_user->id'";
	}
	$list_result = $adb->limitQuery($list_query,0,5);
	$open_activity_list = array();
	$noofrows = $adb->num_rows($list_result);
	if (count($list_result)>0)
		for($i=0;$i<$noofrows;$i++) 
		{
			$parent_name=getRelatedTo("Activities",$list_result,$i);
			$open_activity_list[] = Array('name' => $adb->query_result($list_result,$i,'subject'),
					'id' => $adb->query_result($list_result,$i,'activityid'),
					'type' => $adb->query_result($list_result,$i,'activitytype'),
					'module' => $adb->query_result($list_result,$i,'setype'),
					'status' => $adb->query_result($list_result,$i,'status'),
					'firstname' => $adb->query_result($list_result,$i,'firstname'),
					'lastname' => $adb->query_result($list_result,$i,'lastname'),
					'accountname' => $adb->query_result($list_result,$i,'accountname'),
					'accountid' => $adb->query_result($list_result, $i, 'accountid'),
					'contactid' => $adb->query_result($list_result,$i,'contactid'),
					'date_start' => getDisplayDate($adb->query_result($list_result,$i,'date_start')),
					'due_date' => getDisplayDate($adb->query_result($list_result,$i,'due_date')),
					'recurringtype' => getDisplayDate($adb->query_result($list_result,$i,'recurringtype')),
					'recurringdate' => getDisplayDate($adb->query_result($list_result,$i,'recurringdate')),
					'parent'=> $parent_name,
					// Fredy Klammsteiner, 4.8.2005: changes from 4.0.1 migrated to 4.2
					'priority' => $adb->query_result($list_result,$i,'priority'), // Armando Lüscher 04.07.2005 -> §priority -> Desc: Get priority from db
					);
		}
	$later_day = getDisplayDate(date("Y-m-d", strtotime("$later -1 day ")));
	
	$title=array();
	$title[]='myUpcoPendAct.gif';
	$title[]=$current_module_strings["LBL_UPCOMING"];
	//.'('.$current_module_strings["LBL_TODAY"].' '.$later_day.')';
	$title[]='home_myact';
	$title[]=getActivityView($activity_view);
	$title[]='showActivityView';		
	$title[]='MyUpcumingFrm';
	$title[]='activity_view';

	$header=array();
	$header[] ='Type';
	$header[] =$current_module_strings['LBL_LIST_CLOSE'];
	$header[] =$current_module_strings['LBL_LIST_SUBJECT'];
	$header[] =$current_module_strings['LBL_LIST_CONTACT'];
	$header[] =$current_module_strings['LBL_LIST_ACCOUNT'];
	$header[] =$current_module_strings['LBL_LIST_RELATED_TO'];
	$header[] =$current_module_strings['LBL_LIST_DATE'];
	$header[] =$current_module_strings['LBL_LIST_RECURRING_TYPE'];
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
		// begin: Armando Lüscher 04.07.2005 -> §priority
		// Desc: Set priority colors
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
		// end: Armando Lüscher 04.07.2005 -> §priority


		$end_date=$event['due_date']; //included for getting the OverDue Activities in the Upcoming Activities
		$start_date=$event['date_start'];

		switch ($event['type']) {
			case 'Call':
				$activity_fields = "<a href='index.php?return_module=Home&return_action=index&return_id=$focus->activityid&action=Save&module=Activities&record=".$event['id']."&activity_type=".$event['type']."&change_status=true&eventstatus=Held' style='".$font_color."'>X</a>"; // Armando Lüscher 05.07.2005 -> §priority -> Desc: inserted style="$P_FONT_COLOR"
				break;
			case 'Meeting':
				$activity_fields = "<a href='index.php?return_module=Home&return_action=index&return_id=$focus->activityid&action=Save&module=Activities&record=".$event['id']."&activity_type=".$event['type']."&change_status=true&eventstatus=Held' style='".$font_color."'>X</a>"; // Armando Lüscher 05.07.2005 -> §priority -> Desc: inserted style="$P_FONT_COLOR"

			case 'Task':
				$activity_fields = "<a href='index.php?return_module=Home&return_action=index&return_id=$focus->activityid&action=Save&module=Activities&record=".$event['id']."&activity_type=".$event['type']."&change_status=true&status=Completed' style='".$font_color."'>X</a>"; // Armando Lüscher 05.07.2005 -> §priority -> Desc: inserted style="$P_FONT_COLOR"
				break;
		}

		if($event['type'] == 'Call' || $event['type'] == 'Meeting')
			$activity_type = 'Events';
		else
			$activity_type = 'Task';

		//$xtpl->assign("ACTIVITY", $activity_fields);


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
				'IMAGE' => '<IMG src="'.$image_path.$event["type"].'s.gif">',
				'ACTIVITY' => $activity_fields,
				'NAME' => '<a href="index.php?action=DetailView&module='.$event["module"].'&activity_mode='.$activity_type.'&record='.$event["id"].''.$return_url.'" style="'.$font_color.';">'.$event["name"].'</a>',
				'CONTACT_NAME' => '<a href="index.php?action=DetailView&module=Contacts&record='.$event['contactid'].''.$return_url.'" style="'.$font_color.';">'.$event['firstname'].' '.$event['lastname'].'</a>',
				'ACCOUNT_NAME' => '<a href="index.php?action=DetailView&module=Accounts&record='.$event['accountid'].'" style="'.$font_color.';">'.$event['accountname'].'</a>',
				'PARENT_NAME' => $event['parent'],
				'TIME' => $event['date_start'],
				'RECURRINGTYPE' => ereg_replace('--','',$event['recurringtype']),
				);
	}
	$values=Array('Title'=>$title,'Header'=>$header,'Entries'=>$entries);
		return $values;
}
function getActivityview($activity_view)	
{	
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
	else if($activity_view == 'OverDue')	
	{
		$selected5 = 'selected';
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
	$ACTIVITY_VIEW_SELECT_OPTION .= '<option value="OverDue" '.$selected5.'>';
	$ACTIVITY_VIEW_SELECT_OPTION .= 'OverDue';
	$ACTIVITY_VIEW_SELECT_OPTION .= '</option>';
	$ACTIVITY_VIEW_SELECT_OPTION .= '</select>';
	
	return $ACTIVITY_VIEW_SELECT_OPTION;
}
?>
