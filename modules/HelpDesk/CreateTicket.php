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
require_once('include/database/PearDatabase.php');
require_once('HelpDeskUtil.php');
require_once('XTemplate/xtpl.php');
require_once('include/utils.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;
global $adb;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$xtpl=new XTemplate ('modules/HelpDesk/CreateTicket.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

if(isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if(isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if(isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$result = get_group_options();
$nameArray = $adb->fetch_array($result);
$GROUP_SELECT_OPTION = '<select name="assigned_group_name">';
$GROUP_SELECT_OPTION .='<option value=none>none</option>';
do
{
	$groupname=$nameArray["name"];
	$GROUP_SELECT_OPTION .= '<option value=';
	$GROUP_SELECT_OPTION .=  $groupname;
	$GROUP_SELECT_OPTION .=  '>';
	$GROUP_SELECT_OPTION .= $nameArray["name"];
	$GROUP_SELECT_OPTION .= '</option>';
}while($nameArray = $adb->fetch_array($result));
$GROUP_SELECT_OPTION .= ' </select>';

$picklistval = '';
$xtpl->assign("ASSIGNED_USER_GROUP_OPTIONS",getComboValues("assigned_group_name","groups","name","1",$picklistval));
//Assigning the combo values
$xtpl->assign("PRIORITYOPTIONS",getComboValues("priority","troubleticketpriorities","priority","1",$picklistval));
$xtpl->assign("STATUSOPTIONS",getComboValues("status","troubleticketstatus","status","1",'Open'));
$xtpl->assign("CATEGORYOPTIONS",getComboValues("category","troubleticketcategories","category","1",$picklistval));
//$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js());
//$xtpl->assign("ID", $focus->id);
//if (isset($focus->account_name)) $xtpl->assign("ACCOUNT_NAME", $focus->account_name);
//$xtpl->assign("ACCOUNT_ID", $focus->account_id);	
//$xtpl->assign("CONTACT_ID", $focus->contact_id);	
//if (isset($focus->name)) $xtpl->assign("NAME", $focus->name);
//else $xtpl->assign("NAME", "");
//$xtpl->assign("DATE_ENTERED", $focus->date_entered);
//$xtpl->assign("NUMBER", $focus->number);
//$xtpl->assign("DESCRIPTION", $focus->description);

if($_REQUEST['return_module']!='HelpDesk')
{
	$returnid=$_REQUEST['return_id'];
	$returnmodule=$_REQUEST['return_module'];

	if($returnmodule=='Accounts')
	{
		$tablename='accounts';
		$name='name';
		$selected='ACCOUNTSELECTED';
	}
	if($returnmodule=='Opportunities')
	{
		$tablename='opportunities';
		$name='name';
		$selected='OPPORTUNITYSELECTED';
	}
	if($returnmodule=='Products')
	{
		$tablename='products';
		$name='productname';
		$selected='PRODUCTSELECTED';
	}
	if($returnmodule=='Contacts')
	{
		$sql="select * from contacts where id='".$_REQUEST['contact_id']."'";
		$result=$adb->query($sql);
		$firstname=$adb->query_result($result,0,'first_name');
		$lastname=$adb->query_result($result,0,'last_name');
		$xtpl->assign("CONTACT_NAME",$firstname.' '.$lastname);

		$entityname='';
		$returnid=$_REQUEST['contact_id'];
	}
	else
	{
		$query="select * from ".$tablename." where id = '".$returnid."'";
		$rs=$adb->query($query);
		$entityname=$adb->query_result($rs,0,$name);
	}

	$xtpl->assign("ENTITYNAME",$entityname);
	$xtpl->assign("PARENT_ID",$returnid);
	$xtpl->assign("PARENT_TYPE",$returnmodule);
	$xtpl->assign("CONTACT_ID",$returnid);
	$xtpl->assign($selected,'selected');
}

if ($focus->assigned_user_id == '' && (!isset($focus->id) || $focus->id=0)) $focus->assigned_user_id = $current_user->id; 
$xtpl->assign("ASSIGNED_USER_OPTIONS", get_select_options_with_id(get_user_array(TRUE, "Active", $focus->assigned_user_id), $focus->assigned_user_id));
$xtpl->assign("STATUS_OPTIONS", get_select_options_with_id($app_list_strings['case_status_dom'], $focus->status));

$ticketid;
//Updating the Custom Field
$xtpl->assign("CALENDAR_LANG", $app_strings['LBL_JSCALENDAR_LANG']);
$xtpl->assign("CALENDAR_DATEFORMAT", parse_calendardate($app_strings['NTC_DATE_FORMAT']));
$xtpl->assign("ESTIMATED_FINISHING_DATE", $focus->estimated_finishing_date);
$xtpl->assign("ESTIMATED_FINISHING_TIME", $focus->estimated_finishing_time); 

require_once('include/CustomFieldUtil.php');
$custfld = CustomFieldEditView($ticketid, "HelpDesk", "ticketcf", "ticketid", $app_strings, $theme);
$xtpl->assign("CUSTOMFIELD", $custfld);
if ($focus->send_mail == 'on') $xtpl->assign("SEND_MAIL", "checked");

$xtpl->parse("main");

$xtpl->out("main");

?>
