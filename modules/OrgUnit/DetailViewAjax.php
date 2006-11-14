<?/*********************************************************************************
 ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
  * ("License"); You may not use this file except in compliance with the License
  * The Original Code is:  vtiger CRM Open Source
  * The Initial Developer of the Original Code is vtiger.
  * Portions created by vtiger are Copyright (C) vtiger.
  * All Rights Reserved.
 *
  ********************************************************************************/
	      
require_once('include/logging.php');
require_once('modules/Users/Users.php');
require_once('include/database/PearDatabase.php');
require_once('modules/OrgUnit/OrgUnit.php');
global $adb;

$local_log =& LoggerManager::getLogger('OrgUnitAjax');
$my_status = "Failure";
$ajaxaction = $_REQUEST["ajxaction"];
if($ajaxaction == "DETAILVIEW")
{
    $id = $_REQUEST["recordid"];
    $tablename = $_REQUEST["tableName"];
    $fieldname = $_REQUEST["fldName"];
    $fieldvalue = $_REQUEST["fieldValue"];
    $module = $_REQUEST["module"];
    $uitype = "";
    $my_status = "failure";
    global $adb;

    $local_log->debug("Entering DetailViewAjax id=".$id." tab=".$tablename." fld=".$fieldname." val='".$fieldvalue."'");

    // Parameter check and UI type gathering
    if( $id != '') {
	$sql = "SELECT uitype FROM vtiger_field WHERE tablename='".$tablename."' AND columnname='".$fieldname."'";
	$result = $adb->query( $sql);
	if( $adb->num_rows( $result) == 1) {
	    $uitype = $adb->query_result($result,0,"uitype");
	}
    }

    if( $uitype != '' && $module == "OrgUnit") {

	// Database update
	$sql = "UPDATE ".$tablename." SET ".$fieldname."='".$fieldvalue."'
	    WHERE orgunitid=".$id;
	$adb->query( $sql);
	$my_status = 'success';
    }

    // Return our status to the web page
    if( $my_status == 'success') {
	echo ":#:SUCCESS";
    } else {
	echo ":#:FAILURE";
    }

    $local_log->debug("Exit DetailViewAjax: ".$my_status);
}
?>
