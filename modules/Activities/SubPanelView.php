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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Activities/SubPanelView.php,v 1.14 2005/03/02 13:56:52 jack Exp $
 * Description:  TODO: To be written.
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");

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

// history_list is the means of passing data to a SubPanelView.
global $focus_tasks_list;
global $focus_meetings_list;
global $focus_calls_list;
global $focus_emails_list;

$open_activity_list = Array();
$history_list = Array();

foreach ($focus_tasks_list as $task) {
	if ($task->status != "Not Started" && $task->status != "In Progress" && $task->status != "Pending Input") {
		$history_list[] = Array('name' => $task->name,
									 'id' => $task->id,
									 'type' => "Task",
									 'module' => "Activities",
									 'status' => $task->status,
									 'parent_id' => $task->parent_id,
									 'parent_type' => $task->parent_type,
									 'parent_name' => $task->parent_name,
									 'contact_id' => $task->contact_id,
									 'contact_name' => $task->contact_name,
									 'date_modified' => $task->date_modified
									 );
	}
	else {
		if ($task->date_due == '0000-00-00') $date_due = ''; 
		else $date_due = $task->date_due;
		$open_activity_list[] = Array('name' => $task->name,
									 'id' => $task->id,
									 'type' => "Task",
									 'module' => "Activities",
									 'status' => $task->status,
									 'parent_id' => $task->parent_id,
									 'parent_type' => $task->parent_type,
									 'parent_name' => $task->parent_name,
									 'contact_id' => $task->contact_id,
									 'contact_name' => $task->contact_name,
									 'date_due' => $date_due
									 );	
	}
}

foreach ($focus_meetings_list as $meeting) {
	if ($meeting->status != "Planned") {
		$history_list[] = Array('name' => $meeting->name,
									 'id' => $meeting->id,
									 'type' => "Meeting",
									 'module' => "Meetings",
									 'status' => $meeting->status,
									 'parent_id' => $meeting->parent_id,
									 'parent_type' => $meeting->parent_type,
									 'parent_name' => $meeting->parent_name,
									 'contact_id' => $meeting->contact_id,
									 'contact_name' => $meeting->contact_name,
									 'date_modified' => $meeting->date_modified
									 );
	}
	else {
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
									 'date_due' => $meeting->date_start
									 );	
	}
}

foreach ($focus_calls_list as $call) {
	if ($call->status != "Planned") {
		$history_list[] = Array('name' => $call->name,
									 'id' => $call->id,
									 'type' => "Call",
									 'module' => "Calls",
									 'status' => $call->status,
									 'parent_id' => $call->parent_id,
									 'parent_type' => $call->parent_type,
									 'parent_name' => $call->parent_name,
									 'contact_id' => $call->contact_id,
									 'contact_name' => $call->contact_name,
									 'date_modified' => $call->date_modified
									 );
	}
	else {
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
									 'date_due' => $call->date_start
									 );	
	}
}

foreach ($focus_emails_list as $email) {
	$history_list[] = Array('name' => $email->name,
									 'id' => $email->id,
									 'type' => "Email",
									 'module' => "Emails",						 
									 'status' => '',
									 'parent_id' => $email->parent_id,
									 'parent_type' => $email->parent_type,
									 'parent_name' => $email->parent_name,
									 'contact_id' => $email->contact_id,
									 'contact_name' => $email->contact_name,
									 'date_modified' => $email->date_modified
									 );
}

foreach ($focus_notes_list as $note) {
	$history_list[] = Array('name' => $note->name,
									 'id' => $note->id,
									 'type' => "Note",
									 'module' => "Notes",						 
									 'status' => '',
									 'parent_id' => $note->parent_id,
									 'parent_type' => $note->parent_type,
									 'parent_name' => $note->parent_name,
									 'contact_id' => $note->contact_id,
									 'contact_name' => $note->contact_name,
									 'date_modified' => $note->date_modified
									 );
}

if ($currentModule == 'Contacts' || $currentModule == 'Leads') {
	$xtpl=new XTemplate ('modules/Activities/SubPanelViewContacts.html'); 
	$xtpl->assign("CONTACT_ID", $focus->id);
}
else $xtpl=new XTemplate ('modules/Activities/SubPanelView.html');

$xtpl->assign("MOD", $current_module_strings);
$xtpl->assign("APP", $app_strings);

$button  = "<table cellspacing='0' cellpadding='1' border='0'><form border='0' action='index.php' method='post' name='form' id='form'>\n";
$button .= "<input type='hidden' name='module'>\n";
if ($currentModule == 'Accounts') $button .= "<input type='hidden' name='parent_type' value='Account'>\n<input type='hidden' name='parent_id' value='$focus->id'>\n<input type='hidden' name='parent_name' value='$focus->name'>\n";
if ($currentModule == 'Opportunities') $button .= "<input type='hidden' name='parent_type' value='Opportunity'>\n<input type='hidden' name='parent_id' value='$focus->id'>\n<input type='hidden' name='parent_name' value='$focus->name'>\n";
if ($currentModule == 'Cases') $button .= "<input type='hidden' name='parent_type' value='Case'>\n<input type='hidden' name='parent_id' value='$focus->id'>\n<input type='hidden' name='parent_name' value='$focus->name'>\n";
if ($currentModule == 'Contacts') {
	$button .= "<input type='hidden' name='contact_id' value='$focus->id'>\n<input type='hidden' name='contact_name' value='$focus->first_name $focus->last_name'>\n";
	$button .= "<input type='hidden' name='parent_type' value='Contacts'>\n<input type='hidden' name='parent_id' value='$focus->account_id'>\n<input type='hidden' name='parent_name' value='$focus->account_name'>\n";
}
if ($currentModule == 'Leads') {
	$button .= "<input type='hidden' name='lead_id' value='$focus->id'>\n<input type='hidden' name='parent_name' value='$focus->first_name $focus->last_name'>\n";
	$button .= "<input type='hidden' name='parent_type' value='Leads'>\n<input type='hidden' name='parent_id' value='$focus->id'>\n";
}

$button .= "<input type='hidden' name='return_module' value='".$currentModule."'>\n";
$button .= "<input type='hidden' name='return_action' value='".$action."'>\n";
$button .= "<input type='hidden' name='return_id' value='".$focus->id."'>\n";
$button .= "<input type='hidden' name='action'>\n";
$button .= "<tr><td>&nbsp;</td>";
$button .= "<td><input title='".$current_module_strings['LBL_NEW_TASK_BUTTON_TITLE']."' accessyKey='".$current_module_strings['LBL_NEW_TASK_BUTTON_KEY']."' class='button' onclick=\"this.form.action.value='EditView';this.form.module.value='Activities'\" type='submit' name='button' value='".$current_module_strings['LBL_NEW_TASK_BUTTON_LABEL']."'></td>\n";
$button .= "<td><input title='".$current_module_strings['LBL_SCHEDULE_MEETING_BUTTON_TITLE']."' accessKey='".$current_module_strings['LBL_SCHEDULE_MEETING_BUTTON_KEY']."' class='button' onclick=\"this.form.action.value='EditView';this.form.module.value='Meetings'\" type='submit' name='button' value='".$current_module_strings['LBL_SCHEDULE_MEETING_BUTTON_LABEL']."'></td>\n";
$button .= "<td><input title='".$current_module_strings['LBL_SCHEDULE_CALL_BUTTON_TITLE']."' accessyKey='".$current_module_strings['LBL_SCHEDULE_CALL_BUTTON_KEY']."' class='button' onclick=\"this.form.action.value='EditView';this.form.module.value='Calls'\" type='submit' name='button' value='".$current_module_strings['LBL_SCHEDULE_CALL_BUTTON_LABEL']."'></td>\n";
$button .= "</tr></form></table>\n";

// Stick the form header out there.
echo get_form_header($current_module_strings['LBL_OPEN_ACTIVITIES'], $button, false);

$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("RETURN_URL", "&return_module=$currentModule&return_action=DetailView&return_id=$focus->id");

$oddRow = true;
if (count($open_activity_list) > 0) $open_activity_list = array_csort($open_activity_list, 'date_due', SORT_DESC);
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
		'DATE' => $activity['date_due']
	);
	if (isset($activity['parent_type'])) $activity_fields['PARENT_MODULE'] = $app_list_strings['record_type_module'][$activity['parent_type']];
	switch ($activity['type']) {
		case 'Call':
			$activity_fields['SET_COMPLETE'] = "<a href='index.php?return_module=$currentModule&return_action=$action&return_id=$focus->id&action=Save&module=Calls&record=".$activity['id']."&status=Held'>X</a>";
			break;
		case 'Meeting':
			$activity_fields['SET_COMPLETE'] = "<a href='index.php?return_module=$currentModule&return_action=$action&return_id=$focus->id&action=Save&module=Meetings&record=".$activity['id']."&status=Held'>X</a>";
			break;
		case 'Task':
			$activity_fields['SET_COMPLETE'] = "<a href='index.php?return_module=$currentModule&return_action=$action&return_id=$focus->id&action=Save&module=Activities&record=".$activity['id']."&status=Completed'>X</a>";
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
$xtpl->out("open_activity");
echo "<BR>";
// Stick on the form footer
echo get_form_footer();


$button  = "<table cellspacing='0' cellpadding='1' border='0'><form border='0' action='index.php' method='post' name='form' id='form'>\n";
$button .= "<input type='hidden' name='module'>\n";
if ($currentModule == 'Accounts') $button .= "<input type='hidden' name='parent_type' value='Account'>\n<input type='hidden' name='parent_id' value='$focus->id'>\n<input type='hidden' name='parent_name' value='$focus->name'>\n";
if ($currentModule == 'Opportunities') $button .= "<input type='hidden' name='parent_type' value='Opportunity'>\n<input type='hidden' name='parent_id' value='$focus->id'>\n<input type='hidden' name='parent_name' value='$focus->name'>\n";
if ($currentModule == 'Cases') $button .= "<input type='hidden' name='parent_type' value='Case'>\n<input type='hidden' name='parent_id' value='$focus->id'>\n<input type='hidden' name='parent_name' value='$focus->name'>\n";
if ($currentModule == 'Contacts') {
	$button .= "<input type='hidden' name='contact_id' value='$focus->id'>\n<input type='hidden' name='contact_name' value='$focus->first_name $focus->last_name'>\n";
	$button .= "<input type='hidden' name='parent_type' value='Contacts'>\n<input type='hidden' name='parent_id' value='$focus->account_id'>\n<input type='hidden' name='parent_name' value='$focus->account_name'>\n";
}
if ($currentModule == 'Leads') {
	$button .= "<input type='hidden' name='lead_id' value='$focus->id'>\n<input type='hidden' name='parent_name' value='$focus->first_name $focus->last_name'>\n";
	$button .= "<input type='hidden' name='parent_type' value='Leads'>\n<input type='hidden' name='parent_id' value='$focus->id'>\n";
}
$button .= "<input type='hidden' name='return_module' value='".$currentModule."'>\n";
$button .= "<input type='hidden' name='return_action' value='".$action."'>\n";
$button .= "<input type='hidden' name='return_id' value='".$focus->id."'>\n";
$button .= "<input type='hidden' name='action'>\n";
$button .= "<tr><td>&nbsp;</td>";
$button .= "<td><input title='".$current_module_strings['LBL_NEW_NOTE_BUTTON_TITLE']."' accessyKey='".$current_module_strings['LBL_NEW_NOTE_BUTTON_KEY']."' class='button' onclick=\"this.form.action.value='EditView';this.form.module.value='Notes'\" type='submit' name='button' value='".$current_module_strings['LBL_NEW_NOTE_BUTTON_LABEL']."'></td>\n";
$button .= "<td><input title='".$current_module_strings['LBL_TRACK_EMAIL_BUTTON_TITLE']."' accessKey='".$current_module_strings['LBL_TRACK_EMAIL_BUTTON_KEY']."' class='button' onclick=\"this.form.action.value='EditView';this.form.module.value='Emails'\" type='submit' name='button' value='".$current_module_strings['LBL_TRACK_EMAIL_BUTTON_LABEL']."'></td>\n";
$button .= "</tr></form></table>\n";

// Stick the form header out there.
echo get_form_header($current_module_strings['LBL_HISTORY'], $button, false);

$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("RETURN_URL", "&return_module=$currentModule&return_action=DetailView&return_id=$focus->id");

$oddRow = true;
if (count($history_list) > 0) $history_list = array_csort($history_list, 'date_modified', SORT_DESC);
foreach($history_list as $activity)
{
	$activity_fields = array(
		'ID' => $activity['id'],
		'NAME' => $activity['name'],
		'TYPE' => $activity['type'],
		'MODULE' => $activity['module'],
		'CONTACT_NAME' => $activity['contact_name'],
		'CONTACT_ID' => $activity['contact_id'],
		'PARENT_TYPE' => $activity['parent_type'],
		'PARENT_NAME' => $activity['parent_name'],
		'PARENT_ID' => $activity['parent_id'],
		'DATE' => substr($activity['date_modified'], 0, 16)
	);
	if (isset($activity['status'])) $activity_fields['STATUS'] = $activity['status'];
	if (isset($activity['location'])) $activity_fields['LOCATION'] = $activity['location'];
    
	if (isset($activity['parent_type'])) $activity_fields['PARENT_MODULE'] = $app_list_strings['record_type_module'][$activity['parent_type']];
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

	$xtpl->parse("history.row");
// Put the rows in.
}

$xtpl->parse("history");
$xtpl->out("history");

require_once('modules/uploads/binaryfilelist.php');
echo '<br><br>';
echo '<table width="100%" cellpadding="0" cellspacing="0" border="0"><tbody><tr>';
echo '<form border="0" action="index.php" method="post" name="form" id="form">';

echo '<input type="hidden" name="module">';
echo '<input type="hidden" name="return_module" value="'.$currentModule.'">';
echo '<input type="hidden" name="return_id" value="'.$focus->id.'">';
echo '<input type="hidden" name="action">';

echo '<td>';
echo '<table cellpadding="0" cellspacing="0" border="0"><tbody><tr>
                <td class="formHeader" vAlign="top" align="left" height="20">
         <img src="' .$image_path. '/left_arc.gif" border="0"></td>

        <td class="formHeader" vAlign="middle" background="' . $image_path. '/header_tile.gif" align="left" noWrap width="100%" height="20">'.$current_module_strings['LBL_ATTACHMENTS'].'</td>
        <td  class="formHeader" vAlign="top" align="right" height="20">
                  <img src="' .$image_path. '/right_arc.gif" border="0"></td>
                </tr></tbody></table>
      </td>';
echo '<td>&nbsp;</td>';
echo '<td>&nbsp;</td>';
echo '<td valign="bottom"><input title="Attach File" accessyKey="F" class="button" onclick="this.form.action.value=\'upload\';this.form.module.value=\'uploads\'" type="submit" name="button" value="'. $current_module_strings['LBL_NEW_ATTACHMENT'].'"></td>';
echo '<td width="100%"></td>';

echo '</td></tr></form></tbody></table>';
echo getAttachmentsList($focus->id, $theme,$currentModule,'');
// Stick on the form footer
echo get_form_footer();
 
?>
