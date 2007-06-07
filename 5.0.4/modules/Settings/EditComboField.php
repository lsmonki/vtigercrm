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
global $app_list_strings, $current_language;

$tableName=$_REQUEST["fieldname"];
$moduleName=$_REQUEST["fld_module"];
$uitype=$_REQUEST["uitype"];


global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$smarty = new vtigerCRM_Smarty;

//Added to get the strings from language files if present
if($moduleName == 'Events')
	$temp_module_strings = return_module_language($current_language, 'Calendar');
else
	$temp_module_strings = return_module_language($current_language, $moduleName);

//Get the Editable Picklist Values 
$query = "select * from vtiger_".$tableName." where presence=1";
$result = $adb->query($query);
$fldVal='';

while($row = $adb->fetch_array($result))
{
	if($temp_module_strings[$row[$tableName]] != '')
		$fldVal .= $temp_module_strings[$row[$tableName]];
	else
		$fldVal .= $row[$tableName];
	$fldVal .= "\n";	
}

//Get the Non - Editable Picklist Values 
$qry = "select * from vtiger_".$tableName." where presence=0"; 
$res = $adb->query($qry);
$nonedit_fldVal='';

while($row = $adb->fetch_array($res))
{
	if($temp_module_strings[$row[$tableName]] != '')
		$nonedit_fldVal .= $temp_module_strings[$row[$tableName]];
	else
		$nonedit_fldVal .= $row[$tableName];
	$nonedit_fldVal .= "<br>";	
}


$query = 'select fieldlabel from vtiger_tab inner join vtiger_field on vtiger_tab.tabid=vtiger_field.tabid where vtiger_tab.name="'.$moduleName.'" and fieldname="'.$tableName.'"';
$fieldlabel = $adb->query_result($adb->query($query),0,'fieldlabel'); 

if($nonedit_fldVal == '')
	$smarty->assign("EDITABLE_MODE","edit");
else
	$smarty->assign("EDITABLE_MODE","nonedit");
$smarty->assign("NON_EDITABLE_ENTRIES", $nonedit_fldVal);
$smarty->assign("ENTRIES",$fldVal);
$smarty->assign("MODULE",$moduleName);
$smarty->assign("FIELDNAME",$tableName);
//First look into app_strings and then mod_strings and if not available then original label will be displayed
$temp_label = getTranslatedString($fieldlabel);
$smarty->assign("FIELDLABEL",$temp_label);
$smarty->assign("UITYPE", $uitype);
$smarty->assign("MOD", return_module_language($current_language,'Settings'));
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("APP", $app_strings);
$smarty->assign("CMOD", $mod_strings);
$smarty->assign("TEMP_MOD", $temp_module_strings);

$smarty->display("Settings/EditPickList.tpl");
?>
