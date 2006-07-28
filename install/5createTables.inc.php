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
require_once('data/Tracker.php');
require_once('include/utils/utils.php');
require_once('modules/Users/DefaultDataPopulator.php');
require_once('modules/Users/CreateUserPrivilegeFile.php');

// load the config_override.php file to provide default user settings
if (is_file("config_override.php")) {
	require_once("config_override.php");
}

$db = new PearDatabase();

$log =& LoggerManager::getLogger('INSTALL');

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
        $user->column_fields["last_name"] = 'Administrator';
        $user->column_fields["user_name"] = 'admin';
        $user->column_fields["status"] = 'Active';
        $user->column_fields["is_admin"] = 'on';
        $user->column_fields["user_password"] = $admin_password;
        $user->column_fields["tz"] = 'Europe/Berlin';
        $user->column_fields["holidays"] = 'de,en_uk,fr,it,us,';
        $user->column_fields["workdays"] = '0,1,2,3,4,5,6,';
        $user->column_fields["weekstart"] = '1';
        $user->column_fields["namedays"] = '';
        $user->column_fields["currency_id"] = 1;
	$user->column_fields["date_format"] = 'yyyy-mm-dd';
	$user->column_fields["hour_format"] = 'am/pm';
	$user->column_fields["start_hour"] = '08:00';
	$user->column_fields["end_hour"] = '23:00';
	// added by jeri to populate default image and tagcloud for admin	
	$user->column_fields["imagename"] = 'shanky.jpg';
        $user->column_fields["tagcloud"] = '';	
        $user->column_fields["activity_view"] = 'This Year';	
	$user->column_fields["lead_view"] = 'Today';
	$user->column_fields["defhomeview"] = 'home_metrics';
        //added by philip for default default admin emailid
	if($admin_email == '')
	$admin_email ="admin@vtigeruser.com";
        $user->column_fields["email1"] = $admin_email;
	//to get the role id for standard_user	
	$role_query = "select roleid from vtiger_role where rolename='CEO'";
	$db->checkConnection();
	$db->database->SetFetchMode(ADODB_FETCH_ASSOC);
	$role_result = $db->query($role_query);
	$role_id = $db->query_result($role_result,0,"roleid");
	$user->column_fields["roleid"] = $role_id;

        $user->save("Users");

        // we need to change the admin user to a fixed id of 1.
        //$query = "update vtiger_users set id='1' where user_name='$user->user_name'";
        //$result = $db->query($query, true, "Error updating admin user ID: ");

        $log->info("Created ".$user->table_name." vtiger_table. for user $user->id");

	//Creating the flat files
	createUserPrivilegesfile($user->id);
        createUserSharingPrivilegesfile($user->id);


	//Creating the Standard User
    	$user = new User();
        $user->column_fields["last_name"] = 'StandardUser';
        $user->column_fields["user_name"] = 'standarduser';
        $user->column_fields["is_admin"] = 'off';
        $user->column_fields["status"] = 'Active';
        $user->column_fields["user_password"] = 'standarduser';
        $user->column_fields["tz"] = 'Europe/Berlin';
        $user->column_fields["holidays"] = 'de,en_uk,fr,it,us,';
        $user->column_fields["workdays"] = '0,1,2,3,4,5,6,';
        $user->column_fields["weekstart"] = '1';
        $user->column_fields["namedays"] = '';
        $user->column_fields["currency_id"] = 1;
	$user->column_fields["date_format"] = 'yyyy-mm-dd';
	// added by jeri to populate default image and tagcloud for admin	
	$user->column_fields["imagename"] = 'ela.jpg';
        $user->column_fields["activity_view"] = 'This Year';	
	$user->column_fields["lead_view"] = 'Today';
        $user->column_fields["tagcloud"] = '';	
	$user->column_fields["defhomeview"] = 'home_metrics';
        //added by philip for default default admin emailid
	if($admin_email == '')
	$std_email ="standarduser@vtigeruser.com";
        $user->column_fields["email1"] = $std_email;
	//to get the role id for standard_user	
	$role_query = "select roleid from vtiger_role where rolename='Vice President'";
	$db->database->SetFetchMode(ADODB_FETCH_ASSOC);
	$role_result = $db->query($role_query);
	$role_id = $db->query_result($role_result,0,"roleid");
	$user->column_fields["roleid"] = $role_id;

    $user->save('Users');

	//Creating the flat vtiger_files
	createUserPrivilegesfile($user->id);
        createUserSharingPrivilegesfile($user->id);

}

//$startTime = microtime();
$modules = array("DefaultDataPopulator");
$focus=0;				
// tables creation
//eecho("Creating Core tables: ");
//$adb->setDebug(true);
$success = $adb->createTables("schema/DatabaseSchema.xml");

// TODO HTML
if($success==0)
	die("Error: Tables not created.  Table creation failed.\n");
elseif ($success==1)
	die("Error: Tables partially created.  Table creation failed.\n");
	//eecho("Tables Successfully created.\n");

foreach ($modules as $module ) 
{
	$focus = new $module();
	$focus->create_tables();
}
			

// create and populate combo tables
require_once('include/PopulateComboValues.php');
$combo = new PopulateComboValues();
$combo->create_tables();

//Writing tab data in flat file
create_tab_data_file();
create_parenttab_data_file();

create_default_users();

// default report population
require_once('modules/Reports/PopulateReports.php');

// default customview population
require_once('modules/CustomView/PopulateCustomView.php');


// ensure required sequences are created (adodb creates them as needed, but if
// creation occurs within a transaction we get problems
$db->getUniqueID("vtiger_crmentity");
$db->getUniqueID("vtiger_seactivityrel");
$db->getUniqueID("vtiger_freetags");

// populate the db with seed data
if ($db_populate) {
        //eecho ("Populate seed data into $db_name");
        include("install/populateSeedData.php");
        //eecho ("...<font color=\"00CC00\">done</font><BR><P>\n");
}

// populate forums data
global $log, $db;

//$endTime = microtime();
//$deltaTime = microtime_diff($startTime, $endTime);


// populate calendar data

//eecho ("total time: $deltaTime seconds.\n");
?>
