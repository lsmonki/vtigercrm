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



require_once('include/ComboUtil.php');
require_once('modules/Leads/Lead.php');
global $app_list_strings;
global $app_strings;
global $current_user;

$focus = new Lead();
$idlist = $_POST['idlist'];
//echo $idlist;

// Get _dom arrays from Database
$comboFieldNames = Array('leadstatus'=>'lead_status_dom');
$comboFieldArray = getComboArray($comboFieldNames);

?>

<script language="javascript">
function updateOwner()
{
	var username=document.setLeadOwner.lead_owner.value;
	//alert(username);
	document.setLeadOwner.user_id.value=username
	document.setLeadOwner.action="index.php?module=Users&action=updateLeadDBStatus"
}
function updateStatus()
{
	var leadstatusvalue=document.setLeadStatus.lead_status.value
	//alert(leadstatusvalue);
	document.setLeadStatus.leadval.value=leadstatusvalue;
	document.setLeadStatus.action="index.php?module=Users&action=updateLeadDBStatus"
}
function goBack()
{
	document.setLeadStatus.action="index.php?module=Leads&action=index"
}
function goBack1()
{
	document.setLeadOwner.action="index.php?module=Leads&action=index"
}
</script>
<?php

if(isset($_REQUEST['change_status']) && $_REQUEST['change_status']=='true')
{
   ?>
	<form name="setLeadStatus" method="post">
	<table>
		<th align="left"><h3> Leads : Mass Change Status </h3></th><tr></tr>
		<tr>
		<td width="30%">
		<select name='lead_status' size='8'>
		<?php
			echo get_select_options_with_id($comboFieldArray['lead_status_dom'], $focus->lead_status);
		?>
		</select>
		</td>
	</tr>
	<tr><td>
		<input type="submit" name="submit" value="Update Lead Status" onclick="return updateStatus()">&nbsp;
		<input type="submit" name="Cancel" value="cancel" onclick="return goBack()"></td></tr> 
		<input type="hidden" name="leadval">
		<input type="hidden" name="idlist" value="<?php echo $idlist ?>">
	</table>
	
	</form>
   <?php
}
elseif(isset($_REQUEST['change_owner']) && $_REQUEST['change_owner']=='true')
{
	$result=$adb->query("select * from users");
	for($i=0;$i<$adb->num_rows($result);$i++)
	{
		$useridlist[$i]=$adb->query_result($result,$i,'id');
		$usernamelist[$useridlist[$i]]=$adb->query_result($result,$i,'user_name');
	}

?>
	<form name="setLeadOwner" method="post">
	<table>
		<th align="left"><h3> Leads : Mass Change Owner </h3></th><tr></tr>
		<tr>
		<td width="30%">
		<select name='lead_owner' size='8'>
		<?php

		echo get_select_options_with_id($usernamelist, $focus->lead_owner);

		?>
		</select>
		</td>
	</tr>
	<tr><td>
		<input type="submit" name="submit" value="Change Leads Owner" onclick="return updateOwner()">&nbsp;
		<input type="submit" name="Cancel" value="cancel" onclick="return goBack1()"></td></tr> 
		<input type="hidden" name="user_id">
		<input type="hidden" name="idlist" value="<?php echo $idlist ?>">
	</table>
	
	</form>
<?php
}
?>
<!-- header("Location: index.php?module=Leads&action=index");-->

