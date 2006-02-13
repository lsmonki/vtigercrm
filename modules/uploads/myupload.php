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
<?
// In PHP earlier then 4.1.0, $HTTP_POST_FILES should be used instead of $_FILES.

if(!empty($_FILES["userfile"])) 
{
    $uploaddir = "/home/tom/vtiger1.0/installs/apache/htdocs/vtigerCRM/test/upload/" ;// set this to wherever
    //copy the file to some permanent location
    if (move_uploaded_file($_FILES["userfile"]["tmp_name"],$uploaddir.$_FILES["userfile"]["name"])) 
	{
          //make blob and store in DB
             include "open_db.php";
             if (isset($_FILES["userfile"])) 
             {
               echo "inside";
               $data = addslashes(fread(fopen($userfile, "r"), filesize($userfile)));
               $strDescription = addslashes(nl2br($txtDescription));
               $sql = "INSERT INTO filestorage ";
               $sql .= "(description, data, filename, filesize, filetype) ";
               $sql .= "VALUES ('$strDescription', '$data', ";
               $sql .= "'$userfile_name', '$userfile_size', '$userfile_type')";
               echo "sql" .$sql;
               $result = mysql_query($sql, $db);
               mysql_free_result($result); // it's always nice to clean up!
               echo "Thank you. The new file was successfully added to our database.<br><br>";
               // echo "<a href='main.php'>Continue</a>";
             }
             mysql_close();
             
             
    	}
    else
    {
      echo ("error in uploading file. Please check the size of the file !");
    }
}

?>


</html>

