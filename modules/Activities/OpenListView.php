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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Activities/OpenListView.php,v 1.19 2005/03/28 18:19:29 rank Exp $
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
$where = "AND (activity.status != 'Completed' or activity.status is null) AND date_start >= '$today' AND date_start < '$later' AND crmentity.smownerid ='{$current_user->id}' ORDER BY date_start";

$list_query = getListQuery("Activities",$where);
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
                                     'contactid' => $adb->query_result($list_result,$i,'contactid'),
                                     'date_start' => $adb->query_result($list_result,$i,'date_start'),
				     'parent'=> $parent_name,	
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
#if (count($open_activity_list) > 0) $open_activity_list = array_csort($open_activity_list, 'date_start', 'time_start', SORT_ASC);
foreach($open_activity_list as $event)
{
	$activity_fields = array(
		'ID' => $event['id'],
		'CONTACT_ID' => $event['contactid'],
		'NAME' => $event['name'],
		'TYPE' => $event['type'],
		'MODULE' => $event['module'],
		'STATUS' => $event['status'],
		'CONTACT_NAME' => $event['firstname'].' '.$event['lastname'],
		'TIME' => $event['date_start'],
		'PARENT_NAME' => $event['parent'],
	);
	switch ($event['type']) {
	//	case 'Call':
	//		$activity_fields['SET_COMPLETE'] = "<a href='index.php?return_module=Home&return_action=index&return_id=$focus->activityid&action=Save&module=Activities&record=".$event['id']."&activity_type=".$event['type']."&change_status=true&status=Completed'>X</a>";
	//		break;
	//	case 'Meeting':
	//		$activity_fields['SET_COMPLETE'] = "<a href='index.php?return_module=Home&return_action=index&return_id=$focus->activityid&action=Save&module=Activities&record=".$event['id']."&activity_type=".$event['type']."&change_status=true&status=Completed'>X</a>";
		case 'Task':
			$activity_fields['SET_COMPLETE'] = "<a href='index.php?return_module=Home&return_action=index&return_id=$focus->activityid&action=Save&module=Activities&record=".$event['id']."&activity_type=".$event['type']."&change_status=true&status=Completed'>X</a>";
			break;
	}

        if($event['type'] == 'Call' || $event['type'] == 'Meeting')
                $activity_fields['MODE'] = 'Events';
	else
		$activity_fields['MODE'] = 'Task';

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
