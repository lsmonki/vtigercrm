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

//or die("Couldn't connect to database $dbDatabase");

function getTicketList($id,$module,$image_path,$theme)
{

global $app_strings,$adb;
if($module == "Contacts")
{
	$dbQuery = "select troubletickets.id,groupname,priority,troubletickets.status,parent_id,parent_type,category,troubletickets.title,troubletickets.assigned_user_id,users.user_name from troubletickets left join users on troubletickets.assigned_user_id=users.id where troubletickets.deleted=0 and troubletickets.status !='Closed' and contact_id='".$id."'";
}
else
{
	$dbQuery = "select troubletickets.id,groupname,priority,troubletickets.status,parent_id,parent_type,category,troubletickets.title,troubletickets.assigned_user_id,users.user_name from troubletickets left join users on troubletickets.assigned_user_id=users.id where troubletickets.deleted=0 and troubletickets.status !='Closed' and parent_type='".$module."' and parent_id='".$id."'";
}
$result = $adb->query($dbQuery);

$list = '<br><br>';
$list .= '<table width="100%" cellpadding="0" cellspacing="0" border="0"><tbody><tr>';
$list .= '<form border="0" action="index.php" method="post" name="form" id="form">';

$list .= '<input type="hidden" name="module">';
$list .= '<input type="hidden" name="return_module" value="'.$module.'">';
$list .= '<input type="hidden" name="return_id" value="'.$id.'">';
if($module == "Products")
{
	$list .= '<input type="hidden" name="return_action" value="ProductDetailView">';
}
else
{
	$list .= '<input type="hidden" name="return_action" value="DetailView">';
}
$list .= '<input type="hidden" name="action">';

$list .= '<td>';
$list .= '<table cellpadding="0" cellspacing="0" border="0"><tbody><tr>
                <td class="formHeader" vAlign="top" align="left" height="20">
         <img src="' .$image_path. '/left_arc.gif" border="0"></td>

        <td class="formHeader" vAlign="middle" background="' . $image_path. '/header_tile.gif" align="left" noWrap width="100%" height="20">Tickets</td>
        <td  class="formHeader" vAlign="top" align="right" height="20">
                  <img src="' .$image_path. '/right_arc.gif" border="0"></td>
                </tr></tbody></table>
      </td>';
$list .= '<td>&nbsp;</td>';
$list .= '<td>&nbsp;</td>';
$list .="<input type='hidden' name='contact_id' value='".$id."'>";
$list .= '<td valign="bottom"><input title="New Ticket" accessyKey="F" class="button" onclick="this.form.action.value=\'CreateTicket\';this.form.module.value=\'HelpDesk\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_TICKET'].'"></td>';
$list .= '<td width="100%"></td>';

$list .= '</td></tr></form></tbody></table>';

$list .= '<table border="0" cellpadding="0" cellspacing="0" class="FormBorder" width="100%">';

$list .= '<tr class="ModuleListTitle" height=20>';

$list .= '';

$list .= '<td class="moduleListTitle" height="21" style="padding:0px 3px 0px 3px;">';


$list .= $app_strings['LBL_TITLE'].'</td>';
$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
$list .= '<td class="moduleListTitle" style="padding:0px 3px 0px 3px;">';


$list .= $app_strings['LBL_PRIORITY'].'</td>';
$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
$list .= '<td class="moduleListTitle" style="padding:0px 3px 0px 3px;">';



$list .= $app_strings['LBL_STATUS'].'</td>';
$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
$list .= '<td class="moduleListTitle" style="padding:0px 3px 0px 3px;">';


$list .= $app_strings['LBL_GROUP_NAME'].'</td>';

$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
$list .= '<td class="moduleListTitle" style="padding:0px 3px 0px 3px;">';

$list .= $app_strings['LBL_LIST_ASSIGNED_USER'].'</td>';


$list .= '</tr>';

//$list .= '<tr><td COLSPAN="7" class="blackLine"><IMG SRC="themes/'.$theme.'/images//blank.gif"></td></tr>';

$i=1;
while($row = $adb->fetch_array($result))
{


if ($i%2==0)
$trowclass = 'evenListRow';
else
$trowclass = 'oddListRow';
	$list .= '<tr class="'. $trowclass.'"><td height="21" style="padding:0px 3px 0px 3px;">';
	$subject = '<a href="index.php?action=TicketInfoView&module=HelpDesk&record='.$row['id'].'">'.$row["title"].'</a>';

	 $list .= $subject; 

	$list .= '</td>';


	$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td><td height="21" style="padding:0px 3px 0px 3px;">';

	 $list .= $row["priority"]; 

	$list .= '</td>';


	$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td><td height="21" style="padding:0px 3px 0px 3px;">';

	 $list .= $row["status"]; 

	$list .= '</td>';


	$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td><td height="21" style="padding:0px 3px 0px 3px;">';

	$list .= $row["groupname"];

	$list .= '</td>';


	$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td><td height="21" style="padding:0px 3px 0px 3px;">';

	$list .= $row["user_name"];

	$list .= '</td></tr>';
$i++;
}
	$list .= '</table>';

return $list;
}
?>
