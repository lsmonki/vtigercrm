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


global $adb;
global $log;
$return_id = $_REQUEST['return_id'];
$record = $_REQUEST['record'];
$return_action = $_REQUEST['return_action'];
if($return_action !='' && $return_action=="DetailView")
{
	$log->info("Products :: Deleting Price Book");
	$query = "delete from pricebookproductrel where pricebookid=".$record." and productid=".$return_id;
	$adb->query($query); 
}
else
{
	$log->info("Products :: Deleting Price Book");
	$query = "delete from pricebookproductrel where pricebookid=".$return_id." and productid=".$record;
	$adb->query($query); 
}

header("Location: index.php?action=".$return_action."&module=PriceBooks&record=".$return_id);
?>
