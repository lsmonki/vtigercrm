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
$return_module = $_REQUEST['return_module'];
$return_action = $_REQUEST['return_action'];

if($return_action !='' && $return_module == "PriceBooks" && $return_action == "CallRelatedList")
{
	$log->info("Products :: Deleting Price Book - Delete from PriceBook RelatedList");
	$query = "delete from vtiger_pricebookproductrel where pricebookid=".$return_id." and productid=".$record;
	$adb->query($query); 
}
else
{
	$log->info("Products :: Deleting Price Book");
	$query = "delete from vtiger_pricebookproductrel where pricebookid=".$record." and productid=".$return_id;
	$adb->query($query); 
}

header("Location: index.php?module=".$return_module."&action=".$return_module."Ajax&file=$return_action&ajax=delpbprorel&record=".$return_id);
?>
