<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Users/DetailView.php,v 1.21 2005/04/19 14:44:02 ray Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('Smarty_setup.php');
require_once('data/Tracker.php');
require_once('modules/Users/Users.php');
require_once('include/utils/utils.php');
require_once('include/utils/CommonUtils.php');
require_once('include/utils/UserInfoUtil.php');
require_once('include/database/PearDatabase.php');

global $current_user;
global $adb;
global $log;

$log->debug("Entering GetUserOrg userid=".$crmid);

//Organization assignment
$sql = "SELECT organizationname,primarytag FROM vtiger_user2org WHERE userid=".$crmid;
$result = $adb->query($sql);
$orgs = array();
$curorg = '';
$defcurorg = '';
$org_separator = "<br>&nbsp;";
$assigned_org = "";
$sql_org = "";
while($org_result = $adb->fetch_array($result)) {
    $key = $org_result["organizationname"];

    //Array used for the primary organization select box
    if( $org_result["primarytag"] == 1) {
	$orgs[$key] = 'selected';
	$curorg = $key;
    } else {
	$orgs[$key] = '';
    }

    //string used for the assigned organization multiselect box
    if( $assigned_org == '') {
	$assigned_org = $key;
    } else {
	$assigned_org .= $org_separator.$key;
    }

    //query list used for orgunit selection
    if( $sql_org == '') {
	$sql_org = "'".$key."'";
    } else {
	$sql_org .= ",'".$key."'";
    }

    if( $defcurorg == '')
	$defcurorg = $key;
}

if( $curorg == '') {
    if( $defcurorg != '') {
	$curorg = $defcurorg;
	$orgs[$defcurorg] = 'selected';
    } else {
	$curorg = "-- NONE --";
    }
}
$smarty_orgs = array($orgs);

//all organizations
$sql = "SELECT organizationname FROM vtiger_organizationdetails WHERE deleted=0";
$result = $adb->query($sql);
$allorgs = array();
while($org_result = $adb->fetch_array($result)) {
    $key = $org_result["organizationname"];
    if( isset( $orgs[$key])) {
	$allorgs[$key] = 'selected';
    } else {
	$allorgs[$key] = '';
    }
}
$smarty_allorgs = array($allorgs);

//Organization untis
$orgunits = array();
$prim_orgunits = "";
if( isset( $sql_org) && $sql_org != '') {
    $sql = "SELECT vtiger_orgunit.organizationname AS org, vtiger_orgunit.name AS orgunit,
	       vtiger_user2orgunit.primarytag AS primarytag
	       FROM vtiger_orgunit
	       LEFT JOIN vtiger_user2orgunit
		   ON (vtiger_orgunit.orgunitid = vtiger_user2orgunit.orgunitid
		       AND vtiger_user2orgunit.userid = ".$crmid.")
	       INNER JOIN vtiger_organizationdetails
		   ON (vtiger_orgunit.organizationname = vtiger_organizationdetails.organizationname)
	       WHERE vtiger_orgunit.organizationname in (".$sql_org.")
	       AND vtiger_orgunit.deleted=0 AND vtiger_organizationdetails.deleted=0
	       ORDER BY vtiger_orgunit.organizationname,vtiger_orgunit.name ASC";
    $sql = fixPostgresQuery( $sql, $log, 0);

    $result = $adb->query($sql);
    while($orgunit_result = $adb->fetch_array($result)) {
	$key = $orgunit_result["org"]." - ".$orgunit_result["orgunit"];

	//Array used for the primary orgunit select box
	if( $orgunit_result["primarytag"] == 1) {
	    $orgunits[$key] = 'selected';
	    if( $prim_orgunits == '') {
		$prim_orgunits = $key;
	    } else {
		$prim_orgunits .= $org_separator.$key;
	    }
	} else {
	    $orgunits[$key] = '';
	}
    }

    if( $prim_orgunits == '')
	$prim_orgunits = '-- NONE --';
}
$smarty_orgunits = array($orgunits);

//Also keep those values in our session
$log->debug("GetUserOrg userid=".$crmid." - doing session assignments");
$_SESSION['all_user_organizations'] = $smarty_allorgs;
$_SESSION['edit_user_organizations'] = $smarty_orgs;
$_SESSION['edit_user_orgunits'] = $smarty_orgunits;
$_SESSION['edit_user_primary_organization'] = $curorg;
$_SESSION['edit_user_assigned_organization'] = $assigned_org;
$_SESSION['edit_user_primary_orgunits'] = $prim_orgunits;

$log->debug("Leaving GetUserOrg userid=".$crmid);
?>
