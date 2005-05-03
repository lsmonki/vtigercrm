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
require_once('include/utils.php');

global $theme;
$theme_path="themes/".$theme."/";

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="en">
<head>
  <title><?php echo $mod_strings['LBL_EMAIL_TEMPLATES_LIST']; ?></title>
  <link type="text/css" rel="stylesheet" href="<?php echo $theme_path ?>/style.css"/>
</head>
<body>
            <form action="index.php">
	     <div class="moduleTitle hline"><?php echo $mod_strings['LBL_EMAIL_TEMPLATES']; ?></div>
	<br>
             <input type="hidden" name="module" value="Users">
		<table width="30%" border="0" cellspacing="0" cellpadding="0" class="FormBorder">
		<tr>
		<td class="moduleListTitle" height="25"><b><?php echo $mod_strings['LBL_TEMPLATE_NAME']; ?></b></td>
                <td class="moduleListTitle"><b><?php echo $mod_strings['LBL_DESCRIPTION']; ?></b></td>
                </tr>
<?php
   $sql = "select * from emailtemplates order by templateid desc";
   $result = $adb->query($sql);
   $temprow = $adb->fetch_array($result);
$cnt=1;

require_once('modules/Users/UserInfoUtil.php');
do
{
  //$name=$temprow["name"];
  if ($cnt%2==0)
  printf("<tr class='evenListRow'> <td height='25'>");
  else
  printf("<tr class='oddListRow'> <td height='25'>");
 $templatename = $temprow["templatename"]; 
  printf("<a href='index.php?module=Users&action=populatetemplate&templatename=".$temprow['templatename']."&templateid=".$temprow['templateid']."&entityid=".$_REQUEST["entityid"]."&entity=".$_REQUEST['entity']."'>%s</a></td>",$temprow["templatename"]);
   printf("<td height='25'>%s</td>",$temprow["description"]);
  $cnt++;
}while($temprow = $adb->fetch_array($result));
?>
</table>
</body>
</html>
