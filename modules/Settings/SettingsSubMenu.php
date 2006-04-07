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
require_once('include/CustomFieldUtil.php');
global $mod_strings;
global $app_strings;
global $app_list_strings;

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$smarty = new vtigerCRM_Smarty;
$smarty->assign("MOD", $mod_strings);
$smarty->assign("IMAGE_PATH", $image_path);
$module_array=Array('Leads'=>'Leads',
			'Accounts'=>'Accounts',
			'Contacts'=>'Contacts',
			'Potentials'=>'Potentials',
			'HelpDesk'=>'HelpDesk',
			'Products'=>'Products',
			'Vendor'=>'Vendor',
			'PriceBook'=>'PriceBook',
			'PurchaseOrder'=>'PurchaseOrder',
			'SalesOrder'=>'SalesOrder',
			'Quotes'=>'Quotes',
			'Invoice'=>'Invoice'
			);
if($_REQUEST['type']=='CustomField')
{
	$smarty->display("CustomFieldindex.tpl");
}
	$smarty->display("CustomFieldindex.tpl");
elseif($_REQUEST['type']=='PickList')
	$smarty->display("PickListindex.tpl");
elseif($_REQUEST['type']=='FieldOrder')
	$smarty->display("FieldOrderindex.tpl");
?>
