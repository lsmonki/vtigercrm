<?php

require_once("Smarty_setup.php");
require_once("include/utils/CommonUtils.php");
require_once("include/FormValidationUtil.php");

global $mod_strings,$current_user;
global $app_strings;
global $adb;
global $app_list_strings;
global $theme;

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');


$smarty = new vtigerCRM_Smarty;

$qcreate_array = QuickCreate("$module");
$validationData = $qcreate_array['data'];
$data = split_validationdataArray($validationData);
$smarty->assign("QUICKCREATE", $qcreate_array['form']);
$smarty->assign("THEME",$theme);
$smarty->assign("APP",$app_strings);
$smarty->assign("MOD",$mod_strings);
$smarty->assign("THEME",$theme);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("MODULE",$module);
$smarty->assign("USERID",$current_user->id);
$smarty->assign("VALIDATION_DATA_FIELDNAME",$data['fieldname']);
$smarty->assign("VALIDATION_DATA_FIELDDATATYPE",$data['datatype']);
$smarty->assign("VALIDATION_DATA_FIELDLABEL",$data['fieldlabel']);
$smarty->assign("CATEGORY",$category);

$smarty->display("QuickCreate.tpl");

?>
