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

//Retreiving the id from the request:
//$ticketid = $_REQUEST['record'];
if(isset($_REQUEST['record']))$ticketid = $_REQUEST['record'];
else $ticketid = $_REQUEST['return_id'];

//Retreiving the ticket info from database
$query = "select troubletickets.id,groupname,contact_id,priority,status,parent_id,parent_type,category,troubletickets.title,troubletickets.description,update_log,version_id,troubletickets.date_created,troubletickets.date_modified,troubletickets.assigned_user_id,troubletickets.estimate_finish_time,first_name,last_name from troubletickets left join contacts on troubletickets.contact_id=contacts.id where troubletickets.id='".$ticketid."'";
$ticketresult = $adb->query($query);

$user_id = $adb->query_result($ticketresult,0,'assigned_user_id');

$user_query = "select user_name from users where id='".$user_id."'"; 
$user_result = $adb->query($user_query);
$user_name = $adb->query_result($user_result,0,'user_name');

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

$xtpl->assign("NAME", $adb->query_result($ticketresult,0,'title'));
$xtpl->assign("RETURN_MODULE", 'HelpDesk');
$xtpl->assign("ID", $adb->query_result($ticketresult,0,'id'));
$xtpl->assign("GROUPVALUE", $adb->query_result($ticketresult,0,'groupname'));
$xtpl->assign("CREATEDDATE", $adb->query_result($ticketresult,0,'date_created'));
$xtpl->assign("USERNAME", $user_name);
$xtpl->assign("EXPECTED_CLOSE_DATE", $adb->query_result($ticketresult,0,'estimate_finish_time'));

$parent_type = $adb->query_result($ticketresult,0,'parent_type');
$parent_id = $adb->query_result($ticketresult,0,'parent_id');


if($parent_type == 'Accounts')
{
	$pt_type = "Account Name";
	if(isset($parent_id) && $parent_id != '')
	{
		$pt_rst=$adb->query("select name from accounts where id='".$parent_id."'");
		$xtpl->assign("ENTITYNAME", $adb->query_result($pt_rst,0,'name'));
	}
	
}
elseif($parent_type == 'Opportunities')
{
	$pt_type = "Opportunity Name";
	if(isset($parent_id) && $parent_id != '')
	{
		$pt_rst=$adb->query("select name from opportunities where id='".$parent_id."'");
		$xtpl->assign("ENTITYNAME", $adb->query_result($pt_rst,0,'name'));
	}
}
elseif($parent_type == 'Products')
{
	$pt_type = "Product Name";
	if(isset($parent_id) && $parent_id != '')
	{
		$pt_rst=$adb->query("select productname from products where id='".$parent_id."'");
		$xtpl->assign("ENTITYNAME", $adb->query_result($pt_rst,0,'productname'));
	}
}
$xtpl->assign("ENTITY", $pt_type);
$last_name = $adb->query_result($ticketresult,0,'last_name');
if(isset($last_name) && $last_name != '')
{
   $contactname = $adb->query_result($ticketresult,0,'first_name')." ".$adb->query_result($ticketresult,0,'last_name');
   $xtpl->assign("CONTACTNAME", $contactname);
}

$xtpl->assign("PRIORITYOPTIONS", $adb->query_result($ticketresult,0,'priority'));
$xtpl->assign("STATUSOPTIONS", $adb->query_result($ticketresult,0,'status'));
$xtpl->assign("CATEGORYOPTIONS", $adb->query_result($ticketresult,0,'category'));
$xtpl->assign("SUBJECT", $adb->query_result($ticketresult,0,'title'));
$xtpl->assign("DESCRIPTION", nl2br($adb->query_result($ticketresult,0,'description')));

//Assigning Custom Field Values
require_once('include/CustomFieldUtil.php');
$custfld = CustomFieldDetailView($ticketid, "HelpDesk", "ticketcf", "ticketid");
$xtpl->assign("CUSTOMFIELD", $custfld);

$xtpl->parse("main");
$xtpl->out("main");

require_once('modules/uploads/binaryfilelist.php');
echo '<br><br>';
echo '<table width="50%" cellpadding="0" cellspacing="0" border="0"><tbody><tr>';
echo '<form border="0" action="index.php" method="post" name="form" id="form">';

echo '<input type="hidden" name="module">';
echo '<input type="hidden" name="mode">';
echo '<input type="hidden" name="return_module" value="'.$currentModule.'">';
echo '<input type="hidden" name="return_id" value="'.$ticketid.'">';
echo '<input type="hidden" name="action">';

echo '<td>';
echo '<table cellpadding="0" cellspacing="0" border="0"><tbody><tr>
                <td class="formHeader" vAlign="top" align="left" height="20">
         <img src="' .$image_path. '/left_arc.gif" border="0"></td>
   <td class="formHeader" vAlign="middle" background="' . $image_path. '/header_tile.gif" align="left" noWrap width="100%" h
eight="20">'.$mod_strings['LBL_ATTACHMENTS'].'</td>
        <td  class="formHeader" vAlign="top" align="right" height="20">
                  <img src="' .$image_path. '/right_arc.gif" border="0"></td>
                </tr></tbody></table>
      </td>';
echo '<td>&nbsp;</td>';
echo '<td>&nbsp;</td>';
echo '<td valign="bottom"><input title="Attach File" accessyKey="F" class="button" onclick="this.form.action.value=\'upload\';this.form.module.value=\'uploads\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_ATTACHMENT'].'"></td>';
echo '<td width="50%"></td>';

echo '</td></tr></form></tbody></table>';

//echo 'current module : '.$currentModule.'........'.$action;

echo getAttachmentsList($ticketid, $theme,$currentModule,$action);

echo "<BR>\n";
echo "<BR>\n";

echo get_form_footer();


?>
