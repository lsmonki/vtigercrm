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

$pricebook_id = $_REQUEST['pricebook_id'];
$product_id = $_REQUEST['record'];
$listprice = $_REQUEST['listprice'];

$xtpl=new XTemplate ('modules/Products/EditListPrice.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("PRICEBOOKID", $pricebook_id);
$xtpl->assign("PRODUCTID", $product_id);
$xtpl->assign("LISTPRICE", $listprice);

$xtpl->parse("main");
$xtpl->out("main");

?>
