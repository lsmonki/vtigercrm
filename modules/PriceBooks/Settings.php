<?PHP
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

global $mod_strings,$app_strings,$theme;
$smarty = new vtigerCRM_Smarty;

$module = $_REQUEST['formodule'];

$smarty->assign("MOD",$mod_strings);
$smarty->assign("APP",$app_strings);
$smarty->assign("IMAGE_PATH", "themes/$theme/images/");
$smarty->assign('MODULE',$module);
$smarty->assign('MODULE_LBL',getTranslatedString($module));

$smarty->display(vtlib_getModuleTemplate('Vtiger','Settings.tpl'));


?>
