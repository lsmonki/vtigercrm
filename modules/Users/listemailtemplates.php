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
<!--fmt:setLocale value="ja_JP"/--><form action="index.php">
<input type="hidden" name="module" value="Users">
<input type="hidden" name="action" value="createemailtemplate">
<?php echo get_module_title($mod_strings['LBL_MODULE_NAME'],$mod_strings['LBL_EMAIL_TEMPLATES'],false);?>
<br>
<input type="submit" class="button" name="Submit" value="<?php echo $mod_strings['LBL_NEW_TEMPLATE']; ?>">
<br>
<br>
<table width="65%" border="0" cellspacing="1" cellpadding="5" class="FormBorder">
  <tr>
    <td width="40%" class="moduleListTitle" height="20" style="padding:0px 3px 0px 3px;"><b><?php echo $mod_strings['LBL_TEMPLATE_NAME']; ?></b></td>
    <td class="moduleListTitle" style="padding:0px 3px 0px 3px;"><b><?php echo $mod_strings['LBL_DESCRIPTION']; ?></b></td>
  </tr>
  <?php
   $sql = "select * from emailtemplates order by templateid DESC";
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
 $templatename = $temprow["templatename"]; 
  printf("<a href=index.php?module=Users&action=detailviewemailtemplate&templateid=".$temprow["templateid"].">%s</a></td>",$temprow["templatename"]);
  printf("<td height='21' style='padding:0px 3px 0px 3px;' nowrap>%s</td>",$temprow["description"]);
  $cnt++;
}while($temprow = $adb->fetch_array($result));
?>
</table>
</body>
</html>
