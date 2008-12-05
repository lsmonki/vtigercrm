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

require_once('include/utils/utils.php');
require_once('Smarty_setup.php');

global $app_strings;
global $mod_strings;
global $currentModule;
global $current_language;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

$selectedModule = $_REQUEST['selmodule'];
if($selectedModule == '') $selectedModule = 'Leads';

$recprefix = $_REQUEST['recprefix'];
$recnumber = $_REQUEST['recnumber'];

$module_array=getCRMSupportedModules();

$mode = $_REQUEST['mode'];
if(in_array($selectedModule, $module_array)) {
	require_once("modules/$selectedModule/$selectedModule.php");
	$focus = new $selectedModule();
}
if($mode == 'UPDATESETTINGS') {
	if(isset($focus)) {

		$status = $focus->setModuleSeqNumber('configure', $selectedModule, $recprefix, $recnumber);
		if($status === false) {
			$STATUSMSG = "<font color='red'>UPDATE FAILED</font> $recprefix$recnum IN USE";
		} else {
			$STATUSMSG = "<font color='green'>UPDATE DONE.</font>";
		}
	}
} else {
	if(isset($focus)) {
		$seqinfo = $focus->getModuleSeqInfo($selectedModule);
		$recprefix = $seqinfo[0];
		$recnumber = $seqinfo[1];
	}
}

$smarty = new vtigerCRM_Smarty;

$smarty->assign("MOD", return_module_language($current_language,'Settings'));
$smarty->assign("CMOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMAGE_PATH",$image_path);

$smarty->assign("MODULES",$module_array);
$smarty->assign("SELMODULE",$selectedModule);
$smarty->assign("MODNUM_PREFIX",$recprefix);
$smarty->assign("MODNUM", $recnumber);
$smarty->assign("STATUSMSG", $STATUSMSG);

if($_REQUEST['ajax'] == 'true') $smarty->display('Settings/CustomModEntityNoInfo.tpl');
else $smarty->display('Settings/CustomModEntityNo.tpl');

function getCRMSupportedModules()
{
	global $adb;
	$sql="select tabid,name from vtiger_tab where name not in(
		'Dashboard','Home','Rss','Webmails','Users','Events','Portal','Reports','Emails','Calendar','Recyclebin') order by name";
	$result = $adb->query($sql);
	while($moduleinfo=$adb->fetch_array($result))
	{
		$modulelist[$moduleinfo['name']] = $moduleinfo['name'];
	}
	return $modulelist;
}

?>
