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
$smarty->assign("THEME", $theme);

$module_array=moduleList();
$smarty->assign("MODULES",$module_array);

if(!empty($_REQUEST['formodule'])){
	$fld_module = $_REQUEST['formodule'];
}
else{
	echo "NO MODULES SELECTED";
	exit;
}
$smarty->assign("MODULE",$fld_module);

$fieldsDropDown = QuickViewFieldList($fld_module);
$smarty->assign("FIELDS",$fieldsDropDown);

if($_REQUEST['mode'] != ''){
	$mode = $_REQUEST['mode'];
}
$smarty->assign("MODE", $mode);
$smarty->assign("FORMODULE", $fld_module);

$smarty->display('QuickView/Quickview.tpl');

?>
