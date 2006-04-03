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

$new_tables = 0;

require_once('config.php');
require_once('include/database/PearDatabase.php');
require_once('include/logging.php');
require_once('modules/Leads/Lead.php');
require_once('modules/Settings/FileStorage.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Accounts/Account.php');
require_once('modules/Potentials/Opportunity.php');
require_once('modules/Activities/Activity.php');
require_once('modules/Notes/Note.php');
require_once('modules/Emails/Email.php');
require_once('modules/Users/User.php');
require_once('modules/Import/ImportMap.php');
require_once('modules/Import/UsersLastImport.php');
require_once('modules/Users/LoginHistory.php');
require_once('modules/Settings/FileStorage.php');
require_once('data/Tracker.php');
require_once('include/utils/utils.php');
require_once('modules/Users/Security.php');

// load the config_override.php file to provide default user settings
if (is_file("config_override.php")) {
	require_once("config_override.php");
}

$db = new PearDatabase();

$log =& LoggerManager::getLogger('create_table');

function eecho($msg = FALSE) {
	if ($useHtmlEntities) {
		echo htmlentities(nl2br($msg));
	}
	else {
		echo $msg;
	}
}

function create_default_users() {
        global $log, $db;
        global $admin_email;
        global $admin_password;
        global $create_default_user;
        global $default_user_name;
        global $default_password;
        global $default_user_is_admin;

        // create default admin user
    	$user = new User();
        $user->last_name = 'Administrator';
        $user->user_name = 'admin';
        $user->status = 'Active';
        $user->is_admin = 'on';
        $user->user_password = $user->encrypt_password($admin_password);
        $user->tz = 'Europe/Berlin';
        $user->holidays = 'de,en_uk,fr,it,us,';
        $user->workdays = '0,1,2,3,4,5,6,';
        $user->weekstart = '1';
        $user->namedays = '';
	$user->date_format = 'yyyy-mm-dd';
	// added by jeri to populate default image and tagcloud for admin	
	$user->imagename = 'admin.jpeg';
        $user->tagcloud = '';	
	$user->defhomeview = 'home_metrics';
        //added by philip for default default admin emailid
	if($admin_email == '')
	$admin_email ="admin@administrator.com";
        $user->email = $admin_email;
        $user->save();

        // we need to change the admin user to a fixed id of 1.
        //$query = "update users set id='1' where user_name='$user->user_name'";
        //$result = $db->query($query, true, "Error updating admin user ID: ");

        $log->info("Created ".$user->table_name." table. for user $user->id");

        if($create_default_user) {
                $default_user = new User();
                $default_user->last_name = $default_user_name;
                $default_user->user_name = $default_user_name;
                $default_user->status = 'Active';

		if (isset($default_user_is_admin) && $default_user_is_admin)
			$default_user->is_admin = 'on';

                $default_user->user_password = $default_user->encrypt_password($default_password);
        	$default_user->tz = 'Europe/Berlin';
	        $default_user->holidays = 'de,en_uk,fr,it,us,';
        	$default_user->workdays = '0,1,2,3,4,5,6,';
	        $default_user->weekstart = '1';
        	$default_user->namedays = '';
                $default_user->save();
        }

	// insert values into user2role table
	$role_query = "select roleid from role where rolename='administrator'";
	$db->database->SetFetchMode(ADODB_FETCH_ASSOC);
	$role_result = $db->query($role_query);
	$role_id = $db->query_result($role_result,0,"roleid");

	$sql_stmt1 = "insert into user2role values(".$user->id.",'".$role_id."')";
	$db->query($sql_stmt1) or die($app_strings['ERR_CREATING_TABLE'].mysql_error());
}

$startTime = microtime();
$modules = array("Security");
$focus=0;				
// tables creation
eecho("Creating Core tables: ");
$success = $db->createTables("schema/DatabaseSchema.xml");

// TODO HTML
if($success==0)
	die("Error: Tables not created.  Table creation failed.\n");
elseif ($success==1)
	die("Error: Tables partially created.  Table creation failed.\n");
else
	eecho("Tables Successfully created.\n");

foreach ( $modules as $module ) 
{
	$focus = new $module();
	$focus->create_tables();
}
			
create_default_users();

// populate users table
$uid = $db->getUniqueID("users");
$sql_stmt1 = "insert into users(id,user_name,user_password,last_name,email1,date_format) values(".$uid.",'standarduser','stX/AHHNK/Gkw','standarduser','standarduser@standard.user.com','yyyy-mm-dd')";
$db->query($sql_stmt1) or die($app_strings['ERR_CREATING_TABLE'].mysql_error());

$role_query = "select roleid from role where rolename='standard_user'";
$db->database->SetFetchMode(ADODB_FETCH_ASSOC);
$role_result = $db->query($role_query);
$role_id = $db->query_result($role_result,0,"roleid");

$sql_stmt2 = "insert into user2role values(".$uid.",'".$role_id."')";
$db->query($sql_stmt2) or die($app_strings['ERR_CREATING_TABLE'].mysql_error());

// create and populate combo tables
require_once('include/PopulateComboValues.php');
$combo = new PopulateComboValues();
$combo->create_tables();

// create and populate custom field tables;
require_once('include/PopulateCustomFieldTables.php');
create_custom_field_tables();

// default report population
require_once('modules/Reports/PopulateReports.php');

// default customview population
require_once('modules/CustomView/PopulateCustomView.php');

//Writing tab data in flat file
create_tab_data_file();
create_parenttab_data_file();

// populate the db with seed data
if ($db_populate) {
        eecho ("Populate seed data into $db_name");
        include("install/populateSeedData.php");
        eecho ("...<font color=\"00CC00\">done</font><BR><P>\n");
}

// populate forums data
global $log, $db;

$endTime = microtime();
$deltaTime = microtime_diff($startTime, $endTime);


// populate calendar data

//eecho ("total time: $deltaTime seconds.\n");
?>
