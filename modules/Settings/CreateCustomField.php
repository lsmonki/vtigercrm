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

require_once ($theme_path."layout_utils.php");
global $mod_strings;

echo get_module_title("Settings", $mod_strings['LBL_MODULE_NAME'].": ".$mod_strings['NEW']." ".$mod_strings[$_REQUEST['fld_module']]." ".$mod_strings['CUSTOMFIELD'], true);
require_once('XTemplate/xtpl.php');
global $mod_strings;
global $app_strings;
global $app_list_strings;

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$xtpl=new XTemplate ('modules/Settings/customfield.html');
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
	//$xtpl->assign("FLDTYPEVALUE", $_REQUEST["fldType"]);
	$typeVal = Array(
	'Text'=>'0',
	'Number'=>'1',
	'Percent'=>'2',
	'Currency'=>'3',
	'Date'=>'4',
	'Email'=>'5',
	'Phone'=>'6',
	'Picklist'=>'7',
	'URL'=>'8');
	$xtpl->assign("FLDTYPEVALUE", $typeVal[$_REQUEST["fldType"]]);
}
else
{
	$xtpl->assign("FLDTYPEVALUE", "0");
}
$xtpl->parse("main");
$xtpl->out("main");
?>
