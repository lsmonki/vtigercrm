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
require_once('database/DatabaseConnection.php');
$return_action = $_REQUEST['return_action'];
$return_module = $_REQUEST['return_module'];
$return_id = $_REQUEST['return_id'];
$ticketid = $_REQUEST['id'];
$query="update troubletickets set deleted='1' where id=".$ticketid;
mysql_query($query); 

$loc = "Location: index.php?action=".$return_action."&module=".$return_module."&record=".$return_id;
header($loc);
?>
