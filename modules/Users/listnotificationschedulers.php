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
  <table alignment="center" width="50%" border="0" cellspacing="0" cellpadding="0" class="FormBorder">
    <tr> 
      <td height="20" class="moduleListTitle" style="padding:0px 3px 0px 3px;"><div align="center"><?php echo $mod_strings['LBL_ACTIVE'];?></div></td>
      <td class="moduleListTitle" style="padding:0px 3px 0px 3px;"><?php echo $mod_strings['LBL_NOTIFICATION'];?></td>
      <td class="moduleListTitle" style="padding:0px 3px 0px 3px;"><?php echo $mod_strings['LBL_DESCRIPTION'];?></td>
    </tr>
    <tr class='oddListRow'> 
 <td height="21" valign="top" nowrap> <div align="center">
<?

$sql = "select active from notificationscheduler where schedulednotificationid=1";
$result = mysql_query($sql);
$result_row = mysql_fetch_row($result);
if($result_row[0] == 1)
{
?>
<INPUT TYPE=CHECKBOX NAME="1" checked>
<?
}
else
{
?>
<INPUT TYPE=CHECKBOX NAME="1" >
<?
  }
?>
     </div></td>
      <td valign="top" nowrap style="padding:0px 3px 0px 3px;"><?php echo $mod_strings['LBL_TASK_NOTIFICATION'];?></a></td>
      <td valign="top" style="padding:0px 3px 0px 3px;"><?php echo $mod_strings['LBL_TASK_NOTIFICATION_DESCRITPION'];?></td>
    </tr>



    <tr class='evenListRow'> 
      <td valign="top" nowrap><div align="center">

<?

$sql = "select active from notificationscheduler where schedulednotificationid=2";
$result = mysql_query($sql);
$result_row = mysql_fetch_row($result);
if($result_row[0] == 1)
{
?>
<INPUT TYPE=CHECKBOX NAME="2" checked>
<?
}
else
{
?>
<INPUT TYPE=CHECKBOX NAME="2">
<?
  }
?>

</div></td>
      <td valign="top" nowrap style="padding:0px 3px 0px 3px;"><?php echo $mod_strings['LBL_MANY_TICKETS'];?></a></td>
      <td valign="top" style="padding:0px 3px 0px 3px;"><?php echo $mod_strings['LBL_MANY_TICKETS_DESCRIPTION'];?></td>
    </tr>


      <tr class='oddListRow'> 
      <td valign="top" nowrap style="padding:0px 3px 0px 3px;"><div align="center">


<?

$sql = "select active from notificationscheduler where schedulednotificationid=3";
$result = mysql_query($sql);
$result_row = mysql_fetch_row($result);
if($result_row[0] == 1)
{
?>
<INPUT TYPE=CHECKBOX NAME="3" checked>
<?
}
else
{
?>
<INPUT TYPE=CHECKBOX NAME="3">
<?
  }
?>



</div></td>
      <td valign="top" nowrap style="padding:0px 3px 0px 3px;"><?php echo $mod_strings['LBL_PENDING_TICKETS'];?></a></td>
      <td valign="top" style="padding:0px 3px 0px 3px;"><?php echo $mod_strings['LBL_TICKETS_DESCRIPTION'];?></td>
    </tr>
    <tr class='evenListRow'> 
      <td valign="top" nowrap><div align="center">

<?

$sql = "select active from notificationscheduler where schedulednotificationid=4";
$result = mysql_query($sql);
$result_row = mysql_fetch_row($result);
if($result_row[0] == 1)
{
?>
<INPUT TYPE=CHECKBOX NAME="4" checked>
<?
}
else
{
?>
<INPUT TYPE=CHECKBOX NAME="4">
<?
  }
?>





</div></td>
      <td valign="top" nowrap style="padding:0px 3px 0px 3px;"><?php echo $mod_strings['LBL_START_NOTIFICATION'];?></a></td>
      <td valign="top" style="padding:0px 3px 0px 3px;"><?php echo $mod_strings['LBL_START_DESCRIPTION'];?></td>
    </tr>
     <tr class='oddListRow'> 
      <td valign="top" nowrap style="padding:0px 3px 0px 3px;"><div align="center">

<?

$sql = "select active from notificationscheduler where schedulednotificationid=5";
$result = mysql_query($sql);
$result_row = mysql_fetch_row($result);
if($result_row[0] == 1)
{
?>
<INPUT TYPE=CHECKBOX NAME="5" checked>
<?
}
else
{
?>
<INPUT TYPE=CHECKBOX NAME="5">
<?
  }
?>


</div></td>
      <td valign="top" nowrap style="padding:0px 3px 0px 3px;"><?php echo $mod_strings['LBL_BEG_DEAL'];?></a></td>
      <td valign="top" style="padding:0px 3px 0px 3px;"><?php echo $mod_strings['LBL_BIG_DEAL_DESCRIPTION'];?></td>
    </tr>
     <tr class='evenListRow'> 
      <td valign="top" nowrap style="padding:0px 3px 0px 3px;"><div align="center">

<?

$sql = "select active from notificationscheduler where schedulednotificationid=6";
$result = mysql_query($sql);
$result_row = mysql_fetch_row($result);
if($result_row[0] == 1)
{
?>
<INPUT TYPE=CHECKBOX NAME="6" checked>
<?
}
else
{
?>
<INPUT TYPE=CHECKBOX NAME="6">
<?
  }
?>

</div></td>
      <td valign="top" nowrap style="padding:0px 3px 0px 3px;"><?php echo $mod_strings['LBL_SUPPORT_NOTICIATION'];?></a></td>
      <td valign="top" style="padding:0px 3px 0px 3px;"><?php echo $mod_strings['LBL_SUPPORT_DESCRIPTION'];?></td>
    </tr>
  </table>
</form>
</body>
</html>
