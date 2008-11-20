<?php

$module_export = $_REQUEST['module_export'];

require_once("vtlib/Vtiger/Package.php");
require_once("vtlib/Vtiger/Module.php");

$package = new Vtiger_Package();
$package->export(Vtiger_Module::getInstance($module_export),'',"$module_export.zip",true);
exit;
?>
