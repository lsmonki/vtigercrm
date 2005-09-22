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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Activities/EditView.php,v 1.11 2005/03/24 16:18:38 samk Exp $
 * Description: TODO:  To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Activities/Activity.php');
require_once('modules/Activities/Forms.php');
require_once('include/database/PearDatabase.php');
require_once('include/CustomFieldUtil.php');
require_once('include/ComboUtil.php');
require_once('include/uifromdbutil.php');
require_once('include/FormValidationUtil.php');
global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;
// Unimplemented until jscalendar language files are fixed
// global $current_language;
// global $default_language;
// global $cal_codes;
$activity_lbl='';
$activity_mode = $_REQUEST['activity_mode'];
if($activity_mode == 'Task')
{
	$tab_type = 'Activities';
	$activity_lbl = $mod_strings['LBL_TASK_INFORMATION'];
}
elseif($activity_mode == 'Events')
{
	$tab_type = 'Events';
	$activity_lbl = $mod_strings['LBL_EVENT_INFORMATION'];
}

$focus = new Activity();

if(isset($_REQUEST['record'])) {
    $focus->id = $_REQUEST['record'];
    $focus->mode = 'edit';
    $focus->retrieve_entity_info($_REQUEST['record'],$tab_type);		
    $focus->name=$focus->column_fields['subject'];		
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
    	$focus->mode = ''; 	
}


//setting default flag value so due date and time not required
if (!isset($focus->id)) $focus->date_due_flag = 'on';

//get Block 1 Information

$block_1 = getBlockInformation($tab_type,1,$focus->mode,$focus->column_fields);

//get Set Reminder

$block_7 = getBlockInformation($tab_type,7,$focus->mode,$focus->column_fields);

//get Description Information

$block_2 = getBlockInformation($tab_type,2,$focus->mode,$focus->column_fields);


global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("Activity detail view");

$xtpl=new XTemplate ('modules/Activities/EditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("BLOCK1", $block_1);
$xtpl->assign("BLOCK2", $block_2);
$xtpl->assign("BLOCK7", $block_7);
$xtpl->assign("ACTIVITY_MODE", $activity_mode);
$xtpl->assign("ACTIVITY_INFORMATION",$activity_lbl);

if (isset($focus->name)) $xtpl->assign("NAME", $focus->name);
else $xtpl->assign("NAME", "");

if($focus->mode == 'edit')
{
	$xtpl->assign("MODE", $focus->mode);
}		


// Unimplemented until jscalendar language files are fixed
// $xtpl->assign("CALENDAR_LANG", ((empty($cal_codes[$current_language])) ? $cal_codes[$default_language] : $cal_codes[$current_language]));
$xtpl->assign("CALENDAR_LANG", $app_strings['LBL_JSCALENDAR_LANG']);
$xtpl->assign("CALENDAR_DATEFORMAT", parse_calendardate($app_strings['NTC_DATE_FORMAT']));

if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
if (isset($_REQUEST['ticket_id'])) $xtpl->assign("TICKETID", $_REQUEST['ticket_id']);
if (isset($_REQUEST['product_id'])) $xtpl->assign("PRODUCTID", $_REQUEST['product_id']);
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js());
$xtpl->assign("ID", $focus->id);
/*
$xtpl->assign("PARENT_NAME", $focus->parent_name);
$xtpl->assign("PARENT_ID", $focus->parent_id);
$xtpl->assign("CONTACT_NAME", $focus->contact_name);
$xtpl->assign("CONTACT_PHONE", $focus->contact_phone);
$xtpl->assign("CONTACT_EMAIL", $focus->contact_email);
$xtpl->assign("CONTACT_ID", $focus->contact_id);
if (isset($focus->name)) $xtpl->assign("NAME", $focus->name);
else $xtpl->assign("NAME", "");
//create the html select code here and assign it
$result = get_group_options();
$nameArray = $adb->fetch_array($result);
$GROUP_SELECT_OPTION = '<select name="assigned_group_name">';
                   do
                   {
                    $groupname=$nameArray["name"];
                    $GROUP_SELECT_OPTION .= '<option value="';
                    $GROUP_SELECT_OPTION .=  $groupname;
                    $GROUP_SELECT_OPTION .=  '">';
                    $GROUP_SELECT_OPTION .= $nameArray["name"];
                    $GROUP_SELECT_OPTION .= '</option>';
                   }while($nameArray = $adb->fetch_array($result));
                   $GROUP_SELECT_OPTION .='<option value=none>none</option>';
                   $GROUP_SELECT_OPTION .= ' </select>';

$xtpl->assign("ASSIGNED_USER_GROUP_OPTIONS",$GROUP_SELECT_OPTION);

if (isset($focus->parent_type) && $focus->parent_type != "") {
	$change_parent_button = "<input title='".$app_strings['LBL_CHANGE_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CHANGE_BUTTON_KEY']."' tabindex='2' type='button' class='button' value='".$app_strings['LBL_CHANGE_BUTTON_LABEL']."' name='button' LANGUAGE=javascript onclick='return window.open(\"index.php?module=\"+ document.EditView.parent_type.value + \"&action=Popup&html=Popup_picker&form=ActivitiesEditView\",\"test\",\"width=600,height=400,resizable=1,scrollbars=1\");'>";
	$xtpl->assign("CHANGE_PARENT_BUTTON", $change_parent_button);
}
if ($focus->parent_type == "Account") $xtpl->assign("DEFAULT_SEARCH", "&query=true&account_id=$focus->parent_id&account_name=".urlencode($focus->parent_name));

if ($focus->date_due_flag == 'on') {
	$xtpl->assign("DATE_DUE_NONE", "checked");
	$xtpl->assign("READONLY", "readonly");
}

$xtpl->assign("STATUS", $focus->status);
if ($focus->date_due == '0000-00-00') $xtpl->assign("DATE_DUE", '');
else $xtpl->assign("DATE_DUE", $focus->date_due);
if ($focus->time_due == '00:00:00') $xtpl->assign("TIME_DUE", '');
else $xtpl->assign("TIME_DUE", substr($focus->time_due,0,5));
$xtpl->assign("DESCRIPTION", $focus->description);

if ($focus->assigned_user_id == '' && (!isset($focus->id) || $focus->id=0)) $focus->assigned_user_id = $current_user->id;
$xtpl->assign("ASSIGNED_USER_OPTIONS", get_select_options_with_id(get_user_array(TRUE, "Active", $focus->assigned_user_id), $focus->assigned_user_id));
$xtpl->assign("PRIORITY_OPTIONS", get_select_options_with_id($app_list_strings['task_priority_dom'], $focus->priority));
$xtpl->assign("TYPE_OPTIONS", get_select_options_with_id($app_list_strings['record_type_display'], $focus->parent_type));

$xtpl->assign("STATUS_OPTIONS", get_select_options_with_id($app_list_strings['task_status_dom'], $focus->status));
*/







 $activities_tables = Array('activity','crmentity'); 
 $tabid = getTabid($tab_type);
 $validationData = getDBValidationData($activities_tables,$tabid);
 $fieldName = '';
 $fieldLabel = '';
 $fldDataType = '';

 $rows = count($validationData);
 foreach($validationData as $fldName => $fldLabel_array)
 {
   if($fieldName == '')
   {
     $fieldName="'".$fldName."'";
   }
   else
   {
     $fieldName .= ",'".$fldName ."'";
   }
   foreach($fldLabel_array as $fldLabel => $datatype)
   {
	if($fieldLabel == '')
	{
			
     		$fieldLabel = "'".$fldLabel ."'";
	}		
      else
       {
      $fieldLabel .= ",'".$fldLabel ."'";
        }
 	if($fldDataType == '')
         {
      		$fldDataType = "'".$datatype ."'";
    	}
	 else
        {
       		$fldDataType .= ",'".$datatype ."'";
     	}
   }
 }







$xtpl->assign("VALIDATION_DATA_FIELDNAME",$fieldName);
$xtpl->assign("VALIDATION_DATA_FIELDDATATYPE",$fldDataType);
$xtpl->assign("VALIDATION_DATA_FIELDLABEL",$fieldLabel);









$xtpl->parse("main");

$xtpl->out("main");

?>
