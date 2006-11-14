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

require_once('include/utils/utils.php');
require_once('user_privileges/default_module_view.php');
require_once('Smarty_setup.php');
global $app_strings;
global $mod_strings;
global $currentModule;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
global $current_language;

$smarty = new vtigerCRM_Smarty;

$smarty->assign("UMOD", $mod_strings);
$smarty->assign("MOD", return_module_language($current_language,'Settings'));
$smarty->assign("CMOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMAGE_PATH",$image_path);

global $log;
$log->debug("Inside EditUserOrg");

//Assign the organization details to the html output
$smarty->assign("MODULE", 'Users');

//Get current selection out of the request
if( isset($_REQUEST['primary_org']) && $_REQUEST['primary_org'] != '')
    $primary_org = $_REQUEST['primary_org'];
if( isset($_REQUEST['assigned_org']) && $_REQUEST['assigned_org'] != '')
    $_assigned_org = split( ':', $_REQUEST['assigned_org']);
if( isset($_REQUEST['primary_orgunits']) && $_REQUEST['primary_orgunits'] != '')
    $primary_orgunits = split( ':', $_REQUEST['primary_orgunits']);

//Get the entire list of organizations out of the session
if( isset($_SESSION['all_user_organizations']) && $_SESSION['all_user_organizations'] != '') 
    $all_org = $_SESSION['all_user_organizations'][0];

//Set up the selection array for the primary organization
$assigned_org = array();
foreach( $_assigned_org as $org) {
    if( $org == $primary_org)
	$assigned_org[$org] = "selected";
    else
	$assigned_org[$org] = "";
}

//Update the selection status in $all_org
$sql_org = "";
foreach( array_keys($all_org) as $org) {
    if( isset( $assigned_org[$org])) {
	$all_org[$org] = "selected";

	//query list used for organization selection
	if( $sql_org == '') {
	    $sql_org = "'".$org."'";
	} else {
	    $sql_org .= ",'".$org."'";
	}

    } else {
	$all_org[$org] = "";
    }
}

//Set up the selection list for the primary orgunits
$selected_orgunits = array();
if( isset( $primary_orgunits)) {
    foreach( $primary_orgunits as $org) {
	$selected_orgunits[$org] = 1;
    }
}

$primary_orgunits = array();
if( isset( $sql_org) && $sql_org != '') {
    $sql = "SELECT organizationname, name FROM vtiger_orgunit
	       WHERE organizationname in (".$sql_org.") AND deleted=0
	       ORDER BY organizationname,name ASC";

    $result = $adb->query($sql);
    while($orgunit_result = $adb->fetch_array($result)) {
	$key = $orgunit_result["organizationname"]." - ".$orgunit_result["name"];

	//Array used for the primary orgunit select box
	if( isset( $selected_orgunits[$key])) {
	    $primary_orgunits[$key] = 'selected';
	} else {
	    $primary_orgunits[$key] = '';
	}
    }
}

//Set up the session variables
$_SESSION['all_user_organizations'] = array( $all_org);
$_SESSION['edit_user_organizations'] = array( $assigned_org);
$_SESSION['edit_user_orgunits'] = array( $primary_orgunits);

$smarty->assign("ALL_USER_ORGANIZATIONS", $_SESSION['all_user_organizations']);
$smarty->assign("EDIT_USER_ORGANIZATIONS", $_SESSION['edit_user_organizations']);
$smarty->assign("EDIT_USER_ORGUNITS", $_SESSION['edit_user_orgunits']);

//redisplay the organization part
$smarty->display('UserEditOrg.tpl');
?>

