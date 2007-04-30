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
$fld_module=$_REQUEST["fld_module"];
$tableName=$_REQUEST["table_name"];
$fldPickList =  $_REQUEST['listarea'];
//changed by dingjianting on 2006-10-1 for picklist editor
$fldPickList = utf8RawUrlDecode($fldPickList); 
$uitype = $_REQUEST['uitype'];

global $adb;

//Delete the already existing values which are editable (presence=0 means non editable, we will not touch that values)
$delquery="delete from vtiger_".$tableName." where presence=1";
$adb->query($delquery);

$pickArray = explode("\n",$fldPickList);
$count = count($pickArray);

$tabname=explode('cf_',$tableName);

if($tabname[1]!='')
       	$custom=true;

/* ticket2369 fixed */
$columnName = $tableName;
foreach ($pickArray as $index => $data)
{
        $data = trim($data);
        if(!empty($data))
	{
		$data = $adb->formatString("vtiger_$tableName",$columnName,$data);
		$query = "insert into vtiger_$tableName values('',$data,$index,1)";
		$adb->query($query);
        }
} 

header("Location:index.php?action=SettingsAjax&module=Settings&directmode=ajax&file=PickList&fld_module=".$fld_module);
?>
