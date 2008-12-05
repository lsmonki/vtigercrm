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
require_once('modules/Products/Products.php');
require_once('include/FormValidationUtil.php');

global $app_strings;
global $mod_strings;
global $currentModule, $current_user;

$encode_val=$_REQUEST['encode_val'];
$decode_val=base64_decode($encode_val);

 $saveimage=isset($_REQUEST['saveimage'])?$_REQUEST['saveimage']:"false";
 $errormessage=isset($_REQUEST['error_msg'])?$_REQUEST['error_msg']:"false";
 $image_error=isset($_REQUEST['image_error'])?$_REQUEST['image_error']:"false";
 



$focus = new Products();
$smarty = new vtigerCRM_Smarty();

//added to fix the issue4600
$searchurl = getBasic_Advance_SearchURL();
$smarty->assign("SEARCH", $searchurl);
//4600 ends

if($_REQUEST['record']!="") 
{
    $focus->id = $_REQUEST['record'];
    $focus->mode = 'edit'; 	
    $focus->retrieve_entity_info($_REQUEST['record'],"Products");
    $focus->name=$focus->column_fields['productname'];	
    $product_base_currency = getProductBaseCurrency($focus->id);
} else {
	$product_base_currency = fetchCurrency($current_user->id);
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
$smarty->assign("SINGLE_MOD",'Product');


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
}

//Tax handling (get the available taxes only) - starts
if($focus->mode == 'edit')
{
	$retrieve_taxes = true;
	$productid = $focus->id;
	$tax_details = getTaxDetailsForProduct($productid,'available_associated');
}
elseif($_REQUEST['isDuplicate'] == 'true')
{
	$retrieve_taxes = true;
	$productid = $_REQUEST['record'];
	$tax_details = getTaxDetailsForProduct($productid,'available_associated');
}
else
	$tax_details = getAllTaxes('available');

for($i=0;$i<count($tax_details);$i++)
{
	$tax_details[$i]['check_name'] = $tax_details[$i]['taxname'].'_check';
	$tax_details[$i]['check_value'] = 0;
}

//For Edit and Duplicate we have to retrieve the product associated taxes and show them
if($retrieve_taxes)
{
	for($i=0;$i<count($tax_details);$i++)
	{
		$tax_value = getProductTaxPercentage($tax_details[$i]['taxname'],$productid);
		$tax_details[$i]['percentage'] = $tax_value;
		$tax_details[$i]['check_value'] = 1;
		//if the tax is not associated with the product then we should get the default value and unchecked
		if($tax_value == '')
		{
			$tax_details[$i]['check_value'] = 0;
			$tax_details[$i]['percentage'] = getTaxPercentage($tax_details[$i]['taxname']);
		}
	}
}

$smarty->assign("TAX_DETAILS", $tax_details);
//Tax handling - ends

$unit_price = $focus->column_fields['unit_price'];
$price_details = getPriceDetailsForProduct($productid, $unit_price, 'available');
$smarty->assign("PRICE_DETAILS", $price_details);

$base_currency = 'curname' . $product_base_currency;	
$smarty->assign("BASE_CURRENCY", $base_currency);

if(isset($_REQUEST['return_module'])) $smarty->assign("RETURN_MODULE", $_REQUEST['return_module']);
if(isset($_REQUEST['return_action'])) $smarty->assign("RETURN_ACTION", $_REQUEST['return_action']);
if(isset($_REQUEST['return_id'])) $smarty->assign("RETURN_ID", $_REQUEST['return_id']);
if(isset($_REQUEST['activity_mode'])) $smarty->assign("ACTIVITYMODE", $_REQUEST['activity_mode']);
if(isset($_REQUEST['return_viewname'])) $smarty->assign("RETURN_VIEWNAME", $_REQUEST['return_viewname']);
$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);

if(isset($focus->id) && $_REQUEST['isDuplicate'] != 'true')
	$is_parent = $focus->isparent_check();
else
	$is_parent = 0;
$smarty->assign("IS_PARENT",$is_parent);

if($_REQUEST['return_module']=='Products' && isset($_REQUEST['return_action'])){
	$return_name = getProductName($_REQUEST['return_id']);
	$smarty->assign("RETURN_NAME", $return_name);
}

 $tabid = getTabid("Products");
 $validationData = getDBValidationData($focus->tab_name,$tabid);
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

// Added to set product active when creating a new product
$mode=$focus->mode;
if($mode != "edit" && $_REQUEST['isDuplicate'] != "true")
$smarty->assign("PROD_MODE", "create");

$check_button = Button_Check($module);
$smarty->assign("CHECK", $check_button);
$smarty->assign("DUPLICATE", $_REQUEST['isDuplicate']);

// Module Sequence Numbering
if($focus->mode != 'edit') {
		$autostr = getTranslatedString('MSG_AUTO_GEN_ON_SAVE');
		$inv_no = $adb->pquery("SELECT prefix, cur_id from vtiger_modentity_num where semodule = ? and active=1",array($module));
        $invstr = $adb->query_result($inv_no,0,'prefix');
        $invno = $adb->query_result($inv_no,0,'cur_id');
        if($focus->checkModuleSeqNumber('vtiger_products', 'product_no', $invstr.$invno))
                echo '<br><font color="#FF0000"><b>Duplicate Product Number - Click <a href="index.php?module=Settings&action=CustomModEntityNo&parenttab=Settings">here</a> to Configure the Product Number</b></font>'.$num_rows;
        else
                $smarty->assign("inv_no",$autostr);
}
// END

if($focus->mode == 'edit')
	$smarty->display('Inventory/InventoryEditView.tpl');
else
	$smarty->display('Inventory/InventoryCreateView.tpl');
?>
