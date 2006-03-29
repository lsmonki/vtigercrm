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
require_once('modules/PriceBooks/PriceBook.php');
require_once('include/utils/utils.php');

$focus = new PriceBook();

if(isset($_REQUEST['record']) && isset($_REQUEST['record'])) 
{
	$focus->retrieve_entity_info($_REQUEST['record'],"PriceBooks");
	$focus->id = $_REQUEST['record'];
	$focus->name = $focus->column_fields['bookname'];
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

$smarty->assign("BLOCKS", getBlocks("PriceBooks","detail_view",'',$focus->column_fields));

$smarty->assign("UPDATEINFO",updateInfo($focus->id));
$category = getParentTab();
$smarty->assign("CATEGORY",$category);

$smarty->assign("CUSTOMFIELD", $cust_fld);

if(isPermitted("PriceBooks",1,$_REQUEST['record']) == 'yes')
	$smarty->assign("EDIT_DUPLICATE","permitted");

if(isPermitted("PriceBooks",2,$_REQUEST['record']) == 'yes')
	$smarty->assign("DELETE","permitted");

$smarty->assign("IMAGE_PATH", $image_path);
$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$smarty->assign("ID", $_REQUEST['record']);
$smarty->assign("NAME", $focus->name);

$smarty->assign("MODULE", $module);
$smarty->assign("SINGLE_MOD","PriceBook");
$smarty->display("DetailView.tpl");
?>
