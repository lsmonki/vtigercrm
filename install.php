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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/install.php,v 1.2 2004/10/06 09:02:02 jack Exp $
 * Description:  Starts the installation process.
 ********************************************************************************/

include('adodb/adodb.inc.php');

if(version_compare(phpversion(), '5.0') < 0) {
        require_once('phpversionfail.php');
        die();
}

/*
//This code sets php to 4.0 compatilibity. 
if (substr(phpversion(), 0, 1) == "5") {
	ini_set("zend.ze1_compatibility_mode", "1");
}*/

/** Function to  return a string with backslashes stripped off
 * @param $value -- value:: Type string
 * @returns $value -- value:: Type string array
 */
 function stripslashes_checkstrings($value){
 	if(is_string($value)){
 		return stripslashes($value);
 	}
 	return $value;

 }
 if(get_magic_quotes_gpc() == 1){
 	$_REQUEST = array_map("stripslashes_checkstrings", $_REQUEST);
	$_POST = array_map("stripslashes_checkstrings", $_POST);
	$_GET = array_map("stripslashes_checkstrings", $_GET);

}

//Run command line if no web var detected
if (!isset($_SERVER['REQUEST_METHOD'])) {
	require("install/5createTables.inc.php");
	exit;
}
			
if (isset($_POST['file'])) $the_file = $_POST['file'];
else $the_file = "0welcome.php";

include("install/".$the_file);

?>
