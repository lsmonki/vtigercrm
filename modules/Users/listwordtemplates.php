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
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="en">
<head>
  <title>Word Templates List</title>
<!--meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"-->
</head>
<body>
            <form action="index.php">
	     <div class="moduleTitle hline">Word Templates</div>
	<br>
             <input type="hidden" name="module" value="Users">
             <input type="hidden" name="action" value="upload">
             <input type="submit" class="button" name="Submit" value="New Word Template">
<br><br>
		<table width="50%" border="0" cellspacing="0" cellpadding="0" class="FormBorder">
		
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
</table>
</body>
</html>
