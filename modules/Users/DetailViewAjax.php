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
	      
require_once('include/logging.php');
require_once('modules/Users/Users.php');
require_once('include/database/PearDatabase.php');
global $adb;

$org_separator = "<br>&nbsp;";
$local_log =& LoggerManager::getLogger('UsersAjax');
$my_status = "Failure";
$ajaxaction = $_REQUEST["ajxaction"];
if($ajaxaction == "DETAILVIEW")
{
	$crmid = $_REQUEST["recordid"];
	$tablename = $_REQUEST["tableName"];
	$fieldname = $_REQUEST["fldName"];
	$fieldvalue = utf8RawUrlDecode($_REQUEST["fieldValue"]); 
	$local_log->debug("Entering DetailViewAjax crmid=".$crmid." tab=".$tablename." fld=".$fieldname." val='".$fieldvalue."'");

	if($crmid != "")
	{
		$userObj = new Users();
		$userObj->retrieve_entity_info($crmid,"Users");

		//assigned organizations
		if( $fieldname == 'assigned_org[]') {
		    $assign = split( ' \|##\| ', $fieldvalue);
		    $delete = array();

		    //get current settings from database
		    $sql = "select organizationname,primarytag from vtiger_user2org where userid=".$crmid;
		    $result = $adb->query($sql);
		    if($adb->num_rows($result) >= 1) {

			while($result_set = $adb->fetch_array($result)) {
			    $current = $result_set['organizationname'];
			    $index = 0;
			    foreach( $assign as $org) {
				if( $current == $org) {

				    //Found. Do no need to reassign.
				    //Skip to the next item already stored in database.
				    array_splice( $assign, $index, 1);

				    //get the next record from database
				    continue 2;
				}
				$index++;
			    }

			    //Not found. Need to remove from database
			    $delete[] = $current;
			}
		    }

		    //Now in $assign there is a list of new assignments.
		    //In $delete there's a list of assignments to be removed
		    $adb->startTransaction();
		    foreach( $assign as $org) {
			$sql = "insert into vtiger_user2org (userid,organizationname) values (".$crmid.",'".$org."')";
			$result = $adb->query($sql);
		    }
		    foreach( $delete as $org) {
			$sql = "delete from vtiger_user2org where userid=".$crmid." and organizationname='".$org."'";
			$result = $adb->query($sql);
		    }
		    $adb->completeTransaction();

		    //Organization assignment
		    require('modules/Users/GetUserOrg.php');
		}

		//primary organization
		elseif( $fieldname == 'primary_org') {

		    //consistency check
		    $sql = "select primarytag from vtiger_user2org where userid=".$crmid." and organizationname='".$fieldvalue."'";
		    $result = $adb->query($sql);
		    if($adb->num_rows($result) >= 1) {

			//reset the current primary organization
			$adb->startTransaction();
			$sql = "update vtiger_user2org set primarytag = 0 where userid=".$crmid;
			$result = $adb->query($sql);

			//define the new primary organization
			$sql = "update vtiger_user2org set primarytag = 1 where userid=".$crmid." and organizationname='".$fieldvalue."'";
			$result = $adb->query($sql);
			$result = $adb->completeTransaction();

			//Organization assignment
			require('modules/Users/GetUserOrg.php');
		    }
		}

		//primary organization units - one per organization
		elseif( $fieldname == 'primary_orgunits[]') {
		    $values = split( ' \|##\| ', $fieldvalue);
		    $delete = array();

		    //Only one orgunit per organization may be a primary
		    //just build an assoziative array
		    $assign = array();
		    foreach( $values as $orgunit) {
			$organdunit = split( ' - ', $orgunit);
			if( count( $organdunit) > 1 && !isset( $assign[$organdunit[0]]))
			    $assign[$organdunit[0]] = $organdunit[1];
		    }

		    //get the  orgunit ids we're abount to assign to
		    $assignid = array();
		    $sql = '';
		    foreach( array_keys( $assign) as $key) {
			if( $sql != '') 
			    $sql .= " or ";
			$sql .= "(organizationname = '".$key."' and name='".$assign[$key]."')";
		    }

		    $adb->startTransaction();
		    if( $sql != '') {
			$sql = "select orgunitid from vtiger_orgunit where (".$sql.") and deleted=0";
			$result = $adb->query($sql);
			if($adb->num_rows($result) >= 1) {
			    while($result_set = $adb->fetch_array($result)) {
				$assignid[] = $result_set['orgunitid'];
			    }
			}
		    }

		    //get current settings from database
		    $sql = "select orgunitid from vtiger_user2orgunit where userid=".$crmid." and deleted=0";
		    $result = $adb->query($sql);
		    if($adb->num_rows($result) >= 1) {

			while($result_set = $adb->fetch_array($result)) {
			    $current = $result_set['orgunitid'];
			    $index = 0;
			    foreach( $assignid as $orgid) {
				if( $current == $orgid) {

				    //Found. Do no need to reassign.
				    //Skip to the next item already stored in database.
				    array_splice( $assignid, $index, 1);
				    continue 2;
				}
				$index++;
			    }

			    //Not found. Need to remove from database
			    $delete[] = $current;
			}
		    }

		    //Now in $assignid there is a list of new assignments.
		    //In $delete there's a list of assignments to be removed
		    foreach( $assignid as $orgunitid) {
			$sql = "insert into vtiger_user2orgunit (userid,orgunitid,primarytag) values (".$crmid.",'".$orgunitid."',1)";
			$result = $adb->query($sql);
		    }
		    foreach( $delete as $orgunitid) {
			$sql = "delete from vtiger_user2orgunit where userid=".$crmid." and orgunitid='".$orgunitid."'";
			$result = $adb->query($sql);
		    }
		    $adb->completeTransaction();

		    //Organization assignment
		    require('modules/Users/GetUserOrg.php');
		}

		//anything else is a field update
		else {
		    $userObj->column_fields[$fieldname] = $fieldvalue;
		    $userObj->id = $crmid;
		    $userObj->mode = "edit";
		    $userObj->save("Users");
		}

		//result would be success as long as the user exists
		if($userObj->id != "")
		{
			echo ":#:SUCCESS";
			$my_status = "Success";
		}else
		{
			echo ":#:FAILURE";
		}   
	}else
	{
		echo ":#:FAILURE";
	}
	$local_log->debug("Exit DetailViewAjax: ".$my_status);
}
?>
