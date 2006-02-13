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

$fld_module = $_REQUEST["fld_module"];
//echo $fld_module;
$id = $_REQUEST["fld_id"];
//echo '<BR>';
//echo $id;
$colName = $_REQUEST["colName"];
$uitype = $_REQUEST["uitype"];

//Deleting the CustomField from the Custom Field Table
$query='delete from field where fieldid="'.$id.'"';
$adb->query($query);

//Deleting from profile2field table
$query='delete from profile2field where fieldid="'.$id.'"';
$adb->query($query);

//Deleting from def_org_field table
$query='delete from def_org_field where fieldid="'.$id.'"';
$adb->query($query);

//Dropping the column in the module table
if($fld_module == "Leads")
{
	$tableName = "leadscf";
}
elseif($fld_module == "Accounts")
{
	$tableName = "accountscf";
}
elseif($fld_module == "Contacts")
{
	$tableName = "contactscf";
}
elseif($fld_module == "Potentials")
{
	$tableName = "potentialscf";
}
elseif($fld_module == "HelpDesk")
{
	$tableName = "ticketcf";
}
elseif($fld_module == "Products")
{
	$tableName = "productcf";
}
//echo '<BR>';
//echo $tableName;
$dbquery = 'Alter table '.$tableName.' Drop Column '.$colName;
$adb->query($dbquery);

//Deleting from convert lead mapping table- Jaguar
if($fld_module=="Leads")
{
	$deletequery = 'delete from convertleadmapping where leadfid='.$id;
	$adb->query($deletequery);
}


if($uitype == 15)
{
$deltablequery = 'drop table '.$colName;
$adb->query($deltablequery);
}
header("Location:index.php?module=Settings&action=CustomFieldList&fld_module=".$fld_module);
?>
