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
require_once('XTemplate/xtpl.php');
global $mod_strings;
global $app_strings;
global $app_list_strings;

$fld_module=$_REQUEST["fld_module"];
$fld_name=$_REQUEST["fld_name"];
$tableName=$_REQUEST["table_name"];
$columnName=$_REQUEST["column_name"];

echo get_module_title("Settings", $mod_strings['LBL_MODULE_NAME'].": ".$fld_module." ".$mod_strings['EditPickListValues'], true);
echo '<br>';

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$xtpl=new XTemplate ('modules/Settings/EditField.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("FIELDNAME", $fld_name);
$xtpl->assign("TABLENAME", $tableName);
$xtpl->assign("COLUMNNAME", $columnName);
$xtpl->assign("FIELDMODULE", $fld_module);
$query = "select * from ".$tableName ;//." order by sortorderid";
$result = $adb->query($query);
$fldVal='';

while($row = $adb->fetch_array($result))
{
	$fldVal .= $row[$columnName];
	$fldVal .= "\n";	
}
$xtpl->assign("FLDVALUES", $fldVal);
$xtpl->parse("main");
$xtpl->out("main");
?>
