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
global $app_list_strings;
global $mod_strings;
global $current_user;

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

//needed when creating a new product with a default vendor name to passed 
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
$smarty->assign("BLOCKS",getBlocks("Products",$disp_view,$mode,$focus->column_fields));
//echo '<pre>';print_r(getBlocks("Products",$disp_view,$mode,$focus->column_fields));echo '</pre>';
$smarty->assign("OP_MODE",$disp_view);

$smarty->assign("MODULE",$currentModule);
$smarty->assign("SINGLE_MOD","Product");


$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
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
//	$smarty->assign("IMAGELISTS",getProductImages($focus->id));
}

if(isset($_REQUEST['return_module'])) $smarty->assign("RETURN_MODULE", $_REQUEST['return_module']);
if(isset($_REQUEST['return_action'])) $smarty->assign("RETURN_ACTION", $_REQUEST['return_action']);
if(isset($_REQUEST['return_id'])) $smarty->assign("RETURN_ID", $_REQUEST['return_id']);
if(isset($_REQUEST['activity_mode'])) $smarty->assign("ACTIVITYMODE", $_REQUEST['activity_mode']);
if(isset($_REQUEST['return_viewname'])) $smarty->assign("RETURN_VIEWNAME", $_REQUEST['return_viewname']);
$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);




$product_tables = Array('products','productcf','productcollaterals'); 

 $validationData = getDBValidationData($product_tables);
 $fieldName = '';
 $fieldLabel = '';
 $fldDataType = '';

 $rows = count($validationData);
 foreach($validationData as $fldName => $fldLabel_array)
 {
   if($fieldName == '')
   {
     $fieldName="'".$fldName."'";
   }
   else
   {
     $fieldName .= ",'".$fldName ."'";
   }
   foreach($fldLabel_array as $fldLabel => $datatype)
   {
	if($fieldLabel == '')
	{
			
     		$fieldLabel = "'".$fldLabel ."'";
	}		
      else
       {
      $fieldLabel .= ",'".$fldLabel ."'";
        }
 	if($fldDataType == '')
         {
      		$fldDataType = "'".$datatype ."'";
    	}
	 else
        {
       		$fldDataType .= ",'".$datatype ."'";
     	}
   }
 }

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

$smarty->assign("VALIDATION_DATA_FIELDNAME",$fieldName);
$smarty->assign("VALIDATION_DATA_FIELDDATATYPE",$fldDataType);
$smarty->assign("VALIDATION_DATA_FIELDLABEL",$fieldLabel);

$smarty->display('salesEditView.tpl');
?>
