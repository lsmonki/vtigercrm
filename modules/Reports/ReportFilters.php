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
global $mod_strings;
?>
<html>
<head>
<title></title>
<!--meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"-->
</head>
<body>
<table width="100%" cellspacing="0" cellpadding="0">
    <tr> 
    <td>
        <br>
         <table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
            <tr>
        	<td class="uline"><strong><?php echo $mod_strings['LBL_STANDARD_FILTER'];?>:</strong></td>
            </tr>
            <tr><td>
		<?php include("modules/Reports/StandardFilter.php"); ?>
            </td></tr>
        </table>
          <br>
        <table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
            <tr>
        	<td class="uline"><strong><?php echo $mod_strings['LBL_ADVANCED_FILTER'];?>:</strong></td>
            </tr>
            <tr><td>
		<?php include("modules/Reports/AdvancedFilter.php");?>
            </tr></td>
          </table>
        <br>
    </td>
  </tr>
</table>
</body>
</html>
