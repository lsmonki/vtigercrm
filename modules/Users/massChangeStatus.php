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



require_once('modules/Leads/Lead.php');
global $app_list_strings;
global $app_strings;
global $current_user;

$focus = new Lead();
$idlist = $_POST['idlist'];
//echo $idlist;
?>

<script language="javascript">
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
</script>
<form name="setLeadStatus" method="post">
<table>
<tr>
<td width="30%"><select name='lead_status' size='8'>
<?php
echo get_select_options_with_id($app_list_strings['lead_status_dom'], $focus->lead_status);
?>
</select>
</td>
</tr>
<tr><td><input type="submit" name="submit" value="Update Lead Status" onclick="return updateStatus()">&nbsp;<input type="submit" name="Cancel" value="cancel" onclick="return goBack()"></td></tr> 
<input type="hidden" name="leadval">
<input type="hidden" name="idlist" value="<?php echo $idlist ?>">
</table>

</form>

<!-- header("Location: index.php?module=Leads&action=index");-->

