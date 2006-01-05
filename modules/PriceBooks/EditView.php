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
require_once('modules/PriceBooks/PriceBook.php');
require_once('include/FormValidationUtil.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;

$focus = new PriceBook();
$smarty = new vtigerCRM_Smarty();

if(isset($_REQUEST['record'])) 
{
	$focus->id = $_REQUEST['record'];
	$focus->mode = 'edit'; 	
	$focus->retrieve_entity_info($_REQUEST['record'],"PriceBooks");
	$focus->name = $focus->column_fields['bookname'];
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') 
{
	$focus->id = "";
    	$focus->mode = ''; 	
} 

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$disp_view = getView($focus->mode);
$smarty->assign("BLOCKS",getBlocks("PriceBooks",$disp_view,$mode,$focus->column_fields));
$smarty->assign("OP_MODE",$disp_view);

$smarty->assign("MODULE",$currentModule);
$smarty->assign("SINGLE_MOD","Product");

$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
if(isset($cust_fld))
{
        $smarty->assign("CUSTOMFIELD", $cust_fld);
}

$smarty->assign("ID", $focus->id);
if(isset($focus->name))
        $smarty->assign("NAME", $focus->name);

$smarty->assign("CALENDAR_LANG", $app_strings['LBL_JSCALENDAR_LANG']);
$smarty->assign("CALENDAR_DATEFORMAT", parse_calendardate($app_strings['NTC_DATE_FORMAT']));
if($focus->mode == 'edit')
{
        $smarty->assign("MODE", $focus->mode);
}

if(isset($_REQUEST['return_module'])) $smarty->assign("RETURN_MODULE", $_REQUEST['return_module']);
if(isset($_REQUEST['return_action'])) $smarty->assign("RETURN_ACTION", $_REQUEST['return_action']);
if(isset($_REQUEST['return_id'])) $smarty->assign("RETURN_ID", $_REQUEST['return_id']);
$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);


$pb_tables = Array('pricebook'); 

$validationData = getDBValidationData($pb_tables);
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


$category = getParentTab();
$smarty->assign("CATEGORY",$category);

$smarty->assign("VALIDATION_DATA_FIELDNAME",$fieldName);
$smarty->assign("VALIDATION_DATA_FIELDDATATYPE",$fldDataType);
$smarty->assign("VALIDATION_DATA_FIELDLABEL",$fieldLabel);

$smarty->display('salesEditView.tpl');
?>
