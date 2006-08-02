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
require_once('Smarty_setup.php');
global $mod_strings;
global $app_strings;
global $app_list_strings;

$tableName=$_REQUEST["fieldname"];
$moduleName=$_REQUEST["fld_module"];
$uitype=$_REQUEST["uitype"];


global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$smarty = new vtigerCRM_Smarty;

//To get the Editable Picklist Values 
if($uitype != 111)
{
	$query = "select * from vtiger_".$tableName ;
	$result = $adb->query($query);
	$fldVal='';

	while($row = $adb->fetch_array($result))
	{
		$fldVal .= $row[$tableName];
		$fldVal .= "\n";	
	}
}
else
{
	$query = "select * from vtiger_".$tableName." where presence=0"; 
	$result = $adb->query($query);
	$fldVal='';

	while($row = $adb->fetch_array($result))
	{
		$fldVal .= $row[$tableName];
		$fldVal .= "\n";	
	}
}

//To get the Non Editable Picklist Entries
if($uitype == 111) 
{
	$qry = "select * from vtiger_".$tableName." where presence=1"; 
	$res = $adb->query($qry);
	$nonedit_fldVal='';

	while($row = $adb->fetch_array($res))
	{
		$nonedit_fldVal .= $row[$tableName];
		$nonedit_fldVal .= "<br>";	
	}
}

$smarty->assign("NON_EDITABLE_ENTRIES", $nonedit_fldVal);
$smarty->assign("COUNT_NON_EDITABLE_ENTRIES", count($nonedit_fldVal));
$smarty->assign("ENTRIES",$fldVal);
$smarty->assign("MODULE",$moduleName);
$smarty->assign("FIELDNAME",$tableName);
$smarty->assign("UITYPE", $uitype);
$smarty->assign("MOD", return_module_language($current_language,'Settings'));
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("APP", $app_strings);
$smarty->assign("CMOD", $mod_strings);

$smarty->display("Settings/EditPickList.tpl");
?>
