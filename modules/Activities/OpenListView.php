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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Activities/OpenListView.php,v 1.3 2004/10/29 09:55:09 jack Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once("include/utils.php");
require_once("modules/Calls/Call.php");
require_once("modules/Meetings/Meeting.php");

global $currentModule;

global $theme;
global $focus;
global $action;

global $app_strings;
//we don't want the parent module's string file, but rather the string file specifc to this subpanel
global $current_language;
$current_module_strings = return_module_language($current_language, 'Activities');

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$today = date("Y-m-d", time());
$later = date("Y-m-d", strtotime("$today + 7 days"));

$meeting = new Meeting();
$where = "status = 'Planned' AND date_start >= '$today' AND date_start < '$later' AND assigned_user_id='{$current_user->id}'";
$focus_meetings_list = $meeting->get_full_list("time_start", $where);

$call = new Call();
$where = "status = 'Planned' AND date_start >= '$today' AND date_start < '$later' and assigned_user_id='$current_user->id'";
$focus_calls_list = $call->get_full_list("time_start", $where);

$open_activity_list = array();

if (count($focus_meetings_list)>0)
  foreach ($focus_meetings_list as $meeting) {
	$open_activity_list[] = Array('name' => $meeting->name,
								 'id' => $meeting->id,
								 'type' => "Meeting",
								 'module' => "Meetings",
								 'status' => $meeting->status,
								 'parent_id' => $meeting->parent_id,
								 'parent_type' => $meeting->parent_type,
								 'parent_name' => $meeting->parent_name,
								 'contact_id' => $meeting->contact_id,
								 'contact_name' => $meeting->contact_name,
								 'date_start' => $meeting->date_start,
								 'time_start' => $meeting->time_start
								 );
}

if (count($focus_calls_list)>0)
  foreach ($focus_calls_list as $call) {
	$open_activity_list[] = Array('name' => $call->name,
								 'id' => $call->id,
								 'type' => "Call",
								 'module' => "Calls",
								 'status' => $call->status,
								 'parent_id' => $call->parent_id,
								 'parent_type' => $call->parent_type,
								 'parent_name' => $call->parent_name,
								 'contact_id' => $call->contact_id,
								 'contact_name' => $call->contact_name,
								 'date_start' => $call->date_start,
								 'time_start' => $call->time_start
								 );
}

$xtpl=new XTemplate ('modules/Activities/OpenListView.html');
$xtpl->assign("MOD", $current_module_strings);
$xtpl->assign("APP", $app_strings);

// Stick the form header out there.
$later_day = date("Y-m-d", strtotime("$today + 7 days"));
echo get_form_header($current_module_strings['LBL_UPCOMING'], "<table><tr><td nowrap>".$current_module_strings['LBL_TODAY'].$later_day."</td></tr></table>", false);

$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("RETURN_URL", "&return_module=$currentModule&return_action=DetailView&return_id=" . ((is_object($focus)) ? $focus->id : ""));

$oddRow = true;
if (count($open_activity_list) > 0) $open_activity_list = array_csort($open_activity_list, 'date_start', 'time_start', SORT_ASC);
foreach($open_activity_list as $activity)
{
	$activity_fields = array(
		'ID' => $activity['id'],
		'NAME' => $activity['name'],
		'TYPE' => $activity['type'],
		'MODULE' => $activity['module'],
		'STATUS' => $activity['status'],
		'CONTACT_NAME' => $activity['contact_name'],
		'CONTACT_ID' => $activity['contact_id'],
		'PARENT_TYPE' => $activity['parent_type'],
		'PARENT_NAME' => $activity['parent_name'],
		'PARENT_ID' => $activity['parent_id'],
		'TIME' => $activity['date_start'].' '.substr($activity['time_start'],0,5)
	);
	switch ($activity['parent_type']) {
		case 'Accounts':
			$activity_fields['PARENT_MODULE'] = 'Accounts';
			break;
		case 'Cases':
			$activity_fields['PARENT_MODULE'] = 'Cases';
			break;
		case 'Opportunities':
			$activity_fields['PARENT_MODULE'] = 'Opportunities';
			break;
	}
	switch ($activity['type']) {
		case 'Call':
			$activity_fields['SET_COMPLETE'] = "<a href='index.php?return_module=$currentModule&return_action=$action&return_id=$focus->id&action=Save&module=Calls&record=".$activity['id']."&status=Held'>X</a>";
			break;
		case 'Meeting':
			$activity_fields['SET_COMPLETE'] = "<a href='index.php?return_module=$currentModule&return_action=$action&return_id=$focus->id&action=Save&module=Meetings&record=".$activity['id']."&status=Held'>X</a>";
			break;
	}


	$xtpl->assign("ACTIVITY", $activity_fields);

	if($oddRow)
    {
        //todo move to themes
		$xtpl->assign("ROW_COLOR", 'oddListRow');
    }
    else
    {
        //todo move to themes
		$xtpl->assign("ROW_COLOR", 'evenListRow');
    }
    $oddRow = !$oddRow;

	$xtpl->parse("open_activity.row");
// Put the rows in.
}

$xtpl->parse("open_activity");
if (count($open_activity_list)>0) $xtpl->out("open_activity");
else echo "<em>".$current_module_strings['NTC_NONE_SCHEDULED']."</em>";
echo "<BR>";
// Stick on the form footer
echo get_form_footer();

?>
