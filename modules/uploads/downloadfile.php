<?php
/********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
* 
 ********************************************************************************/


require_once('database/DatabaseConnection.php');

global $fileId;

$fileId = $_REQUEST['fileId'];

$dbQuery = "SELECT filetype, data ";

$dbQuery .= "FROM filestorage ";

$dbQuery .= "WHERE fileid = '" .$fileId ."'";

$result = mysql_query($dbQuery) or die("Couldn't get file list");

if(mysql_num_rows($result) == 1)
{

$fileType = @mysql_result($result, 0, "filetype");

$fileContent = @mysql_result($result, 0, "data");

//header("Content-type: $fileType");

echo $fileContent;

}

else

{

echo "Record doesn't exist.";

}


?>

