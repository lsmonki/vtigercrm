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




require_once('database/DatabaseConnection.php');
global $mod_strings;
?>

            <form action="index.php">
	     <div class="moduleTitle hline"><?php echo $mod_strings['LBL_ROLES'] ?></div>
	<br>
             <input type="hidden" name="module" value="Users">
             <input type="hidden" name="action" value="createrole">
             <input type="submit" class="button" name="Submit" value="<?php echo $mod_strings['LBL_CREATE_NEW_ROLE'] ?>">
<br><br>
		<table width="30%" border="0" cellspacing="0" cellpadding="0" class="FormBorder">
		<tr>
		<td class="leftFormHeader" valign="middle" align="left"><b><?echo $mod_strings['LBL_ROLE_NAME']?></b></td>
                </tr>
<!-- Query the db and get the roles dynamically -->
<?php
   $sql = "select name from role";
   $result = mysql_query($sql);
   $temprow = mysql_fetch_array($result);
$edit="Edit  ";
$del="Del  ";
$bar="  | ";


require_once('modules/Users/UserInfoUtil.php');
$currentLoggedRole = $_SESSION['authenticated_user_roleid'];

do
{
  $id=$temprow["id"];
  $name=$temprow["name"];
  printf("<tr class='oddListRow'> <td height='25'>");
  printf(" <a href='index.php?module=Users&action=ListPermissions&currentLoggedRole=$currentLoggedRole&record&rolename=$name'>%s</a></td>",$temprow["name"]);
}while($temprow = mysql_fetch_array($result));
?>
</table>
</body>
</html>
