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

$fld_module = $_REQUEST["fld_module"];
//echo $fld_module;
$id = $_REQUEST["fld_id"];
//echo '<BR>';
//echo $id;
$colName = $_REQUEST["colName"];
$uitype = $_REQUEST["uitype"];

//Deleting the CustomField from the Custom Field Table
$query='delete from customfields where fieldid="'.$id.'"';
mysql_query($query);
//Dropping the column in the module table
if($fld_module == "Leads")
{
	$tableName = "leadcf";
}
elseif($fld_module == "Accounts")
{
	$tableName = "accountcf";
}
elseif($fld_module == "Contacts")
{
	$tableName = "contactcf";
}
elseif($fld_module == "Opportunities")
{
	$tableName = "opportunitycf";
}
//echo '<BR>';
//echo $tableName;
$dbquery = 'Alter table '.$tableName.' Drop Column '.$colName;
mysql_query($dbquery);
if($uitype == 15)
{
$deltablequery = 'drop table '.$fld_module.'_'.$colName;
mysql_query($deltablequery);
}
header("Location:index.php?module=Settings&action=CustomFieldList&fld_module=".$fld_module);
?>
