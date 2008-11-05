<?php

global $currentModule;

require_once("modules/$currentModule/$currentModule.php");

$focus = new $currentModule();

$record = $_REQUEST['record'];
$module = $_REQUEST['module'];
$return_module = $_REQUEST['return_module'];
$return_action = $_REQUEST['return_action'];
$parenttab = $_REQUEST['parenttab'];
$return_id = $_REQUEST['return_id'];

DeleteEntity($currentModule, $return_module, $focus, $record, $return_id);

if($_REQUEST['parenttab']) $parenttab = $_REQUEST['parenttab'];

header("Location: index.php?module=$return_module&action=$return_action&record=$return_id&parenttab=$parenttab&relmodule=$module");

?>
