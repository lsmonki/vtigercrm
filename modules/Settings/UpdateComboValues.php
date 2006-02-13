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
$fld_module=$_REQUEST["field_module"];
$fldName=$_REQUEST["field_name"];
$tableName=$_REQUEST["table_name"];
$columnName=$_REQUEST["column_name"];
$fldPickList =  $_REQUEST['listarea'];

//Deleting the already existing values
//$delquery="truncate ".$tableName;
$delquery="delete from ".$tableName;
$adb->query($delquery);
$pickArray = explode("\n",$fldPickList);
$count = count($pickArray);

$tabname=explode('cf_',$tableName);

if($tabname[1]!='')
       	$custom=true;

for($i = 0; $i < $count; $i++)
{
	$pickArray[$i] = trim($pickArray[$i]);
	if($pickArray[$i] != '')
	{
		if($custom)
			$query = "insert into ".$tableName." values('".$pickArray[$i]."')";
		else
			$query = "insert into ".$tableName." values('','".$pickArray[$i]."',".$i.",1)";

                $adb->query($query);
	}
}
header("Location:index.php?module=Settings&action=EditComboField&fld_module=".$fld_module."&fld_name=".$fldName."&table_name=".$tableName."&column_name=".$columnName);
?>
