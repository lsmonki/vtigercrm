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

include_once('config.php');
include_once('adodb/adodb.inc.php');


session_start();
$conn = ADONewConnection($dbconfig['db_type']);
$conn->Connect($dbconfig['db_host_name'],$dbconfig['db_user_name'],$dbconfig['db_password'],$dbconfig['db_name']);
$perf =& NewPerfMonitor($conn);
$perf->UI($pollsecs=5);

?>

