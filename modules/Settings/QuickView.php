<?php
/**
 * this file implements the tooltip management part in settings
 */
require_once 'Smarty_setup.php';
require_once 'include/database/PearDatabase.php';
require_once 'include/utils/utils.php';
require_once 'include/utils/TooltipUtils.php';

global $mod_strings,$app_strings,$theme;
$smarty=new vtigerCRM_Smarty;
$smarty->assign("MOD",$mod_strings);
$smarty->assign("APP",$app_strings);
$smarty->assign("IMAGES","themes/images/");

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
$smarty->assign("IMAGE_PATH", $image_path);

$module_array=moduleList();
$smarty->assign("MODULES",$module_array);

if($_REQUEST['fld_module'] !=''){
	$fld_module = $_REQUEST['fld_module'];
}
else{
	$fld_module = 'Accounts';
}
$smarty->assign("MODULE",$fld_module);

$field_array = getFieldList($fld_module);
$smarty->assign("FIELDS",$field_array);

if($_REQUEST['mode'] != ''){
	$mode = $_REQUEST['mode'];
}
$smarty->assign("MODE", $mode);

$smarty->display('QuickView/Quickview.tpl');

?>
