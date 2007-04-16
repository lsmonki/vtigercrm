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

if( $return_action != '' && $return_module == "Products" &&
    $return_action == "CallDependencyList") {
    $log->info("Products :: Deleting Products - Products DependencyList");
    $query = "DELETE FROM vtiger_products2products_rel
		  WHERE productid=".$return_id."
		  AND related_productid=".$record;
    $adb->query($query); 
}

if( isset( $_REQUEST['activity_mode']))
    $activitymode = '&activity_mode='.$_REQUEST['activity_mode'];

if( isset( $_REQUEST['parenttab']) && $_REQUEST['parenttab'] != "")
    $parenttab = $_REQUEST['parenttab'];

header("Location: index.php?module=".$return_module."&action=".$return_action."&&record=".$return_id.$activitymode."&parenttab=".$parenttab."&relmodule=".$_REQUEST['module']);
?>
