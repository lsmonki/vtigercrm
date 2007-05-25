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

global $current_user;
if($current_user->is_admin != 'on')
{
	die("<br><br><center>".$app_strings['LBL_PERMISSION']." <a href='javascript:window.history.back()'>".$app_strings['LBL_GO_BACK'].".</a></center>");
}

include("modules/Migration/versions.php");

require_once('Smarty_setup.php');

global $app_strings,$app_list_strings,$mod_strings,$theme,$currentModule;

$smarty = new vtigerCRM_Smarty();

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("MODULE","Migration");

$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);

$source_versions = "<select id='source_version' name='source_version'>";
foreach($versions as $ver => $label)
{
	$source_versions .= "<option value='".$ver."'> $label </option>";
}
$source_versions .= "</select>";

$smarty->assign("SOURCE_VERSION", $source_versions);
global $vtiger_current_version;
$smarty->assign("CURRENT_VERSION", $vtiger_current_version);

$smarty->display("Migration.tpl");

//include("modules/Migration/DBChanges/501_to_502.php");

?>
