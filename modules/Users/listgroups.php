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
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="en">
<head>
  <title>Roles List</title>
<!--meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"-->
</head>
<body>
<!--c:out value="${locale}"/-->
<!--fmt:setLocale value="ja_JP"/-->
            <form action="index.php">
			<?php echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_GROUPS'], false); ?>
	<br>
             <input type="hidden" name="module" value="Users">
             <input type="hidden" name="action" value="createnewgroup">
             <input type="submit" class="button" name="Submit" value="<? echo $mod_strings['LBL_CREATE_NEW_GROUP']; ?>">
<br><br>
		<table width="30%" border="0" cellspacing="1" cellpadding="5" class="FormBorder">
		<tr>
		<td class="moduleListTitle" height="20" style='padding:0px 3px 0px 3px;'><b><?php echo $mod_strings['LBL_GROUP_NAME']; ?></b></td>
                <td class="moduleListTitle" style='padding:0px 3px 0px 3px;'><b><?php echo $mod_strings['LBL_DESCRIPTION']; ?></b></td>
                </tr>
<?php
   $sql = "select * from groups";
   $result = $adb->query($sql);
   $temprow = $adb->fetch_array($result);
$edit="Edit  ";
$del="Del  ";
$bar="  | ";
$cnt=1;

require_once('modules/Users/UserInfoUtil.php');
do
{
  $name=$temprow["name"];
  if ($cnt%2==0)
  printf("<tr class='evenListRow'> <td height='21' style='padding:0px 3px 0px 3px;'>");
  else
  printf("<tr class='oddListRow'> <td height='21' style='padding:0px 3px 0px 3px;'>");
  
  printf("<a href='index.php?module=Users&action=UserInfoUtil&groupname=$name'>%s</a></td>",$temprow["name"]);
  printf("<td height='21' style='padding:0px 3px 0px 3px;'>%s</td>",$temprow["description"]);
  $cnt++;
}while($temprow = $adb->fetch_array($result));
?>
</tr>
</table>
</body>
</html>
