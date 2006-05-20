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
global $mod_strings;
global $app_strings;
global $adb;
global $log;

$smarty = new vtigerCRM_Smarty;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$taxes_array = Array('VAT','Sales','Service');

if($_REQUEST['save_tax'] == 'true')
{
	foreach($taxes_array as $tax_type)
	{
		$new_percentages[$tax_type] = $_REQUEST[$tax_type];
	}
	updateTaxPercentages($new_percentages);
}
if($_REQUEST['edit_tax'] == 'true')
{
	//Edit View
	$smarty->assign("EDIT_MODE", 'true');
}

//To get the ListView of Taxes
$tax_percentage_values = getTaxConfigValues($taxes_array);
$smarty->assign("TAX_VALUES", $tax_percentage_values);
//echo '<pre>';print_r($tax_percentage_values);echo '</pre>';

$smarty->assign("MOD", return_module_language($current_language,'Settings'));
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("APP", $app_strings);
$smarty->assign("MOD", $mod_strings);
$smarty->display("Settings/TaxConfig.tpl");



/**	Function to get the list of Tax percentage for the tax types
 *	@param array $taxes_array - array of tax types (VAT, Sales, Service)
 *	return array $tax_percentages - array of tax percentages for the passed tax types like [VAT]= 4.5
 */
function getTaxConfigValues($taxes_array)
{
	global $adb, $log;
	$log->debug("Entering into the function getTaxConfigValues()");

	$tax_percentages = Array();
	
	foreach($taxes_array as $tax_type)
	{
		$res = $adb->query("select * from inventorytaxinfo where taxname=\"$tax_type\"");
		$tax_percentages[$tax_type] = $adb->query_result($res,0,'percentage');	
	}
	
	$log->debug("Exiting from the function getTaxConfigValues()");
	return $tax_percentages;
}

/**	Function to update the list of Tax percentages for the passed tax types
 *	@param array $new_percentages - array of tax types and the values like [VAT]=3.56, [Sales]=11.45, [Service]=15.250
 */
function updateTaxPercentages($new_percentages)
{
	global $adb, $log;
	$log->debug("Entering into the function updateTaxPercentages");

	$tax_percentage = Array();
	
	foreach($new_percentages as $tax_type => $new_val)
	{
		if($new_val != '')
			$res = $adb->query("update inventorytaxinfo set percentage = \"$new_val\" where taxname=\"$tax_type\"");
	}

	$log->debug("Exiting from the function updateTaxPercentages");
}

?>
