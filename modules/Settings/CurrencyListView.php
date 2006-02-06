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
<input type="hidden" name="module" value="Settings">
<input type="hidden" name="action" value="CreateCurrencyInfo">
<?php echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].' : '.$mod_strings['LBL_CURRENCY_CONFIG'], true);?>
<br>
<input type="submit" class="button" name="Submit" value="<?php echo $mod_strings['LBL_NEW_CURRENCY']; ?>">
<br>
<br>
<table width="65%" border="0" cellspacing="1" cellpadding="5" class="FormBorder">
  <tr>
    <td width="20%" class="moduleListTitle" style="padding:0px 3px 0px 3px;"><b><?php echo $mod_strings['LBL_CURRENCY_NAME']; ?></b></td>
    <td width="10%" class="moduleListTitle" style="padding:0px 3px 0px 3px;"><b><?php echo $mod_strings['LBL_CURRENCY_CODE']; ?></b></td>
    <td width="10%" class="moduleListTitle" style="padding:0px 3px 0px 3px;"><b><?php echo $mod_strings['LBL_CURRENCY_SYMBOL']; ?></b></td>
    <td width="10%" class="moduleListTitle" style="padding:0px 3px 0px 3px;"><b><?php echo $mod_strings['LBL_CURRENCY_CRATE']; ?></b></td>
    <td width="10%" class="moduleListTitle" style="padding:0px 3px 0px 3px;"><b><?php echo $mod_strings['LBL_CURRENCY_STATUS']; ?></b></td>
    <td width="10%" class="moduleListTitle" style="padding:0px 3px 0px 3px;">&nbsp;</td>
  </tr>
 <?php
   $sql = "select * from currency_info";
   $result = $adb->query($sql);
   $temprow = $adb->fetch_array($result);
   $del="Del  ";
   $cnt=1;

require_once('include/utils/UserInfoUtil.php');
do
{
  if ($cnt%2==0)
  printf("<tr class='evenListRow'> <td height='21' style='padding:0px 3px 0px 3px;'>");
  else
  printf("<tr class='oddListRow'> <td height='21' style='padding:0px 3px 0px 3px;'>");
  if($temprow["defaultid"] == '-11')
        printf("%s</td>",$temprow["currency_name"]);
  else
 	printf("<a href=index.php?module=Settings&action=CurrencyDetailView&record=".$temprow["id"].">%s</a></td>",$temprow["currency_name"]);
  printf("<td style='padding:0px 3px 0px 3px;' nowrap>%s</td>",$temprow["currency_code"]);
  printf("<td style='padding:0px 3px 0px 3px;' nowrap>%s</td>",$temprow["currency_symbol"]);
  printf("<td style='padding:0px 3px 0px 3px;' nowrap>%s</td>",$temprow["conversion_rate"]);
  printf("<td style='padding:0px 3px 0px 3px;' nowrap>%s</td>",$temprow["currency_status"]);
  if($temprow["defaultid"] != '-11')
	printf("<td style='padding:0px 3px 0px 3px;' nowrap><a href=index.php?module=Settings&action=CurrencyDelete&record=".$temprow["id"].">%s</a></td>",$del);
  $cnt++;
}while($temprow = $adb->fetch_array($result));
?>
</table>
</body>
</html>

