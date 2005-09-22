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
//require_once('database/DatabaseConnection.php');
require_once('HelpDeskUtil.php');
require_once('XTemplate/xtpl.php');
require_once('include/utils.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

//Retreiving the ticket id
if(isset($_REQUEST['id']))	$ticketid=$_REQUEST['id'];
else	$ticketid = $_REQUEST['record'];

//Retreiving the ticket info from database
$query = "select troubletickets.id,groupname,contact_id,priority,status,parent_id,parent_type,category,troubletickets.title,troubletickets.description,update_log,version_id,troubletickets.date_created,troubletickets.date_modified,troubletickets.assigned_user_id,troubletickets.estimate_finish_time,first_name,last_name from troubletickets left join contacts on troubletickets.contact_id=contacts.id where troubletickets.id='".$ticketid."'";
$ticketresult = $adb->query($query);//mysql_query($query);

$user_id = $adb->query_result($ticketresult,0,'assigned_user_id');

$user_query = "select user_name from users where id='".$user_id."'";
$user_result = $adb->query($user_query);//mysql_query($user_query);
$user_name = $adb->query_result($user_result,0,'user_name');//mysql_result($user_result,0,'user_name');

$xtpl=new XTemplate ('modules/HelpDesk/CreateTicket.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != '')
{
	$xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
}
else
{
	$xtpl->assign("RETURN_MODULE", "HelpDesk");
}
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != '')
{
	$xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
}
else
{
	$xtpl->assign("RETURN_ACTION", "TicketInfoView");
}
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != '')
{
	$xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
}
else
{
	$xtpl->assign("RETURN_ID", $ticketid);
}

$str="<b>".$mod_strings['LBL_TICKET_ID'].$mod_strings['LBL_COLON']."&nbsp;".$ticketid."</b>";
$xtpl->assign("TICKETID",$str);
$xtpl->assign("ID", $ticketid);
$xtpl->assign("THEME", $theme);
$xtpl->assign("MODE", "Edit");
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);


$groupname_val = $adb->query_result($ticketresult,0,'groupname');//mysql_result($ticketresult,0,'groupname');
$priority_val = $adb->query_result($ticketresult,0,'priority');//mysql_result($ticketresult,0,'priority');
$status_val = $adb->query_result($ticketresult,0,'status');
$category_val = $adb->query_result($ticketresult,0,'category');

//Assigning the combo values
$xtpl->assign("ASSIGNED_USER_GROUP_OPTIONS",getComboValues("assigned_group_name","groups","name","1",$groupname_val));
$xtpl->assign("PRIORITYOPTIONS",getComboValues("priority","troubleticketpriorities","priority","1",$priority_val));
$xtpl->assign("STATUSOPTIONS",getComboValues("status","troubleticketstatus","status","1",$status_val));
$xtpl->assign("CATEGORYOPTIONS",getComboValues("category","troubleticketcategories","category","1",$category_val));

//Assigning the User Options

$xtpl->assign("ASSIGNED_USER_OPTIONS", get_select_options_with_id(get_user_array(TRUE, "Active", $user_id), $user_id));

$xtpl->assign("ESTIMATED_FINISHING_DATE", $adb->query_result($ticketresult,0,'estimate_finish_time'));
$estimated_finishing_time=split(' ',$adb->query_result($ticketresult,0,'estimate_finish_time'));
$xtpl->assign("ESTIMATED_FINISHING_TIME", $estimated_finishing_time[1]);

//Assignig the Entity Values
$parent_type = $adb->query_result($ticketresult,0,'parent_type');
$parent_id = $adb->query_result($ticketresult,0,'parent_id');
if($parent_type == 'Accounts')
{
	
	$xtpl->assign("ACCOUNTSELECTED", "selected");
	$pt_type = "Account Name";
	if(isset($parent_id) && $parent_id != '')
	{
		$pt_rst=$adb->query("select name from accounts where id='".$parent_id."'");
		$xtpl->assign("PARENT_ID", $parent_id);
		$xtpl->assign("ENTITYNAME", $adb->query_result($pt_rst,0,'name'));
	}
	
}
elseif($parent_type == 'Opportunities')
{
	
	$xtpl->assign("OPPORTUNITYSELECTED", "selected");
	$pt_type = "Opportunity Name";
	if(isset($parent_id) && $parent_id != '')
	{
		$pt_rst=$adb->query("select name from opportunities where id='".$parent_id."'");
		$xtpl->assign("ENTITYNAME", $adb->query_result($pt_rst,0,'name'));
		$xtpl->assign("PARENT_ID", $parent_id);
	}
}
elseif($parent_type == 'Products')
{
	$pt_type = "Product Name";
	$xtpl->assign("PRODUCTSELECTED", "selected");
	if(isset($parent_id) && $parent_id != '')
	{
		$pt_rst=$adb->query("select productname from products where id='".$parent_id."'");
		$xtpl->assign("ENTITYNAME", $adb->query_result($pt_rst,0,'productname'));
		$xtpl->assign("PARENT_ID", $parent_id);
	}
}

//Assigining the contact Name
$last_name = $adb->query_result($ticketresult,0,'last_name');
if(isset($last_name) && $last_name != '')
{
   $contactname = $adb->query_result($ticketresult,0,'first_name')." ".$adb->query_result($ticketresult,0,'last_name');
   $xtpl->assign("CONTACT_NAME", $contactname);
   $xtpl->assign("CONTACT_ID", $adb->query_result($ticketresult,0,'contact_id'));
}

//Assigning the subject and description
   $xtpl->assign("SUBJECT", $adb->query_result($ticketresult,0,'title'));
   $xtpl->assign("DESCRIPTION", $adb->query_result($ticketresult,0,'description'));

//Updating the Custom Field
$xtpl->assign("CALENDAR_LANG", $app_strings['LBL_JSCALENDAR_LANG']);
$xtpl->assign("CALENDAR_DATEFORMAT", parse_calendardate($app_strings['NTC_DATE_FORMAT']));
require_once('include/CustomFieldUtil.php');
$custfld = CustomFieldEditView($ticketid, "HelpDesk", "ticketcf", "ticketid", $app_strings, $theme);
$xtpl->assign("CUSTOMFIELD", $custfld);

$xtpl->parse("main");

$xtpl->out("main");

?>
