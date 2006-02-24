<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is: SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/install/5createTables.php,v 1.58 2005/04/19 16:57:08 ray Exp $
 * Description:  Executes a step in the installation process.
 ********************************************************************************/

set_time_limit(600);

require_once('include/database/PearDatabase.php');
require_once('include/logging.php');
require_once('modules/Leads/Lead.php');
require_once('modules/Settings/FileStorage.php');
//require_once('modules/imports/Headers.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Accounts/Account.php');
require_once('modules/Potentials/Opportunity.php');
require_once('modules/Activities/Activity.php');
require_once('modules/Notes/Note.php');
require_once('modules/Emails/Email.php');
require_once('modules/Users/User.php');
require_once('modules/Import/SugarFile.php');
require_once('modules/Import/ImportMap.php');
require_once('modules/Import/UsersLastImport.php');
require_once('modules/Users/TabMenu.php');
require_once('modules/Users/LoginHistory.php');
require_once('modules/Settings/FileStorage.php');
require_once('data/Tracker.php');
require_once('include/utils.php');
require_once('modules/Users/Security.php');

// load the config_override.php file to provide default user settings
if (is_file("config_override.php")) {
	require_once("config_override.php");
}
require_once('config.php');

$conn = ADONewConnection($dbconfig['db_type']);
$conn->Connect(
	$dbconfig['db_hostname'],
	$dbconfig['db_username'],
	$dbconfig['db_password'],
	$dbconfig['db_name']);


$schema = new adoSchema( $conn );
$schema->XMLS_DEBUG = true;

//Get schema without data
$xmlresult = $schema->ExtractSchema(false);
header("content-type: text/plain");
#echo "abcd";

if (!$schemaFile = fopen("schema/DatabaseSchema.xml", w)) {
	echo "Cannot open file ($filename)";
	exit;
}

if (fwrite($schemaFile, $xmlresult) === FALSE) {
	echo "Cannot write to file ($schemaFile)";
	exit;
}

echo $xmlresult;
