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

require_once("connection.php");

// make MySQL run in desired port  
$sock_path=":" .$mysql_port;
$H_NAME=gethostbyaddr($_SERVER['SERVER_ADDR']);

/* database configuration
      db_host_name:     MySQL Database Hostname
      db_user_name:    	MySQL Username
      db_password:     	MySQL Password
      db_name:     	MySQL Database Name
*/
// all of these commented values get populated by install.php
//$dbconfig['db_host_name'] = 	'$H_NAME.$sock_path';
//$dbconfig['db_user_name'] = 	'vtigercrm';
//$dbconfig['db_password'] = 	'';
//$dbconfig['db_name'] = 	'vtigercrm';

//$host_name = '';
//$site_URL = '';
//$root_directory = '';

// full path to the include directory including the trailing slash
//$includeDirectory = $root_directory.'include/';

$list_max_entries_per_page = '20';
$history_max_viewed = '5';

// define list of menu tabs
//$moduleList = Array('Home', 'Dashboard', 'Leads', 'Contacts', 'Accounts', 'Opportunities', 'Cases', 'Notes', 'Calls', 'Emails', 'Meetings', 'Tasks');

$default_module = 'Home';
$default_action = 'index';

// set default theme
$default_theme = 'blue';
$databasetype = 'mysql';

// show or hide time to compose each page
$calculate_response_time = true;

// default text that is placed initially in the login form for user name
$default_user_name = '';

// default text that is placed initially in the login form for password
$default_password = '';

// create user with default username and password
$create_default_user = false;

// login message
// if a message is provided, it will be placed on the login screen
// this is for site specific special instructions
$login_message = 'Please login to the application.';

?>
