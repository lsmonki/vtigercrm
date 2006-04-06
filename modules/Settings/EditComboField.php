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

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$smarty = new vtigerCRM_Smarty;
$query = "select * from ".$tableName ;//." order by sortorderid";
$result = $adb->query($query);
$fldVal='';

while($row = $adb->fetch_array($result))
{
	$fldVal .= $row[$tableName];
	$fldVal .= "\n";	
}
$smarty->assign("ENTRIES",$fldVal);
$smarty->assign("MODULE",$moduleName);
$smarty->assign("FIELDNAME",$tableName);
$smarty->assign("MOD", return_module_language($current_language,'Settings'));
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("APP", $app_strings);
$smarty->assign("CMOD", $mod_strings);
$smarty->display("Settings/EditPickList.tpl");
?>
