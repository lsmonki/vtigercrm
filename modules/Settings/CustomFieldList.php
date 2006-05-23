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
require_once('modules/Settings/SettingsSubMenu.php');
require_once('include/database/PearDatabase.php');
require_once('include/CustomFieldUtil.php');
global $mod_strings;
$smarty=new vtigerCRM_Smarty;
$fld_module = $_REQUEST['fld_module'];
$smarty->assign("MODULE",$fld_module);
$smarty->assign("CFENTRIES",getCFListEntries($fld_module));
$smarty->assign("MOD",$mod_strings);
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');
$smarty->assign("IMAGE_PATH", $image_path);
$module_array=Array('Leads'=>'Leads',
                        'Accounts'=>'Accounts',
                        'Contacts'=>'Contacts',
                        'Potentials'=>'Potentials',
                        'HelpDesk'=>'HelpDesk',
                        'Products'=>'Products',
                        'Vendors'=>'Vendors',
                        'PriceBooks'=>'PriceBooks',
                        'PurchaseOrder'=>'PurchaseOrder',
                        'SalesOrder'=>'SalesOrder',
                        'Quotes'=>'Quotes',
                        'Invoice'=>'Invoice',
			'Campaigns'=>'Campaigns'
                        );
$smarty->assign("MODULES",$module_array);
if(isset($_REQUEST["duplicate"]) && $_REQUEST["duplicate"] == "yes")
{
	$error='Custom Field in the Name '.$_REQUEST["fldlabel"].' already exists. Please specify a different Label';
	$smarty->assign("DUPLICATE_ERROR", $error);
}
if($_REQUEST['ajax'] != 'true')
	$smarty->display('CustomFieldList.tpl');	
else
	$smarty->display('CustomFieldEntries.tpl');

?>
