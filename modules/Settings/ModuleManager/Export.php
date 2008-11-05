<?php

$module_export = $_REQUEST['module_export'];

require_once("vtlib/Vtiger/Package.php");

$package = new Vtiger_Package();
$package->export($module_export,'',"$module_export.zip",true);
exit;
?>
