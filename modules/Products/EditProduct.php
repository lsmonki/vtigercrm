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
require_once('include/utils.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

//Retreiving the ticket id
if(isset($_REQUEST['id']))	$productid = $_REQUEST['id'];
else 				$productid = $_REQUEST['record'];
//echo $productid;

//Retreiving the ticket info from database
$query = "select * from products where id='".$productid."'";
$ticketresult = $adb->query($query);

$xtpl=new XTemplate ('modules/Products/EditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != '')
{
	$xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
}
else
{
	$xtpl->assign("RETURN_MODULE", "Products");
}
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != '')
{
	$xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
}
else
{
	$xtpl->assign("RETURN_ACTION", "ProductDetailView");
}
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != '')
{
	$xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
}
else
{
	$xtpl->assign("RETURN_ID", $productid);
}


$xtpl->assign("PRODUCTID", $productid);
$xtpl->assign("THEME", $theme);
$xtpl->assign("MODE", "Edit");
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);

if($adb->query_result($ticketresult,0,'discontinued') == 1)
{
	$xtpl->assign("ACTIVE",'checked');
}


$xtpl->assign("PRODUCT_NAME",$adb->query_result($ticketresult,0,'productname'));
$xtpl->assign("PRODUCT_CODE",$adb->query_result($ticketresult,0,'category'));
$xtpl->assign("COMMISSION_RATE",$adb->query_result($ticketresult,0,'commissionrate'));
$xtpl->assign("QTY_PER_UNIT", $adb->query_result($ticketresult,0,'qty_per_unit'));
$xtpl->assign("UNIT_PRICE", $adb->query_result($ticketresult,0,'unit_price'));
$xtpl->assign("DESCRIPTION", $adb->query_result($ticketresult,0,'product_description'));

$xtpl->parse("main");

$xtpl->out("main");

?>
