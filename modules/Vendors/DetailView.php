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
require_once('modules/Vendors/Vendor.php');
require_once('include/utils/utils.php');

$focus = new Vendor();

if(isset($_REQUEST['record']) && isset($_REQUEST['record'])) 
{
	$focus->retrieve_entity_info($_REQUEST['record'],"Vendors");
	$focus->id = $_REQUEST['record'];
	$focus->name = $focus->column_fields['vendorname'];
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') 
{
	$focus->id = "";
}

global $app_strings,$mod_strings,$theme;

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');
$smarty = new vtigerCRM_Smarty;
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);

if(isset($focus->name))
	$smarty->assign("NAME", $focus->name);

$smarty->assign("BLOCKS", getBlocks("Vendors","detail_view",'',$focus->column_fields));

$smarty->assign("CUSTOMFIELD", $cust_fld);

if(isPermitted("Vendors",1,$_REQUEST['record']) == 'yes')
	$smarty->assign("EDIT_DUPLICATE","permitted");
if(isPermitted("Vendors",2,$_REQUEST['record']) == 'yes')
	$smarty->assign("DELETE","permitted");


$category = getParentTab();
$smarty->assign("CATEGORY",$category);

$smarty->assign("UPDATEINFO",updateInfo($focus->id));
$smarty->assign("IMAGE_PATH", $image_path);
$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$smarty->assign("ID", $_REQUEST['record']);
$smarty->assign("MODULE", $module);
$smarty->assign("SINGLE_MOD","Vendor");
$smarty->display("DetailView.tpl");

?>
