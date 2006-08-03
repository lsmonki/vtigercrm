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
require_once('include/utils/utils.php');

global $app_strings;
global $mod_strings;
$delete_group_id = $_REQUEST['groupid'];
$delete_group_name = fetchGroupName($delete_group_id);


$output='';
$output ='<div id="DeleteLay">
<form name="deleteGroupForm" action="index.php">
<input type="hidden" name="module" value="Users">
<input type="hidden" name="action" value="DeleteGroup">
<input type="hidden" name="delete_group_id" value="'.$delete_group_id.'">	
<table width="100%" border="0" cellpadding="3" cellspacing="0">
<tr>
	<td class="genHeaderSmall" align="left" style="border-bottom:1px solid #CCCCCC;" width="50%">'.$mod_strings['LBL_DELETE_GROUP'].'</td>
	<td style="border-bottom:1px solid #CCCCCC;">&nbsp;</td>
	<td align="right" style="border-bottom:1px solid #CCCCCC;" width="40%"><a href="#" onClick="document.getElementById(\'DeleteLay\').style.display=\'none\'";>Close</a></td>
</tr>
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
<tr>
	<td width="50%"><b>'.$mod_strings['LBL_DELETE_GROUPNAME'].'</b></td>
	<td width="2%"><b>:</b></td>
	<td width="48%"><b>'.$delete_group_name.'</b></td>
</tr>
<tr>
	<td style="text-align:left;"><b>'.$mod_strings['LBL_TRANSFER_GROUP'].'</b></td>
	<td ><b>:</b></td>
	<td align="left">';
	global $adb;	
	$sql = "select groupid,groupname from vtiger_groups";
	$result = $adb->query($sql);
	$num_groups = $adb->num_rows($result);

	$sql1 = "select id,user_name from vtiger_users where deleted=0";
	$result1= $adb->query($sql1);
	$num_users = $adb->num_rows($result1);
	

	$output.= '<input name="assigntype" checked value="U" onclick="toggleAssignType(this.value)" type="radio">&nbsp;User';
	if($num_groups > 1)
	{
		$output .= '<input name="assigntype"  value="T" onclick="toggleAssignType(this.value)" type="radio">&nbsp;Group';
	}	
	
	$output .= '<span id="assign_user" style="display: block;">';

	$output .= '<select class="select" name="transfer_user_id">';
	

	for($i=0;$i<$num_users;$i++)
	{
		$user_name=$adb->query_result($result1,$i,"user_name");
		$user_id=$adb->query_result($result1,$i,"id");
		
    		$output.='<option value="'.$user_id.'">'.$user_name.'</option>';
	}	
	
	$output .='</select></span>';

	if($num_groups > 1)
	{	
		$output .= '<span id="assign_team" style="display: none;">';
	

		$output.='<select class="select" name="transfer_group_id">';
	
		$temprow = $adb->fetch_array($result);
		do
		{
			$group_name=$temprow["groupname"];
			$group_id=$temprow["groupid"];
			if($delete_group_id 	!= $group_id)
			{
    				$output.='<option value="'.$group_id.'">'.$group_name.'</option>';
	    		}	
		}while($temprow = $adb->fetch_array($result));
		$output.='</select></span>';
	}	

	$output.='</td>
</tr>
<tr><td colspan="3" style="border-bottom:1px dashed #CCCCCC;">&nbsp;</td></tr>
<tr>
	<td colspan="3" align="center"><input type="submit" name="Delete" value="'.$app_strings["LBL_SAVE_BUTTON_LABEL"].'" class="small">
	</td>
</tr>
</table>
</form></div>';

echo $output;
?>
