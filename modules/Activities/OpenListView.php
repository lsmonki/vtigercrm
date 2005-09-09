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

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once("include/utils.php");

global $currentModule;

global $theme;
global $focus;
global $action;

global $app_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Activities');

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$today = date("Y-m-d", time());
$later = date("Y-m-d", strtotime("$today + 7 days"));

//$activity = new Activity();
//change made as requested by community by shaw
 $list_query = " select crmentity.crmid,crmentity.smownerid,crmentity.setype, activity.*, contactdetails.lastname, contactdetails.firstname, contactdetails.contactid, account.accountid, account.accountname, recurringevents.recurringtype,recurringevents.recurringdate from activity inner join crmentity on crmentity.crmid=activity.activityid left join cntactivityrel on cntactivityrel.activityid= activity.activityid left join contactdetails on contactdetails.contactid= cntactivityrel.contactid left join seactivityrel on seactivityrel.activityid = activity.activityid left outer join account on account.accountid = contactdetails.accountid left outer join recurringevents on recurringevents.activityid=activity.activityid inner join salesmanactivityrel on salesmanactivityrel.activityid=activity.activityid WHERE crmentity.deleted=0 and (activity.activitytype = 'Meeting' or activity.activitytype='Call' or activity.activitytype='Task') AND ( activity.status is NULL || activity.status != 'Completed' ) and (  activity.eventstatus is NULL ||  activity.eventstatus != 'Held') and (  activity.eventstatus is NULL ||  activity.eventstatus != 'Not Held' ) AND (((date_start >= '$today' AND date_start < '$later') OR (date_start < '$today'))  OR (recurringevents.recurringdate between '$today' and '$later') ) AND salesmanactivityrel.smid ='{$current_user->id}'";

//$list_query = getListQuery("Activities",$where);
//echo $list_query."<h3>END</h3>";
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
                                     );
}

$xtpl=new XTemplate ('modules/Activities/OpenListView.html');
$xtpl->assign("MOD", $current_module_strings);
$xtpl->assign("APP", $app_strings);

// Stick the form header out there.
$later_day = getDisplayDate(date("Y-m-d", strtotime("$today + 7 days")));
//echo get_form_header($current_module_strings['LBL_UPCOMING'], "<table><tr><td nowrap>".$current_module_strings['LBL_TODAY'].$later_day."</td></tr></table>", false);

$xtpl->assign("ENDDATE", $later_day);

$xtpl->assign("IMAGE_PATH", $image_path);

$xtpl->assign("RETURN_URL", "&return_module=$currentModule&return_action=DetailView&return_id=" .((is_object($focus)) ? $focus->id : ""));

$oddRow = true;
#if (count($open_activity_list) > 0) $open_activity_list = array_csort($open_activity_list, 'date_start', 'time_start', SORT_ASC);

foreach($open_activity_list as $event)
{
	$recur_date=ereg_replace('--','',$event['recurringdate']);
        if($recur_date!="")
                 $event['date_start']=$event['recurringdate'];
	$activity_fields = array(
		'ID' => $event['id'],
		'CONTACT_ID' => $event['contactid'],
		'ACCOUNT_ID' => $event['accountid'],
		'NAME' => $event['name'],
		'TYPE' => $event['type'],
		'MODULE' => $event['module'],
		'STATUS' => $event['status'],
		'CONTACT_NAME' => $event['firstname'].' '.$event['lastname'],
		'ACCOUNT_NAME' => $event['accountname'],
		'TIME' => $event['date_start'],
		'RECURRINGTYPE' => ereg_replace('--','',$event['recurringtype']),
		'DUEDATE' => ereg_replace('--','',$event['due_date']),
		'RECURRINGDATE' => ereg_replace('--','',$event['recurringdate']),
		'PARENT_NAME' => $event['parent'],
	);
	$end_date=$event['due_date']; //included for getting the OverDue Activities in the Upcoming Activities
	$start_date=$event['date_start'];

	switch ($event['type']) {
		case 'Call':
			$activity_fields['SET_COMPLETE'] = "<a href='index.php?return_module=Home&return_action=index&return_id=$focus->activityid&action=Save&module=Activities&record=".$event['id']."&activity_type=".$event['type']."&change_status=true&eventstatus=Held'>X</a>";
		break;
		case 'Meeting':
			$activity_fields['SET_COMPLETE'] = "<a href='index.php?return_module=Home&return_action=index&return_id=$focus->activityid&action=Save&module=Activities&record=".$event['id']."&activity_type=".$event['type']."&change_status=true&eventstatus=Held'>X</a>";
		case 'Task':
			$activity_fields['SET_COMPLETE'] = "<a href='index.php?return_module=Home&return_action=index&return_id=$focus->activityid&action=Save&module=Activities&record=".$event['id']."&activity_type=".$event['type']."&change_status=true&status=Completed'>X</a>";
			break;
	}

        if($event['type'] == 'Call' || $event['type'] == 'Meeting')
                $activity_fields['MODE'] = 'Events';
	else
		$activity_fields['MODE'] = 'Task';

	$xtpl->assign("ACTIVITY", $activity_fields);
	
	
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


    if($oddRow)
    {
        	//todo move to themes
		$xtpl->assign("ROW_COLOR", $x);
		//$xtpl->assign("ROW_COLOR", 'oddListRow');
    }
    else
    {
        //todo move to themes
		$xtpl->assign("ROW_COLOR", $x);
		//$xtpl->assign("ROW_COLOR", 'evenListRow');
    }
        $oddRow = !$oddRow;
        
	$xtpl->parse("open_activity.row");
        // Put the rows in.
}

$xtpl->parse("open_activity");
$xtpl->out("open_activity");
//if (count($open_activity_list)>0) $xtpl->out("open_activity");
//else echo "<em>".$current_module_strings['NTC_NONE_SCHEDULED']."</em>";
echo "<BR>";
// Stick on the form footer
echo get_form_footer();

?>
