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

$delete_user_id = $_REQUEST['record'];
$delete_user_name = getUserName($delete_user_id);


$output='';
$output ='<div id="DeleteLay">
<form name="newProfileForm" action="index.php">
<input type="hidden" name="module" value="Users">
<input type="hidden" name="action" value="DeleteUser">
<input type="hidden" name="delete_user_id" value="'.$delete_user_id.'">	
<table width="100%" border="0" cellpadding="3" cellspacing="0">
<tr>
	<td class="genHeaderSmall" align="left" style="border-bottom:1px solid #CCCCCC;" width="50%">Delete User</td>
	<td style="border-bottom:1px solid #CCCCCC;">&nbsp;</td>
	<td align="right" style="border-bottom:1px solid #CCCCCC;" width="40%"><a href="#" onClick="document.getElementById(\'DeleteLay\').style.display=\'none\'";>Close</a></td>
</tr>
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
<tr>
	<td width="50%"><b>User to be Deteted</b></td>
	<td width="2%"><b>:</b></td>
	<td width="48%"><b>'.$delete_user_name.'</b></td>
</tr>
<tr>
	<td style="text-align:left;"><b>Transfer Ownership to User</b></td>
	<td ><b>:</b></td>
	<td align="left">';
           
$output.='<select class="select" name="transfer_user_id">';
	     
		 global $adb;	
         $sql = "select * from users";
         $result = $adb->query($sql);
         $temprow = $adb->fetch_array($result);
         do
         {
         	$user_name=$temprow["user_name"];
		    $user_id=$temprow["id"];
		    if($delete_user_id 	!= $user_id)
		    {	 
            	$output.='<option value="'.$user_id.'">'.$user_name.'</option>';
		    }	
         }while($temprow = $adb->fetch_array($result));
$output.='</select>';

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
