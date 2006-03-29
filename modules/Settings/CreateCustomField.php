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
 
require_once('include/CustomFieldUtil.php');
require_once('XTemplate/xtpl.php');

echo get_module_title("Settings", $mod_strings['LBL_MODULE_NAME'].": ".$mod_strings['NEW']." ".$mod_strings[$_REQUEST['fld_module']]." ".$mod_strings['CUSTOMFIELD'], true);

global $mod_strings,$app_strings,$app_list_strings,$theme,$adb;

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

require_once($theme_path.'layout_utils.php');

$tabid=$_REQUEST['tabid'];
$fieldid=$_REQUEST['fieldid'];

$xtpl=new XTemplate ('modules/Settings/customfield.html');
if(isset($fieldid) && $fieldid!='')
{
	$mode='edit';
	$customfield_columnname=getCustomFieldData($tabid,$fieldid,'columnname');
	$customfield_typeofdata=getCustomFieldData($tabid,$fieldid,'typeofdata');
	$customfield_fieldlabel=getCustomFieldData($tabid,$fieldid,'fieldlabel');
	$customfield_typename=getCustomFieldTypeName($_REQUEST['uitype']);
	$fieldtype_lengthvalue=getFldTypeandLengthValue($customfield_typename,$customfield_typeofdata);
	list($fieldtype,$fieldlength,$decimalvalue)= explode(";",$fieldtype_lengthvalue);
	$xtpl->assign("LABELVALUE",$customfield_fieldlabel);
	$xtpl->assign("LENGTHVALUE",$fieldlength);
	$xtpl->assign("DECIMALVALUE",$decimalvalue);
	$xtpl->assign("READ","readonly");
	if($fieldtype == '7' || $fieldtype == '11')
	{
		$query = "select * from ".$customfield_columnname;
		$result = $adb->query($query);
		$fldVal='';
		while($row = $adb->fetch_array($result))
		{
			$fldVal .= $row[$customfield_columnname];
			$fldVal .= "\n";
		}
		$xtpl->assign("PICKLISTVALUE",$fldVal);
	}
	$xtpl->assign("FLDTYPEVALUE", $fieldtype);
	$xtpl->assign("FLDID", $fieldid);
	$xtpl->assign("COLUMN",$customfield_columnname);
}
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("FLD_MODULE", $_REQUEST['fld_module']);
if(isset($_REQUEST["duplicate"]) && $_REQUEST["duplicate"] == "yes")
{
	$error='Custom Field in the Name '.$_REQUEST["fldlabel"].' already exists. Please specify a different Label';
	$xtpl->assign("DUPLICATE_ERROR", $error);
	$xtpl->assign("LABELVALUE", $_REQUEST["fldlabel"]);
	$xtpl->assign("LENGTHVALUE", $_REQUEST["fldlength"]);
	$xtpl->assign("DECIMALVALUE", $_REQUEST["flddecimal"]);
	$xtpl->assign("PICKLISTVALUE", $_REQUEST["fldPickList"]);
	$typeVal = Array(
	'Text'=>'0',
	'Number'=>'1',
	'Percent'=>'2',
	'Currency'=>'3',
	'Date'=>'4',
	'Email'=>'5',
	'Phone'=>'6',
	'Picklist'=>'7',
	'URL'=>'8',
	'MultiSelectCombo'=>'11');
	$xtpl->assign("FLDTYPEVALUE", $typeVal[$_REQUEST["fldType"]]);
}
elseif($fieldid == '')
{
	$xtpl->assign("FLDTYPEVALUE", "0");
}
$xtpl->parse("main");
$xtpl->out("main");
?>
