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

echo $action
if ($action == "upload") 
{
  // ok, let's get the uploaded data and insert it into the db now
  include "open_db.inc";

  if (isset($binFile) && $binFile != "none") {
    $data = addslashes(fread(fopen($binFile, "r"), filesize($binFile)));
    $strDescription = addslashes(nl2br($txtDescription));
    $sql = "INSERT INTO tbl_Files ";
    $sql .= "(description, bin_data, filename, filesize, filetype) ";
    $sql .= "VALUES ('$strDescription', '$data', ";
    $sql .= "'$binFile_name', '$binFile_size', '$binFile_type')";

    echo $sql;
    $result = mysql_query($sql, $db);
    mysql_free_result($result); // it's always nice to clean up!
    echo "Thank you. The new file was successfully added to our database.<br><br>";
    echo "<a href='index.php'>Continue</a>";
  }
  mysql_close();

} 
else {
?>
<HTML>
<BODY>
<FORM METHOD="post" ACTION="index.php?module=uploads&action=add" ENCTYPE="multipart/form-data">
 <INPUT TYPE="hidden" NAME="MAX_FILE_SIZE" VALUE="1000000">
 <INPUT TYPE="hidden" NAME="action" VALUE="upload">
 <TABLE BORDER="1">
  <TR>
   <TD>Description: </TD>
   <TD><TEXTAREA NAME="txtDescription" ROWS="10" COLS="50"></TEXTAREA></TD>
  </TR>
  <TR>
   <TD>File: </TD>
   <TD><INPUT TYPE="file" NAME="binFile"></TD>
  </TR>
  <TR>
   <TD COLSPAN="2"><INPUT TYPE="submit" VALUE="Upload"></TD>
  </TR>
 </TABLE>
</FORM>
</BODY>
</HTML>
<?php
}
?>






