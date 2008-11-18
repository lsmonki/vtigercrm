<?php

global $mod_strings,$app_strings,$theme,$currentModule,$current_user;

require_once('Smarty_setup.php');
require_once("modules/$currentModule/$currentModule.php");
require_once('include/utils/utils.php');

$focus = new $currentModule();
$focus->mode = '';
$mode = 'mass_edit';
$disp_view = getView($focus->mode);

$smarty = new vtigerCRM_Smarty;
$smarty->assign("BLOCKS",getBlocks($currentModule,$disp_view,$mode,$focus->column_fields,'',$focus->non_mass_edit_fields));	
$smarty->assign("IDS",$_REQUEST['idstring']);
$smarty->assign("MASS_EDIT","1");
$smarty->assign("MODULE",$currentModule);
$smarty->assign("APP",$app_strings);

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);

// Field Validation Information 
$tabid = getTabid($currentModule);
$validationData = getDBValidationData($focus->tab_name,$tabid);
$validationArray = split_validationdataArray($validationData);

$smarty->assign("VALIDATION_DATA_FIELDNAME",$validationArray['fieldname']);
$smarty->assign("VALIDATION_DATA_FIELDDATATYPE",$validationArray['datatype']);
$smarty->assign("VALIDATION_DATA_FIELDLABEL",$validationArray['fieldlabel']);

$smarty->display('MassEditForm.tpl');

?>
