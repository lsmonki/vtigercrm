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

if (substr(phpversion(), 0, 1) == "5") {
	ini_set("zend.ze1_compatibility_mode", "1");
}


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

echo "<style>
		.bggray
		{
			background-color: #dfdfdf;
		}
	.bgwhite
	{
		background-color: #FFFFFF;
	}
	.copy
	{
		font-size:9px;
		font-family: Verdana, Arial, Helvetica, Sans-serif;
	}
	</style>
	<script language = 'JavaScript' type='text/javascript' src = 'include/js/popup.js'></script>
	<table width=20% border=0 cellspacing=1 cellpadding=0 class=\"bggray\" align=center><tr><td align=center>\n
	<table width=100% border=0 cellspacing=1 cellpadding=0 class=\"bgwhite\" align=center><tr><td align=center class=\"copy\">\n
	&copy; Click <a href ='javascript:mypopup()'>here</a> for Copyright details.<br>
	</td></tr></table></td></tr></table>\n";

?>
