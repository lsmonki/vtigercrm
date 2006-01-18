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

require_once('XTemplate/xtpl.php');
global $mod_strings;
global $app_strings;
global $app_list_strings;

echo get_module_title($mod_strings['LBL_MODULE_NAME'], 'PriceBook: Edit List Price', true);
echo '<br><br>';

global $adb;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

if(isset($_REQUEST['return_module']) && $_REQUEST['return_module']=="PriceBooks")
{
	$pricebook_id = $_REQUEST['pricebook_id'];
	$product_id = $_REQUEST['record'];
	$listprice = $_REQUEST['listprice'];
	$return_action = "CallRelatedList";
	$return_id = $_REQUEST['pricebook_id'];
}
else
{
	$product_id = $_REQUEST['record'];
	$pricebook_id = $_REQUEST['pricebook_id'];
	$listprice = getListPrice($product_id,$pricebook_id);
	$return_action = "CallRelatedList";
	$return_id = $_REQUEST['pricebook_id'];
}
$xtpl=new XTemplate ('modules/Products/EditListPrice.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("PRICEBOOKID", $pricebook_id);
$xtpl->assign("PRODUCTID", $product_id);
$xtpl->assign("LISTPRICE", $listprice);
$xtpl->assign("RETURN_ACTION", $return_action);
$xtpl->assign("RETURN_ID", $return_id);

$xtpl->parse("main");
$xtpl->out("main");

?>
