<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
require_once('include/utils/utils.php');

global $app_strings, $default_charset, $currentModule, $current_user, $theme;

$smarty = new vtigerCRM_Smarty;
if (!isset($where)) $where = "";

if(isset($_REQUEST['parenttab']) && $_REQUEST['parenttab']){
$parent_tab=htmlspecialchars($_REQUEST['parenttab'], ENT_QUOTES, $default_charset);
$smarty->assign("CATEGORY",$parent_tab);}

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("THEME", $theme);
$smarty->assign("THEME_PATH",$theme_path);
$smarty->assign("MODULE",$currentModule);

require_once("modules/$currentModule/$currentModule.php");
$focus = new $currentModule();
$accountid = $_REQUEST['accountid'];
if (!empty($accountid)) {
	$hierarchy = $focus->getAccountHierarchy($accountid);
}
$smarty->assign("ACCOUNT_HIERARCHY",$hierarchy);
$smarty->display("AccountHierarchy.tpl");
	
?>

