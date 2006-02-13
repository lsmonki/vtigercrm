<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?
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

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
<?php echo get_module_title($_REQUEST["module"],$mod_strings['INVENTORYNOTIFICATION'],false);?>
<br>
<?php echo $mod_strings['LBL_INV_NOT_DESC'];?>
<br><br>
  <table width="80%" border="0" cellspacing="1" cellpadding="0" class="FormBorder">
    <tr height="20"> 
      <td class="moduleListTitle" style="padding:0px 3px 0px 3px;"><b><?php echo $mod_strings['LBL_NOTIFICATION'];?></b></td>
      <td class="moduleListTitle" style="padding:0px 3px 0px 3px;"><b><?php echo $mod_strings['LBL_DESCRIPTION'];?></b></td>
    </tr>
<?

$query = "SELECT * FROM inventorynotification";
$result = $adb->query($query);
$num_rows = $adb->num_rows($result);
$out = '';
for($i=0; $i<$num_rows; $i++)
{
	$not_id = $adb->query_result($result,$i,'notificationid');
	$not_mod = $adb->query_result($result,$i,'notificationname');	
	$not_des = $adb->query_result($result,$i,'label');
	if($row_list%2 ==1)
	{
		$ListRow = "oddListRow";
	}
	else
	{
		$ListRow = "evenListRow";
	}
	$out .= '<tr class="'.$ListRow.'" height="70">';
	$out .='<td valign="top" nowrap style="padding:0px 3px 0px 3px;"><a href="index.php?module=Users&action=EditInventoryNotification&record='.$not_id.'">'.$mod_strings[$not_mod].'</a></td>';
	$out .= '<td valign="top" style="padding:0px 3px 0px 3px;">'.$mod_strings[$not_des].'</td>';
	$out .= '</tr>';
}
	
	echo $out;
?>
  </table>
</form>
</body>
</html>
