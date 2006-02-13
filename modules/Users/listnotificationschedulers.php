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
<form action="index.php" method="post">
<input type="hidden" name="module" value="Users">
<input type="hidden" name="action" value="updateNotificationSchedulers">
<?php echo get_module_title($_REQUEST["module"],$mod_strings['LBL_HDR_EMAIL_SCHDS'],false);?>
<br>
<?php echo $mod_strings['LBL_EMAIL_SCHDS_DESC'];?>
<br><br>
<input class="button" type="submit" value ="<?php echo $mod_strings['LBL_BUTTON_UPDATE'];?>">
<br><br>
  <table width="70%" border="0" cellspacing="1" cellpadding="5" class="FormBorder">
    <tr height="20"> 
      <td  class="moduleListTitle" style="padding:0px 3px 0px 3px;"><div align="center"><b><?php echo $mod_strings['LBL_ACTIVE'];?></b></div></td>
      <td class="moduleListTitle" style="padding:0px 3px 0px 3px;"><b><?php echo $mod_strings['LBL_NOTIFICATION'];?></b></td>
      <td class="moduleListTitle" style="padding:0px 3px 0px 3px;"><b><?php echo $mod_strings['LBL_DESCRIPTION'];?></b></td>
    </tr>
<?

$query = "SELECT * FROM notificationscheduler";
$result = $adb->query($query);
if($adb->num_rows($result) >=1)
{
	$row_list  = 1;
	while($result_row = $adb->fetch_array($result))
	{
		$chkd = '';
		$disabled = '';
		$active = $result_row['active'];
		$shedid = $result_row['schedulednotificationid'];
		$label = $result_row['label'];
		$shedname = $result_row['schedulednotificationname'];
		if($active == 1)
		{
			$chkd = "CHECKED";
		}
		if($row_list%2 ==1)
		{
			$ListRow = "oddListRow";
		}
		else
		{
			$ListRow = "evenListRow";
		}
		
		if ($label == 'LBL_ACTIVITY_NOTIFICATION')
		{
			$disabled = 'disabled="disabled"';
		} 
		echo   '<tr class="'.$ListRow.'"> 
			 <td height="21" valign="top" nowrap> <div align="center">
			<INPUT TYPE=CHECKBOX '.$disabled.' NAME="'.$shedid.'" '.$chkd.' ></div></td>
		         <td valign="top" nowrap style="padding:0px 3px 0px 3px;"><a href="index.php?module=Users&action=EditNotification&record='.$shedid.'">'.$mod_strings[$label].'</a></td>
			 <td valign="top" style="padding:0px 3px 0px 3px;">'.$mod_strings[$shedname].'</td>
			</tr>';
		$disabled ='';
	}

}
?>
  </table>
</form>
</body>
</html>
