<?php

$module_import_step = $_REQUEST['module_import'];

require_once('Smarty_setup.php');
require_once('vtlib/Vtiger/Package.php');

global $mod_strings,$app_strings,$theme;
$smarty = new vtigerCRM_Smarty;
$smarty->assign("MOD",$mod_strings);
$smarty->assign("APP",$app_strings);
$smarty->assign("IMAGE_PATH", "themes/$theme/images/");

global $modulemanager_uploaddir; // Defined in modules/Settings/ModuleManager.php

if($module_import_step == 'Step2') {
	if(!is_dir($modulemanager_uploaddir)) mkdir($modulemanager_uploaddir);
	$uploadfile = "usermodule_". time() . ".zip";
	$uploadfilename = "$modulemanager_uploaddir/$uploadfile";

	if(!move_uploaded_file($_FILES['module_zipfile']['tmp_name'], $uploadfilename)) {
		$smarty->assign("MODULEIMPORT_FAILED", "true");
	} else {
		$package = new Vtiger_Package();
		$moduleimport_name = $package->getModuleNameFromZip($uploadfilename);

		if($moduleimport_name == null) {
			$smarty->assign("MODULEIMPORT_FAILED", "true");
			$smarty->assign("MODULEIMPORT_FILE_INVALID", "true");
		} else {
			$moduleInstance = Vtiger_Module::getInstance($moduleimport_name);
			$moduleimport_exists=($moduleInstance)? "true" : "false";
			$moduleimport_dir_name="modules/$moduleimport_name";
			$moduleimport_dir_exists= (is_dir($moduleimport_dir_name)? "true" : "false");

			$moduleimport_dep_vtversion = $package->getDependentVtigerVersion();

			$smarty->assign("MODULEIMPORT_FILE", $uploadfile);
			$smarty->assign("MODULEIMPORT_NAME", $moduleimport_name);
			$smarty->assign("MODULEIMPORT_EXISTS", $moduleimport_exists);
			$smarty->assign("MODULEIMPORT_DIR", $moduleimport_dir_name);	
			$smarty->assign("MODULEIMPORT_DIR_EXISTS", $moduleimport_dir_exists);
			$smarty->assign("MODULEIMPORT_DEP_VTVERSION", $moduleimport_dep_vtversion);
		}
	}
} else if($module_import_step == 'Step3') {
	$uploadfile = $_REQUEST['module_import_file'];
	$uploadfilename = "$modulemanager_uploaddir/$uploadfile";

	//$overwritedir = ($_REQUEST['module_dir_overwrite'] == 'true')? true : false;
	$overwritedir = false; // Disallowing overwrites through Module Manager UI

	$package = new Vtiger_Package();
	$Vtiger_Utils_Log = true;
	// NOTE: Import function will be called from Smarty to capture the log cleanly.
	//$package->import($uploadfilename, $overwritedir);
	//unlink($uploadfilename);
	$smarty->assign("MODULEIMPORT_PACKAGE", $package);
	$smarty->assign("MODULEIMPORT_DIR_OVERWRITE", $overwritedir);
	$smarty->assign("MODULEIMPORT_PACKAGE_FILE", $uploadfilename);
}

$smarty->display("Settings/ModuleManager/ModuleImport$module_import_step.tpl");

?>
