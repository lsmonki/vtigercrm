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
require_once('database/DatabaseConnection.php');
require_once('HelpDeskUtil.php');
require_once('XTemplate/xtpl.php');
require_once('include/utils.php');

//Retreiving the id from the request:
$ticketid = $_REQUEST['record'];

//Retreiving the ticket info from database
$query = "select troubletickets.id,groupname,contact_id,priority,status,parent_id,parent_type,category,troubletickets.title,troubletickets.description,update_log,version_id,troubletickets.date_created,troubletickets.date_modified,troubletickets.assigned_user_id,first_name,last_name from troubletickets left join contacts on troubletickets.contact_id=contacts.id where troubletickets.id='".$ticketid."'";
$ticketresult = mysql_query($query);

$user_id = mysql_result($ticketresult,0,'assigned_user_id');

$user_query = "select user_name from users where id='".$user_id."'"; 
$user_result = mysql_query($user_query);
$user_name = mysql_result($user_result,0,'user_name');

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$xtpl=new XTemplate ('modules/HelpDesk/TicketInfoView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);

$xtpl->assign("NAME", mysql_result($ticketresult,0,'title'));
$xtpl->assign("RETURN_MODULE", $_REQUEST['HelpDesk']);
$xtpl->assign("ID", mysql_result($ticketresult,0,'id'));
$xtpl->assign("GROUPVALUE", mysql_result($ticketresult,0,'groupname'));
$xtpl->assign("USERNAME", $user_name);

$parent_type = mysql_result($ticketresult,0,'parent_type');
$parent_id = mysql_result($ticketresult,0,'parent_id');
if($parent_type == 'Accounts')
{
	$pt_type = "Account Name";
	if(isset($parent_id) && $parent_id != '')
	{
		$pt_rst=mysql_query("select name from accounts where id='".$parent_id."'");
		$xtpl->assign("ENTITYNAME", mysql_result($pt_rst,0,'name'));
	}
	
}
elseif($parent_type == 'Opportunities')
{
	$pt_type = "Opportunity Name";
	if(isset($parent_id) && $parent_id != '')
	{
		$pt_rst=mysql_query("select name from opportunities where id='".$parent_id."'");
		$xtpl->assign("ENTITYNAME", mysql_result($pt_rst,0,'name'));
	}
}
elseif($parent_type == 'Products')
{
	$pt_type = "Product Name";
	if(isset($parent_id) && $parent_id != '')
	{
		$pt_rst=mysql_query("select productname from products where id='".$parent_id."'");
		$xtpl->assign("ENTITYNAME", mysql_result($pt_rst,0,'productname'));
	}
}
$xtpl->assign("ENTITY", $pt_type);
$last_name = mysql_result($ticketresult,0,'last_name');
if(isset($last_name) && $last_name != '')
{
   $contactname = mysql_result($ticketresult,0,'first_name')." ".mysql_result($ticketresult,0,'last_name');
   $xtpl->assign("CONTACTNAME", $contactname);
}
$xtpl->assign("PRIORITYOPTIONS", mysql_result($ticketresult,0,'priority'));
$xtpl->assign("STATUSOPTIONS", mysql_result($ticketresult,0,'status'));
$xtpl->assign("CATEGORYOPTIONS", mysql_result($ticketresult,0,'category'));
$xtpl->assign("SUBJECT", mysql_result($ticketresult,0,'title'));
$xtpl->assign("DESCRIPTION", mysql_result($ticketresult,0,'description'));



$xtpl->parse("main");
$xtpl->out("main");

?>
