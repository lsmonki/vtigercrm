<!--*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
* 
 ********************************************************************************/
-->




<html>
<head>

<h3><?php echo $mod_strings['LBL_IMPORT_LEADS']; ?></h3>
</head>

<table border="0" width="60%" cellspacing="0" cellpadding="2">
<tr>
<td width="20%" nowrap>
<form action="index.php?module=Leads&action=import" method=post enctype="multipart/form-data">
<?php echo $mod_strings['LBL_LEADS_FILE_LIST'].$mod_strings['LBL_COLON'] ; ?></td><td> <input type=file name="userfile"><br> </td></tr>
<tr><td></td>
<td><input type="submit" name="submit" value="submit" align="center"><br></td></tr>
</table>
</form>
<br>
<?php echo $mod_strings['LBL_INSTRUCTIONS'].$mod_strings['LBL_COLON']; ?>
<ul>
<li>
<?php echo $mod_strings['LBL_KINDLY_PROVIDE_AN_XLS_FILE']; ?> 
</li>

<li>
<?php echo $mod_strings['LBL_PROVIDE_ATLEAST_ONE_FILE']; ?>
</li>
</ul>
</html>
