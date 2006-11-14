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
 * $Header$
 * Description:  Deletes an Account record and then redirects the browser to the 
 * defined return URL.
 ********************************************************************************/

require_once('modules/OrgUnit/OrgUnit.php');
global $mod_strings;

require_once('include/logging.php');
$log = LoggerManager::getLogger('ticket_delete');

$focus = new OrgUnit();

if(!isset($_REQUEST['record']))
	die($mod_strings['ERR_DELETE_RECORD']);

// Back navigation
$return_action = "DetailView";

if( isset( $_REQUEST['return_module']) && $_REQUEST['return_module'] != '')  {
    $return_module = $_REQUEST['return_module'];
} else {
    $return_module = "Organization";
    $return_action = "ListView";
    $return_id = "";
}

if( $return_module == "OrgUnit") {
    $return_module = "Organization";
    if( isset( $_REQUEST['return_id']) && $_REQUEST['return_id'] != '') 
	$return_id = $_REQUEST['return_id'];
    elseif( isset( $_REQUEST['parentid']) && $_REQUEST['parentid'] != '') 
	$return_id = $_REQUEST['parentid'];
    elseif( isset( $_REQUEST['organizationname']) && $_REQUEST['organizationname'] != '') 
	$return_id = $_REQUEST['organizationname'];
    else {
	$return_id = "";
	$return_id = "ListView";
    }
}

DeleteEntity($_REQUEST['module'],$return_module,$focus,$_REQUEST['record'],$return_id);

header("Location: index.php?module=".$return_module."&action=".$return_action."&record=".$return_id."&relmodule=".$_REQUEST['module']);

?>
