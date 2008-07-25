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
require_once('include/utils/utils.php');
require_once('database/DatabaseConnection.php');
require_once('themes/'.$theme.'/layout_utils.php');
global $mod_strings;
global $app_strings;
global $app_list_strings;
global $current_language, $currentModule, $current_userid;

require_once('modules/Vendors/Vendors.php');

$smarty = new vtigerCRM_Smarty;
$focus=new Vendors();

$parenttab = getParenttab();
$req_module=$_REQUEST['module'];
$return_module=$_REQUEST['module'];
$delete_idstring=$_REQUEST['idlist'];

if(isset($_REQUEST['del_rec']))
{
	$delete_id_array=explode(",",$delete_idstring,-1);
	foreach ($delete_id_array as $val)
	{
		DeleteEntity($req_module,$return_module,$focus,$val,"");	
	}
}

include("include/saveMergeCriteria.php");
$ret_arr=getDuplicateRecordsArr($req_module);

$fld_values=$ret_arr[0];
$total_num_group=count($fld_values);
$fld_name=$ret_arr[1];
$ui_type=$ret_arr[2];

$smarty->assign("NAVIGATION",$ret_arr["navigation"]);//Added for page navigation
$smarty->assign("MODULE",$req_module);
$smarty->assign("NUM_GROUP",$total_num_group);
$smarty->assign("FIELD_NAMES",$fld_name);
$smarty->assign("GROUP_COUNT",$count_group);
$smarty->assign("CATEGORY",$parenttab);
$smarty->assign("ALL_VALUES",$fld_values);
if(isPermitted($req_module,'Delete','') == 'yes')
	$button_del = $app_strings[LBL_MASS_DELETE];
$smarty->assign("DELETE",$button_del);

$smarty->assign("MOD", return_module_language($current_language,'Vendors'));
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("APP", $app_strings);
$smarty->assign("CMOD", $mod_strings);
$smarty->assign("MODE",'view');
if(isset($_REQUEST['button_view']))
{	
	$smarty->assign("VIEW",'true');
}

if(isset($_REQUEST['ajax']) && $_REQUEST['ajax'] != '')
	$smarty->display("FindDuplicateAjax.tpl");
else
	$smarty->display('FindDuplicateDisplay.tpl');

?>

