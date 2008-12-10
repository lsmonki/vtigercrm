<?php

global $current_user, $currentModule;
require_once("modules/$currentModule/$currentModule.php");

$focus = new $currentModule();

if($mode) $focus->mode = $mode;
if($record)$focus->id  = $record;

setObjectValuesFromRequest($focus);

$mode = $_REQUEST['mode'];
$record=$_REQUEST['record'];

$focus->save($currentModule);
$return_id = $focus->id;

if($_REQUEST['parenttab'] != '')     $parenttab = $_REQUEST['parenttab'];
if($_REQUEST['return_module'] != '') $return_module = $_REQUEST['return_module'];
else $return_module = $currentModule;

if($_REQUEST['return_action'] != '') $return_action = $_REQUEST['return_action'];
else $return_action = "DetailView";

if($_REQUEST['return_id'] != '') $return_id = $_REQUEST['return_id'];

header("Location: index.php?action=$return_action&module=$return_module&record=$return_id&parenttab=$parenttab&start=".$_REQUEST['pagenumber'].$search);


?>
