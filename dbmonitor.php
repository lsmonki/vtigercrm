<?php

include_once('config.php');
include_once('adodb/adodb.inc.php');


session_start();
$conn = ADONewConnection($dbconfig['db_type']);
$conn->Connect($dbconfig['db_host_name'],$dbconfig['db_user_name'],$dbconfig['db_password'],$dbconfig['db_name']);
$perf =& NewPerfMonitor($conn);
$perf->UI($pollsecs=5);

?>

