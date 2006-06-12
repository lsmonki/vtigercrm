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
require_once('modules/Products/Product.php');
require_once('include/FormValidationUtil.php');

global $app_strings;
global $mod_strings;
global $currentModule;

$encode_val=$_REQUEST['encode_val'];
$decode_val=base64_decode($encode_val);

 $saveimage=isset($_REQUEST['saveimage'])?$_REQUEST['saveimage']:"false";
 $errormessage=isset($_REQUEST['error_msg'])?$_REQUEST['error_msg']:"false";
 $image_error=isset($_REQUEST['image_error'])?$_REQUEST['image_error']:"false";
 



$focus = new Product();
$smarty = new vtigerCRM_Smarty();

if($_REQUEST['record']!="") 
{
    $focus->id = $_REQUEST['record'];
    $focus->mode = 'edit'; 	
    $focus->retrieve_entity_info($_REQUEST['record'],"Products");
    $focus->name=$focus->column_fields['productname'];		
}

if($image_error=="true")
{
	$explode_decode_val=explode("&",$decode_val);
	for($i=1;$i<count($explode_decode_val);$i++)
	{
		$test=$explode_decode_val[$i];
		$values=explode("=",$test);
		$field_name_val=$values[0];
		$field_value=$values[1];
		$focus->column_fields[$field_name_val]=$field_value;
	}
}


if(isset($_REQUEST['vendorid']) && $_REQUEST['vendorid']!='')
{
        $focus->column_fields['vendorid'] = $_REQUEST['vendorid'];
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
    	$focus->mode = ''; 	
} 

//needed when creating a new product with a default vtiger_vendor name to passed 
if (isset($_REQUEST['name']) && is_null($focus->name)) {
	$focus->name = $_REQUEST['name'];
	
}
if (isset($_REQUEST['vendorid']) && is_null($focus->vendorid)) {
	$focus->vendorid = $_REQUEST['vendorid'];
}

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$disp_view = getView($focus->mode);
if($disp_view == 'edit_view')
	$smarty->assign("BLOCKS",getBlocks($currentModule,$disp_view,$mode,$focus->column_fields));
else	
{
	$bas_block = getBlocks($currentModule,$disp_view,$mode,$focus->column_fields,'BAS');
	$adv_block = getBlocks($currentModule,$disp_view,$mode,$focus->column_fields,'ADV');

	$blocks['basicTab'] = $bas_block;
	if(is_array($adv_block))
		$blocks['moreTab'] = $adv_block;

	$smarty->assign("BLOCKS",$blocks);
	$smarty->assign("BLOCKS_COUNT",count($blocks));
}
$smarty->assign("OP_MODE",$disp_view);

$smarty->assign("MODULE",$currentModule);
$smarty->assign("SINGLE_MOD",$app_strings['Product']);


$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
if($focus->id != '')
	$smarty->assign("ROWCOUNT", getImageCount($focus->id));
if (isset($focus->name)) $smarty->assign("NAME", $focus->name);
else $smarty->assign("NAME", "");

if(isset($cust_fld))
{
        $smarty->assign("CUSTOMFIELD", $cust_fld);
}
$smarty->assign("ID", $focus->id);
$category = getParentTab();
$smarty->assign("CATEGORY",$category);

$smarty->assign("CALENDAR_LANG", $app_strings['LBL_JSCALENDAR_LANG']);
$smarty->assign("CALENDAR_DATEFORMAT", parse_calendardate($app_strings['NTC_DATE_FORMAT']));
if($focus->mode == 'edit')
{
	$smarty->assign("UPDATEINFO",updateInfo($focus->id));
	$smarty->assign("MODE", $focus->mode);

	$vat_tax = getProductTaxPercentage('VAT',$focus->id);
	$sales_tax = getProductTaxPercentage('Sales',$focus->id);
	$service_tax = getProductTaxPercentage('Service',$focus->id);
}

$vat_check = 1;
$sales_check = 1;
$service_check = 1;
if($vat_tax == '')
{
	$vat_tax = getTaxPercentage('VAT');
	$vat_check = 0;
}
if($sales_tax == '')
{
	$sales_tax = getTaxPercentage('Sales');
	$sales_check = 0;
}
if($service_tax == '')
{
	$service_tax = getTaxPercentage('Service');
	$service_check = 0;
}

//Following values has been added to display the Tax information
$smarty->assign("VAT_TAX", $vat_tax);
$smarty->assign("SALES_TAX", $sales_tax);
$smarty->assign("SERVICE_TAX", $service_tax);

$smarty->assign("VAT_CHECK", $vat_check);
$smarty->assign("SALES_CHECK", $sales_check);
$smarty->assign("SERVICE_CHECK", $service_check);

if(isset($_REQUEST['return_module'])) $smarty->assign("RETURN_MODULE", $_REQUEST['return_module']);
if(isset($_REQUEST['return_action'])) $smarty->assign("RETURN_ACTION", $_REQUEST['return_action']);
if(isset($_REQUEST['return_id'])) $smarty->assign("RETURN_ID", $_REQUEST['return_id']);
if(isset($_REQUEST['activity_mode'])) $smarty->assign("ACTIVITYMODE", $_REQUEST['activity_mode']);
if(isset($_REQUEST['return_viewname'])) $smarty->assign("RETURN_VIEWNAME", $_REQUEST['return_viewname']);
$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);




$product_tables = Array('products','productcf','productcollaterals'); 

 $validationData = getDBValidationData($product_tables);
 $data = split_validationdataArray($validationData);
if($errormessage==2)
{
	$msg =$mod_strings['LBL_MAXIMUM_LIMIT_ERROR'];
        $errormessage ="<B><font color='red'>".$msg."</font></B> <br><br>";
}
else if($errormessage==3)
{
        $msg = $mod_strings['LBL_UPLOAD_ERROR'];
        $errormessage ="<B><font color='red'>".$msg."</font></B> <br><br>";
	
}
else if($errormessage=="image")
{
        $msg = $mod_strings['LBL_IMAGE_ERROR'];
        $errormessage ="<B><font color='red'>".$msg."</font></B> <br><br>";
}
else if($errormessage =="invalid")
{
        $msg = $mod_strings['LBL_INVALID_IMAGE'];
        $errormessage ="<B><font color='red'>".$msg."</font></B> <br><br>";
}
else
{
	$errormessage="";
}
if($errormessage!="")
{
	$smarty->assign("ERROR_MESSAGE",$errormessage);
}

$smarty->assign("VALIDATION_DATA_FIELDNAME",$data['fieldname']);
$smarty->assign("VALIDATION_DATA_FIELDDATATYPE",$data['datatype']);
$smarty->assign("VALIDATION_DATA_FIELDLABEL",$data['fieldlabel']);

$check_button = Button_Check($module);
$smarty->assign("CHECK", $check_button);

if($focus->mode == 'edit')
	$smarty->display('Inventory/InventoryEditView.tpl');
else
	$smarty->display('Inventory/InventoryCreateView.tpl');
?>
