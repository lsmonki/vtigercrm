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

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";


?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
<form method="post" action="index.php?module=Users&action=listnotificationschedulers" name="">
	<TABLE WIDTH="100%" CELLPADDING=0 CELLSPACING=0 BORDER=0>
          <TR> 
            <TD ALIGN=LEFT CLASS="moduleTitle hline" NOWRAP> Notification Email Information: 
            </TD>
          </TR>
        </TABLE>
  <br>
  <table width="50%" border=0 cellspacing=1 cellpadding=2 class="formOuterBorder">
      <td class="formSecHeader" colspan=2 nowrap>Nofitication Email Information:</td>
    <tr>
      <td nowrap class="dataLabel" width="50%">Notification Activity:</td>
      <td></td>
    </tr>
    <tr >
      <td nowrap class="dataLabel">Description:</td>
      <td></td>
    </tr>
    <tr >
      <td nowrap class="dataLabel">Active: </td>
      <td> <img src="yes.gif" alt="" width="13" height="12" align="absmiddle"> 
        [<a href=#>Deactivate</a>]</td>
    </tr>
    <tr >
      <td nowrap class="dataLabel">Subject:</td>
      <td></td>
    </tr>
    <tr >
      <td nowrap valign="top" class="dataLabel">Email Body:</td>
      <td valign="top"></td>
    </tr>
  </table>
  <TABLE WIDTH="50%" CELLPADDING="0" CELLSPACING="5" BORDER="0">
    <TD NOWRAP>&nbsp;
      <input type="submit" name="cancel" value="Goto List View" class="button" onclick="this.form.action.value='listemailtemplates'">
      &nbsp;</TD>
    </TR>
  </TABLE>
</form>
</body>
</html>
