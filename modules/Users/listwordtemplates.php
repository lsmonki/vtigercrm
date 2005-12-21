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
<title>Word Templates List</title>
<!--meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"-->
</head>
<body><form action="index.php" method="post">
<input type="hidden" name="module" value="Users">
<input type="hidden" name="action" value="upload">
<?php echo get_module_title($mod_strings['LBL_MODULE_NAME'],$mod_strings['LBL_WORD_TEMPLATES'],false);?>	
<br>
<input type="submit" class="button" name="Submit" value="<?php echo $mod_strings['LBL_NEW_WORD_TEMPLATE']; ?>">
<br>
<br>
<?php
require_once('modules/Users/binaryfilelist.php');
echo getAttachmentsList();
/*
   $sql = "select * from wordtemplatestorage";
   $result = mysql_query($sql);
   $temprow = mysql_fetch_array($result);
$edit="Edit  ";
$del="Del  ";
$bar="  | ";
$cnt=1;
*/
/*
do
{
  $name=$temprow["name"];
  if ($cnt%2==0)
  printf("<tr class='evenListRow'> <td height='25'>");
  else
  printf("<tr class='oddListRow'> <td height='25'>");
  
  printf(" <a href='#'>%s</a></td>",$temprow["filename"]);
*/
/*
  printf("<td height='25'>%s</td>",$temprow["description"]);
   $cnt++;
 }
 while($temprow = mysql_fetch_array($result));
*/

?>
</body>
</html>
