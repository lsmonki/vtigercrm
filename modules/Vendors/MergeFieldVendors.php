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
require_once('Smarty_setup.php');
require_once('include/database/PearDatabase.php');
require_once('database/DatabaseConnection.php');
require_once("modules/".$_REQUEST['module']."/".$_REQUEST['module'].".php");
require_once('modules/Users/Users.php');
require_once('include/utils/utils.php');
require_once('themes/'.$theme.'/layout_utils.php');
global $mod_strings;
global $app_strings;
global $app_list_strings;
global $current_language, $currentModule, $current_user;


$idstring=$_REQUEST['passurl'];
$parent_tab=$_REQUEST['parenttab'];
$module_name=$_REQUEST['module'];

$exploded_id=explode(",",$idstring,-1);
$record_count = count($exploded_id);

$smarty = new vtigerCRM_Smarty;

$all_values_array=getRecordValues($exploded_id,$module_name);
$all_values=$all_values_array[0];
$js_arr_val=$all_values_array[1];
$fld_array=$all_values_array[2];
$js_arr=implode(",",$js_arr_val);

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

//pavani
$imported_records = Array();
$sql="select bean_id from vtiger_users_last_import where bean_type=? and deleted=0";
$result = $adb->pquery($sql, array('Vendors'));
$num_rows=$adb->num_rows($result);
$count=0;
for($i=0; $i<$num_rows;$i++)
{
	foreach($exploded_id as $value)
		if($value == $adb->query_result($result,$i,"bean_id"))
			$count++;
	array_push($imported_records,$adb->query_result($result,$i,"bean_id"));
}

if ($record_count == $count)
	$no_existing=1;
else
	$no_existing=0;

$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("RECORD_COUNT",$record_count);
$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);
$smarty->assign("MODULENAME", $module_name);
$smarty->assign("PARENT_TAB", $parent_tab);
$smarty->assign("ID_ARRAY", $exploded_id);
$smarty->assign("JS_ARRAY", $js_arr);
$smarty->assign("IDSTRING",$idstring);
$smarty->assign("ALLVALUES", $all_values);
$smarty->assign("FIELD_ARRAY", $fld_array);
$smarty->assign("IMPORTED_RECORDS", $imported_records);
$smarty->assign("NO_EXISTING", $no_existing);
$smarty->display("MergeFields.tpl");

?>

