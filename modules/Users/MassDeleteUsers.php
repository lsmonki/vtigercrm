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
$idlist = $_POST['idlist'];
//split the string and store in an array
$storearray = explode(";",$idlist);
foreach($storearray as $id)
{
$sql = "Delete from users where id='" .$id ."'";
$result = mysql_query($sql);
}

header("Location: index.php?module=Administration&action=index");




?>
