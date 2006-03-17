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

$delete_prof_id = $_REQUEST['profileid'];
$delete_prof_name = getProfileName($delete_prof_id);


$output='';
$output ='<div id="DeleteLay">
<form name="newProfileForm" action="index.php">
<input type="hidden" name="module" value="Users">
<input type="hidden" name="action" value="DeleteProfile">
<input type="hidden" name="delete_prof_id" value="'.$delete_prof_id.'">	
<table width="100%" border="0" cellpadding="3" cellspacing="0">
<tr>
	<td class="genHeaderSmall" align="left" style="border-bottom:1px solid #CCCCCC;" width="50%">Delete Profile</td>
	<td style="border-bottom:1px solid #CCCCCC;">&nbsp;</td>
	<td align="right" style="border-bottom:1px solid #CCCCCC;" width="40%"><a href="#" onClick="document.getElementById(\'DeleteLay\').style.display=\'none\'";>Close</a></td>
</tr>
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
<tr>
	<td width="50%"><b>Profile to be Deteted</b></td>
	<td width="2%"><b>:</b></td>
	<td width="48%"><b>'.$delete_prof_name.'</b></td>
</tr>
<tr>
	<td style="text-align:left;"><b>Transfer Roles to Profile</b></td>
	<td ><b>:</b></td>
	<td align="left">';
	$output.='<select class="select" name="transfer_prof_id">';
	global $adb;	
	$sql = "select * from profile";
	$result = $adb->query($sql);
	$temprow = $adb->fetch_array($result);
	do
	{
		$prof_name=$temprow["profilename"];
		$prof_id=$temprow["profileid"];
		if($delete_prof_id 	!= $prof_id)
		{	 
    		$output.='<option value="'.$prof_id.'">'.$prof_name.'</option>';
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
